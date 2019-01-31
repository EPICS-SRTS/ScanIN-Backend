<?php

/******** Tickerr - Controller ********
 * Controller Name:	Ticket
 * Description: 	Inside of this controller are functions that are only
 *					available for guest users but related to tickets. It
 *					is used to see a ticket, submit new replies and rate
 *					the agent.
**/
class Ticket extends CI_Controller {
	
	// See a ticket
	public function index($code) {
		$this->load->model('Settings_model', 'settings_model', true);
		
		// Check if the user is already logged in
		if($this->session->tickerr_logged != NULL && is_array($this->session->tickerr_logged)) {
			header('Location: ' . $this->config->base_url().'panel/');
			die();
		}
			
		// Check if the ticket exists and if it's public (guest)
		$this->load->model('Tickets_model', 'tickets_model', true);
		if($this->tickets_model->check_guest_ticket($code) == false) {
			header('Location: ' . $this->config->base_url());
			die();
		}
		
		$this->load->model('Users_model', 'users_model', true);
		$config['base_url'] = $this->config->base_url();
		$config['ticket_info'] = $this->tickets_model->guest_ticket_info($code);
		
		if($config['ticket_info']->agentid == '0')
			$config['agent_info'] = false;
		else
			$config['agent_info'] = $this->users_model->get_user_info($config['ticket_info']->agentid);
		
		if($config['ticket_info']->files != '') {
			/**
			 * String syntax:
			   secure_file_name.ext*original_file_name.ext|secure_file_name.ext*original_file_name.ext
			 *
			**/
			$this->load->helper('uploaded_files');
			
			$config['ticket_files'] = array();
			$_files = separate_files($config['ticket_info']->files);
			foreach($_files as $file) {
				$file_ = explode('*', $file);
				$check = check_file($file_);
				if($check != false) {
					$file_[] = $check;
					$config['ticket_files'][] = $file_;
				}
			}
			
		}else{
			$config['ticket_files'] = false;
		}
		
		$config['department_name'] = $this->tickets_model->get_department_name($config['ticket_info']->department);
		$config['created_on'] = date('M jS, Y \a\t H:i:s', strtotime($config['ticket_info']->date));
		if($config['ticket_info']->last_update == '0000-00-00 00:00:00')
			$config['last_update'] = 'N/A';
		else
			$config['last_update'] = date('M jS, Y \a\t H:i:s', strtotime($config['ticket_info']->last_update));		
		if(isset($_SESSION['guest_ticket_error']) && $_SESSION['guest_ticket_error'] != null) {
			$config['error'] = $_SESSION['guest_ticket_error'];
			$_SESSION['guest_ticket_error'] = null;
		}else{
			$config['error'] = false;
		}
		
		if(isset($_SESSION['guest_ticket_rate_error']) && $_SESSION['guest_ticket_rate_error'] != null) {
			$config['error_rate'] = $_SESSION['guest_ticket_rate_error'];
			$_SESSION['guest_ticket_rate_error'] = null;
		}else{
			$config['error_rate'] = false;
		}
		
		$config['n_ticket_replies'] = $this->tickets_model->count_ticket_replies($code);
		if($config['n_ticket_replies'] == 0)
			$config['ticket_replies'] = false;
		else
			$config['ticket_replies'] = $this->tickets_model->get_ticket_replies($code);
		
		$config['users_model'] = $this->users_model;
		
		// Get the site title for the header
		$config['site_title'] = $this->settings_model->get_setting('site_title');
		
		// Finish loading view
		$this->load->view('guest_ticket', $config);
	}
	
