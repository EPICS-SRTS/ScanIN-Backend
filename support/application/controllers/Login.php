<?php


/******** Tickerr - Controller ********
 * Controller Name:    Login
 * Description:    This controller has functions that allows to do actions
 *                    before and while loggin in
 **/
class Login extends CI_Controller
{

    // This is the principal page. The page that every logged out user will see.
    public function index()
    {
        $session = $this->session;

        // Is the user logged? Redirect to the panel
        if ($session->tickerr_logged != NULL && is_array($session->tickerr_logged))
            header('Location: ' . $this->config->base_url() . 'panel/');

        $this->load->model('Settings_model', 'settings_model', true);

        // Check for different options that need to be enabled to show certain stuff
        $settings_model = $this->settings_model;
        $recovery = $settings_model->get_setting('allow_account_recovery') == '0' ? false : true;
        $allow_bug = $settings_model->get_setting('allow_guest_bug_reports') == '0' ? false : true;
        $allow_ticket = $settings_model->get_setting('allow_guest_tickets') == '0' ? false : true;
        $allow_account = $settings_model->get_setting('allow_account_creations') == '0' ? false : true;

        $data = array(
            'site_title' => $this->settings_model->get_setting('site_title'),
            'siteurl' => $this->config->item('base_url'),
            'account_recovery' => $recovery,
            'allow_bug_reports' => $allow_bug,
            'allow_tickets' => $allow_ticket,
            'allow_accounts' => $allow_account
        );

        $this->load->view('login_index', $data);
    }

    // Function to login
    public function login_action()
    {
        // Load model needed
        $this->load->model('Loginactions_model', 'loginactions_model', true);
        $this->load->model('Settings_model', 'settings_model', true);

        if ($this->input->post('user') != NULL) {
            $user = $this->input->post('user');
        }
        if ($this->input->post('pass') != NULL) {
            $pass = md5($this->input->post('pass'));
        }
        if ($this->input->get('user') != NULL) {
            $user = $this->input->get('user');
        }
        if ($this->input->get('pass') != NULL) {
            $pass = md5($this->input->get('pass'));
            $redir = 1;
        }


        if ($user == null || strlen($user) < 5)
            die('1');
        if ($pass == null || strlen($pass) < 5)
            die('2');

        // Is the user logged?
        if ($this->session->tickerr_logged != NULL && is_array($this->session->tickerr_logged)) {
            // Validate session
            $session_user = $this->session->tickerr_logged[0];
            $session_pass = $this->session->tickerr_logged[1];

            // Is logged
            if ($this->loginactions_model->validate_session($session_user, $session_pass) == true)
                die('3');
        }

        // Proceed with the login
        if ($this->loginactions_model->validate_session($user, $pass) == true) {
            // Good data, save session
            $this->session->tickerr_logged = array($user, $pass);
            if ($redir == 1) {
                header("Location: http://admin.scaninsystem.com/support/");
            }
                die('3');
        }

        // Wrong info
        die('4');
    }

