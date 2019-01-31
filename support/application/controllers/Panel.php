<?php

/******** Tickerr - Controller ********
 * Controller Name:	Panel
 * Description:		This is the most important controller, because here are
 *					the functions that run the panel for logged users (clients
 *					agents, and admins)
**/
class Panel extends CI_Controller {
	// This is the constructor of the class, and it acts as a filter
	// to allow logged users only
	public function __construct() {
		parent::__construct();
		
		// Load needed models
		$this->load->model('Loginactions_model', 'loginactions_model', true);
		$this->load->model('Settings_model', 'settings_model', true);
		
		// Session
		$session = $this->session;
		
		if($session->tickerr_logged == NULL || !is_array($session->tickerr_logged)) {
			header('Location: '.$this->config->base_url());
			die();
		}
		
		$session_user = $session->tickerr_logged[0];
		$session_pass = $session->tickerr_logged[1];
		
		if($this->loginactions_model->validate_session($session_user, $session_pass) == false) {
			header('Location: '.$this->config->base_url());
			die();
		}
	}
	
	// Load sidebar stats of an agent
	private function load_agent_sidebar_stats(&$config) {
		$config['sidebar_agent_new_tickets'] = $this->agent_model->count_new_tickets();
		$config['sidebar_agent_open_tickets'] = $this->agent_model->count_open_tickets();
		$config['sidebar_agent_tickets'] = $config['sidebar_agent_new_tickets'] + $config['sidebar_agent_open_tickets'];
		$config['sidebar_agent_free_bugs'] = $this->agent_model->count_free_bugs();
		$config['sidebar_agent_my_bugs'] = $this->agent_model->count_my_bugs();
		$config['sidebar_agent_bugs'] = $config['sidebar_agent_free_bugs'] + $config['sidebar_agent_my_bugs'];
		return true;
	}
	
	// Load sidebar stats of an admin
	private function load_admin_sidebar_stats(&$config) {
		$this->load->model('Admin_model', 'admin_model', true);
		$config['sidebar_admin_new_tickets'] = $this->admin_model->count_new_tickets();
		$config['sidebar_admin_open_tickets'] = $this->admin_model->count_open_tickets();
		$config['sidebar_admin_tickets'] = $config['sidebar_admin_new_tickets'] + $config['sidebar_admin_open_tickets'];
		$config['sidebar_admin_free_bugs'] = $this->admin_model->count_free_bugs();
		$config['sidebar_admin_bugs'] = $config['sidebar_admin_free_bugs'];
		return true;
	}
	
	// Load sidebar stats of a client
	private function load_client_sidebar_stats(&$config) {
		$config['sidebar_client_open_tickets'] = $this->client_model->count_open_tickets();
		return true;
	}
	
	// Page: panel/
	// Displays the index page
	public function index() {
		// Load user model
		$this->load->model('Users_model', 'users_model', true);
		$this->load->model('Tickets_model', 'tickets_model', true);
		$this->load->model('Bugs_model', 'bugs_model', true);
		
		// Get user id
		$userid = $this->users_model->get_user_id($this->session->tickerr_logged[0]);
		
		$config['users_model'] = $this->users_model;
		$config['tickets_model'] = $this->tickets_model;
		$config['bugs_model'] = $this->bugs_model;
		
		// Get user info
		$config['user_info'] = $this->users_model->get_user_info($userid);
		
		// Base
		$config['base_url'] = $this->config->base_url();
		
		// Admin stats
		if($config['user_info']->role == '3')
			$this->load_admin_sidebar_stats($config);
		
		// Same thing for agent and admin
		if($config['user_info']->role == '2' || $config['user_info']->role == '3') {
			$this->load->model('Agent_model', 'agent_model', true);
			$this->agent_model->set_agent_id($config['user_info']->id);
			
			$config['top_counter'] = array(
				'pending_bugs' => $this->agent_model->count_pending_bugs(),
				'pending_tickets' => $this->agent_model->count_pending_tickets(),
				'no_agent_tickets' => $this->agent_model->count_no_agent_tickets(),
				'pending_client_tickets' => $this->agent_model->count_pending_client_tickets(),
				'solved_tickets' => $this->agent_model->count_solved_tickets(),
				'customer_satisfaction' => $this->agent_model->get_customer_satisfaction()
			);
			
			$config['tickets_no_agent'] = $this->agent_model->get_tickets_no_agent();
			$config['tickets_awaiting'] = $this->agent_model->get_tickets_awaiting();
			$config['pending_bugs'] = $this->agent_model->get_pending_bugs();
			
			// Current page
			$config['current_page'] = 1;
			
			// Load sidebar stats
			$this->load_agent_sidebar_stats($config);
			
			// Get the site title for the header
			$config['site_title'] = $this->settings_model->get_setting('site_title');
		
			$this->load->view('panel_header', $config);			// Load header
			$this->load->view('panel_sidebar', $config);		// Load sidebar
			$this->load->view('agent/dashboard', $config);		// Load rest of the page
		}else{
			$this->load->model('Client_model', 'client_model', true);
			$this->client_model->set_client_id($config['user_info']->id);
			
			// Current page
			$config['current_page'] = 24;
			
			// Load sidebar stats
			$this->load_client_sidebar_stats($config);
			
			$config['top_counter'] = array(
				'pending_tickets' => $this->client_model->count_pending_c_tickets(),
				'no_agent_tickets' => $this->client_model->count_no_agent_tickets(),
				'pending_agent_tickets' => $this->client_model->count_pending_agent_tickets(),
				'solved_tickets' => $this->client_model->count_solved_tickets(),
				'pending_bugs' => $this->client_model->count_pending_bugs()
			);
			
			$config['tickets_awaiting'] = $this->client_model->get_tickets_awaiting();
			$config['tickets_awaiting_agent'] = $this->client_model->get_tickets_awaiting_agent();
			$config['tickets_without_agent'] = $this->client_model->get_tickets_without_agent();
			$config['solved_tickets'] = $this->client_model->get_closed_tickets(9);
			$config['pending_bugs'] = $this->client_model->get_pending_bugs();
			
			// Get the site title for the header
			$config['site_title'] = $this->settings_model->get_setting('site_title');
			
			$this->load->view('panel_header', $config);			// Load header
			$this->load->view('panel_sidebar', $config);		// Load sidebar
			$this->load->view('client/dashboard', $config);		// Load rest of the page
		}
	}
	