	// Submit a new reply to the ticket
	public function new_reply($code) {
		// Check if the user is already logged in
		if($this->session->tickerr_logged != NULL && is_array($this->session->tickerr_logged)) {
			header('Location: ' . $this->config->base_url().'panel/');
			die();
		}

		// Check if the ticket exists and if it's public (guest)
		$this->load->model('Tickets_model', 'tickets_model', true);
		if($this->tickets_model->check_guest_ticket($code) == false) {
			header('Location: ' . $this->config->base_url());
			die();
		}
		
		$this->load->model('Settings_model', 'settings_model', true);
		$this->load->model('Users_model', 'users_model', true);
		$this->load->library('form_validation');
		
		// Validate reply
		$this->form_validation->set_rules('reply','reply','required|min_length[11]');
		if($this->form_validation->run() == false) die();
		
		// Keep with the actions
		$nfiles = (isset($_FILES['files'])) ? count($_FILES['files']['name']) : 0;
		
		// Do we have a file? Upload
		if($nfiles > 0) {
			$this->load->helper('upload_helper');
			$uploaded = upload_multiple_files('files', $nfiles);
			if($uploaded == false) {
				$_SESSION['guest_ticket_error'] = "One or more files couldn't be uploaded";
				header('Location: ' . $this->config->base_url() . 'ticket/' .$code);
			}else{
				$dbfiles = implode('|', $uploaded);
			}
		}else{
			// No files
			$dbfiles = '';
		}
		
		// Tinyeditor fix to the message
		$reply = str_replace('<span style="letter-spacing: -0.129999995231628px;">','<span>', $_POST['reply']);
		
		// Submit reply and update last update date
		$this->tickets_model->submit_guest_reply($code, $reply, $dbfiles);
		
		// NEW
		// Get ticket info
		$ticket_info = $this->tickets_model->guest_ticket_info($code);
		
		// Check if we should send email or not
		// If email is enabled, load library and continue
		if($this->settings_model->get_setting('mailing') == '1') {
			$this->load->library('email');
			
			// Should we send an email to all agents on this department?
			if($this->settings_model->get_setting('send_agent_email_new_reply') == '1') {
			
				// Get email settings and initialize everything
				$config = $this->settings_model->get_email_settings();
				$email_info = $this->settings_model->get_email_info();
				$email_specific = $this->settings_model->get_email_specific('agent_email_new_reply');
				
				$config['mailtype'] = $email_specific['type'];
				
				$this->email->initialize($config);
				
				// Does the ticket has an agent assigned?
				// If it does, continue...
				if($ticket_info->agentid != 0) {
					// Get agent's information
					$agent = $this->users_model->get_user_info($ticket_info->agentid);
					
					$this->email->clear();
					
					$this->email->from($email_info['email_from_address'], $email_info['email_from_name']);
					$this->email->cc($email_info['email_cc']);
					$this->email->subject($email_specific['title']);
					
					$this->email->to($agent->email);
					
					$replace_from = array(
						'%site_title%',
						'%site_url%',
						'%user_name%',
						'%ticket_code%',
						'%ticket_url%',
						'%ticket_subject%',
						'%ticket_department_id%',
						'%ticket_department_name%',
						'%ticket_content%',
						'%ticket_status%',
						'%ticket_priority%',
						'%reply_content%',
						'%agent_user_name%',
						'%agent_username%'
					);
					
					// Get status
					if($ticket_info->status == '1' || $ticket_info->status == '2')
						$ticket_status = 'Open (Awaiting your reply)';
					else
						$ticket_status = 'Closed';
					
					// Get priority
					if($ticket_info->priority == '1') $ticket_priority = 'High';
					elseif($ticket_info->priority == '2') $ticket_priority = 'Medium';
					else $ticket_priority = 'Low';
					
					
					$replace_to = array(
						$this->settings_model->get_setting('site_title'),
						$this->config->base_url(),
						$ticket_info->guest_name,
						$ticket_info->access,
						$this->config->base_url() . 'ticket/' . $ticket_info->access . '/',
						$ticket_info->subject,
						$ticket_info->department,
						$this->tickets_model->get_department_name($ticket_info->department),
						$ticket_info->content,
						$ticket_status,
						$ticket_priority,
						$reply,
						$agent->name,
						$agent->username
					);
					$this->email->message(str_replace($replace_from, $replace_to, $email_specific['content']));
					
					$this->email->send();
				}
			}
		}

		header('Location: ' . $this->config->base_url() . 'ticket/' .$code);
	}
	
	// Function to submit a new rate to an agent
	public function rate($code) {
		// Check if the user is already logged in
		if($this->session->tickerr_logged != NULL && is_array($this->session->tickerr_logged)) {
			header('Location: ' . $this->config->base_url().'panel/');
			die();
		}

		// Check if the ticket exists and if it's public (guest)
		$this->load->model('Tickets_model', 'tickets_model', true);
		if($this->tickets_model->check_guest_ticket($code) == false) {
			header('Location: ' . $this->config->base_url());
			die();
		}
		
		// Check if ticket is closed
		if($this->tickets_model->get_guest_ticket_status($code) != '3') {
			header('Location: ' . $this->config->base_url() . 'ticket/' .$code);
			return;
		}
		
		// Validate rating
		if(!isset($_POST['rating']) || (int)$_POST['rating'] > 10 || (int)$_POST['rating'] == 0) {
			$_SESSION['guest_ticket_rate_error'] = 'Please select a rating';
			header('Location: ' . $this->config->base_url() . 'ticket/' .$code);
			return;
		}
		
		// Post values to var...
		$rating = (int)$_POST['rating'] / 2;
		$msg = htmlentities($_POST['rate_text']);
		
		$this->tickets_model->submit_guest_rating($code, $rating, $msg);

		header('Location: ' . $this->config->base_url() . 'ticket/' .$code);
	}
}