    // When a user tries to recover his account, this is the function
    // that does the job
    public function password_recovery()
    {
        // Load needed models
        $this->load->model('Loginactions_model', 'loginactions_model', true);
        $this->load->model('Guest_model', 'guest_model', true);
        $this->load->model('Settings_model', 'settings_model', true);
        $this->load->library('form_validation');

        // Get the site title for the header
        $data['site_title'] = $this->settings_model->get_setting('site_title');

        // Is account recovery disabled?
        if ($this->settings_model->get_setting('allow_account_recovery') == '0') {
            header('Location: ' . $this->config->base_url());
            die();
        }

        // Form sent?
        if ($this->input->post('email') != null && $this->session->password_rec_sent == null) {
            // Validate
            $this->form_validation->set_rules('email', 'email', 'required|valid_email');

            // Wrong validation, direct access
            if ($this->form_validation->run() == false) die();

            $email = $this->input->post('email');

            // Check non-existing email
            if ($this->guest_model->check_existing_email($email) == false) {
                $data = array(
                    'action' => 1,
                    'error' => "The email you entered doesn't exist"
                );
                $this->load->view('password_recovery', $data);
                return;
            }

            // Email exists, generate recovery code
            $recovery_code = $this->loginactions_model->generate_recovery($email);

            // Recovery URL
            $recovery_url = $this->config->base_url() . 'login/recovery/?email=' . $email . '&code=' . $recovery_code;

            // Ready to send email. Get settings
            $config = $this->settings_model->get_email_settings();
            $email_info = $this->settings_model->get_email_info();
            $email_specific = $this->settings_model->get_email_specific('email_recover');
            $config['mailtype'] = $email_specific['type'];

            // Load library and prepare info
            $this->load->library('email');
            $this->email->initialize($config);

            $this->email->from($email_info['email_from_address'], $email_info['email_from_name']);
            $this->email->to($email);
            $this->email->cc($email_info['email_cc']);

            $this->email->subject($email_specific['title']);

            $replace_from = array(
                '%site_title%',
                '%site_url%',
                '%user_email%',
                '%recovery_code%',
                '%recovery_url%'
            );

            $replace_to = array(
                $this->settings_model->get_setting('site_title'),
                $this->config->base_url(),
                $email,
                $recovery_code,
                $recovery_url
            );

            $this->email->message(str_replace($replace_from, $replace_to, $email_specific['content']));

            $this->email->send();

            // Success
            $this->session->password_rec_sent = true;
            $data['action'] = 2;
            $this->load->view('password_recovery', $data);
            return;
        }

        $this->session->password_rec_sent = null;
        $data['action'] = 1;
        $this->load->view('password_recovery', $data);
    }

    // When a user tries to recover his account, this is the function
    // that does the job
    public function recovery()
    {
        // Prevent direct or wrong access
        if (!isset($_GET['email']) || !isset($_GET['code']))
            header('Location: ' . $this->config->base_url());

        // Load needed models
        $this->load->model('Loginactions_model', 'loginactions_model', true);
        $this->load->model('Guest_model', 'guest_model', true);
        $this->load->model('Settings_model', 'settings_model', true);
        $this->load->library('form_validation');

        // Get the site title for the header
        $data['site_title'] = $this->settings_model->get_setting('site_title');

        // Is account recovery disabled?
        if ($this->settings_model->get_setting('allow_account_recovery') == '0') {
            header('Location: ' . $this->config->base_url());
            die();
        }

        // Make sure email and code are right
        if ($this->loginactions_model->validate_recovery($_GET['email'], $_GET['code']) == false)
            header('Location: ' . $this->config->base_url());

        // Form sent?
        if ($this->input->post('password1') != null && $this->session->recovery_done_sent == null) {
            // Validate
            $this->form_validation->set_rules('password1', 'password1', 'required|min_length[5]');
            $this->form_validation->set_rules('password2', 'password2', 'required|min_length[5]');

            // Wrong validation, direct access
            if ($this->form_validation->run() == false) die();

            // Assign passwords and re-validate them
            $password1 = $this->input->post('password1');
            $password2 = $this->input->post('password2');
            if ($password1 != $password2) die();

            // All good. Recover password
            $this->loginactions_model->recover($_GET['email'], $password1);

            // Do we need to send an email?
            if ($this->settings_model->get_setting('mailing') == '1' && $this->settings_model->get_setting('send_email_recovery_done') == '1') {
                // Get settings
                $config = $this->settings_model->get_email_settings();
                $email_info = $this->settings_model->get_email_info();
                $email_specific = $this->settings_model->get_email_specific('email_recovery_done');
                $config['mailtype'] = $email_specific['type'];

                // Load library and prepare info
                $this->load->library('email');
                $this->email->initialize($config);

                $this->email->from($email_info['email_from_address'], $email_info['email_from_name']);
                $this->email->to($_GET['email']);
                $this->email->cc($email_info['email_cc']);

                $this->email->subject($email_specific['title']);

                $replace_from = array(
                    '%site_title%',
                    '%site_url%',
                    '%user_email%'
                );

                $replace_to = array(
                    $this->settings_model->get_setting('site_title'),
                    $this->config->base_url(),
                    $_GET['email']
                );

                $this->email->message(str_replace($replace_from, $replace_to, $email_specific['content']));

                $this->email->send();
            }

            // Success
            $this->session->recovery_done_sent = true;
            $data['action'] = 4;
            $this->load->view('password_recovery', $data);
            return;
        }

        $this->session->recovery_done_sent = null;
        $data['action'] = 3;
        $this->load->view('password_recovery', $data);
    }
}