	// Page: panel/new-ticket
	// Displays the form to create a new ticket (clients only)
	public function new_ticket() {
		// Load models
		$this->load_model_combo();
		
		// Get user id
		$userid = $this->users_model->get_user_id($this->session->tickerr_logged[0]);
		
		// Get user info
		$config['user_info'] = $this->users_model->get_user_info($userid);
		$userinfo = $config['user_info'];
		
		// Only for clients
		if($config['user_info']->role != '1') {
			header('Location: ' . $this->config->base_url() . 'panel/');
			die();
		}
		
		// Base
		$config['base_url'] = $this->config->base_url();
		
		// Current page for the sidebar
		$config['current_page'] = 25;
		
		// Load sidebar stats
		$this->client_model->set_client_id($userid);
		$this->load_client_sidebar_stats($config);
		
		// Get tickets' departments
		$config['ticket_departments'] = $this->tickets_model->get_ticket_departments();
		
		if($this->settings_model->get_setting('allow_file_uploads') == '1')
			$config['allow_files'] = true;
		else
			$config['allow_files'] = false;
			
		// Allowed extensions
		$ext = $this->settings_model->get_setting('file_uploads_extensions');
		if($ext == '') {
			$config['all_extensions_allowed'] = true;
			$config['allowed_extensions'] = '';
		}else{
			$config['all_extensions_allowed'] = false;
			$config['allowed_extensions'] = $ext;
		}
		
		// If POST information is received
		if(isset($_POST['subject'])) {
			$this->load->library('form_validation');
			$this->form_validation->set_rules('subject','subject','required|min_length[5]');
			$this->form_validation->set_rules('ticket_msg','ticket_msg','required|min_length[11]');
			
			if($this->form_validation->run() == false) die();
			
			$subject = $this->input->post('subject');
			$department = $this->input->post('department');
			$message = $this->input->post('ticket_msg');
			$nfiles = (isset($_FILES['files'])) ? count($_FILES['files']['name']) : 0;
			
			// Tinyeditor fix to the message
			$message = str_replace('<span style="letter-spacing: -0.129999995231628px;">','<span>', $message);
			
			$this->load->library('upload');
			
			// Can we receive files?
			if($this->settings_model->get_setting('allow_file_uploads') == '1') {
				// File? Upload
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
								$config['error'] = "One or more files had an invalid extension. The only allowed extensions are: ".$allowed_ext;
								$this->load_view_combo('client/new_ticket', $config);
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
							$config['error'] = "Files size cannot be greater than $max_filesize MB";
							$this->load_view_combo('client/new_ticket', $config);
							return;
						}
					}
					
					$uploaded = upload_multiple_files('files', $nfiles);
					
					if($uploaded == false) {
						$config['error'] = "One or more files couldn't be uplaoded. Please try again";
						$this->load_view_combo('client/new_ticket', $config);
						return;
					}else{
						$dbfiles = implode('|', $uploaded);
					}
				}else{
					$dbfiles = '';
				}
			}else{
				$dbfiles = '';
			}

			$subject = filter_var($subject, FILTER_SANITIZE_STRING);
			
			$insert = $this->tickets_model->new_ticket($userid, $subject, $department, $message, $dbfiles);
			if($insert == false) {
				$config['error'] = "Your ticket couldn't be created. Please try again";
				$this->load_view_combo('client/new_ticket', $config);
				return;
			}else{
				// NEW
				// Success. Check if we should send email or not
				// If email is enabled, load library and continue
				if($this->settings_model->get_setting('mailing') == '1') {
					$this->load->library('email');
					
					// Should we send an email to all agents on this department?
					if($this->settings_model->get_setting('send_agents_email_ticket_client_submitted') == '1') {
					
						// Get email settings and initialize everything
						$config = $this->settings_model->get_email_settings();
						$email_info = $this->settings_model->get_email_info();
						$email_specific = $this->settings_model->get_email_specific('agents_email_ticket_client_submitted');
						
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
									'%user_username%',
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
									$userinfo->name,
									$userinfo->username,
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

				
				header('Location: ' . $this->config->base_url() . 'panel/ticket/' . $insert);
				die();
			}
		}else{
			$this->load_view_combo('client/new_ticket', $config);
		}
	}
	
	// Page: panel/new-bug-report
	// Displays the form to create a new bug report (clients only)
	public function new_bug_report() {
		// Load required models
		$this->load->model('Users_model', 'users_model', true);
		$this->load->model('Tickets_model', 'tickets_model', true);
		$this->load->model('Bugs_model', 'bugs_model', true);
		$this->load->model('Client_model', 'client_model', true);
		
		// Get user id
		$userid = $this->users_model->get_user_id($this->session->tickerr_logged[0]);
		
		// Get user info
		$config['user_info'] = $this->users_model->get_user_info($userid);
		$userinfo = $config['user_info'];
		
		// Base
		$config['base_url'] = $this->config->base_url();
		
		// Current page for the sidebar
		$config['current_page'] = 26;
		
		// Load sidebar stats
		$this->client_model->set_client_id($userid);
		$this->load_client_sidebar_stats($config);
		
		// List of bugs' departments
		$config['bug_departments'] = $this->bugs_model->get_bug_departments();
		
		if($this->settings_model->get_setting('allow_file_uploads') == '1')
			$config['allow_files'] = true;
		else
			$config['allow_files'] = false;
			
		// Allowed extensions
		$ext = $this->settings_model->get_setting('file_uploads_extensions');
		if($ext == '') {
			$config['all_extensions_allowed'] = true;
			$config['allowed_extensions'] = '';
		}else{
			$config['all_extensions_allowed'] = false;
			$config['allowed_extensions'] = $ext;
		}
		
		if(isset($_POST['subject'])) {
			$this->load->library('form_validation');
			$this->form_validation->set_rules('subject','subject','required|min_length[5]');
			$this->form_validation->set_rules('report_msg','report_msg','required|min_length[11]');
			
			if($this->form_validation->run() == false) die();
			
			$subject = $this->input->post('subject');
			$department = $this->input->post('department');
			$message = $this->input->post('report_msg');
			$nfiles = (isset($_FILES['files'])) ? count($_FILES['files']['name']) : 0;
			
			// Tinyeditor fix to the message
			$message = str_replace('<span style="letter-spacing: -0.129999995231628px;">','<span>', $message);
			
			$this->load->library('upload');
			
			// Can we receive files?
			if($this->settings_model->get_setting('allow_file_uploads') == '1') {
				// File? Upload
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
								$config['error'] = "One or more files had an invalid extension. The only allowed extensions are: ".$allowed_ext;
								$this->load_view_combo('client/new_bug_report', $config);
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
							$config['error'] = "Files size cannot be greater than $max_filesize MB";
							$this->load_view_combo('client/new_bug_report', $config);
							return;
						}
					}
					
					$uploaded = upload_multiple_files('files', $nfiles);
					
					if($uploaded == false) {
						$config['error'] = "One or more files couldn't be uplaoded. Please try again";
						$this->load_view_combo('client/new_bug_report', $config);
						return;
					}else{
						$dbfiles = implode('|', $uploaded);
					}
				}else{
					$dbfiles = '';
				}
			}else{
				$dbfiles = '';
			}
			
			$insert = $this->bugs_model->new_bug_report($userid, $subject, $department, $message, $dbfiles);
			if($insert == false) {
				$config['error'] = "Your bug report couldn't be created. Please try again";
				$this->load_view_combo('client/new_bug_report', $config);
				return;
			}else{
				// NEW
				// Success. Check if we should send email or not
				// If email is enabled, load library and continue
				if($this->settings_model->get_setting('mailing') == '1') {
					$this->load->library('email');
					
					// Should we send an email to all agents on this department?
					if($this->settings_model->get_setting('send_agents_email_bug_client_submitted') == '1') {
					
						// Get email settings and initialize everything
						$config = $this->settings_model->get_email_settings();
						$email_info = $this->settings_model->get_email_info();
						$email_specific = $this->settings_model->get_email_specific('agents_email_bug_client_submitted');
						
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
								$this->email->cc($email_info['email_cc']);
								$this->email->subject($email_specific['title']);
								
								$this->email->to($agent->email);
								
								$replace_from = array(
									'%site_title%',
									'%site_url%',
									'%user_name%',
									'%user_username%',
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
									$userinfo->name,
									$userinfo->username,
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
			
				header('Location: ' . $this->config->base_url() . 'panel/bug/' . $insert);
				die();
			}
		}else{
			$this->load_view_combo('client/new_bug_report', $config);
		}
	}
	
	// Page: panel/all-tickets
	// Displays list of all tickets
	public function all_tickets() {
		// Required models
		$this->load_model_combo();
		
		// Get user id and info
		$userid = $this->users_model->get_user_id($this->session->tickerr_logged[0]);
		$config['user_info'] = $this->users_model->get_user_info($userid);
		
		// Set client id
		$this->client_model->set_client_id($config['user_info']->id);
		
		// Pass base and models to the view
		$config['base_url'] = $this->config->base_url();
		$config['users_model'] = $this->users_model;
		$config['tickets_model'] = $this->tickets_model;

		// Different sort for client and agent
		$client_sort = array('last_update','id','subject','priority','department_name','last_update');
		$agent_sort = array('last_update','id','subject','priority','client_final_name','department_name','last_update');
		
		// Sort...
		if(isset($_GET['sort']) && isset($_GET['w'])) {
			if($config['user_info']->role == '1')
				$sort = $this->sorter($_GET['sort'], $client_sort);
			else
				$sort = $this->sorter($_GET['sort'], $agent_sort);
			$config['sort'] = $_GET['sort'];
			$sort_direction = $this->sort_direction($_GET['w']);
		}else{
			$sort_direction = 'DESC';
			if($config['user_info']->role == '1')
				$config['sort'] = 5;
			else
				$config['sort'] = 6;
			$sort = 'last_update';
		}
		$config['sort_direction'] = $sort_direction;
		
		// Records to show per page
		$records_per_page = 20;
		
		// Pagination
		if(!isset($_GET['page'])) $page = 1;
		else $page = $_GET['page'];
		if($page == 1) $from = 0;
		else $from = (($page-1)*$records_per_page);
		
		// Admin stats
		if($config['user_info']->role == '3')
			$this->load_admin_sidebar_stats($config);
		
		// Only for clients
		if($config['user_info']->role == 1) {
			// Current page for the sidebar
			$config['current_page'] = 27;
			
			// Load sidebar stats
			$this->client_model->set_client_id($userid);
			$this->load_client_sidebar_stats($config);
			
			// Partially load view
			$this->load_partial_view_combo($config);
			
			// Search?
			if(isset($_GET['search'])) {
				$config['search'] = $_GET['search'];
				$config['all_tickets'] = $this->client_model->get_all_tickets($records_per_page,$from,$sort,$sort_direction,$_GET['search']);
				$config['all_tickets_count'] = $this->client_model->count_search_all_tickets($_GET['search']);
			}else{
				$config['search'] = false;
				$config['all_tickets'] = $this->client_model->get_all_tickets($records_per_page,$from,$sort,$sort_direction);
				$config['all_tickets_count'] = $this->client_model->count_all_tickets();
			}
			
			// Total pages
			$config['total_pages'] = round($config['all_tickets_count'] / $records_per_page);
			$config['page'] = $page;
			
			// Finish loading view
			$this->load->view('client/tickets/all_tickets', $config);
		}elseif($config['user_info']->role == '2'|| $config['user_info']->role == '3') {
			// Current page for the sidebar
			$config['current_page'] = 2;
			
			$this->agent_model->set_agent_id($userid);
			
			// Load sidebar stats
			$this->load_agent_sidebar_stats($config);
			
			// Partially load view
			$this->load_partial_view_combo($config);
			
			// Search?
			if(isset($_GET['search'])) {
				$config['search'] = $_GET['search'];
				$config['all_tickets'] = $this->agent_model->get_all_tickets($records_per_page,$from,$sort,$sort_direction,$_GET['search']);
				$config['all_tickets_count'] = $this->agent_model->count_search_all_tickets($_GET['search']);
			}else{
				$config['search'] = false;
				$config['all_tickets'] = $this->agent_model->get_all_tickets($records_per_page,$from,$sort,$sort_direction);
				$config['all_tickets_count'] = $this->agent_model->count_all_tickets();
			}
			
			// Total pages
			$config['total_pages'] = round($config['all_tickets_count'] / $records_per_page);
			$config['page'] = $page;
			
			// Finish loading view
			$this->load->view('agent/tickets/all_tickets', $config);
		}
	}
	
	// Page: panel/new-tickets
	// Displays list of all new tickets
	public function new_tickets() {
		// Required models
		$this->load_model_combo();
		
		// Get user id
		$userid = $this->users_model->get_user_id($this->session->tickerr_logged[0]);
		
		// Get user info
		$config['user_info'] = $this->users_model->get_user_info($userid);
		
		$this->client_model->set_client_id($config['user_info']->id);
		
		// Pass base and models to the view
		$config['base_url'] = $this->config->base_url();
		$config['users_model'] = $this->users_model;
		$config['tickets_model'] = $this->tickets_model;
		
		// Different sort for client and agent
		$client_sort = array('last_update','id','subject','priority','department_name','last_update');
		$agent_sort = array('last_update','id','subject','priority','client_final_name','department_name','last_update');
		
		// Sort...
		if(isset($_GET['sort']) && isset($_GET['w'])) {
			if($config['user_info']->role == '1')
				$sort = $this->sorter($_GET['sort'], $client_sort);
			else
				$sort = $this->sorter($_GET['sort'], $agent_sort);
			$config['sort'] = $_GET['sort'];
			$sort_direction = $this->sort_direction($_GET['w']);
		}else{
			$sort_direction = 'DESC';
			$config['sort'] = 6;
			$sort = 'last_update';
		}
		$config['sort_direction'] = $sort_direction;
		
		// Records to show per page
		$records_per_page = 20;
		
		// Pagination
		if(!isset($_GET['page'])) $page = 1;
		else $page = $_GET['page'];
		if($page == 1) $from = 0;
		else $from = (($page-1)*$records_per_page);
		
		// Admin stats
		if($config['user_info']->role == '3')
			$this->load_admin_sidebar_stats($config);
		
		// Only for clients
		if($config['user_info']->role == 1) {
			// Current page for the sidebar
			$config['current_page'] = 28;
			
			// Load sidebar stats
			$this->client_model->set_client_id($userid);
			$this->load_client_sidebar_stats($config);
			
			// Partially load view
			$this->load_partial_view_combo($config);
			
			// Search?
			if(isset($_GET['search'])) {
				$config['search'] = $_GET['search'];
				$config['all_tickets'] = $this->client_model->get_new_tickets($records_per_page,$from,$sort,$sort_direction,$_GET['search']);
				$config['all_tickets_count'] = $this->client_model->count_search_new_tickets($_GET['search']);
			}else{
				$config['search'] = false;
				$config['all_tickets'] = $this->client_model->get_new_tickets($records_per_page,$from,$sort,$sort_direction);
				$config['all_tickets_count'] = $this->client_model->count_new_tickets();
			}
			
			// Total pages
			$config['total_pages'] = round($config['all_tickets_count'] / $records_per_page);
			$config['page'] = $page;
			
			// Finish loading view
			$this->load->view('client/tickets/new_tickets', $config);
		}elseif($config['user_info']->role == '2'|| $config['user_info']->role == '3') {
			// Current page for the sidebar
			$config['current_page'] = 3;
			
			$this->agent_model->set_agent_id($userid);
			
			// Load sidebar stats
			$this->load_agent_sidebar_stats($config);
			
			// Partially load view
			$this->load_partial_view_combo($config);
			
			// Search?
			if(isset($_GET['search'])) {
				$config['search'] = $_GET['search'];
				$config['all_tickets'] = $this->agent_model->get_new_tickets($records_per_page,$from,$sort,$sort_direction,$_GET['search']);
				$config['all_tickets_count'] = $this->agent_model->count_search_new_tickets($_GET['search']);
			}else{
				$config['search'] = false;
				$config['all_tickets'] = $this->agent_model->get_new_tickets($records_per_page,$from,$sort,$sort_direction);
				$config['all_tickets_count'] = $this->agent_model->count_new_tickets();
			}
			
			// Total pages
			$config['total_pages'] = round($config['all_tickets_count'] / $records_per_page);
			$config['page'] = $page;
			
			// Finish loading view
			$this->load->view('agent/tickets/new_tickets', $config);
		}
	}
	
	// Page: panel/open-tickets
	// Displays list of all open tickets
	public function open_tickets() {
		// Required models
		$this->load_model_combo();
		
		// Get user id
		$userid = $this->users_model->get_user_id($this->session->tickerr_logged[0]);
		
		// Get user info
		$config['user_info'] = $this->users_model->get_user_info($userid);
		
		$this->client_model->set_client_id($config['user_info']->id);
		
		// Pass base and models to the view
		$config['base_url'] = $this->config->base_url();
		$config['users_model'] = $this->users_model;
		$config['tickets_model'] = $this->tickets_model;
		
		// Different sort for client and agent
		$client_sort = array('last_update','id','subject','priority','department_name','last_update');
		$agent_sort = array('last_update','id','subject','priority','client_final_name','department_name','last_update');
		
		// Sort...
		if(isset($_GET['sort']) && isset($_GET['w'])) {
			if($config['user_info']->role == '1')
				$sort = $this->sorter($_GET['sort'], $client_sort);
			else
				$sort = $this->sorter($_GET['sort'], $agent_sort);
			$config['sort'] = $_GET['sort'];
			$sort_direction = $this->sort_direction($_GET['w']);
		}else{
			$sort_direction = 'DESC';
			$config['sort'] = 6;
			$sort = 'last_update';
		}
		$config['sort_direction'] = $sort_direction;
		
		// Records to show per page
		$records_per_page = 20;
		
		// Pagination
		if(!isset($_GET['page'])) $page = 1;
		else $page = $_GET['page'];
		if($page == 1) $from = 0;
		else $from = (($page-1)*$records_per_page);
		
		// Admin stats
		if($config['user_info']->role == '3')
			$this->load_admin_sidebar_stats($config);
		
		// Only for clients
		if($config['user_info']->role == 1) {
			// Current page for the sidebar
			$config['current_page'] = 29;
			
			// Load sidebar stats
			$this->client_model->set_client_id($userid);
			$this->load_client_sidebar_stats($config);
			
			// Partially load view
			$this->load_partial_view_combo($config);
			
			// Search?
			if(isset($_GET['search'])) {
				$config['search'] = $_GET['search'];
				$config['all_tickets'] = $this->client_model->get_open_tickets($records_per_page,$from,$sort,$sort_direction,$_GET['search']);
				$config['all_tickets_count'] = $this->client_model->count_search_open_tickets($_GET['search']);
			}else{
				$config['search'] = false;
				$config['all_tickets'] = $this->client_model->get_open_tickets($records_per_page,$from,$sort,$sort_direction);
				$config['all_tickets_count'] = $this->client_model->count_open_tickets();
			}
			
			// Total pages
			$config['total_pages'] = round($config['all_tickets_count'] / $records_per_page);
			$config['page'] = $page;
			
			// Finish loading view
			$this->load->view('client/tickets/open_tickets', $config);
		}elseif($config['user_info']->role == '2'|| $config['user_info']->role == '3') {
			// Current page for the sidebar
			$config['current_page'] = 4;
			
			$this->agent_model->set_agent_id($userid);
			
			// Load sidebar stats
			$this->load_agent_sidebar_stats($config);
			
			// Partially load view
			$this->load_partial_view_combo($config);
			
			// Search?
			if(isset($_GET['search'])) {
				$config['search'] = $_GET['search'];
				$config['all_tickets'] = $this->agent_model->get_open_tickets($records_per_page,$from,$sort,$sort_direction,$_GET['search']);
				$config['all_tickets_count'] = $this->agent_model->count_search_open_tickets($_GET['search']);
			}else{
				$config['search'] = false;
				$config['all_tickets'] = $this->agent_model->get_open_tickets($records_per_page,$from,$sort,$sort_direction);
				$config['all_tickets_count'] = $this->agent_model->count_open_tickets();
			}
			
			// Total pages
			$config['total_pages'] = round($config['all_tickets_count'] / $records_per_page);
			$config['page'] = $page;
			
			// Finish loading view
			$this->load->view('agent/tickets/open_tickets', $config);
		}
	}
	
	// Page: panel/closed-tickets
	// Displays list of all closed tickets
	public function closed_tickets() {
		// Required models
		$this->load_model_combo();
		
		// Get user id
		$userid = $this->users_model->get_user_id($this->session->tickerr_logged[0]);
		
		// Get user info
		$config['user_info'] = $this->users_model->get_user_info($userid);
		
		$this->client_model->set_client_id($config['user_info']->id);
		
		// Pass base and models to the view
		$config['base_url'] = $this->config->base_url();
		$config['users_model'] = $this->users_model;
		$config['tickets_model'] = $this->tickets_model;
		
		// Different sort for client and agent
		$client_sort = array('last_update','id','subject','priority','department_name','last_update');
		$agent_sort = array('last_update','id','subject','priority','client_final_name','department_name','last_update');
		
		// Sort...
		if(isset($_GET['sort']) && isset($_GET['w'])) {
			if($config['user_info']->role == '1')
				$sort = $this->sorter($_GET['sort'], $client_sort);
			else
				$sort = $this->sorter($_GET['sort'], $agent_sort);
			$config['sort'] = $_GET['sort'];
			$sort_direction = $this->sort_direction($_GET['w']);
		}else{
			$sort_direction = 'DESC';
			$config['sort'] = 6;
			$sort = 'last_update';
		}
		$config['sort_direction'] = $sort_direction;
		
		// Records to show per page
		$records_per_page = 20;
		
		// Pagination
		if(!isset($_GET['page'])) $page = 1;
		else $page = $_GET['page'];
		if($page == 1) $from = 0;
		else $from = (($page-1)*$records_per_page);
		
		// Admin stats
		if($config['user_info']->role == '3')
			$this->load_admin_sidebar_stats($config);
		
		// Only for clients
		if($config['user_info']->role == 1) {
			// Current page for the sidebar
			$config['current_page'] = 30;
			
			// Load sidebar stats
			$this->client_model->set_client_id($userid);
			$this->load_client_sidebar_stats($config);
			
			// Partially load view
			$this->load_partial_view_combo($config);
			
			// Search?
			if(isset($_GET['search'])) {
				$config['search'] = $_GET['search'];
				$config['all_tickets'] = $this->client_model->get_closed_tickets($records_per_page,$from,$sort,$sort_direction,$_GET['search']);
				$config['all_tickets_count'] = $this->client_model->count_search_closed_tickets($_GET['search']);
			}else{
				$config['search'] = false;
				$config['all_tickets'] = $this->client_model->get_closed_tickets($records_per_page,$from,$sort,$sort_direction);
				$config['all_tickets_count'] = $this->client_model->count_closed_tickets();
			}
			
			// Total pages
			$config['total_pages'] = round($config['all_tickets_count'] / $records_per_page);
			$config['page'] = $page;
			
			// Finish loading view
			$this->load->view('client/tickets/closed_tickets', $config);
		}elseif($config['user_info']->role == '2'|| $config['user_info']->role == '3') {
			// Current page for the sidebar
			$config['current_page'] = 5;
			
			$this->agent_model->set_agent_id($userid);
			
			// Load sidebar stats
			$this->load_agent_sidebar_stats($config);
			
			// Partially load view
			$this->load_partial_view_combo($config);
			
			// Search?
			if(isset($_GET['search'])) {
				$config['search'] = $_GET['search'];
				$config['all_tickets'] = $this->agent_model->get_closed_tickets($records_per_page,$from,$sort,$sort_direction,$_GET['search']);
				$config['all_tickets_count'] = $this->agent_model->count_search_closed_tickets($_GET['search']);
			}else{
				$config['search'] = false;
				$config['all_tickets'] = $this->agent_model->get_closed_tickets($records_per_page,$from,$sort,$sort_direction);
				$config['all_tickets_count'] = $this->agent_model->count_closed_tickets();
			}
			
			// Total pages
			$config['total_pages'] = round($config['all_tickets_count'] / $records_per_page);
			$config['page'] = $page;
			
			// Finish loading view
			$this->load->view('agent/tickets/closed_tickets', $config);
		}
	}

	// Page: panel/pending-tickets
	// Displays list of all pending tickets
	public function pending_tickets() {
		// Required models
		$this->load_model_combo();
		
		// Get user id
		$userid = $this->users_model->get_user_id($this->session->tickerr_logged[0]);
		
		// Get user info
		$config['user_info'] = $this->users_model->get_user_info($userid);
		
		$this->client_model->set_client_id($config['user_info']->id);
		
		// Pass base and models to the view
		$config['base_url'] = $this->config->base_url();
		$config['users_model'] = $this->users_model;
		$config['tickets_model'] = $this->tickets_model;
		
		// Different sort for client and agent
		$client_sort = array('last_update','id','subject','priority','department_name','last_update');
		$agent_sort = array('last_update','id','subject','priority','client_final_name','department_name','last_update');
		
		// Sort...
		if(isset($_GET['sort']) && isset($_GET['w'])) {
			if($config['user_info']->role == '1')
				$sort = $this->sorter($_GET['sort'], $client_sort);
			else
				$sort = $this->sorter($_GET['sort'], $agent_sort);
			$config['sort'] = $_GET['sort'];
			$sort_direction = $this->sort_direction($_GET['w']);
		}else{
			$sort_direction = 'DESC';
			$config['sort'] = 6;
			$sort = 'last_update';
		}
		$config['sort_direction'] = $sort_direction;
		
		// Records to show per page
		$records_per_page = 20;
		
		// Pagination
		if(!isset($_GET['page'])) $page = 1;
		else $page = $_GET['page'];
		if($page == 1) $from = 0;
		else $from = (($page-1)*$records_per_page);
		
		// Admin stats
		if($config['user_info']->role == '3')
			$this->load_admin_sidebar_stats($config);
		
		// Only for clients
		if($config['user_info']->role == 1) {
			// Current page for the sidebar
			$config['current_page'] = 31;
			
			// Load sidebar stats
			$this->client_model->set_client_id($userid);
			$this->load_client_sidebar_stats($config);
			
			// Partially load view
			$this->load_partial_view_combo($config);
			
			// Search?
			if(isset($_GET['search'])) {
				$config['search'] = $_GET['search'];
				$config['all_tickets'] = $this->client_model->get_pending_tickets($records_per_page,$from,$sort,$sort_direction,$_GET['search']);
				$config['all_tickets_count'] = $this->client_model->count_search_pending_tickets($_GET['search']);
			}else{
				$config['search'] = false;
				$config['all_tickets'] = $this->client_model->get_pending_tickets($records_per_page,$from,$sort,$sort_direction);
				$config['all_tickets_count'] = $this->client_model->count_pending_tickets();
			}
			
			// Total pages
			$config['total_pages'] = round($config['all_tickets_count'] / $records_per_page);
			$config['page'] = $page;
			
			// Finish loading view
			$this->load->view('client/tickets/pending_tickets', $config);
		}elseif($config['user_info']->role == '2'|| $config['user_info']->role == '3') {
			// Current page for the sidebar
			$config['current_page'] = 6;
			
			$this->agent_model->set_agent_id($userid);
			
			// Load sidebar stats
			$this->load_agent_sidebar_stats($config);
			
			// Partially load view
			$this->load_partial_view_combo($config);
			
			// Search?
			if(isset($_GET['search'])) {
				$config['search'] = $_GET['search'];
				$config['all_tickets'] = $this->agent_model->get_pending_tickets($records_per_page,$from,$sort,$sort_direction,$_GET['search']);
				$config['all_tickets_count'] = $this->agent_model->count_search_pending_tickets($_GET['search']);
			}else{
				$config['search'] = false;
				$config['all_tickets'] = $this->agent_model->get_pending_tickets($records_per_page,$from,$sort,$sort_direction);
				$config['all_tickets_count'] = $this->agent_model->count_pending_tickets_();
			}
			
			// Total pages
			$config['total_pages'] = round($config['all_tickets_count'] / $records_per_page);
			$config['page'] = $page;
			
			// Finish loading view
			$this->load->view('agent/tickets/pending_tickets', $config);
		}
	}
	
	// Page: panel/all-bugs
	// Displays list of all bug reports
	public function all_bugs() {
		// Required models
		$this->load_model_combo();
		
		// Get user id
		$userid = $this->users_model->get_user_id($this->session->tickerr_logged[0]);
		
		// Get user info
		$config['user_info'] = $this->users_model->get_user_info($userid);
		
		$this->client_model->set_client_id($config['user_info']->id);
		
		// Pass base and models to the view
		$config['base_url'] = $this->config->base_url();
		$config['users_model'] = $this->users_model;
		$config['tickets_model'] = $this->tickets_model;
		
		// Sort only for clients
		$client_sort = array('date','id','subject','priority','status','agent_final_name','department_name','date');
		
		// Sort...
		if(isset($_GET['sort']) && isset($_GET['w'])) {
			if($config['user_info']->role == '1')
				$sort = $this->sorter($_GET['sort'], $client_sort);
			else
				$sort = $this->sorter($_GET['sort'], $client_sort);
			$config['sort'] = $_GET['sort'];
			$sort_direction = $this->sort_direction($_GET['w']);
		}else{
			$sort_direction = 'DESC';
			$config['sort'] = 7;
			$sort = 'date';
		}
		$config['sort_direction'] = $sort_direction;
		
		// Records to show per page
		$records_per_page = 20;
		
		// Pagination
		if(!isset($_GET['page'])) $page = 1;
		else $page = $_GET['page'];
		if($page == 1) $from = 0;
		else $from = (($page-1)*$records_per_page);
		
		// Admin stats
		if($config['user_info']->role == '3')
			$this->load_admin_sidebar_stats($config);
		
		// Only for clients
		if($config['user_info']->role == 1) {
			// Current page for the sidebar
			$config['current_page'] = 32;
			
			// Load sidebar stats
			$this->client_model->set_client_id($userid);
			$this->load_client_sidebar_stats($config);
			
			// Partially load view
			$this->load_partial_view_combo($config);
			
			// Search?
			if(isset($_GET['search'])) {
				$config['search'] = $_GET['search'];
				$config['all_bugs'] = $this->client_model->get_all_bugs($records_per_page,$from,$sort,$sort_direction,$_GET['search']);
				$config['all_bugs_count'] = $this->client_model->count_search_all_bugs($_GET['search']);
			}else{
				$config['search'] = false;
				$config['all_bugs'] = $this->client_model->get_all_bugs($records_per_page,$from,$sort,$sort_direction);
				$config['all_bugs_count'] = $this->client_model->count_all_bugs();
			}
			
			// Total pages
			$config['total_pages'] = round($config['all_bugs_count'] / $records_per_page);
			$config['page'] = $page;
			
			// Finish loading view
			$this->load->view('client/bugs/all_bugs', $config);
		}
	}
	
	// Page: panel/solved-bugs
	// Displays list of all solved bug reports
	public function solved_bugs() {
		// Required models
		$this->load_model_combo();
		
		// Get user id
		$userid = $this->users_model->get_user_id($this->session->tickerr_logged[0]);
		
		// Get user info
		$config['user_info'] = $this->users_model->get_user_info($userid);
		
		$this->client_model->set_client_id($config['user_info']->id);
		
		// Pass base and models to the view
		$config['base_url'] = $this->config->base_url();
		$config['users_model'] = $this->users_model;
		$config['tickets_model'] = $this->tickets_model;
		
		// Different sort for client and agent
		$client_sort = array('last_update','id','subject','priority','agent_final_name','department_name','date');
		$agent_sort = array('last_update','id','subject','priority','client_final_name','department_name','date');
		
		// Sort...
		if(isset($_GET['sort']) && isset($_GET['w'])) {
			if($config['user_info']->role == '1')
				$sort = $this->sorter($_GET['sort'], $client_sort);
			else
				$sort = $this->sorter($_GET['sort'], $agent_sort);
			$config['sort'] = $_GET['sort'];
			$sort_direction = $this->sort_direction($_GET['w']);
		}else{
			$sort_direction = 'DESC';
			if($config['user_info']->role == '1')
				$config['sort'] = 7;
			else
				$config['sort'] = 6;
			$sort = 'last_update';
		}
		$config['sort_direction'] = $sort_direction;
		
		// Records to show per page
		$records_per_page = 20;
		
		// Pagination
		if(!isset($_GET['page'])) $page = 1;
		else $page = $_GET['page'];
		if($page == 1) $from = 0;
		else $from = (($page-1)*$records_per_page);
		
		// Admin stats
		if($config['user_info']->role == '3')
			$this->load_admin_sidebar_stats($config);
		
		// Only for clients
		if($config['user_info']->role == 1) {
			// Current page for the sidebar
			$config['current_page'] = 33;
			
			// Load sidebar stats
			$this->client_model->set_client_id($userid);
			$this->load_client_sidebar_stats($config);
			
			// Partially load view
			$this->load_partial_view_combo($config);
			
			// Search?
			if(isset($_GET['search'])) {
				$config['search'] = $_GET['search'];
				$config['all_bugs'] = $this->client_model->get_solved_bugs($records_per_page,$from,$sort,$sort_direction,$_GET['search']);
				$config['all_bugs_count'] = $this->client_model->count_search_solved_bugs($_GET['search']);
			}else{
				$config['search'] = false;
				$config['all_bugs'] = $this->client_model->get_solved_bugs($records_per_page,$from,$sort,$sort_direction);
				$config['all_bugs_count'] = $this->client_model->count_solved_bugs();
			}
			
			// Total pages
			$config['total_pages'] = round($config['all_bugs_count'] / $records_per_page);
			$config['page'] = $page;
			
			// Finish loading view
			$this->load->view('client/bugs/solved_bugs', $config);
		}elseif($config['user_info']->role == '2'|| $config['user_info']->role == '3') {
			// Current page for the sidebar
			$config['current_page'] = 9;
			
			$this->agent_model->set_agent_id($userid);
			
			// Load sidebar stats
			$this->load_agent_sidebar_stats($config);
			
			// Partially load view
			$this->load_partial_view_combo($config);
			
			// Search?
			if(isset($_GET['search'])) {
				$config['search'] = $_GET['search'];
				$config['all_bugs'] = $this->agent_model->get_solved_bugs($records_per_page,$from,$sort,$sort_direction,$_GET['search']);
				$config['all_bugs_count'] = $this->agent_model->count_search_solved_bugs($_GET['search']);
			}else{
				$config['search'] = false;
				$config['all_bugs'] = $this->agent_model->get_solved_bugs($records_per_page,$from,$sort,$sort_direction);
				$config['all_bugs_count'] = $this->agent_model->count_solved_bugs();
			}
			
			// Total pages
			$config['total_pages'] = round($config['all_bugs_count'] / $records_per_page);
			$config['page'] = $page;
			
			// Finish loading view
			$this->load->view('agent/bugs/solved_bugs', $config);
		}
	}
	
	// Page: panel/free-bugs
	// Displays list of all free bug reports
	public function free_bugs() {
		// Required models
		$this->load_model_combo();
		
		// Get user id
		$userid = $this->users_model->get_user_id($this->session->tickerr_logged[0]);
		
		// Get user info
		$config['user_info'] = $this->users_model->get_user_info($userid);
		
		$this->client_model->set_client_id($config['user_info']->id);
		$this->agent_model->set_agent_id($config['user_info']->id);
		
		// Pass base and models to the view
		$config['base_url'] = $this->config->base_url();
		$config['users_model'] = $this->users_model;
		$config['tickets_model'] = $this->tickets_model;
		
		// Different sort for client and agent
		$agent_sort = array('last_update','id','subject','priority','department_name','last_update');
		
		// Sort...
		if(isset($_GET['sort']) && isset($_GET['w'])) {
			$sort = $this->sorter($_GET['sort'], $agent_sort);
			$config['sort'] = $_GET['sort'];
			$sort_direction = $this->sort_direction($_GET['w']);
		}else{
			$sort_direction = 'DESC';
			$config['sort'] = 6;
			$sort = 'last_update';
		}
		$config['sort_direction'] = $sort_direction;
		
		// Records to show per page
		$records_per_page = 20;
		
		// Pagination
		if(!isset($_GET['page'])) $page = 1;
		else $page = $_GET['page'];
		if($page == 1) $from = 0;
		else $from = (($page-1)*$records_per_page);
		
		// Admin stats
		if($config['user_info']->role == '3')
			$this->load_admin_sidebar_stats($config);
		
		// Only for agents or admins
		if($config['user_info']->role == '2' || $config['user_info']->role == '3') {
			// Current page for the sidebar
			$config['current_page'] = 7;
			
			// Load sidebar stats
			$this->load_agent_sidebar_stats($config);
			
			// Partially load view
			$this->load_partial_view_combo($config);
			
			// Search?
			if(isset($_GET['search'])) {
				$config['search'] = $_GET['search'];
				$config['all_bugs'] = $this->agent_model->get_free_bugs($records_per_page,$from,$sort,$sort_direction,$_GET['search']);
				$config['all_bugs_count'] = $this->agent_model->count_search_free_bugs($_GET['search']);
			}else{
				$config['search'] = false;
				$config['all_bugs'] = $this->agent_model->get_free_bugs($records_per_page,$from,$sort,$sort_direction);
				$config['all_bugs_count'] = $this->agent_model->count_free_bugs();
			}
			
			// Total pages
			$config['total_pages'] = round($config['all_bugs_count'] / $records_per_page);
			$config['page'] = $page;
			
			// Finish loading view
			$this->load->view('agent/bugs/free_bugs', $config);
		}
	}
	
	// Page: panel/my-bugs
	// Displays list of all bug reports that have been taken by the current agent
	public function my_bugs() {
		// Required models
		$this->load_model_combo();
		
		// Get user id
		$userid = $this->users_model->get_user_id($this->session->tickerr_logged[0]);
		
		// Get user info
		$config['user_info'] = $this->users_model->get_user_info($userid);
		
		$this->client_model->set_client_id($config['user_info']->id);
		$this->agent_model->set_agent_id($config['user_info']->id);
		
		// Pass base and models to the view
		$config['base_url'] = $this->config->base_url();
		$config['users_model'] = $this->users_model;
		$config['tickets_model'] = $this->tickets_model;
		
		// Sort...
		if(isset($_GET['sort']) && isset($_GET['w'])) {
			$sort_ = $_GET['sort'];
			if($sort_ == '1') $sort = 'id';
			elseif($sort_ == '2') $sort = 'subject';
			elseif($sort_ == '3') $sort = 'priority';
			elseif($sort_ == '4') $sort = 'department_name';
			elseif($sort_ == '5') $sort = 'last_update';
			else $sort = 'last_update';
			
			$config['sort'] = $sort_;
			
			if($_GET['w'] == 'd')
				$sort_direction = 'DESC';
			else
				$sort_direction = 'ASC';
		}else{
			$sort_direction = 'DESC';
			$config['sort'] = 6;
			$sort = 'last_update';
		}
		$config['sort_direction'] = $sort_direction;
		
		// Records to show per page
		$records_per_page = 20;
		
		// Pagination
		if(!isset($_GET['page'])) $page = 1;
		else $page = $_GET['page'];
		if($page == 1) $from = 0;
		else $from = (($page-1)*$records_per_page);
		
		// Admin stats
		if($config['user_info']->role == '3')
			$this->load_admin_sidebar_stats($config);
		
		// Only for agents or admins
		if($config['user_info']->role == '2' || $config['user_info']->role == '3') {
			// Current page for the sidebar
			$config['current_page'] = 8;
			
			// Load sidebar stats
			$this->load_agent_sidebar_stats($config);
			
			// Partially load view
			$this->load_partial_view_combo($config);
			
			// Search?
			if(isset($_GET['search'])) {
				$config['search'] = $_GET['search'];
				$config['all_bugs'] = $this->agent_model->get_my_bugs($records_per_page,$from,$sort,$sort_direction,$_GET['search']);
				$config['all_bugs_count'] = $this->agent_model->count_search_my_bugs($_GET['search']);
			}else{
				$config['search'] = false;
				$config['all_bugs'] = $this->agent_model->get_my_bugs($records_per_page,$from,$sort,$sort_direction);
				$config['all_bugs_count'] = $this->agent_model->count_my_bugs();
			}
			
			// Total pages
			$config['total_pages'] = round($config['all_bugs_count'] / $records_per_page);
			$config['page'] = $page;
			
			// Finish loading view
			$this->load->view('agent/bugs/my_bugs', $config);
		}
	}
	
	// Page: panel/ticket/$code
	// Displays a ticket
	public function ticket($code) {
		// Required models
		$this->load_model_combo();
		
		// Get user id, info and role
		$userid = $this->users_model->get_user_id($this->session->tickerr_logged[0]);
		$config['user_info'] = $this->users_model->get_user_info($userid);
		$userrole = $config['user_info']->role;
		
		$config['users_model'] = $this->users_model;
		
		// Check if the ticket exists and if the user has privileges to see it
		if($this->tickets_model->ticket_exists($code) == false) {
			header('Location: '.$this->config->base_url() . 'panel/');
			return;
		}
		$config['ticket_info'] = $this->tickets_model->guest_ticket_info($code);
		
		if($userrole == '1') {
			if($config['ticket_info']->userid != $userid) {
				header('Location: '.$this->config->base_url() . 'panel/');
				return;
			}
		}elseif($userrole == '2') {
			if($config['ticket_info']->agentid != $userid && $config['ticket_info']->agentid != '0') {
				header('Location: '.$this->config->base_url() . 'panel/');
				return;
			}
		}elseif($userrole != '3') {
			header('Location: '.$this->config->base_url() . 'panel/');
			return;
		}
		
		// If the user is client
		if($config['user_info']->role == '1') {
			$this->client_model->set_client_id($userid);
			$this->load_client_sidebar_stats($config);
		}
		
		// Admin stats
		if($config['user_info']->role == '3')
			$this->load_admin_sidebar_stats($config);
		
		$config['is_client'] = false;
		$config['is_agent'] = false;
		$config['is_admin'] = false;
		
		if($userrole == '1') $config['is_client'] = true;
		elseif($userrole == '2') $config['is_agent'] = true;
		elseif($userrole == '3') $config['is_admin'] = true;
		
		// Pass the base to the view
		$config['base_url'] = $this->config->base_url();
		
		if($config['ticket_info']->agentid == '0')
			$config['agent_info'] = false;
		else
			$config['agent_info'] = $this->users_model->get_user_info($config['ticket_info']->agentid);
		
		$this->load->helper('uploaded_files');
		
		if($config['ticket_info']->files != '') {
			
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
		
		if(isset($_SESSION['new_reply_error']) && $_SESSION['new_reply_error'] != null) {
			$config['error'] = $_SESSION['new_reply_error'];
			$config['textarea_cont'] = $_SESSION['new_reply_error_textarea_cont'];
			$_SESSION['new_reply_error'] = null;
			$_SESSION['new_reply_error_textarea_cont'] = null;
		}else{
			$config['error'] = false;
			$config['textarea_cont'] = '';
		}
		
		if(isset($_SESSION['ticket_rate_error']) && $_SESSION['ticket_rate_error'] != null) {
			$config['error_rate'] = $_SESSION['ticket_rate_error'];
			$_SESSION['ticket_rate_error'] = null;
		}else{
			$config['error_rate'] = false;
		}
		
		$config['n_ticket_replies'] = $this->tickets_model->count_ticket_replies($code);
		if($config['n_ticket_replies'] == 0)
			$config['ticket_replies'] = false;
		else
			$config['ticket_replies'] = $this->tickets_model->get_ticket_replies($code);
		
		$config['users_model'] = $this->users_model;
		
		if($this->settings_model->get_setting('allow_file_uploads') == '1')
			$config['allow_files'] = true;
		else
			$config['allow_files'] = false;
			
		// Allowed extensions
		$ext = $this->settings_model->get_setting('file_uploads_extensions');
		if($ext == '') {
			$config['all_extensions_allowed'] = true;
			$config['allowed_extensions'] = '';
		}else{
			$config['all_extensions_allowed'] = false;
			$config['allowed_extensions'] = $ext;
		}
		
		$config['self_agent'] = false;
		
		if($userrole == '1') {
			$config['current_page'] = 27;
			$this->load_view_combo('client/ticket', $config);
		}elseif($userrole == '2' || $userrole == '3') {
			if($config['ticket_info']->agentid == $userid)
				$config['self_agent'] = true;
			
			$exp_departments = explode('|', $config['user_info']->ticket_departments);
			
			// Should we verify existing purchase code?
			if($this->settings_model->get_setting('confirm_purchase_codes') == '1') {
				// Load helper
				$this->load->helper('envato_verifier_helper');
				
				// Get envato Username and API Key
				$envato_username = $this->settings_model->get_setting('confirm_purchase_codes_username');
				$envato_api = $this->settings_model->get_setting('confirm_purchase_codes_api');
				
				// Detect code in the initial message first
				$message = $config['ticket_info']->content;
				preg_match_all('/.{8}-.{4}-.{4}-.{4}-.{12}/', $message, $matches);
				
				if(count($matches[0]) > 0) {
					// Check every code
					foreach($matches[0] as $code) {
						if(verify_envato_purchase_code($envato_username, $envato_api, $code) == true)
							$config['ticket_info']->content = str_replace($code, '<span class="envato-verified">'.$code.' <i class="fa fa-check-circle"></i></span>', $config['ticket_info']->content);
						else
							$config['ticket_info']->content = str_replace($code, '<span class="envato-unverified">'.$code.'</span>', $config['ticket_info']->content);
					}
				}
				
				// Needed vars to verify codes in replies
				$config['confirm_purchase_codes'] = true;
				$config['confirm_purchase_codes_username'] = $envato_username;
				$config['confirm_purchase_codes_api'] = $envato_api;
			}else{
				$config['confirm_purchase_codes'] = false;
			}
			
			// See as agent
			// *If I'm the agent
			// *If doesn't have agent and I have access to departent
			if($config['ticket_info']->agentid == $userid || (in_array($config['ticket_info']->department, $exp_departments) && $config['ticket_info']->agentid == '0')) {
				$config['current_page'] = 2;
				
				$this->agent_model->set_agent_id($userid);
				
				// Load sidebar stats
				$this->load_agent_sidebar_stats($config);
				
				$config['departments'] = $this->tickets_model->get_ticket_departments();
				$this->load_view_combo('agent/ticket', $config);
			}elseif($config['ticket_info']->agentid != $userid || !in_array($config['ticket_info']->department, $exp_departments)) {
				// See as admin
				// * If I'm not the agent
				// * If doesn't have agent and I don't have access to department
				$config['current_page'] = 12;
				
				$this->agent_model->set_agent_id($userid);
				
				// Load sidebar stats
				$this->load_agent_sidebar_stats($config);
				
				$config['departments'] = $this->tickets_model->get_ticket_departments();
				$this->load_view_combo('agent/ticket', $config);
			}
		}
	}
	
	// Submits a new agent's reply and returns to the ticket itself
	public function ticket_new_agent_reply($code) {
		// Required models
		$this->load->model('Agent_model', 'agent_model', true);
		$this->load->model('Users_model', 'users_model', true);
		$this->load->model('Tickets_model', 'tickets_model', true);
		
		// Get user id, info and role
		$userid = $this->users_model->get_user_id($this->session->tickerr_logged[0]);
		$config['user_info'] = $this->users_model->get_user_info($userid);
		$userrole = $config['user_info']->role;
		
		// Client? Get out of here
		if($userrole == '1') {
			header('Location: '.$this->config->base_url() . 'panel/');
			return;
		}
		
		// Ticket doesn't exist? Get out of here
		if($this->tickets_model->ticket_exists($code) == false) {
			header('Location: '.$this->config->base_url() . 'panel/');
			return;
		}
		
		// Get ticket info
		$ticket_info = $this->tickets_model->guest_ticket_info($code);
		
		// Can we receive files?
		if($this->settings_model->get_setting('allow_file_uploads') == '1') {
			// Do we have files?
			$nfiles = (isset($_FILES['files'])) ? count($_FILES['files']['name']) : 0;
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
							$_SESSION['new_reply_error'] = "One or more files had an invalid extension. The only allowed extensions are: ".$allowed_ext;
							$_SESSION['new_reply_error_textarea_cont'] = $_POST['reply'];
							header('Location: ' . $this->config->base_url() . 'panel/ticket/' .$code);
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
						$_SESSION['new_reply_error'] = "Files size cannot be greater than $max_filesize MB";
						$_SESSION['new_reply_error_textarea_cont'] = $_POST['reply'];
						header('Location: ' . $this->config->base_url() . 'panel/ticket/' .$code);
						return;
					}
				}

				$uploaded = upload_multiple_files('files', $nfiles);
				if($uploaded == false) {
					$_SESSION['new_reply_error'] = "One or more files couldn't be uploaded";
					$_SESSION['new_reply_error_textarea_cont'] = $_POST['reply'];
					header('Location: ' . $this->config->base_url() . 'panel/ticket/' .$code);
				}else{
					$dbfiles = implode('|', $uploaded);
				}
			}else{
				$dbfiles = '';
			}
		}else{
			$dbfiles = '';
		}
		
		if($_POST['reply'] != '') {
			// Tinyeditor fix to the message
			$reply = str_replace('<span style="letter-spacing: -0.129999995231628px;">','<span>', $_POST['reply']);
			
			// Submit reply and update last update date
			$this->tickets_model->submit_agent_reply($ticket_info->id, $userid, $reply, $dbfiles);
			
			// Assign ticket to agent
			$this->tickets_model->assign_ticket_agent($ticket_info->id, $userid);
			
			// Get client information
			if($ticket_info->userid != '0') {
				$client_info = $this->users_model->get_user_info($ticket_info->userid);
				if($client_info->email_on_tactivity == '1')
					$to = $client_info->email;
				else
					$to = false;
			}else{
				$to = $ticket_info->guest_email;
			}
			
			if($to != false) {
				if($this->settings_model->get_setting('mailing') == '1' && $this->settings_model->get_setting('send_email_new_reply') == '1') {
					
					// Email settings
					$config = $this->settings_model->get_email_settings();
					$email_info = $this->settings_model->get_email_info();
					$email_specific = $this->settings_model->get_email_specific('email_new_reply');
					
					$config['mailtype'] = $email_specific['type'];
					
					// Load library and prepare info
					$this->load->library('email');
					$this->email->initialize($config);
					$this->email->from($email_info['email_from_address'], $email_info['email_from_name']);
					$this->email->to($to);
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
						'%ticket_content%',
						'%ticket_status%',
						'%ticket_priority%',
						'%reply_content%'
					);
					
					// Is this ticket going to be closed?
					if(isset($_POST['close']) && $_POST['close'] == '1') $ticket_info->status = '3';
					
					// Get status
					if($ticket_info->status == '1' || $ticket_info->status == '2')
						$ticket_status = 'Open (Awaiting your reply)';
					else
						$ticket_status = 'Closed';
					
					// Get priority
					if($ticket_info->priority == '1') $ticket_priority = 'High';
					elseif($ticket_info->priority == '2') $ticket_priority = 'Medium';
					else $ticket_priority = 'Low';
					
					// Get user name
					if($ticket_info->userid != '0') $user_name = $client_info->name;
					else $user_name = $ticket_info->guest_name;
					
					$replace_to = array(
						$this->settings_model->get_setting('site_title'),
						$this->config->base_url(),
						$user_name,
						$ticket_info->access,
						$this->config->base_url() . 'panel/ticket/' . $ticket_info->access . '/',
						$ticket_info->subject,
						$ticket_info->department,
						$ticket_info->department_name,
						$ticket_info->content,
						$ticket_status,
						$ticket_priority,
						$_POST['reply']
					);
					
					$this->email->message(str_replace($replace_from, $replace_to, $email_specific['content']));
					$this->email->send();
				}
			}
		}
		
		// Do we need to close ticket?
		if(isset($_POST['close']) && $_POST['close'] == '1') {
			$this->tickets_model->close_ticket($ticket_info->id);
		}
		
		// Do we need to transfer ticket?
		if($_POST['transferToDept'] != 'none') {
			$this->tickets_model->transfer_to_dept($ticket_info->id, $_POST['transferToDept']);
		}
		
		// Do we need to change priority?
		$prevPriority = $_POST['previousPriority'];
		$priority = $_POST['changePriority'];
		if($prevPriority != $priority) {
			if($priority == '1' || $priority == '2' || $priority == '3')
				$this->tickets_model->change_priority($ticket_info->id, $priority);
		}
		
		// All done
		header('Location: ' . $this->config->base_url() . 'panel/ticket/' .$code);
	}
	
	// Submit a new rating. This is available for clients only to rate agents
	// Returns to the ticket itself
	public function ticket_rate($code) {
		// Required models
		$this->load->model('Users_model', 'users_model', true);
		$this->load->model('Tickets_model', 'tickets_model', true);
		
		// Check if user is client
		$userid = $this->users_model->get_user_id($this->session->tickerr_logged[0]);
		$userrole = $this->users_model->get_user_info($userid)->role;
		if($userrole != '1') {
			header('Location: ' . $this->config->base_url() . 'panel/ticket/' .$code);
			return;
		}
		
		// Check if ticket exists
		if($this->tickets_model->ticket_exists($code) == false) {
			header('Location: ' . $this->config->base_url() . 'panel/');
			return;
		}
		
		// Check if ticket is closed
		if($this->tickets_model->get_guest_ticket_status($code) != '3') {
			header('Location: ' . $this->config->base_url() . 'panel/ticket/' .$code);
			return;
		}
		
		// Validate rating
		if(!isset($_POST['rating']) || (int)$_POST['rating'] > 10 || (int)$_POST['rating'] == 0) {
			$_SESSION['guest_ticket_rate_error'] = 'Please select a rating';
			header('Location: ' . $this->config->base_url() . 'ticket/' .$code);
			return;
		}
		
		// Post values to var
		$rating = (int)$_POST['rating'] / 2;
		$msg = htmlentities($_POST['rate_text']);
		
		// Submit rating
		$this->tickets_model->submit_guest_rating($code, $rating, $msg);
		
		// Return to ciekt
		header('Location: ' . $this->config->base_url() . 'panel/ticket/' .$code);
	}
	
	// Submits a new client's reply and returns to the ticket itself
	public function ticket_new_client_reply($code) {
		// Load libraries
		$this->load->model('Users_model', 'users_model', true);
		$this->load->model('Tickets_model', 'tickets_model', true);
		
		// Check if user is client
		$userid = $this->users_model->get_user_id($this->session->tickerr_logged[0]);
		
		$userinfo = $this->users_model->get_user_info($userid);
		
		$userrole = $userinfo->role;
		
		if($userrole != '1') {
			header('Location: ' . $this->config->base_url() . 'panel/ticket/' .$code);
			return;
		}
		
		// Check if ticket exists
		if($this->tickets_model->ticket_exists($code) == false) {
			header('Location: ' . $this->config->base_url() . 'panel/');
			return;
		}
		
		$ticket_info = $this->tickets_model->guest_ticket_info($code);
		
		// Validate reply
		if(strlen($_POST['reply']) < 10) die();
		
		$nfiles = (isset($_FILES['files'])) ? count($_FILES['files']['name']) : 0;
		
		// Can we receive files?
		if($this->settings_model->get_setting('allow_file_uploads') == '1') {
			// Do we have a file? Upload
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
							$_SESSION['new_reply_error'] = "One or more files had an invalid extension. The only allowed extensions are: ".$allowed_ext;
							$_SESSION['new_reply_error_textarea_cont'] = $_POST['reply'];
							header('Location: ' . $this->config->base_url() . 'panel/ticket/' .$code);
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
						$_SESSION['new_reply_error'] = "Files size cannot be greater than $max_filesize MB";
						$_SESSION['new_reply_error_textarea_cont'] = $_POST['reply'];
						header('Location: ' . $this->config->base_url() . 'panel/ticket/' .$code);
						return;
					}
				}
				
				$uploaded = upload_multiple_files('files', $nfiles);
				if($uploaded == false) {
					$_SESSION['new_reply_error'] = "One or more files couldn't be uploaded";
					$_SESSION['new_reply_error_textarea_cont'] = $_POST['reply'];
					header('Location: ' . $this->config->base_url() . 'panel/ticket/' .$code);
				}else{
					$dbfiles = implode('|', $uploaded);
				}
			}else{
				// No files
				$dbfiles = '';
			}
		}else{
			$dbfiles = '';
		}
		
		// Tinyeditor fix to the message
		$reply = str_replace('<span style="letter-spacing: -0.129999995231628px;">','<span>', $_POST['reply']);
		
		// Submit reply and update last update date
		$this->tickets_model->submit_client_reply($ticket_info->id, $userid, $reply, $dbfiles);
		
		// NEW
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
						$userinfo->name,
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

		header('Location: ' . $this->config->base_url() . 'panel/ticket/' .$code);
	}
	
	// Page: panel/bug/$code
	// Displays a bug report
	public function bug($code) {
		// Required models
		$this->load->model('Users_model', 'users_model', true);
		$this->load->model('Client_model', 'client_model', true);
		$this->load->model('Agent_model', 'agent_model', true);
		$this->load->model('Bugs_model', 'bugs_model', true);
		
		// Get user id, info and role
		$userid = $this->users_model->get_user_id($this->session->tickerr_logged[0]);
		$config['user_info'] = $this->users_model->get_user_info($userid);
		$userrole = $config['user_info']->role;
		
		$config['users_model'] = $this->users_model;
		
		// Check if the report exists and if the user has privileges to see it
		if($this->bugs_model->bug_exists($code) == false) {
			header('Location: '.$this->config->base_url() . 'panel/');
			return;
		}
		$config['bug_info'] = $this->bugs_model->guest_bug_info($code);
		
		if($userrole == '1') {
			if($config['bug_info']->userid != $userid) {
				header('Location: '.$this->config->base_url() . 'panel/');
				return;
			}
		}elseif($userrole == '2') {
			if($config['bug_info']->agentid != $userid && $config['bug_info']->agentid != '0') {
				header('Location: '.$this->config->base_url() . 'panel/');
				return;
			}
		}elseif($userrole != '3') {
			header('Location: '.$this->config->base_url() . 'panel/');
			return;
		}
		
		// Client?
		if($config['user_info']->role == '1') {
			$this->client_model->set_client_id($userid);
			$this->load_client_sidebar_stats($config);
		}
		
		// Admin stats
		if($config['user_info']->role == '3')
			$this->load_admin_sidebar_stats($config);
		
		$config['is_client'] = false;
		$config['is_agent'] = false;
		$config['is_admin'] = false;
		
		if($userrole == '1') $config['is_client'] = true;
		elseif($userrole == '2') $config['is_agent'] = true;
		elseif($userrole == '3') $config['is_admin'] = true;
		
		$config['base_url'] = $this->config->base_url();
		
		if($config['bug_info']->agentid == '0')
			$config['agent_info'] = false;
		else
			$config['agent_info'] = $this->users_model->get_user_info($config['bug_info']->agentid);
		
		if($config['bug_info']->files != '') {
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
		
		
		if($userrole == '1') {
			$config['current_page'] = 32;
			$this->load_view_combo('client/bug', $config);
		}elseif($userrole == '2' || $userrole == '3') {
			$exp_departments = explode('|', $config['user_info']->bug_departments);
			
			if($config['bug_info']->agentid == $userid)
				$config['self_agent'] = true;
			
			// See as agent
			// *If I'm the agent
			// *If doesn't have agent and I have access to departent
			if($config['bug_info']->agentid == $userid || (in_array($config['bug_info']->department, $exp_departments) && $config['bug_info']->agentid == '0')) {
				$config['bug_departments'] = $this->bugs_model->get_bug_departments();
				
				if($config['bug_info']->agentid == $userid)
					$config['current_page'] = 8;
				else
					$config['current_page'] = 7;
				$this->agent_model->set_agent_id($userid);
				
				// Load sidebar stats
				$this->load_agent_sidebar_stats($config);
				
				// Finish loading view
				$this->load_view_combo('agent/bug', $config);
			}elseif($config['bug_info']->agentid != $userid || !in_array($config['bug_info']->department, $exp_departments)) {
				$config['bug_departments'] = $this->bugs_model->get_bug_departments();
				$config['current_page'] = 19;
				$this->agent_model->set_agent_id($userid);
				
				// Load sidebar stats
				$this->load_agent_sidebar_stats($config);
				
				// Finish loading view
				$this->load_view_combo('agent/bug', $config);
			}
		}
	}
	
	// Updates a bug report's status. For agents/admins only.
	// Returns to the bug report itself
	public function update_bug_status($code) {
		// Required models
		$this->load->model('Users_model', 'users_model', true);
		$this->load->model('Bugs_model', 'bugs_model', true);
		
		// Get user id, info and role
		$userid = $this->users_model->get_user_id($this->session->tickerr_logged[0]);
		$userinfo = $this->users_model->get_user_info($userid);
		$userrole = $userinfo->role;
		$bug_info = $this->bugs_model->guest_bug_info($code);
		
		// Is the user a client? Out..
		if($userrole == '1') {
			header('Location: '.$this->config->base_url() . 'panel/bug/'.$code);
			return;
		}
		
		// Check if the report exists
		if($this->bugs_model->bug_exists($code) == false) {
			header('Location: '.$this->config->base_url() . 'panel/');
			return;
		}
		
		$updated = false;
		
		// Check status
		$status = $_POST['bug_status'];
		if($status == '1' || $status == '2' || $status == '3' || $status == '4') {
			$status = (int)$status + 2;
			if($bug_info->status != $status) {
				$this->bugs_model->update_bug_status($code, $status);
				$updated = true;
			}
		}
		
		// Check message
		if($_POST['reply'] != '') {
			// Tinyeditor fix to the message
			$reply = str_replace('<span style="letter-spacing: -0.129999995231628px;">','<span>', $_POST['reply']);
			$this->bugs_model->update_bug_reply($code, $reply);
			$updated = true;
		}else{
			$reply = '';
		}
		
		if($updated == true) {
			// Get client information
			if($bug_info->userid != '0') {
				$client_info = $this->users_model->get_user_info($bug_info->userid);
				if($client_info->email_on_bactivity == '1')
					$to = $client_info->email;
				else
					$to = false;
			}else{
				$to = $bug_info->guest_email;
			}
			
			if($to != false) {
				if($this->settings_model->get_setting('mailing') == '1' && $this->settings_model->get_setting('send_email_bug_new_status') == '1') {
					// Email settings
					$config = $this->settings_model->get_email_settings();
					$email_info = $this->settings_model->get_email_info();
					$email_specific = $this->settings_model->get_email_specific('email_bug_new_status');
					
					$config['mailtype'] = $email_specific['type'];
					
					// Load library and prepare info
					$this->load->library('email');
					$this->email->initialize($config);
					$this->email->from($email_info['email_from_address'], $email_info['email_from_name']);
					$this->email->to($to);
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
						'%report_content%',
						'%report_status%',
						'%report_priority%'
					);
					
					// Get status
					if($bug_info->status == '1') $bug_status = 'Sent';
					elseif($bug_info->status == '2') $bug_status = 'Taken by Agent';
					elseif($bug_info->status == '3') $bug_status = 'Solved';
					elseif($bug_info->status == '4') $bug_status = 'Reviewed';
					elseif($bug_info->status == '5') $bug_status = 'Insolvable';
					elseif($bug_info->status == '6') $bug_status = 'Other';
					
					// Get priority
					if($bug_info->priority == '1') $bug_priority = 'High';
					elseif($bug_info->priority == '2') $bug_priority = 'Medium';
					else $bug_priority = 'Low';
					
					// Get user name
					if($bug_info->userid != '0') $user_name = $client_info->name;
					else $user_name = $bug_info->guest_name;
					
					$replace_to = array(
						$this->settings_model->get_setting('site_title'),
						$this->config->base_url(),
						$user_name,
						$bug_info->access,
						$this->config->base_url() . 'panel/ticket/' . $bug_info->access . '/',
						$bug_info->subject,
						$bug_info->department,
						$bug_info->department_name,
						$bug_info->content,
						$bug_status,
						$bug_priority,
						$_POST['reply']
					);
					
					$this->email->message(str_replace($replace_from, $replace_to, $email_specific['content']));
					$this->email->send();
				}
			}
		}
		
		// No agent? Assign one!
		if($updated == true) {
			if($bug_info->agentid == '0')
				$this->bugs_model->assign_agent($code, $userinfo->id);
		}
		
		// Return
		header('Location: '.$this->config->base_url() . 'panel/bug/'.$code);
	}
	
	// Transfers a bug report to another department
	public function transfer_bug($code) {
		// Required models
		$this->load->model('Users_model', 'users_model', true);
		$this->load->model('Bugs_model', 'bugs_model', true);
		
		// Get user id, info and role
		$userid = $this->users_model->get_user_id($this->session->tickerr_logged[0]);
		$userinfo = $this->users_model->get_user_info($userid);
		$userrole = $userinfo->role;
		$bug_info = $this->bugs_model->guest_bug_info($code);
		
		// Is the user a client? Out..
		if($userrole == '1') {
			header('Location: '.$this->config->base_url() . 'panel/bug/'.$code);
			return;
		}
		
		// Check if the report exists
		if($this->bugs_model->bug_exists($code) == false) {
			header('Location: '.$this->config->base_url() . 'panel/');
			return;
		}
		
		// Check if department exists
		if($this->bugs_model->department_exists($_POST['transfer-to']) == false) {
			header('Location: '.$this->config->base_url() . 'panel/');
			return;
		}
		
		// Transfer!
		$this->bugs_model->transfer_to($code, $_POST['transfer-to']);
		
		// Return
		header('Location: '.$this->config->base_url() . 'panel/bug/'.$code);
	}
	
	// Changes bug report's priority
	public function change_bug_priority($code) {
		// Required models
		$this->load->model('Users_model', 'users_model', true);
		$this->load->model('Bugs_model', 'bugs_model', true);
		
		// Get user id, info and role
		$userid = $this->users_model->get_user_id($this->session->tickerr_logged[0]);
		$userinfo = $this->users_model->get_user_info($userid);
		$userrole = $userinfo->role;
		$bug_info = $this->bugs_model->guest_bug_info($code);
		
		// Is the user a client? Out..
		if($userrole == '1') {
			header('Location: '.$this->config->base_url() . 'panel/bug/'.$code);
			return;
		}
		
		// Check if the report exists
		if($this->bugs_model->bug_exists($code) == false) {
			header('Location: '.$this->config->base_url() . 'panel/');
			return;
		}
		
		// Change priority
		$prevPriority = $_POST['previousPriority'];
		$priority = $_POST['changePriority'];
		if($prevPriority != $priority) {
			if($priority == '1' || $priority == '2' || $priority == '3')
				$this->bugs_model->change_priority($bug_info->id, $priority);
		}
		
		// Return
		header('Location: '.$this->config->base_url() . 'panel/bug/'.$code);
	}
	
	// This function assigns a bug report to an admin
	public function take_bug($code) {
		// Required models
		$this->load->model('Users_model', 'users_model', true);
		$this->load->model('Bugs_model', 'bugs_model', true);
		
		// Get user id, info and role
		$userid = $this->users_model->get_user_id($this->session->tickerr_logged[0]);
		$userinfo = $this->users_model->get_user_info($userid);
		$userrole = $userinfo->role;
		$bug_info = $this->bugs_model->guest_bug_info($code);
		
		// Is the user a client? Out..
		if($userrole == '1') {
			header('Location: '.$this->config->base_url() . 'panel/bug/'.$code);
			return;
		}
		
		// Check if the report exists
		if($this->bugs_model->bug_exists($code) == false) {
			header('Location: '.$this->config->base_url() . 'panel/');
			return;
		}
		
		// Check if doesn't have agent
		if($bug_info->agentid != '0') {
			header('Location: '.$this->config->base_url() . 'panel/bug/'.$code);
			return;
		}
		
		$this->bugs_model->take_bug($code, $userid);
		header('Location: '.$this->config->base_url() . 'panel/bug/'.$code);
	}
	
	// Deletes a bug report
	// Deletes a bug report
	public function delete_bug($code) {
		// Required models
		$this->load->model('Users_model', 'users_model', true);
		$this->load->model('Bugs_model', 'bugs_model', true);
		
		// Get user id, info and role
		$userid = $this->users_model->get_user_id($this->session->tickerr_logged[0]);
		$userinfo = $this->users_model->get_user_info($userid);
		$userrole = $userinfo->role;
		
		$bug_info = $this->bugs_model->guest_bug_info($code);
		
		// Is the user a client? Out..
		if($userrole == '1') {
			header('Location: '.$this->config->base_url() . 'panel/bug/'.$code);
			return;
		}
		
		// Delete!
		$this->bugs_model->delete_bug($code, $bug_info->department);

		// Return
		header('Location: '.$this->config->base_url() . 'panel/free-bugs');
	}
	
	// Deletes a ticket
	public function delete_ticket($code) {
		// Required models
		$this->load->model('Users_model', 'users_model', true);
		$this->load->model('Tickets_model', 'tickets_model', true);
		
		// Get user id, info and role
		$userid = $this->users_model->get_user_id($this->session->tickerr_logged[0]);
		$userinfo = $this->users_model->get_user_info($userid);
		$userrole = $userinfo->role;
		
		// Get ticket info
		$ticket_info = $this->tickets_model->guest_ticket_info($code);
		
		// Is the user a client? Out..
		if($userrole == '1') {
			header('Location: '.$this->config->base_url() . 'panel/bug/'.$code);
			return;
		}
		
		// Delete!
		$this->tickets_model->delete_ticket($code, $ticket_info->department);
		
		// Return
		header('Location: '.$this->config->base_url() . 'panel/all-tickets');
	}
	
	// Page: panel/admin/account-settings
	// Displays current acount's settings form
	public function account_settings($extra = false) {
		if($extra == 'basic-settings') {
			$this->account_settings_basic_settings();
			return;
		}elseif($extra == 'change-pp') {
			$this->account_settings_change_pp();
			return;
		}elseif($extra == 'change-password') {
			$this->account_settings_change_password();
			return;
		}
		
		// Required models
		$this->load->model('Users_model', 'users_model', true);
		$this->load->model('Client_model', 'client_model', true);
		$this->load->model('Agent_model', 'agent_model', true);
		
		// Get user id, info and role
		$userid = $this->users_model->get_user_id($this->session->tickerr_logged[0]);
		$config['user_info'] = $this->users_model->get_user_info($userid);
		$userrole = $config['user_info']->role;
		
		$config['users_model'] = $this->users_model;
		
		// Pass base to the view
		$config['base_url'] = $this->config->base_url();
		
		if(isset($_SESSION['change_password_error'])) {
			$config['change_password_error'] = true;
			unset($_SESSION['change_password_error']);
		}else
			$config['change_password_error'] = false;
			
		if(isset($_SESSION['change_password_success'])) {
			$config['change_password_success'] = true;
			unset($_SESSION['change_password_success']);
		}else
			$config['change_password_success'] = false;
		
		
		if($userrole == '1') {
			$config['current_page'] = 10;
			$this->client_model->set_client_id($userid);
			$this->load_client_sidebar_stats($config);
		}elseif($userrole == '2' || $userrole == '3') {
			$config['current_page'] = 10;
			$this->agent_model->set_agent_id($userid);
			
			// Load sidebar stats
			$this->load_agent_sidebar_stats($config);
		}
		if($userrole == '3') {
			// Load sidebar stats
			$this->load_admin_sidebar_stats($config);
		}
		
		// Finish loading view
		$this->load_view_combo('panel_account_settings', $config);
	}
	
	// Action of the first form of the previous function
	// (Account settings - Basic Settings)
	public function account_settings_basic_settings() {
		// Save settings
		$this->load->model('Users_model', 'users_model', true);
		$userid = $this->users_model->get_user_id($this->session->tickerr_logged[0]);

		// Check for all values to be set
		$values = array('user_name', 'user_email', 'email_on_tactivity', 'email_on_bactivity');
		foreach($values as $v) {
			if(!isset($_POST[$v])) {
				header('Location: ' . $this->config->base_url() . 'panel/account-settings');
				die();
			}else{
				$$v = $_POST[$v];
			}
		}
		
		if($user_name == ''
		   || filter_var($user_email, FILTER_VALIDATE_EMAIL) == false
		   || ($email_on_tactivity != '0' && $email_on_tactivity != '1')
		   || ($email_on_bactivity != '0' && $email_on_bactivity != '1')
		) {
			header('Location: ' . $this->config->base_url() . 'panel/account-settings');
			die();
		}
		
		// Save data
		$this->users_model->change_user_data($userid, 'name', $user_name);
		$this->users_model->change_user_data($userid, 'email', $user_email);
		$this->users_model->change_user_data($userid, 'email_on_tactivity', $email_on_tactivity);
		$this->users_model->change_user_data($userid, 'email_on_bactivity', $email_on_bactivity);		
		
		// Return
		header('Location: ' . $this->config->base_url() . 'panel/account-settings');
		die();
	}
	
	// Action of the second form of the Account Settings function
	// (Account settings - Change Profile Picture)
	public function account_settings_change_pp() {
		// Save settings
		$this->load->model('Users_model', 'users_model', true);
		$userid = $this->users_model->get_user_id($this->session->tickerr_logged[0]);

		// Check for image
		if(count($_FILES) != 1 || !isset($_FILES['new_profile_picture'])) {
			header('Location: ' . $this->config->base_url() . 'panel/account-settings');
			die();
		}
		
		// Check if format is supported
		$extension = pathinfo($_FILES['new_profile_picture']['name'], PATHINFO_EXTENSION);		
		$filename = pathinfo($_FILES['new_profile_picture']['name'], PATHINFO_FILENAME);
		$supported = false;
		if($extension == 'gif' && (imagetypes() & IMG_GIF)) $supported = true;
		if(($extension == 'jpg' || $extension == 'jpeg') && (imagetypes() & IMG_JPG)) $supported = true;
		if($extension == 'png' && (imagetypes() & IMG_PNG)) $supported = true;
		if($supported == false) {
			header('Location: ' . $this->config->base_url() . 'panel/account-settings');
			die();
		}
		
		// At this point the format is supported, create dir for the user (if it doesn't exist)
		$username = $this->session->tickerr_logged[0];
		$dir = FCPATH . 'assets/img/profile_img/' . $username . '/';
		if(!file_exists($dir)) mkdir($dir, 0777, true);
		
		// Upload the image
		$this->load->library('upload');
		$config = array(
			'upload_path' => $dir,
			'file_name' => $_FILES['new_profile_picture']['name'],
			'allowed_types' => 'jpg|png|gif',
			'max_size' => 0,
			'overwrite' => true,
			'remove_spaces' => false
		);
		$this->upload->initialize($config);
		$upload = $this->upload->do_upload('new_profile_picture');
		
		if($upload == false) {
			header('Location: ' . $this->config->base_url() . 'panel/account-settings');
			die();
		}
		
		// Upload was successful, now we need to resize to 3 different sizes
		$this->load->library('image_lib');
		$config['image_library'] = 'gd2';
		$config['source_image'] = $dir . $_FILES['new_profile_picture']['name'];
		$config['new_image'] = $dir . $filename . '@1x.' . $extension;
		$config['maintain_ratio'] = false;
		$config['width'] = 68;
		$config['height'] = 68;
		$this->image_lib->initialize($config);
		$resize1 = $this->image_lib->resize();
		
		// Second size...
		$this->image_lib->clear();
		$config['new_image'] = $dir . $filename . '@2x.' . $extension;
		$config['width'] = 136;
		$config['height'] = 136;
		$this->image_lib->initialize($config);
		$resize2 = $this->image_lib->resize();
		
		// Third size...
		$this->image_lib->clear();
		$config['new_image'] = $dir . $filename . '@3x.' . $extension;
		$config['width'] = 204;
		$config['height'] = 204;
		$this->image_lib->initialize($config);
		$resize3 = $this->image_lib->resize();
		
		// Error?
		if($resize1 == false || $resize2 == false || $resize3 == false) {
			header('Location: ' . $this->config->base_url() . 'panel/account-settings');
			die();
		}
		
		// All good? Remove original
		unlink($dir . $_FILES['new_profile_picture']['name']);
		
		// Update MySQL settings
		$this->users_model->change_user_data($userid, 'profile_img1x', $username . '/' . $filename . '@1x.' . $extension);
		$this->users_model->change_user_data($userid, 'profile_img2x', $username . '/' . $filename . '@2x.' . $extension);
		$this->users_model->change_user_data($userid, 'profile_img3x', $username . '/' . $filename . '@3x.' . $extension);
		
		// Done!
		header('Location: ' . $this->config->base_url() . 'panel/account-settings');
		die();
	}
	
	// Action of the third form of the Account Settings function
	// (Account settings - Change Password)
	public function account_settings_change_password() {
		// Save settings
		$this->load->model('Users_model', 'users_model', true);
		$userid = $this->users_model->get_user_id($this->session->tickerr_logged[0]);
		$current_pass = $this->session->tickerr_logged[1];
		
		// Check for all values to be set
		$values = array('current_password', 'new_password', 'new_rpassword');
		foreach($values as $v) {
			if(!isset($_POST[$v])) {
				header('Location: ' . $this->config->base_url() . 'panel/account-settings');
				die();
			}else{
				$$v = $_POST[$v];
			}
		}
		
		if($current_password == ''
		   || $new_password == ''
		   || $new_rpassword == ''
		   || strlen($new_password) < 5
		   || ($new_password != $new_rpassword)
		) {
			header('Location: ' . $this->config->base_url() . 'panel/account-settings');
			die();
		}
		
		// All good, check if current password is equal...
		if($current_pass != md5($current_password)) {
			$_SESSION['change_password_error'] = true;
			header('Location: ' . $this->config->base_url() . 'panel/account-settings');
			die();
		}
		
		// Perfect, change password and update session
		$this->users_model->change_user_data($userid, 'password', md5($_POST['new_password']));
		
		$_SESSION['tickerr_logged'][1] = md5($_POST['new_password']);
		$_SESSION['change_password_success'] = true;
		
		// Done!
		header('Location: ' . $this->config->base_url() . 'panel/account-settings');
		die();
	}
	
	// Function to logout
	public function logout() {
		unset($_SESSION['tickerr_logged']);
		header('Location: '.$this->config->item('base_url'));
		die();
	}
	
	private function load_model_combo() {
		$this->load->model('Users_model', 'users_model', true);
		$this->load->model('Tickets_model', 'tickets_model', true);
		$this->load->model('Client_model', 'client_model', true);
		$this->load->model('Agent_model', 'agent_model', true);
	}
	
	private function load_view_combo($last_view, $config) {
		// Get the site title for the header
		$config['site_title'] = $this->settings_model->get_setting('site_title');
		
		$this->load->view('panel_header', $config);
		$this->load->view('panel_sidebar', $config);
		$this->load->view($last_view, $config);
	}
	
	private function load_partial_view_combo($config) {
		// Get the site title for the header
		$config['site_title'] = $this->settings_model->get_setting('site_title');
		
		$this->load->view('panel_header', $config);
		$this->load->view('panel_sidebar', $config);
	}
	
	private function sorter($get, $arr) {
		$final_sort = false;
		$counter = 0;
		foreach($arr as $s) {
			if($get == $counter)
				$final_sort = $s;
			$counter += 1;
		}
		if($final_sort == false) return $arr[0];
		return $final_sort;
	}
	
	private function sort_direction($get) {
		if($get == 'd') return 'DESC';
		return 'ASC';
	}
}