<?php

/******** Tickerr - Controller ********
 * Controller Name:	Account
 * Description: 	Used for general account purposes. It contains
 *					only one function to confirm the email address
 *					of a new account
**/
class Account extends CI_Controller {
	
	// Confirm the email address of a new account
	public function confirm($code = null) {
		// Code was not passed? Return to home
		if($code == null) {
			header('Location: ' . $this->config->base_url());
			die();
		}
		
		// Check if code exists
		$this->load->model('Loginactions_model', 'loginactions_model', true);
		if($this->loginactions_model->check_activation_code($code) == false) {
			header('Location: ' . $this->config->base_url());
			die();
		}
		
		// Check if the account is already activated
		if($this->loginactions_model->check_activated_account($code) == true) {
			header('Location: ' . $this->config->base_url());
			die();
		}
		
		// Activate account
		$this->loginactions_model->activate_account($code);
		
		// Get the email address
		$email = $this->loginactions_model->get_email_by_activation($code);
		
		// Should we send an email?
		$this->load->model('Settings_model', 'settings_model', true);
		if($this->settings_model->get_setting('mailing') == '1' && $this->settings_model->get_setting('send_email_confirmed_account') == '1') {
			// Get email settings
			$config = $this->settings_model->get_email_settings();
			$email_info = $this->settings_model->get_email_info();
			$email_specific = $this->settings_model->get_email_specific('email_confirmed_account');
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
				'%user_email%'
			);
			
			$replace_to = array(
				$this->settings_model->get_setting('site_title'),
				$this->config->base_url(),
				$email
			);
			
			$this->email->message(str_replace($replace_from, $replace_to, $email_specific['content']));
			
			$this->email->send();
		}
		
		// Great
		$config['login_url'] = $this->config->base_url();
		$this->load->view('account_confirmation', $config);
		return;
	}
}