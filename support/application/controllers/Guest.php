<?php

/******** Tickerr - Controller ********
 * Controller Name:	Guest
 * Description: 	Inside of this controller are functions that are only
 *					available for guest users. It is used to create new
 *					guest tickets, guest bug reports, and so on...
**/
class Guest extends CI_Controller {

	// If user is logged, redirect to the panel
	public function __construct() {
		parent::__construct();
		
		// Load needed models
		$this->load->model('Loginactions_model', 'loginactions_model', true);
		$this->load->model('Settings_model', 'settings_model', true);
		$this->load->model('Users_model', 'users_model', true);
		
		// Session
		$session = $this->session;
		if($session->tickerr_logged != NULL && is_array($session->tickerr_logged)) {
			// Validate session
			$session_user = $session->tickerr_logged[0];
			$session_pass = $session->tickerr_logged[1];

			// Is logged
			if($this->loginactions_model->validate_session($session_user, $session_pass) == true)
				header('Location: '.$this->config->base_url().'panel/');
		}
	}
	
	// Page: guest/new-ticket
	// Displays form to create a new ticket as guest
	public function new_ticket() {
		// Load validation library
		$this->load->library('form_validation');
		$this->load->model('Guest_model','guest_model',true);
		
		// Is this enabled?
		if($this->settings_model->get_setting('allow_guest_tickets') != '1') {
			header('Location: '.$this->config->base_url());
			return;
		}
		
		// Get the site title for the header
		$data['site_title'] = $this->settings_model->get_setting('site_title');
		
		// Form sent?
		// Ticket_sent session is used to avoid duplicate tickets if reloading the page
		if($this->input->post('name') != null && $this->session->ticket_sent == null) {
			// Validate fields
			// Even though they are jQuery-Validated, let's prevent any direct access
			$this->form_validation->set_rules('name','name','required|min_length[5]');
			$this->form_validation->set_rules('email','email','required|valid_email');
			$this->form_validation->set_rules('subject','subject','required|min_length[5]');
			$this->form_validation->set_rules('message','message','required|min_length[11]');
			
			// Validate fields
			$validation = $this->form_validation->run();
			
			// Validation is wrong, terminate code because it means direct access
			if($validation == false)
				die();
			
			// Check email address
			if($this->guest_model->check_existing_email($this->input->post('email')) == true){
				$data['departments'] = $this->guest_model->get_ticket_departments();
				$data['file_error'] = "It seems that you're already registered. Please login and submit your ticket using your account";
				$this->load->view('guest_new_ticket', $data);
				return;
			}
			
			// Validation is cool, insert data!
			if($validation == true) {
				$name = $this->input->post('name');
				$email = $this->input->post('email');
				$subject = $this->input->post('subject');
				$department = $this->input->post('department');
				$message = $this->input->post('message');
				$nfiles = (isset($_FILES['files'])) ? count($_FILES['files']['name']) : 0;
				
				// Tinyeditor fix to the message
				$message = str_replace('<span style="letter-spacing: -0.129999995231628px;">','<span>', $message);
				
				$this->load->library('upload');
				
				// Can we receive files?
				if($this->settings_model->get_setting('allow_guest_file_uploads') == '1') {
					// Do we have a file? Try to upload it
					if($nfiles > 0) {
						$this->load->helper('upload_helper');
						
						// Check extension and file size of each one
						$allowed_ext = $this->settings_model->get_setting('file_uploads_extensions');
						if($allowed_ext != '') {
							$allowed_ext = explode('|', $allowed_ext);
							foreach($_FILES['files']['name'] as $filename) {
								$file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
								if(!in_array($file_ext, $allowed_ext)) {
									$allowed_ext = implode(', ', $allowed_ext);
									$data['departments'] = $this->guest_model->get_ticket_departments();
									$data['file_error'] = "One or more files had an invalid extension. The only allowed extensions are: ".$allowed_ext;
									if($this->settings_model->get_setting('allow_guest_file_uploads') == '1')
										$data['allow_files'] = true;
									else
										$data['allow_files'] = false;
										
									// Allowed extensions
									$ext = $this->settings_model->get_setting('file_uploads_extensions');
									if($ext == '') {
										$data['all_extensions_allowed'] = true;
										$data['allowed_extensions'] = '';
									}else{
										$data['all_extensions_allowed'] = false;
										$data['allowed_extensions'] = $ext;
									}
									$this->load->view('guest_new_ticket', $data);
									return;
								}
							}
						}
						
						// Now check file sizes..
						$this->load->helper('ini_filesizes_helper');
						$upload_max_filesize_val = get_upload_max_filesize();
						$post_max_size_val = get_post_max_size();
						
						// Compare system vars with the number existing in the settings,
						// and get the lower
						$db_max_filesize = $this->settings_model->get_setting('file_uploads_max_size');
						$max_filesize = min($upload_max_filesize_val, $post_max_size_val, $db_max_filesize);
						
						// Check every file size
						foreach($_FILES['files']['size'] as $size) {
							// Convert size to MB
							$size = $size / 1024 / 1024;
							if($size > $max_filesize) {
								$data['departments'] = $this->guest_model->get_ticket_departments();
								$data['file_error'] = "Files size cannot be greater than $max_filesize MB";
								if($this->settings_model->get_setting('allow_guest_file_uploads') == '1')
									$data['allow_files'] = true;
								else
									$data['allow_files'] = false;
									
								// Allowed extensions
								$ext = $this->settings_model->get_setting('file_uploads_extensions');
								if($ext == '') {
									$data['all_extensions_allowed'] = true;
									$data['allowed_extensions'] = '';
								}else{
									$data['all_extensions_allowed'] = false;
									$data['allowed_extensions'] = $ext;
								}
								$this->load->view('guest_new_ticket', $data);
								return;
							}
						}
						
						$uploaded = upload_multiple_files('files', $nfiles);
						
						if($uploaded == false) {
							$data['departments'] = $this->guest_model->get_ticket_departments();
							$data['file_error'] = "One or more files couldn't be uploaded. Please try again";
							$this->load->view('guest_new_ticket', $data);
							return;
						}else{
							// Everything is cool, glue files together
							$dbfiles = implode('|', $uploaded);
						}
					}else{
						// No files
						$dbfiles = '';
					}
				}else{
					$dbfiles = '';
				}
				
				// Insert info
				$subject = filter_var($subject, FILTER_SANITIZE_STRING);
				$insert = $this->guest_model->new_guest_ticket($name, $email, $subject, $department, $message, $dbfiles);
				
				if($insert == false) {
					$data['departments'] = $this->guest_model->get_ticket_departments();
					$data['file_error'] = "Your ticket couldn't be created. Please try again";
					$this->load->view('guest_new_ticket', $data);
					return;
				}else{
					// Success. Check if we should send email or not
					// If email is enabled, load library and continue
					if($this->settings_model->get_setting('mailing') == '1') {
						$this->load->library('email');
						$this->load->model('Tickets_model','tickets_model',true);
						
						// Should we send an email to the guest?
						if($this->settings_model->get_setting('send_email_ticket_guest_submitted') == '1') {
							
							// Get email settings
							$config = $this->settings_model->get_email_settings();
							$email_info = $this->settings_model->get_email_info();
							$email_specific = $this->settings_model->get_email_specific('email_ticket_guest_submitted');
							
							$config['mailtype'] = $email_specific['type'];
							
							$this->email->initialize($config);
							
							$this->email->from($email_info['email_from_address'], $email_info['email_from_name']);
							$this->email->to($email);
							$this->email->cc($email_info['email_cc']);
							
							$this->email->subject($email_specific['title']);
							
							$replace_from = array(
								'%site_title%',
								'%site_url%',
								'%user_name%',
								'%ticket_code%',
								'%ticket_url%',
								'%ticket_subject%',
								'%ticket_department_id%',
								'%ticket_department_name%',
								'%ticket_content%'
							);
							$replace_to = array(
								$this->settings_model->get_setting('site_title'),
								$this->config->base_url(),
								$name,
								$insert,
								$this->config->base_url() . 'ticket/' . $insert . '/',
								$subject,
								$department,
								$this->tickets_model->get_department_name($department),
								$message
							);
							$this->email->message(str_replace($replace_from, $replace_to, $email_specific['content']));
							
							$this->email->send();
						}
						
						// NEW
						// Should we send an email to all agents on this department?
						if($this->settings_model->get_setting('send_agents_email_ticket_guest_submitted') == '1') {
						
							// Get email settings and initialize everything
							$config = $this->settings_model->get_email_settings();
							$email_info = $this->settings_model->get_email_info();
							$email_specific = $this->settings_model->get_email_specific('agents_email_ticket_guest_submitted');
							
							$config['mailtype'] = $email_specific['type'];
							
							$this->email->initialize($config);
							
							
							$agents_list = $this->users_model->get_agents();
							
							foreach($agents_list->result() as $agent) {
								// Extract departments
								$ticket_departments = explode('|', $agent->ticket_departments);
								
								// Is this agent responsible of this department? Send him an email
								if(in_array($department, $ticket_departments) === true) {
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
										'%agent_user_name%',
										'%agent_username%'
									);
									$replace_to = array(
										$this->settings_model->get_setting('site_title'),
										$this->config->base_url(),
										$name,
										$insert,
										$this->config->base_url() . 'ticket/' . $insert . '/',
										$subject,
										$department,
										$this->tickets_model->get_department_name($department),
										$message,
										$agent->name,
										$agent->username
									);
									$this->email->message(str_replace($replace_from, $replace_to, $email_specific['content']));
									
									$this->email->send();
								}
							}
						}
					}
					
					$data['ticket_id'] = $this->config->base_url() . 'ticket/' . $insert . '/';
					$this->load->view('guest_new_ticket_success', $data);
					$this->session->ticket_sent = true;
					return;
				}
			}
		}else{
			if($this->session->bug_sent != null)
				$_POST = null;

			// Just keep our way...
			$data['departments'] = $this->guest_model->get_ticket_departments();
			$data['file_error'] = false;
			if($this->settings_model->get_setting('allow_guest_file_uploads') == '1')
				$data['allow_files'] = true;
			else
				$data['allow_files'] = false;
				
			// Allowed extensions
			$ext = $this->settings_model->get_setting('file_uploads_extensions');
			if($ext == '') {
				$data['all_extensions_allowed'] = true;
				$data['allowed_extensions'] = '';
			}else{
				$data['all_extensions_allowed'] = false;
				$data['allowed_extensions'] = $ext;
			}
			
			// Reset var
			$this->session->ticket_sent = null;
			
			// Finish loading view
			$this->load->view('guest_new_ticket', $data);
		}
	}
	
	// Page: guest/new-bug-report
	// Displays form to create a new bug report as guest
	public function new_bug_report() {
		// Load validation library
		$this->load->library('form_validation');
		$this->load->model('Guest_model','guest_model',true);
		
		// Is this enabled?
		if($this->settings_model->get_setting('allow_guest_bug_reports') != '1') {
			header('Location: '.$this->config->base_url());
			return;
		}
		
		// Get the site title for the header
		$data['site_title'] = $this->settings_model->get_setting('site_title');
		
		// Form sent?
		// Bug_sent session is used to avoid duplicate tickets if reloading the page
		if($this->input->post('name') != null && $this->session->bug_sent == null) {
			// Validate fields
			// Even thought they are jQuery-Validated, let's prevent any direct access
			$this->form_validation->set_rules('name','name','required|min_length[5]');
			$this->form_validation->set_rules('email','email','required|valid_email');
			$this->form_validation->set_rules('subject','subject','required|min_length[5]');
			$this->form_validation->set_rules('message','message','required|min_length[10]');
			
			// Validate fields
			$validation = $this->form_validation->run();
			
			// Validation is wrong, terminate code because it means direct access
			if($validation == false)
				die();
				
			
			// Check email address
			if($this->guest_model->check_existing_email($this->input->post('email')) == true){
				$data = array(
					'departments' => $this->guest_model->get_bug_departments(),
					'file_error' => "It seems that you're already registered. Please login and submit your bug report using your account."
				);
				$this->load->view('guest_new_bug', $data);
				return;
			}
			
			// Validation is cool, insert data!
			if($validation == true) {
				$name = $this->input->post('name');
				$email = $this->input->post('email');
				$subject = $this->input->post('subject');
				$department = $this->input->post('department');
				$message = $this->input->post('message');
				$nfiles = (isset($_FILES['files'])) ? count($_FILES['files']['name']) : 0;
				
				// Tinyeditor fix to the message
				$message = str_replace('<span style="letter-spacing: -0.129999995231628px;">','<span>', $message);
				
				$this->load->library('upload');
				
				// Can we receive files?
				if($this->settings_model->get_setting('allow_guest_file_uploads') == '1') {
					// Do we have a file? Try to upload it
					if($nfiles > 0) {
						$this->load->helper('upload_helper');
						
						// Check extension and file size of each one
						$allowed_ext = $this->settings_model->get_setting('file_uploads_extensions');
						if($allowed_ext != '') {
							$allowed_ext = explode('|', $allowed_ext);
							foreach($_FILES['files']['name'] as $filename) {
								$file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
								if(!in_array($file_ext, $allowed_ext)) {
									$allowed_ext = implode(', ', $allowed_ext);
									$data['departments'] = $this->guest_model->get_bug_departments();
									$data['file_error'] = "One or more files had an invalid extension. The only allowed extensions are: ".$allowed_ext;
									if($this->settings_model->get_setting('allow_guest_file_uploads') == '1')
										$data['allow_files'] = true;
									else
										$data['allow_files'] = false;
										
									// Allowed extensions
									$ext = $this->settings_model->get_setting('file_uploads_extensions');
									if($ext == '') {
										$data['all_extensions_allowed'] = true;
										$data['allowed_extensions'] = '';
									}else{
										$data['all_extensions_allowed'] = false;
										$data['allowed_extensions'] = $ext;
									}
									$this->load->view('guest_new_bug', $data);
									return;
								}
							}
						}
						
						// Now check file sizes..
						$this->load->helper('ini_filesizes_helper');
						$upload_max_filesize_val = get_upload_max_filesize();
						$post_max_size_val = get_post_max_size();
						
						// Compare system vars with the number existing in the settings,
						// and get the lower
						$db_max_filesize = $this->settings_model->get_setting('file_uploads_max_size');
						$max_filesize = min($upload_max_filesize_val, $post_max_size_val, $db_max_filesize);
						
						// Check every file size
						foreach($_FILES['files']['size'] as $size) {
							// Convert size to MB
							$size = $size / 1024 / 1024;
							if($size > $max_filesize) {
								$data['departments'] = $this->guest_model->get_bug_departments();
								$data['file_error'] = "Files size cannot be greater than $max_filesize MB";
								if($this->settings_model->get_setting('allow_guest_file_uploads') == '1')
									$data['allow_files'] = true;
								else
									$data['allow_files'] = false;
									
								// Allowed extensions
								$ext = $this->settings_model->get_setting('file_uploads_extensions');
								if($ext == '') {
									$data['all_extensions_allowed'] = true;
									$data['allowed_extensions'] = '';
								}else{
									$data['all_extensions_allowed'] = false;
									$data['allowed_extensions'] = $ext;
								}
								$this->load->view('guest_new_bug', $data);
								return;
							}
						}
						
						$uploaded = upload_multiple_files('files', $nfiles);
						
						if($uploaded == false) {
							$data = array(
								'departments' => $this->guest_model->get_bug_departments(),
								'file_error' => "One or more files couldn't be uploaded. Please try again"
							);
							$this->load->view('guest_new_bug', $data);
							return;
						}else{
							// Everything is cool, glue files together
							$dbfiles = implode('|', $uploaded);
						}
					}else{
						// No files
						$dbfiles = '';
					}
				}else{
					$dbfiles = '';
				}
				
				// Insert info
				$insert = $this->guest_model->new_guest_bug($name, $email, $subject, $department, $message, $dbfiles);
				
				if($insert == false) {
					$data['departments'] = $this->guest_model->get_bug_departments();
					$data['file_error'] = "Your ticket couldn't be created. Please try again";
					$this->load->view('guest_new_bug', $data);
				}else{
					// Success. Check if we should send email or not
					// If email is enabled, load library and continue
					if($this->settings_model->get_setting('mailing') == '1') {
						$this->load->library('email');
						$this->load->model('Bugs_model','bugs_model',true);
						
						if($this->settings_model->get_setting('send_email_bug_guest_submitted') == '1') {
						
							// Get email settings
							$config = $this->settings_model->get_email_settings();
							$email_info = $this->settings_model->get_email_info();
							$email_specific = $this->settings_model->get_email_specific('email_bug_guest_submitted');
							
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
								'%user_name%',
								'%report_code%',
								'%report_url%',
								'%report_subject%',
								'%report_department_id%',
								'%report_department_name%',
								'%report_content%'
							);

							$replace_to = array(
								$this->settings_model->get_setting('site_title'),
								$this->config->base_url(),
								$name,
								$insert,
								$this->config->base_url() . 'bug/' . $insert . '/',
								$subject,
								$department,
								$this->bugs_model->get_department_name($department),
								$message
							);
							$this->email->message(str_replace($replace_from, $replace_to, $email_specific['content']));
							
							$this->email->send();
						}
					
						// NEW
						// Should we send an email to all agents on this department?
						if($this->settings_model->get_setting('send_agents_email_bug_guest_submitted') == '1') {
							
							// Get email settings
							$config = $this->settings_model->get_email_settings();
							$email_info = $this->settings_model->get_email_info();
							$email_specific = $this->settings_model->get_email_specific('agents_email_bug_guest_submitted');
							
							$config['mailtype'] = $email_specific['type'];
							
							$this->email->initialize($config);
							
							$agents_list = $this->users_model->get_agents();
							
							foreach($agents_list->result() as $agent) {
								// Extract departments
								$bug_departments = explode('|', $agent->bug_departments);
								
								// Is this agent responsible of this department? Send him an email
								if(in_array($department, $bug_departments) === true) {
									$this->email->clear();
							
									$this->email->from($email_info['email_from_address'], $email_info['email_from_name']);
									$this->email->subject($email_specific['title']);
									$this->email->cc($email_info['email_cc']);
									
									$this->email->to($agent->email);
							
									$replace_from = array(
										'%site_title%',
										'%site_url%',
										'%user_name%',
										'%report_code%',
										'%report_url%',
										'%report_subject%',
										'%report_department_id%',
										'%report_department_name%',
										'%report_content%',
										'%agent_user_name%',
										'%agent_username%'
									);

									$replace_to = array(
										$this->settings_model->get_setting('site_title'),
										$this->config->base_url(),
										$name,
										$insert,
										$this->config->base_url() . 'bug/' . $insert . '/',
										$subject,
										$department,
										$this->bugs_model->get_department_name($department),
										$message,
										$agent->name,
										$agent->username
									);
									$this->email->message(str_replace($replace_from, $replace_to, $email_specific['content']));
									
									$this->email->send();
								}
							}
						}
					}
					
					$data['ticket_id'] = $this->config->base_url() . 'bug/' . $insert . '/';
					$this->load->view('guest_new_bug_success', $data);
					$this->session->bug_sent = true;
				}
			}
		}else{
			if($this->session->bug_sent != null)
				$_POST = null;
				
			// Just keep our way...
			$data['departments'] = $this->guest_model->get_bug_departments();
			$data['file_error'] = false;
			if($this->settings_model->get_setting('allow_guest_file_uploads') == '1')
				$data['allow_files'] = true;
			else
				$data['allow_files'] = false;
				
			// Allowed extensions
			$ext = $this->settings_model->get_setting('file_uploads_extensions');
			if($ext == '') {
				$data['all_extensions_allowed'] = true;
				$data['allowed_extensions'] = '';
			}else{
				$data['all_extensions_allowed'] = false;
				$data['allowed_extensions'] = $ext;
			}
			
			// Reset var
			$this->session->bug_sent = null;
			
			// Finish loading view
			$this->load->view('guest_new_bug', $data);
		}
	}
	
	// Page: guest/new-account
	// Displays form to create a new account
	public function new_account() {
		$this->load->library('form_validation');
		$this->load->model('Guest_model','guest_model',true);
		
		// Is this enabled?
		if($this->settings_model->get_setting('allow_account_creations') != '1') {
			header('Location: '.$this->config->base_url());
			return;
		}
		
		// Get the site title for the header
		$data['site_title'] = $this->settings_model->get_setting('site_title');
		
		// Form sent
		if($this->input->post('name') != null && $this->session->account_form_sent == null) {
			// Form validation
			$validations = array(
				'name' => 'required|min_length[5]',
				'username' => 'required|min_length[5]',
				'email' => 'required|valid_email',
				'password' => 'required|min_length[5]',
				'rpassword' => 'required|matches[password]'
			);
			foreach($validations as $x => $y) {
				$this->form_validation->set_rules($x, $x, $y);
				$$x = $_POST[$x];
			}
			
			// Validate form, and no spaces for username/password
			$validate1 = $this->form_validation->run(); // False means direct access
			$validate2 = preg_match('/\s/', $username); // True means direct access
			$validate3 = preg_match('/\s/', $password); // True means direct access
			if($validate1 == false || $validate2 == true || $validate3 == true)
				die();
			
			// Check if username already exists
			if($this->guest_model->check_existing_username($_POST['username']) == true) {
				$data['error'] = 'Username already exists';
				$this->load->view('guest_new_account', $data);
				return;
			}
			
			// Check if email already exists
			if($this->guest_model->check_existing_email($_POST['email']) == true) {
				$data['error'] = 'Email address already exists';
				$this->load->view('guest_new_account', $data);
				return;
			}
			
			if($this->settings_model->get_setting('email_confirmation') == '0')
				$e_confirmation = false;
			else
				$e_confirmation = true;
			
			// Everything is good, insert new user
			$user = $this->guest_model->new_account($name, $username, $email, $password, $e_confirmation);
			
			// User inserted
			if($user != false) {
				/* If email confirmation NOT needed:
				 * Send email of account created
				 * Link tickets from that email to new account
				 *
				 * If email confirmation needed:
				 * Send email to confirmate account
				*/
				
				// What email should we send?
				// Email confirmation not needed, send email of account created
				if($this->settings_model->get_setting('mailing') == '1' && $e_confirmation == false && $this->settings_model->get_setting('send_email_new_account') == '1') {
					// Get email settings
					$config = $this->settings_model->get_email_settings();
					$email_info = $this->settings_model->get_email_info();
					$email_specific = $this->settings_model->get_email_specific('email_new_account');
					
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
						'%user_name%',
						'%user_username%',
						'%user_email%'
					);
					
					$replace_to = array(
						$this->settings_model->get_setting('site_title'),
						$this->config->base_url(),
						$name,
						$username,
						$email
					);
					$this->email->message(str_replace($replace_from, $replace_to, $email_specific['content']));
					$this->email->send();
					
					// After being sent, link all tickets from this email to the new account
					$this->guest_model->link_tickets($user, $email);
					$this->guest_model->link_bug_reports($user, $email);
				}elseif($e_confirmation == true) {
					// Send confirmation email
					// Get email settings
					$config = $this->settings_model->get_email_settings();
					$email_info = $this->settings_model->get_email_info();
					$email_specific = $this->settings_model->get_email_specific('email_new_account_confirmation');
					
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
						'%user_name%',
						'%user_username%',
						'%user_email%',
						'%confirmation_url%'
					);
					
					$replace_to = array(
						$this->settings_model->get_setting('site_title'),
						$this->config->base_url(),
						$name,
						$username,
						$email,
						$this->config->base_url('account/confirm/' . $this->guest_model->last_confirmation_str())
					);
					
					$this->email->message(str_replace($replace_from, $replace_to, $email_specific['content']));
					$this->email->send();
					
					// After sent, do nothing.
					// Show "you're gonna need to active your account" message
					$this->session->account_form_sent = true;
					$data['login_url'] = $this->config->base_url();
					$data['req_activation'] = true;
					$this->load->view('guest_new_account_success', $data);
					return;
				}
				
				$this->session->account_form_sent = true;
				$data['login_url'] = $this->config->base_url();
				$this->load->view('guest_new_account_success', $data);
				return;
			}else{
				$data['error'] = "Your account couldn't be created. Please try again";
				$this->load->view('guest_new_account', $data);
				return;
			}
		}else{
			// Form not sent
			if($this->session->account_form_sent != null) {
				$_POST = null;
				$this->session->account_form_sent = null;
			}
			
			$data['error'] = false;
			$this->load->view('guest_new_account', $data);
		}
	}
}