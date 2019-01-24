<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'Login';
$route['404_override'] = '';
$route['translate_uri_dashes'] = TRUE;

// Access to assets
$route['assets/(:any)'] = 'assets/$1';

// To download files
$route['file/(:num)/(:num)/(:any)'] = 'file/index/$1/$2/$3';

/***** Starting here are URI Routes to make URLs easier *****/
$route['ticket/(:any)/new-reply'] = 'ticket/new-reply/$1';											// Controller: Ticket    -    Function: new_reply
$route['ticket/(:any)/rate'] = 'ticket/rate/$1';													// Controller: Ticket    -    Function: rate
$route['ticket/(:any)'] = 'ticket/index/$1';														// Controller: Ticket    -    Function: constructor
$route['bug/(:any)'] = 'bug/index/$1';																// Controller: Bug       -    Function: constructor
$route['panel/ticket/(:any)/new-agent-reply'] = 'panel/ticket-new-agent-reply/$1';					// Controller: Panel     -    Function: ticket_new_agent_reply
$route['panel/ticket/(:any)/new-client-reply'] = 'panel/ticket-new-client-reply/$1';				// Controller: Panel     -    Function: ticket_new_client_reply
$route['panel/ticket/(:any)/rate'] = 'panel/ticket-rate/$1';										// Controller: Panel     -    Function: ticket_rate
$route['panel/ticket/(:any)/delete'] = 'panel/delete-ticket/$1';									// Controller: Panel     -    Function: delete_ticket
$route['panel/bug/(:any)/update-bug-status'] = 'panel/update-bug-status/$1';						// Controller: Panel     -    Function: update_bug_status
$route['panel/bug/(:any)/transfer-bug'] = 'panel/transfer-bug/$1';									// Controller: Panel     -    Function: transfer_bug
$route['panel/bug/(:any)/change-priority'] = 'panel/change-bug-priority/$1';						// Controller: Panel     -    Function: change_bug_priority
$route['panel/bug/(:any)/take'] = 'panel/take-bug/$1';												// Controller: Panel     -    Function: take_bug
$route['panel/bug/(:any)/delete'] = 'panel/delete-bug/$1';											// Controller: Panel     -    Function: delete_bug
$route['panel/admin/general-stats'] = 'admin-panel/general-stats';									// Controller: Admin_panel     -    Function: general_stats
$route['panel/admin/all-tickets'] = 'admin-panel/all-tickets';										// Controller: Admin_panel     -    Function: all_tickets
$route['panel/admin/new-tickets'] = 'admin-panel/new-tickets';										// Controller: Admin_panel     -    Function: new_tickets
$route['panel/admin/open-tickets'] = 'admin-panel/open-tickets';									// Controller: Admin_panel     -    Function: open_tickets
$route['panel/admin/closed-tickets'] = 'admin-panel/closed-tickets';								// Controller: Admin_panel     -    Function: closed_tickets
$route['panel/admin/pending-tickets'] = 'admin-panel/pending-tickets';								// Controller: Admin_panel     -    Function: pending_tickets
$route['panel/admin/ticket-departments'] = 'admin-panel/ticket-departments';						// Controller: Admin_panel     -    Function: ticket_departments
$route['panel/admin/ticket-departments/new-department'] = 'admin-panel/new-ticket-department';		// Controller: Admin_panel     -    Function: new_ticket_department
$route['panel/admin/user/(:num)'] = 'admin-panel/user/$1';											// Controller: Admin_panel     -    Function: user
$route['panel/admin/user/(:num)/edit'] = 'admin-panel/edit-user/$1';								// Controller: Admin_panel     -    Function: edit_user
$route['panel/admin/user/(:num)/edit/action'] = 'admin-panel/edit-user-action/$1';					// Controller: Admin_panel     -    Function: edit_user_action
$route['panel/admin/user/(:num)/tickets'] = 'admin-panel/client-all-tickets/$1';					// Controller: Admin_panel     -    Function: client_all_tickets
$route['panel/admin/user/(:num)/bugs'] = 'admin-panel/client-all-bugs/$1';							// Controller: Admin_panel     -    Function: client_all_bugs
$route['panel/admin/user/(:num)/ratings'] = 'admin-panel/client-all-ratings/$1';					// Controller: Admin_panel     -    Function: client_all_ratings
$route['panel/admin/user/(:num)/delete'] = 'admin-panel/delete_user/$1';							// Controller: Admin_panel     -    Function: delete_user
$route['panel/admin/user/(:num)/closed-tickets'] = 'admin-panel/agent-closed-tickets/$1';			// Controller: Admin_panel     -    Function: agent_closed_tickets
$route['panel/admin/user/(:num)/solved-bugs'] = 'admin-panel/agent-solved-bugs/$1';					// Controller: Admin_panel     -    Function: agent_solved_bugs
$route['panel/admin/user/(:num)/received-ratings'] = 'admin-panel/agent-received-ratings/$1';		// Controller: Admin_panel     -    Function: agent_received_ratings
$route['panel/admin/all-users'] = 'admin-panel/all-users';											// Controller: Admin_panel     -    Function: all_users
$route['panel/admin/all-users/(:num)/delete'] = 'admin-panel/delete-user/$1';						// Controller: Admin_panel     -    Function: delete_user
$route['panel/admin/new-user'] = 'admin-panel/new-user';											// Controller: Admin_panel     -    Function: new_user
$route['panel/admin/ticket-department/(:num)'] = 'admin-panel/ticket-department/$1';				// Controller: Admin_panel     -    Function: ticket_department
$route['panel/admin/ticket-department/(:num)/delete'] = 'admin-panel/delete-ticket-department/$1';	// Controller: Admin_panel     -    Function: delete_ticket_department
$route['panel/admin/ticket-department/(:num)/edit'] = 'admin-panel/edit-ticket-department/$1';		// Controller: Admin_panel     -    Function: edit_ticket_department
$route['panel/admin/ticket-department/(:num)/edit/action'] = 'admin-panel/action-edit-ticket-department/$1';			// Controller: Admin_panel     -    Function: action_edit_ticket_department
$route['panel/admin/ticket-department/(:num)/default'] = 'admin-panel/default-ticket-department/$1';					// Controller: Admin_panel     -    Function: default_ticket_department
$route['panel/admin/ticket-department/(:num)/remove-agent/(:num)'] = 'admin-panel/tdepartment-remove-agent/$1/$2';	 	// Controller: Admin_panel     -    Function: tdepartment_remove_agent
$route['panel/admin/ticket-department/(:num)/new-tickets'] = 'admin-panel/ticket-department-new-tickets/$1';			// Controller: Admin_panel     -    Function: ticket_department_new_tickets
$route['panel/admin/ticket-department/(:num)/open-tickets'] = 'admin-panel/ticket-department-open-tickets/$1';			// Controller: Admin_panel     -    Function: ticket_department_open_tickets
$route['panel/admin/ticket-department/(:num)/pending-tickets'] = 'admin-panel/ticket-department-pending-tickets/$1';	// Controller: Admin_panel     -    Function: ticket_department_pending_tickets
$route['panel/admin/free-bugs'] = 'admin-panel/free-bugs';											// Controller: Admin_panel     -    Function: free_bugs
$route['panel/admin/all-bugs'] = 'admin-panel/all-bugs';											// Controller: Admin_panel     -    Function: all_bugs
$route['panel/admin/solved-bugs'] = 'admin-panel/solved-bugs';										// Controller: Admin_panel     -    Function: solved_bugs
$route['panel/admin/bug-departments'] = 'admin-panel/bug-departments';								// Controller: Admin_panel     -    Function: bug_departments
$route['panel/admin/bug-department/(:num)'] = 'admin-panel/bug-department/$1';						// Controller: Admin_panel     -    Function: bug_department
$route['panel/admin/bug-department/(:num)/free-bugs'] = 'admin-panel/bug-department-free-bugs/$1';	// Controller: Admin_panel     -    Function: bug_department_free_bugs
$route['panel/admin/bug-department/(:num)/solved-bugs'] = 'admin-panel/bug-department-solved-bugs/$1';		// Controller: Admin_panel     -    Function: bug_department_solved_bugs
$route['panel/admin/bug-department/(:num)/other-bugs'] = 'admin-panel/bug-department-other-bugs/$1';		// Controller: Admin_panel     -    Function: bug_department_other_bugs
$route['panel/admin/bug-department/(:num)/remove-agent/(:num)'] = 'admin-panel/bdepartment-remove-agent/$1/$2';	// Controller: Admin_panel     -    Function: bdepartment_remove_agent
$route['panel/admin/bug-departments/new-department'] = 'admin-panel/new-bug-department';				// Controller: Admin_panel     -    Function: new_bug_department
$route['panel/admin/bug-department/(:num)/delete'] = 'admin-panel/delete-bug-department/$1';			// Controller: Admin_panel     -    Function: delete_bug_department
$route['panel/admin/bug-department/(:num)/edit'] = 'admin-panel/edit-bug-department/$1';				// Controller: Admin_panel     -    Function: edit_bug_department
$route['panel/admin/bug-department/(:num)/edit/action'] = 'admin-panel/action-edit-bug-department/$1';	// Controller: Admin_panel     -    Function: action_edit_bug_department
$route['panel/admin/bug-department/(:num)/default'] = 'admin-panel/default-bug-department/$1';			// Controller: Admin_panel     -    Function: default_bug_department
$route['panel/admin/general-settings'] = 'admin-panel/general-settings';								// Controller: Admin_panel     -    Function: general_settings
$route['panel/admin/general-settings/action'] = 'admin-panel/general-settings-action';					// Controller: Admin_panel     -    Function: general_settings_action
$route['panel/admin/mailer-settings'] = 'admin-panel/mailer-settings';									// Controller: Admin_panel     -    Function: mailer_settings
$route['panel/admin/mailer-settings/action'] = 'admin-panel/mailer-settings-action';					// Controller: Admin_panel     -    Function: mailer_settings_action
$route['panel/admin/econfirm-settings'] = 'admin-panel/econfirm-settings';								// Controller: Admin_panel     -    Function: econfirm_settings
$route['panel/admin/econfirm-settings/action'] = 'admin-panel/econfirm-settings-action';				// Controller: Admin_panel     -    Function: econfirm_settings_action
$route['panel/admin/arecovery-settings'] = 'admin-panel/arecovery-settings';							// Controller: Admin_panel     -    Function: arecovery_settings
$route['panel/admin/arecovery-settings/action'] = 'admin-panel/arecovery-settings-action';				// Controller: Admin_panel     -    Function: arecovery_settings_action
$route['panel/admin/emails-settings'] = 'admin-panel/emails-settings';									// Controller: Admin_panel     -    Function: emails_settings
$route['panel/admin/emails-settings/action'] = 'admin-panel/emails-settings-action';					// Controller: Admin_panel     -    Function: emails_settings_action
$route['panel/admin/logo-settings'] = 'admin-panel/logo-settings';										// Controller: Admin_panel     -    Function: logo_settings
$route['panel/admin/logo-settings/action'] = 'admin-panel/logo-settings-action';						// Controller: Admin_panel     -    Function: logo_settings_action
