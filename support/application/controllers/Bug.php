<?php

/******** Tickerr - Controller ********
 * Controller Name:	Bug
 * Description: 	This controller is used to display a bug report to a guest
**/
class Bug extends CI_Controller {
	public function index($code) {
		// Load settings model
		$this->load->model('Settings_model', 'settings_model', true);
		
		// Check if the user is already logged in
		if($this->session->tickerr_logged != NULL && is_array($this->session->tickerr_logged)) {
			header('Location: ' . $this->config->base_url().'panel/');
			die();
		}
		
		// Check if the bug report exists and if it's public (guest)
		$this->load->model('Bugs_model', 'bugs_model', true);
		if($this->bugs_model->check_guest_bug($code) == false) {
			header('Location: ' . $this->config->base_url());
			die();
		}
		
		$this->load->model('Users_model', 'users_model', true);
		$config['base_url'] = $this->config->base_url();
		$config['bug_info'] = $this->bugs_model->guest_bug_info($code);
		
		if($config['bug_info']->agentid == '0')
			$config['agent_info'] = false;
		else
			$config['agent_info'] = $this->users_model->get_user_info($config['bug_info']->agentid);
		
		if($config['bug_info']->files != '') {
			/**
			 * String syntax:
			   secure_file_name.ext*original_file_name.ext|secure_file_name.ext*original_file_name.ext
			 *
			**/
			$this->load->helper('uploaded_files');
			
			$config['bug_files'] = array();
			$_files = separate_files($config['bug_info']->files);
			foreach($_files as $file) {
				$file_ = explode('*', $file);
				$check = check_file($file_);
				if($check != false) {
					$file_[] = $check;
					$config['bug_files'][] = $file_;
				}
			}
			
		}else{
			$config['bug_files'] = false;
		}
		
		$config['department_name'] = $this->bugs_model->get_department_name($config['bug_info']->department);
		$config['created_on'] = date('M jS, Y \a\t H:i:s', strtotime($config['bug_info']->date));
		if($config['bug_info']->last_update == '0000-00-00 00:00:00')
			$config['last_update'] = 'N/A';
		else
			$config['last_update'] = date('M jS, Y \a\t H:i:s', strtotime($config['bug_info']->last_update));		
		
		$config['users_model'] = $this->users_model;
		
		// Get the site title for the header
		$config['site_title'] = $this->settings_model->get_setting('site_title');
		
		// Load view
		$this->load->view('guest_bug', $config);
	}
}