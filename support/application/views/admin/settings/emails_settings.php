<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
	<div class="content">
		<div class="page-title-cont clearfix">
			<h3>Emails Settings</h3>
		</div>
		
		<div class="row">
			<div class="col col-xs-12">
				<div class="cont clearfix">
					<div class="top clearfix">
						<h4 class="pull-left">Emails Settings</h4>
					</div>
					
					<p class="bg-danger" style="display:none;"></p>
					
					<?php
					if($settings->mailing == false)
						echo 'To have access to these settings, first you need to enable mailing. To do so, follow this URL: <a href="'.$base_url.'panel/admin/mailer-settings">'.$base_url.'panel/admin/mailer-settings</a>';
					else{
					?>
					<form method="POST" action="<?php echo $base_url; ?>panel/admin/emails-settings/action" name="emails-settings">
						<div class="row no-bottom-margin">
							<div class="col col-md-6">
								<div class="form-group">
									<label for="radios">Send email when guest ticket is submitted</label>
									<span class="label_desc">Enable this option to send an email to a guest user when he submits a new ticket.</span>
									<div class="radio">
										<input type="radio" name="send_email_ticket_guest" id="radio_1" class="green" value="1" <?php if($settings->send_email_ticket_guest_submitted == '1') echo 'checked '; ?>/>
										<label for="radio_1">Enabled</label>
									</div>
									<div class="radio">
										<input type="radio" name="send_email_ticket_guest" id="radio_2" class="gray" value="0" <?php if($settings->send_email_ticket_guest_submitted == '0') echo 'checked'; ?>/>
										<label for="radio_2">Disabled</label>
									</div>
								</div>
							</div>
							
							<div class="col col-md-6 toggle-hide1" style="display:none">
								<div class="from-group">
									<label for="radios">Email Type</label>
									<span class="label_desc">Type of the email. Use HTML to enable bold, italic, etc.</span>
									<div class="radio">
										<input type="radio" name="ticket_guest_type" id="radio_3" class="green" value="text" <?php if($settings->email_ticket_guest_submitted_type == 'text') echo 'checked '; ?>/>
										<label for="radio_3">Text</label>
									</div>
									<div class="radio">
										<input type="radio" name="ticket_guest_type" id="radio_4" class="green" value="html" <?php if($settings->email_ticket_guest_submitted_type == 'html') echo 'checked '; ?>/>
										<label for="radio_4">HTML</label>
									</div>
								</div>
							</div>
						</div>
							
						<div class="row min-bottom-margin toggle-hide1" style="display:none">
							<div class="col col-md-6">
								<div class="hide-cont1">
									<div class="form-group">
										<label for="ticket_guest_title">Email Title</label>
										<span class="label_desc">Title of the email</span>
										<input type="text" name="ticket_guest_title" id="ticket_guest_title" value="<?php echo $settings->email_ticket_guest_submitted_title; ?>" />
									</div>
								</div>
							</div>
							
							<div class="col col-md-6">
								<div class="form-group">
									<label for="ticket_guest_content">Email Content</label>
									<span class="label_desc">
										Content of the email. You can use the following keywords:<br />
										<strong>%site_title%</strong> - Title of the site<br />
										<strong>%site_url%</strong> - URL of the site<br />
										<strong>%user_name%</strong> - Name of the user<br />
										<strong>%ticket_code%</strong> - Code to access the ticket<br />
										<strong>%ticket_url%</strong> - URL to access the ticket<br />
										<strong>%ticket_subject%</strong> - Subject of the ticket<br />
										<strong>%ticket_department_id%</strong> - Department ID of the ticket<br />
										<strong>%ticket_department_name%</strong> - Department name of the ticket<br />
										<strong>%ticket_content%</strong> - Content of the ticket<br />
									</span>
									<textarea name="ticket_guest_content" id="ticket_guest_content" class="nostyle margin-bottom tinyedit"><?php echo $settings->email_ticket_guest_submitted_content; ?></textarea>
								</div>
							</div>
						</div>
						
						<div style="width:100%; height:1px; background-color:#ddd; margin:30px 0 40px 0;"></div>
						
						<div class="row no-bottom-margin">
							<div class="col col-md-6">
								<div class="form-group">
									<label for="radios">Send email when guest bug report is submitted</label>
									<span class="label_desc">Enable this option to send an email to a guest user when he submits a new bug report.</span>
									<div class="radio">
										<input type="radio" name="send_email_bug_guest" id="radio_5" class="green" value="1" <?php if($settings->send_email_bug_guest_submitted == '1') echo 'checked '; ?>/>
										<label for="radio_5">Enabled</label>
									</div>
									<div class="radio">
										<input type="radio" name="send_email_bug_guest" id="radio_6" class="gray" value="0" <?php if($settings->send_email_bug_guest_submitted == '0') echo 'checked'; ?>/>
										<label for="radio_6">Disabled</label>
									</div>
								</div>
							</div>
							
							<div class="col col-md-6 toggle-hide2" style="display:none">
								<div class="from-group">
									<label for="radios">Email Type</label>
									<span class="label_desc">Type of the email. Use HTML to enable bold, italic, etc.</span>
									<div class="radio">
										<input type="radio" name="bug_guest_type" id="radio_7" class="green" value="text" <?php if($settings->email_bug_guest_submitted_type == 'text') echo 'checked '; ?>/>
										<label for="radio_7">Text</label>
									</div>
									<div class="radio">
										<input type="radio" name="bug_guest_type" id="radio_8" class="green" value="html" <?php if($settings->email_bug_guest_submitted_type == 'html') echo 'checked '; ?>/>
										<label for="radio_8">HTML</label>
									</div>
								</div>
							</div>
						</div>
							
						<div class="row min-bottom-margin toggle-hide2" style="display:none">
							<div class="col col-md-6">
								<div class="hide-cont1">
									<div class="form-group">
										<label for="bug_guest_title">Email Title</label>
										<span class="label_desc">Title of the email</span>
										<input type="text" name="bug_guest_title" id="bug_guest_title" value="<?php echo $settings->email_bug_guest_submitted_title; ?>" />
									</div>
								</div>
							</div>
							
							<div class="col col-md-6">
								<div class="form-group">
									<label for="bug_guest_content">Email Content</label>
									<span class="label_desc">
									Content of the email. You can use the following keywords:<br />
									<strong>%site_title%</strong> - Title of the site<br />
										<strong>%site_url%</strong> - URL of the site<br />
										<strong>%user_name%</strong> - Name of the user<br />
										<strong>%report_code%</strong> - Code to access the bug report<br />
										<strong>%report_url%</strong> - URL to access the bug report<br />
										<strong>%report_subject%</strong> - Subject of the bug report<br />
										<strong>%report_department_id%</strong> - Department ID of the bug report<br />
										<strong>%report_department_name%</strong> - Department name of the bug report<br />
										<strong>%report_content%</strong> - Content of the bug report<br />
									</span>
									<textarea name="bug_guest_content" id="bug_guest_content" class="nostyle margin-bottom tinyedit"><?php echo $settings->email_bug_guest_submitted_content; ?></textarea>
								</div>
							</div>
						</div>
						
						<div style="width:100%; height:1px; background-color:#ddd; margin:30px 0 40px 0;"></div>
						
						<div class="row no-bottom-margin">
							<div class="col col-md-6">
								<div class="form-group">
									<label for="radios">Send email when new account is created</label>
									<span class="label_desc">Enable this option to send an email to an user when he creates his account</span>
									<div class="radio">
										<input type="radio" name="send_email_new_account" id="radio_9" class="green" value="1" <?php if($settings->send_email_new_account == '1') echo 'checked '; ?>/>
										<label for="radio_9">Enabled</label>
									</div>
									<div class="radio">
										<input type="radio" name="send_email_new_account" id="radio_10" class="gray" value="0" <?php if($settings->send_email_new_account == '0') echo 'checked'; ?>/>
										<label for="radio_10">Disabled</label>
									</div>
								</div>
							</div>
							
							<div class="col col-md-6 toggle-hide3" style="display:none">
								<div class="from-group">
									<label for="radios">Email Type</label>
									<span class="label_desc">Type of the email. Use HTML to enable bold, italic, etc.</span>
									<div class="radio">
										<input type="radio" name="new_account_type" id="radio_11" class="green" value="text" <?php if($settings->email_new_account_type == 'text') echo 'checked '; ?>/>
										<label for="radio_11">Text</label>
									</div>
									<div class="radio">
										<input type="radio" name="new_account_type" id="radio_12" class="green" value="html" <?php if($settings->email_new_account_type == 'html') echo 'checked '; ?>/>
										<label for="radio_12">HTML</label>
									</div>
								</div>
							</div>
						</div>
							
						<div class="row min-bottom-margin toggle-hide3" style="display:none">
							<div class="col col-md-6">
								<div class="hide-cont1">
									<div class="form-group">
										<label for="new_account_title">Email Title</label>
										<span class="label_desc">Title of the email</span>
										<input type="text" name="new_account_title" id="new_account_title" value="<?php echo $settings->email_new_account_title; ?>" />
									</div>
								</div>
							</div>
							
							<div class="col col-md-6">
								<div class="form-group">
									<label for="new_account_content">Email Content</label>
									<span class="label_desc">
										Content of the email. You can use the following keywords:<br />
										<strong>%site_title%</strong> - Title of the site<br />
										<strong>%site_url%</strong> - URL of the site<br />
										<strong>%user_name%</strong> - Name of the user<br />
										<strong>%user_username%</strong> - Username of the user<br />
										<strong>%user_email%</strong> - Email of the user<br />
									</span>
									<textarea name="new_account_content" id="new_account_content" class="nostyle margin-bottom tinyedit"><?php echo $settings->email_new_account_content; ?></textarea>
								</div>
							</div>
						</div>
						
						<div style="width:100%; height:1px; background-color:#ddd; margin:30px 0 40px 0;"></div>
						
						<div class="row no-bottom-margin">
							<div class="col col-md-6">
								<div class="form-group">
									<label for="radios">Send email when ticket has new reply</label>
									<span class="label_desc">Enable this option to send an email to an user when one of his tickets receives a new reply</span>
									<div class="radio">
										<input type="radio" name="send_email_new_reply" id="radio_13" class="green" value="1" <?php if($settings->send_email_new_reply == '1') echo 'checked '; ?>/>
										<label for="radio_13">Enabled</label>
									</div>
									<div class="radio">
										<input type="radio" name="send_email_new_reply" id="radio_14" class="gray" value="0" <?php if($settings->send_email_new_reply == '0') echo 'checked'; ?>/>
										<label for="radio_14">Disabled</label>
									</div>
								</div>
							</div>
							
							<div class="col col-md-6 toggle-hide4" style="display:none">
								<div class="from-group">
									<label for="radios">Email Type</label>
									<span class="label_desc">Type of the email. Use HTML to enable bold, italic, etc.</span>
									<div class="radio">
										<input type="radio" name="new_reply_type" id="radio_15" class="green" value="text" <?php if($settings->email_new_reply_type == 'text') echo 'checked '; ?>/>
										<label for="radio_15">Text</label>
									</div>
									<div class="radio">
										<input type="radio" name="new_reply_type" id="radio_16" class="green" value="html" <?php if($settings->email_new_reply_type == 'html') echo 'checked '; ?>/>
										<label for="radio_16">HTML</label>
									</div>
								</div>
							</div>
						</div>
							
						<div class="row min-bottom-margin toggle-hide4" style="display:none">
							<div class="col col-md-6">
								<div class="hide-cont1">
									<div class="form-group">
										<label for="new_reply_title">Email Title</label>
										<span class="label_desc">Title of the email</span>
										<input type="text" name="new_reply_title" id="new_reply_title" value="<?php echo $settings->email_new_reply_title; ?>" />
									</div>
								</div>
							</div>
							
							<div class="col col-md-6">
								<div class="form-group">
									<label for="new_reply_content">Email Content</label>
									<span class="label_desc">
										Content of the email. You can use the following keywords:<br />
										<strong>%site_title%</strong> - Title of the site<br />
										<strong>%site_url%</strong> - URL of the site<br />
										<strong>%ticket_code%</strong> - Code to access the ticket<br />
										<strong>%ticket_url%</strong> - URL to access the ticket<br />
										<strong>%ticket_subject%</strong> - Subject of the ticket<br />
										<strong>%ticket_department_id%</strong> - Department ID of the ticket<br />
										<strong>%ticket_department_name%</strong> - Department name of the ticket<br />
										<strong>%ticket_content%</strong> - Content of the ticket<br />
										<strong>%ticket_status%</strong> - Status of the ticket<br />
										<strong>%ticket_priority%</strong> - Priority of the ticket<br />
										<strong>%reply_content%</strong> - Content of the new reply<br />
									</span>
									<textarea name="new_reply_content" id="new_reply_content" class="nostyle margin-bottom tinyedit"><?php echo $settings->email_new_reply_content; ?></textarea>
								</div>
							</div>
						</div>
						
						<div style="width:100%; height:1px; background-color:#ddd; margin:30px 0 40px 0;"></div>
						
						<div class="row no-bottom-margin">
							<div class="col col-md-6">
								<div class="form-group">
									<label for="radios">Send email when bug report has new reply</label>
									<span class="label_desc">Enable this option to send an email to an user when one of his bug reports has a new status</span>
									<div class="radio">
										<input type="radio" name="send_email_new_status" id="radio_17" class="green" value="1" <?php if($settings->send_email_bug_new_status == '1') echo 'checked '; ?>/>
										<label for="radio_17">Enabled</label>
									</div>
									<div class="radio">
										<input type="radio" name="send_email_new_status" id="radio_18" class="gray" value="0" <?php if($settings->send_email_bug_new_status == '0') echo 'checked'; ?>/>
										<label for="radio_18">Disabled</label>
									</div>
								</div>
							</div>
							
							<div class="col col-md-6 toggle-hide5" style="display:none">
								<div class="from-group">
									<label for="radios">Email Type</label>
									<span class="label_desc">Type of the email. Use HTML to enable bold, italic, etc.</span>
									<div class="radio">
										<input type="radio" name="new_status_type" id="radio_19" class="green" value="text" <?php if($settings->email_bug_new_status_type == 'text') echo 'checked '; ?>/>
										<label for="radio_19">Text</label>
									</div>
									<div class="radio">
										<input type="radio" name="new_status_type" id="radio_20" class="green" value="html" <?php if($settings->email_bug_new_status_type == 'html') echo 'checked '; ?>/>
										<label for="radio_20">HTML</label>
									</div>
								</div>
							</div>
						</div>
							
						<div class="row min-bottom-margin toggle-hide5" style="display:none">
							<div class="col col-md-6">
								<div class="hide-cont1">
									<div class="form-group">
										<label for="new_status_title">Email Title</label>
										<span class="label_desc">Title of the email</span>
										<input type="text" name="new_status_title" id="new_status_title" value="<?php echo $settings->email_bug_new_status_title; ?>" />
									</div>
								</div>
							</div>
							
							<div class="col col-md-6">
								<div class="form-group">
									<label for="new_status_content">Email Content</label>
									<span class="label_desc">
										Content of the email. You can use the following keywords:<br />
										<strong>%site_title%</strong> - Title of the site<br />
										<strong>%site_url%</strong> - URL of the site<br />
										<strong>%report_code%</strong> - Code to access the bug report<br />
										<strong>%report_url%</strong> - URL to access the bug report<br />
										<strong>%report_subject%</strong> - Subject of the bug report<br />
										<strong>%report_department_id%</strong> - Department ID of the bug report<br />
										<strong>%report_department_name%</strong> - Department name of the bug report<br />
										<strong>%report_content%</strong> - Content of the bug report<br />
										<strong>%report_status%</strong> - Status of the bug report<br />
										<strong>%report_priority%</strong> - Priority of the bug report<br />
									</span>
									<textarea name="new_status_content" id="new_status_content" class="nostyle margin-bottom tinyedit"><?php echo $settings->email_bug_new_status_content; ?></textarea>
								</div>
							</div>
						</div>
						
						
						
						
						
						
						
						
						
						
						
						
						
						
						
						
						
						<div style="width:100%; height:1px; background-color:#ddd; margin:30px 0 40px 0;"></div>
						
						<div class="row no-bottom-margin">
							<div class="col col-md-6">
								<div class="form-group">
									<label for="radios">Send email to agents when guest has submitted new ticket (<strong style="color:#FF0000">NEW</strong>)</label>
									<span class="label_desc">Enable this option to send an email to all agents in a department when a new ticket has been submitted by a guest</span>
									<div class="radio">
										<input type="radio" name="send_email_agents_new_ticket_guest" id="radio_21" class="green" value="1" <?php if($settings->send_agents_email_ticket_guest_submitted == '1') echo 'checked '; ?>/>
										<label for="radio_21">Enabled</label>
									</div>
									<div class="radio">
										<input type="radio" name="send_email_agents_new_ticket_guest" id="radio_22" class="gray" value="0" <?php if($settings->send_agents_email_ticket_guest_submitted == '0') echo 'checked'; ?>/>
										<label for="radio_22">Disabled</label>
									</div>
								</div>
							</div>
							
							<div class="col col-md-6 toggle-hide6" style="display:none">
								<div class="from-group">
									<label for="radios">Email Type</label>
									<span class="label_desc">Type of the email. Use HTML to enable bold, italic, etc.</span>
									<div class="radio">
										<input type="radio" name="agents_new_ticket_guest_type" id="radio_23" class="green" value="text" <?php if($settings->agents_email_ticket_guest_submitted_type == 'text') echo 'checked '; ?>/>
										<label for="radio_23">Text</label>
									</div>
									<div class="radio">
										<input type="radio" name="agents_new_ticket_guest_type" id="radio_24" class="green" value="html" <?php if($settings->agents_email_ticket_guest_submitted_type == 'html') echo 'checked '; ?>/>
										<label for="radio_24">HTML</label>
									</div>
								</div>
							</div>
						</div>
							
						<div class="row min-bottom-margin toggle-hide6" style="display:none">
							<div class="col col-md-6">
								<div class="hide-cont1">
									<div class="form-group">
										<label for="agents_new_ticket_guest_title">Email Title</label>
										<span class="label_desc">Title of the email</span>
										<input type="text" name="agents_new_ticket_guest_title" id="agents_new_ticket_guest_title" value="<?php echo $settings->agents_email_ticket_guest_submitted_title; ?>" />
									</div>
								</div>
							</div>
							
							<div class="col col-md-6">
								<div class="form-group">
									<label for="agents_new_ticket_guest_content">Email Content</label>
									<span class="label_desc">
										Content of the email. You can use the following keywords:<br />
										<strong>%site_title%</strong> - Title of the site<br />
										<strong>%site_url%</strong> - URL of the site<br />
										<strong>%user_name%</strong> - Name of the guest<br />
										<strong>%ticket_code%</strong> - Code to access the ticket<br />
										<strong>%ticket_url%</strong> - URL to access the ticket<br />
										<strong>%ticket_subject%</strong> - Subject of the ticket<br />
										<strong>%ticket_department_id%</strong> - Department ID of the ticket<br />
										<strong>%ticket_department_name%</strong> - Department name of the ticket<br />
										<strong>%ticket_content%</strong> - Content of the ticket<br />
										<strong>%agent_user_name%</strong> - Agent's name<br />
										<strong>%agent_username%</strong> - Agent's username<br />
									</span>
									<textarea name="agents_new_ticket_guest_content" id="agents_new_ticket_guest_content" class="nostyle margin-bottom tinyedit"><?php echo $settings->agents_email_ticket_guest_submitted_content; ?></textarea>
								</div>
							</div>
						</div>
						
						
						
						
						<div style="width:100%; height:1px; background-color:#ddd; margin:30px 0 40px 0;"></div>
						
						<div class="row no-bottom-margin">
							<div class="col col-md-6">
								<div class="form-group">
									<label for="radios">Send email to agents when client has submitted new ticket</label>
									<span class="label_desc">Enable this option to send an email to all agents in a department when a new ticket has been submitted by a client</span>
									<div class="radio">
										<input type="radio" name="send_email_agents_new_ticket_client" id="radio_25" class="green" value="1" <?php if($settings->send_agents_email_ticket_client_submitted == '1') echo 'checked '; ?>/>
										<label for="radio_25">Enabled</label>
									</div>
									<div class="radio">
										<input type="radio" name="send_email_agents_new_ticket_client" id="radio_26" class="gray" value="0" <?php if($settings->send_agents_email_ticket_client_submitted == '0') echo 'checked'; ?>/>
										<label for="radio_26">Disabled</label>
									</div>
								</div>
							</div>
							
							<div class="col col-md-6 toggle-hide7" style="display:none">
								<div class="from-group">
									<label for="radios">Email Type</label>
									<span class="label_desc">Type of the email. Use HTML to enable bold, italic, etc.</span>
									<div class="radio">
										<input type="radio" name="agents_new_ticket_client_type" id="radio_27" class="green" value="text" <?php if($settings->agents_email_ticket_client_submitted_type == 'text') echo 'checked '; ?>/>
										<label for="radio_27">Text</label>
									</div>
									<div class="radio">
										<input type="radio" name="agents_new_ticket_client_type" id="radio_28" class="green" value="html" <?php if($settings->agents_email_ticket_client_submitted_type == 'html') echo 'checked '; ?>/>
										<label for="radio_28">HTML</label>
									</div>
								</div>
							</div>
						</div>
							
						<div class="row min-bottom-margin toggle-hide7" style="display:none">
							<div class="col col-md-6">
								<div class="hide-cont1">
									<div class="form-group">
										<label for="agents_new_ticket_client_title">Email Title</label>
										<span class="label_desc">Title of the email</span>
										<input type="text" name="agents_new_ticket_client_title" id="agents_new_ticket_client_title" value="<?php echo $settings->agents_email_ticket_client_submitted_title; ?>" />
									</div>
								</div>
							</div>
							
							<div class="col col-md-6">
								<div class="form-group">
									<label for="agents_new_ticket_client_content">Email Content</label>
									<span class="label_desc">
										Content of the email. You can use the following keywords:<br />
										<strong>%site_title%</strong> - Title of the site<br />
										<strong>%site_url%</strong> - URL of the site<br />
										<strong>%user_name%</strong> - Name of the client<br />
										<strong>%user_username%</strong> - Username of the client<br />
										<strong>%ticket_code%</strong> - Code to access the ticket<br />
										<strong>%ticket_url%</strong> - URL to access the ticket<br />
										<strong>%ticket_subject%</strong> - Subject of the ticket<br />
										<strong>%ticket_department_id%</strong> - Department ID of the ticket<br />
										<strong>%ticket_department_name%</strong> - Department name of the ticket<br />
										<strong>%ticket_content%</strong> - Content of the ticket<br />
										<strong>%agent_user_name%</strong> - Agent's name<br />
										<strong>%agent_username%</strong> - Agent's username<br />
									</span>
									<textarea name="agents_new_ticket_client_content" id="agents_new_ticket_client_content" class="nostyle margin-bottom tinyedit"><?php echo $settings->agents_email_ticket_client_submitted_content; ?></textarea>
								</div>
							</div>
						</div>
						
						
						
						<div style="width:100%; height:1px; background-color:#ddd; margin:30px 0 40px 0;"></div>
						
						<div class="row no-bottom-margin">
							<div class="col col-md-6">
								<div class="form-group">
									<label for="radios">Send email to agents when guest has submitted new bug report</label>
									<span class="label_desc">Enable this option to send an email to all agents in a department when a new bug report has been submitted by a guest</span>
									<div class="radio">
										<input type="radio" name="send_email_agents_new_bug_guest" id="radio_29" class="green" value="1" <?php if($settings->send_agents_email_bug_guest_submitted == '1') echo 'checked '; ?>/>
										<label for="radio_29">Enabled</label>
									</div>
									<div class="radio">
										<input type="radio" name="send_email_agents_new_bug_guest" id="radio_30" class="gray" value="0" <?php if($settings->send_agents_email_bug_guest_submitted == '0') echo 'checked'; ?>/>
										<label for="radio_30">Disabled</label>
									</div>
								</div>
							</div>
							
							<div class="col col-md-6 toggle-hide8" style="display:none">
								<div class="from-group">
									<label for="radios">Email Type</label>
									<span class="label_desc">Type of the email. Use HTML to enable bold, italic, etc.</span>
									<div class="radio">
										<input type="radio" name="agents_new_bug_guest_type" id="radio_31" class="green" value="text" <?php if($settings->agents_email_bug_guest_submitted_type == 'text') echo 'checked '; ?>/>
										<label for="radio_31">Text</label>
									</div>
									<div class="radio">
										<input type="radio" name="agents_new_bug_guest_type" id="radio_32" class="green" value="html" <?php if($settings->agents_email_bug_guest_submitted_type == 'html') echo 'checked '; ?>/>
										<label for="radio_32">HTML</label>
									</div>
								</div>
							</div>
						</div>
							
						<div class="row min-bottom-margin toggle-hide8" style="display:none">
							<div class="col col-md-6">
								<div class="hide-cont1">
									<div class="form-group">
										<label for="agents_new_bug_guest_title">Email Title</label>
										<span class="label_desc">Title of the email</span>
										<input type="text" name="agents_new_bug_guest_title" id="agents_new_bug_guest_title" value="<?php echo $settings->agents_email_bug_guest_submitted_title; ?>" />
									</div>
								</div>
							</div>
							
							<div class="col col-md-6">
								<div class="form-group">
									<label for="agents_new_bug_guest_content">Email Content</label>
									<span class="label_desc">
										Content of the email. You can use the following keywords:<br />
										<strong>%site_title%</strong> - Title of the site<br />
										<strong>%site_url%</strong> - URL of the site<br />
										<strong>%user_name%</strong> - Name of the guest<br />
										<strong>%report_code%</strong> - Code to access the bug report<br />
										<strong>%report_url%</strong> - URL to access the bug report<br />
										<strong>%report_subject%</strong> - Subject of the bug report<br />
										<strong>%report_department_id%</strong> - Department ID of the bug report<br />
										<strong>%report_department_name%</strong> - Department name of the bug report<br />
										<strong>%report_content%</strong> - Content of the bug report<br />
										<strong>%agent_user_name%</strong> - Agent's name<br />
										<strong>%agent_username%</strong> - Agent's username<br />
									</span>
									<textarea name="agents_new_bug_guest_content" id="agents_new_bug_guest_content" class="nostyle margin-bottom tinyedit"><?php echo $settings->agents_email_bug_guest_submitted_content; ?></textarea>
								</div>
							</div>
						</div>
						
						
						<div style="width:100%; height:1px; background-color:#ddd; margin:30px 0 40px 0;"></div>
						
						<div class="row no-bottom-margin">
							<div class="col col-md-6">
								<div class="form-group">
									<label for="radios">Send email to agents when client has submitted new bug report</label>
									<span class="label_desc">Enable this option to send an email to all agents in a department when a new bug report has been submitted by a client</span>
									<div class="radio">
										<input type="radio" name="send_email_agents_new_bug_client" id="radio_33" class="green" value="1" <?php if($settings->send_agents_email_bug_client_submitted == '1') echo 'checked '; ?>/>
										<label for="radio_33">Enabled</label>
									</div>
									<div class="radio">
										<input type="radio" name="send_email_agents_new_bug_client" id="radio_34" class="gray" value="0" <?php if($settings->send_agents_email_bug_client_submitted == '0') echo 'checked'; ?>/>
										<label for="radio_34">Disabled</label>
									</div>
								</div>
							</div>
							
							<div class="col col-md-6 toggle-hide9" style="display:none">
								<div class="from-group">
									<label for="radios">Email Type</label>
									<span class="label_desc">Type of the email. Use HTML to enable bold, italic, etc.</span>
									<div class="radio">
										<input type="radio" name="agents_new_bug_client_type" id="radio_35" class="green" value="text" <?php if($settings->agents_email_bug_client_submitted_type == 'text') echo 'checked '; ?>/>
										<label for="radio_35">Text</label>
									</div>
									<div class="radio">
										<input type="radio" name="agents_new_bug_client_type" id="radio_36" class="green" value="html" <?php if($settings->agents_email_bug_client_submitted_type == 'html') echo 'checked '; ?>/>
										<label for="radio_36">HTML</label>
									</div>
								</div>
							</div>
						</div>
							
						<div class="row min-bottom-margin toggle-hide9" style="display:none">
							<div class="col col-md-6">
								<div class="hide-cont1">
									<div class="form-group">
										<label for="agents_new_bug_client_title">Email Title</label>
										<span class="label_desc">Title of the email</span>
										<input type="text" name="agents_new_bug_client_title" id="agents_new_bug_client_title" value="<?php echo $settings->agents_email_bug_client_submitted_title; ?>" />
									</div>
								</div>
							</div>
							
							<div class="col col-md-6">
								<div class="form-group">
									<label for="agents_new_bug_client_content">Email Content</label>
									<span class="label_desc">
										Content of the email. You can use the following keywords:<br />
										<strong>%site_title%</strong> - Title of the site<br />
										<strong>%site_url%</strong> - URL of the site<br />
										<strong>%user_name%</strong> - Name of the client<br />
										<strong>%user_username%</strong> - Username of the client<br />
										<strong>%report_code%</strong> - Code to access the bug report<br />
										<strong>%report_url%</strong> - URL to access the bug report<br />
										<strong>%report_subject%</strong> - Subject of the bug report<br />
										<strong>%report_department_id%</strong> - Department ID of the bug report<br />
										<strong>%report_department_name%</strong> - Department name of the bug report<br />
										<strong>%report_content%</strong> - Content of the bug report<br />
										<strong>%agent_user_name%</strong> - Agent's name<br />
										<strong>%agent_username%</strong> - Agent's username<br />
									</span>
									<textarea name="agents_new_bug_client_content" id="agents_new_bug_client_content" class="nostyle margin-bottom tinyedit"><?php echo $settings->agents_email_bug_client_submitted_content; ?></textarea>
								</div>
							</div>
						</div>
						
						
						<div style="width:100%; height:1px; background-color:#ddd; margin:30px 0 40px 0;"></div>
						
						<div class="row no-bottom-margin">
							<div class="col col-md-6">
								<div class="form-group">
									<label for="radios">Send email to agent when a ticket has a new reply</label>
									<span class="label_desc">Enable this option to send an email to an agent when one of the tickets he's responsible of receives a new reply</span>
									<div class="radio">
										<input type="radio" name="send_email_agent_new_ticket_reply" id="radio_37" class="green" value="1" <?php if($settings->send_agent_email_new_reply == '1') echo 'checked '; ?>/>
										<label for="radio_37">Enabled</label>
									</div>
									<div class="radio">
										<input type="radio" name="send_email_agent_new_ticket_reply" id="radio_38" class="gray" value="0" <?php if($settings->send_agent_email_new_reply == '0') echo 'checked'; ?>/>
										<label for="radio_38">Disabled</label>
									</div>
								</div>
							</div>
							
							<div class="col col-md-6 toggle-hide10" style="display:none">
								<div class="from-group">
									<label for="radios">Email Type</label>
									<span class="label_desc">Type of the email. Use HTML to enable bold, italic, etc.</span>
									<div class="radio">
										<input type="radio" name="agent_new_ticket_reply_type" id="radio_39" class="green" value="text" <?php if($settings->agent_email_new_reply_type == 'text') echo 'checked '; ?>/>
										<label for="radio_39">Text</label>
									</div>
									<div class="radio">
										<input type="radio" name="agent_new_ticket_reply_type" id="radio_40" class="green" value="html" <?php if($settings->agent_email_new_reply_type == 'html') echo 'checked '; ?>/>
										<label for="radio_40">HTML</label>
									</div>
								</div>
							</div>
						</div>
							
						<div class="row min-bottom-margin toggle-hide10" style="display:none">
							<div class="col col-md-6">
								<div class="hide-cont1">
									<div class="form-group">
										<label for="agent_new_ticket_reply_title">Email Title</label>
										<span class="label_desc">Title of the email</span>
										<input type="text" name="agent_new_ticket_reply_title" id="agent_new_ticket_reply_title" value="<?php echo $settings->agent_email_new_reply_title; ?>" />
									</div>
								</div>
							</div>
							
							<div class="col col-md-6">
								<div class="form-group">
									<label for="agent_new_ticket_reply_content">Email Content</label>
									<span class="label_desc">
										Content of the email. You can use the following keywords:<br />
										<strong>%site_title%</strong> - Title of the site<br />
										<strong>%site_url%</strong> - URL of the site<br />
										<strong>%user_name%</strong> - Name of the client / guest<br />
										<strong>%ticket_code%</strong> - Code to access the ticket<br />
										<strong>%ticket_url%</strong> - URL to access the ticket<br />
										<strong>%ticket_subject%</strong> - Subject of the ticket<br />
										<strong>%ticket_department_id%</strong> - Department ID of the ticket<br />
										<strong>%ticket_department_name%</strong> - Department name of the ticket<br />
										<strong>%ticket_content%</strong> - Content of the ticket<br />
										<strong>%ticket_status%</strong> - Status of the ticket<br />
										<strong>%ticket_priority%</strong> - Priority of the ticket<br />
										<strong>%reply_content%</strong> - Content of the new reply<br />
										<strong>%agent_user_name%</strong> - Agent's name<br />
										<strong>%agent_username%</strong> - Agent's username<br />
									</span>
									<textarea name="agent_new_ticket_reply_content" id="agent_new_ticket_reply_content" class="nostyle margin-bottom tinyedit"><?php echo $settings->agent_email_new_reply_content; ?></textarea>
								</div>
							</div>
						</div>
						
						<input type="submit" name="submit" class="btn btn-strong-blue pull-right" value="Save" />
					</form>
					
					<?php
					}
					?>
				</div>
			</div>
		</div>
	</div>
	
	
	<script src="<?php echo asset_url(); ?>js/jquery-1.11.3.min.js"></script>
	<script src="<?php echo asset_url(); ?>js/tickerr_core.js"></script>
	<script src="<?php echo asset_url(); ?>js/tinyeditor.min.js"></script>
	<script type="text/javascript">
		$('document').ready(function() {
			// Enable sidebar
			enable_sidebar();
			
			$('thead tr th').on('mouseover', function() {
				$(this).children('i.fa-sort').addClass('active');
				$(this).children('.hid').css('visibility','visible');
			}).on('mouseout', function() {
				$(this).children('i.fa-sort').removeClass('active');
				$(this).children('.hid').css('visibility','hidden');
			});

			$('thead tr th').click(function(evt) {
				if($(this).data('sort') !== undefined)
					location.href = $(this).data('sort');
			});
			
			$('tr').click(function(evt) {
				if($(this).data('href') !== undefined)
					location.href = $(this).data('href');
			});
			
			var ticket_guest = false;
			var bug_guest = false;
			var new_account = false;
			var new_reply = false;
			var new_status = false;
			
			// new
			var new_ticket_guest = false;
			var new_ticket_client = false;
			var new_bug_guest = false;
			var new_bug_client = false;
			var new_ticket_reply = false;
			
			if($('input#radio_1').is(':checked')) {
				$('.toggle-hide1').slideDown(10);
				ticket_guest = true;
			}
			if($('input#radio_5').is(':checked')) {
				$('.toggle-hide2').slideDown(10);
				bug_guest = true;
			}
			if($('input#radio_9').is(':checked')) {
				$('.toggle-hide3').slideDown(10);
				new_account = true;
			}
			if($('input#radio_13').is(':checked')) {
				$('.toggle-hide4').slideDown(10);
				new_reply = true;
			}
			if($('input#radio_17').is(':checked')) {
				$('.toggle-hide5').slideDown(10);
				new_status = true;
			}
			
			// New
			if($('input#radio_21').is(':checked')) {
				$('.toggle-hide6').slideDown(10);
				new_ticket_guest = true;
			}
			if($('input#radio_25').is(':checked')) {
				$('.toggle-hide7').slideDown(10);
				new_ticket_client = true;
			}
			if($('input#radio_29').is(':checked')) {
				$('.toggle-hide8').slideDown(10);
				new_bug_guest = true;
			}
			if($('input#radio_33').is(':checked')) {
				$('.toggle-hide9').slideDown(10);
				new_bug_client = true;
			}
			if($('input#radio_37').is(':checked')) {
				$('.toggle-hide10').slideDown(10);
				new_ticket_reply = true;
			}
			
			$('input[name=send_email_ticket_guest]').change(function() {
				if($('input#radio_1').is(':checked')) {
					$('.toggle-hide1').slideDown(300);
					ticket_guest = true;
				}else{
					$('.toggle-hide1').slideUp(300);
					ticket_guest = false;
				}
			});
			$('input[name=send_email_bug_guest]').change(function() {
				if($('input#radio_5').is(':checked')) {
					$('.toggle-hide2').slideDown(300);
					bug_guest = true;
				}else{
					$('.toggle-hide2').slideUp(300);
					bug_guest = false;
				}
			});
			$('input[name=send_email_new_account]').change(function() {
				if($('input#radio_9').is(':checked')) {
					$('.toggle-hide3').slideDown(300);
					new_account = true;
				}else{
					$('.toggle-hide3').slideUp(300);
					new_account = false;
				}
			});
			$('input[name=send_email_new_reply]').change(function() {
				if($('input#radio_13').is(':checked')) {
					$('.toggle-hide4').slideDown(300);
					new_reply = true;
				}else{
					$('.toggle-hide4').slideUp(300);
					new_reply = false;
				}
			});
			$('input[name=send_email_new_status]').change(function() {
				if($('input#radio_17').is(':checked')) {
					$('.toggle-hide5').slideDown(300);
					new_status = true;
				}else{
					$('.toggle-hide5').slideUp(300);
					new_status = false;
				}
			});
			
			// New
			$('input[name=send_email_agents_new_ticket_guest]').change(function() {
				if($('input#radio_21').is(':checked')) {
					$('.toggle-hide6').slideDown(300);
					new_ticket_guest = true;
				}else{
					$('.toggle-hide6').slideUp(300);
					new_ticket_guest = false;
				}
			});
			$('input[name=send_email_agents_new_ticket_client]').change(function() {
				if($('input#radio_25').is(':checked')) {
					$('.toggle-hide7').slideDown(300);
					new_ticket_client = true;
				}else{
					$('.toggle-hide7').slideUp(300);
					new_ticket_client = false;
				}
			});
			$('input[name=send_email_agents_new_bug_guest]').change(function() {
				if($('input#radio_29').is(':checked')) {
					$('.toggle-hide8').slideDown(300);
					new_bug_guest = true;
				}else{
					$('.toggle-hide8').slideUp(300);
					new_bug_guest = false;
				}
			});
			$('input[name=send_email_agents_new_bug_client]').change(function() {
				if($('input#radio_33').is(':checked')) {
					$('.toggle-hide9').slideDown(300);
					new_bug_client = true;
				}else{
					$('.toggle-hide9').slideUp(300);
					new_bug_client = false;
				}
			});
			$('input[name=send_email_agent_new_ticket_reply]').change(function() {
				if($('input#radio_37').is(':checked')) {
					$('.toggle-hide10').slideDown(300);
					new_ticket_reply = true;
				}else{
					$('.toggle-hide10').slideUp(300);
					new_ticket_reply = false;
				}
			});
			
			$('form[name=emails-settings]').submit(function(evt) {
				var ticket_guest_title = $('input[name=ticket_guest_title]').val();
				var bug_guest_title = $('input[name=bug_guest_title]').val();
				var new_account_title = $('input[name=new_account_title]').val();
				var new_reply_title = $('input[name=new_reply_title]').val();
				var new_status_title = $('input[name=new_status_title]').val();
				
				// New
				var agents_new_ticket_guest_title = $('input[name=agents_new_ticket_guest_title]').val();
				var agents_new_ticket_client_title = $('input[name=agents_new_ticket_client_title]').val();
				var agents_new_bug_guest_title = $('input[name=agents_new_bug_guest_title]').val();
				var agents_new_bug_client_title = $('input[name=agents_new_bug_client_title]').val();
				var agent_new_ticket_reply_title = $('input[name=agent_new_ticket_reply_title]').val();
				
				txt1.post();
				txt2.post();
				txt3.post();
				txt4.post();
				txt5.post();
				txt6.post();
				txt7.post();
				txt8.post();
				txt9.post();
				txt10.post();
				
				if(ticket_guest == true) {
					if(ticket_guest_title == '') {
						evt.preventDefault();
						error('Please insert the email title of the email sent when a guest user submits a new ticket', '[name=ticket_guest_title]');
						return false;
					}
				}
				if(bug_guest == true) {
					if(bug_guest_title == '') {
						evt.preventDefault();
						error('Please insert the email title of the email sent when a guest user submits a new bug report', '[name=bug_guest_title]');
						return false;
					}
				}
				if(new_account == true) {
					if(new_account_title == '') {
						evt.preventDefault();
						error('Please insert the email title of the email sent when an user creates an account', '[name=new_account_title]');
						return false;
					}
				}
				if(new_reply == true) {
					if(new_reply_title == '') {
						evt.preventDefault();
						error('Please insert the email title of the email sent when an user\'s ticket receives a new reply', '[name=new_reply_title]');
						return false;
					}
				}
				if(new_status == true) {
					if(new_status_title == '') {
						evt.preventDefault();
						error('Please insert the email title of the email sent when an user\'s bug report has a new status', '[name=new_status_title]');
						return false;
					}
				}
				
				
				// New
				if(new_ticket_guest == true) {
					if(agents_new_ticket_guest_title == '') {
						evt.preventDefault();
						error('Please insert the email title of the email sent to agents when a guest submits a new ticket', '[name=agents_new_ticket_guest_title]');
						return false;
					}
				}
				if(new_ticket_client == true) {
					if(agents_new_ticket_client_title == '') {
						evt.preventDefault();
						error('Please insert the email title of the email sent to agents when a client submits a new ticket', '[name=agents_new_ticket_client_title]');
						return false;
					}
				}
				if(new_bug_guest == true) {
					if(agents_new_bug_guest_title == '') {
						evt.preventDefault();
						error('Please insert the email title of the email sent to agents when a guest submits a new bug report', '[name=agents_new_bug_guest_title]');
						return false;
					}
				}
				if(new_bug_client == true) {
					if(agents_new_bug_client_title == '') {
						evt.preventDefault();
						error('Please insert the email title of the email sent to agents when a client submit a new bug report', '[name=agents_new_bug_client_title]');
						return false;
					}
				}
				if(new_ticket_reply == true) {
					if(agent_new_ticket_reply_title == '') {
						evt.preventDefault();
						error('Please insert the email title of the email sent to an agent when a ticket receives a new reply', '[name=agent_new_ticket_reply_title]');
						return false;
					}
				}
			});
			
			var e_active = false;
			function error(e, n) {
				if(e_active != false) {
					$(e_active).removeClass('error');
				}
				
				$(n).addClass('error');
				e_active = n;
				
				$('p.bg-danger').slideUp(200, function() {
					$('p.bg-danger').html(e).slideDown(200);
				});
			}
			
			function validateEmail(email) {
				var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
				return re.test(email);
			}
			
			var txt1 = new TINY.editor.edit('txt1', {
				id: 'ticket_guest_content',
				width: '100%',
				height:160,
				cssclass: 'tinyeditor',
				controlclass: 'tinyeditor-control',
				rowclass: 'tinyeditor-header',
				dividerclass: 'tinyeditor-divider',
				controls: ['bold', 'italic', 'underline', 'strikethrough', '|', 'orderedlist',
					'unorderedlist', '|', 'outdent', 'indent', '|', 'leftalign', 'centeralign',
					'rightalign', 'blockjustify', '|', 'image', 'link', 'unlink'],
				footer: true,
				xhtml: true,
				cssfile: '<?php echo asset_url(); ?>css/tinyeditor.css',
				bodyid: 'editor',
				footerclass: 'tinyeditor-footer',
				toggle: {text: 'source', activetext: 'wysiwyg', cssclass: 'toggle'},
				resize: {cssclass: 'resize'}
			});
			var txt2 = new TINY.editor.edit('txt2', {
				id: 'bug_guest_content',
				width: '100%',
				height:160,
				cssclass: 'tinyeditor',
				controlclass: 'tinyeditor-control',
				rowclass: 'tinyeditor-header',
				dividerclass: 'tinyeditor-divider',
				controls: ['bold', 'italic', 'underline', 'strikethrough', '|', 'orderedlist',
					'unorderedlist', '|', 'outdent', 'indent', '|', 'leftalign', 'centeralign',
					'rightalign', 'blockjustify', '|', 'image', 'link', 'unlink'],
				footer: true,
				xhtml: true,
				cssfile: '<?php echo asset_url(); ?>css/tinyeditor.css',
				bodyid: 'editor',
				footerclass: 'tinyeditor-footer',
				toggle: {text: 'source', activetext: 'wysiwyg', cssclass: 'toggle'},
				resize: {cssclass: 'resize'}
			});
			var txt3 = new TINY.editor.edit('txt3', {
				id: 'new_account_content',
				width: '100%',
				height:160,
				cssclass: 'tinyeditor',
				controlclass: 'tinyeditor-control',
				rowclass: 'tinyeditor-header',
				dividerclass: 'tinyeditor-divider',
				controls: ['bold', 'italic', 'underline', 'strikethrough', '|', 'orderedlist',
					'unorderedlist', '|', 'outdent', 'indent', '|', 'leftalign', 'centeralign',
					'rightalign', 'blockjustify', '|', 'image', 'link', 'unlink'],
				footer: true,
				xhtml: true,
				cssfile: '<?php echo asset_url(); ?>css/tinyeditor.css',
				bodyid: 'editor',
				footerclass: 'tinyeditor-footer',
				toggle: {text: 'source', activetext: 'wysiwyg', cssclass: 'toggle'},
				resize: {cssclass: 'resize'}
			});
			var txt4 = new TINY.editor.edit('txt4', {
				id: 'new_reply_content',
				width: '100%',
				height:160,
				cssclass: 'tinyeditor',
				controlclass: 'tinyeditor-control',
				rowclass: 'tinyeditor-header',
				dividerclass: 'tinyeditor-divider',
				controls: ['bold', 'italic', 'underline', 'strikethrough', '|', 'orderedlist',
					'unorderedlist', '|', 'outdent', 'indent', '|', 'leftalign', 'centeralign',
					'rightalign', 'blockjustify', '|', 'image', 'link', 'unlink'],
				footer: true,
				xhtml: true,
				cssfile: '<?php echo asset_url(); ?>css/tinyeditor.css',
				bodyid: 'editor',
				footerclass: 'tinyeditor-footer',
				toggle: {text: 'source', activetext: 'wysiwyg', cssclass: 'toggle'},
				resize: {cssclass: 'resize'}
			});
			var txt5 = new TINY.editor.edit('txt5', {
				id: 'new_status_content',
				width: '100%',
				height:160,
				cssclass: 'tinyeditor',
				controlclass: 'tinyeditor-control',
				rowclass: 'tinyeditor-header',
				dividerclass: 'tinyeditor-divider',
				controls: ['bold', 'italic', 'underline', 'strikethrough', '|', 'orderedlist',
					'unorderedlist', '|', 'outdent', 'indent', '|', 'leftalign', 'centeralign',
					'rightalign', 'blockjustify', '|', 'image', 'link', 'unlink'],
				footer: true,
				xhtml: true,
				cssfile: '<?php echo asset_url(); ?>css/tinyeditor.css',
				bodyid: 'editor',
				footerclass: 'tinyeditor-footer',
				toggle: {text: 'source', activetext: 'wysiwyg', cssclass: 'toggle'},
				resize: {cssclass: 'resize'}
			});
			
			// New
			var txt6 = new TINY.editor.edit('txt6', {
				id: 'agents_new_ticket_guest_content',
				width: '100%',
				height:160,
				cssclass: 'tinyeditor',
				controlclass: 'tinyeditor-control',
				rowclass: 'tinyeditor-header',
				dividerclass: 'tinyeditor-divider',
				controls: ['bold', 'italic', 'underline', 'strikethrough', '|', 'orderedlist',
					'unorderedlist', '|', 'outdent', 'indent', '|', 'leftalign', 'centeralign',
					'rightalign', 'blockjustify', '|', 'image', 'link', 'unlink'],
				footer: true,
				xhtml: true,
				cssfile: '<?php echo asset_url(); ?>css/tinyeditor.css',
				bodyid: 'editor',
				footerclass: 'tinyeditor-footer',
				toggle: {text: 'source', activetext: 'wysiwyg', cssclass: 'toggle'},
				resize: {cssclass: 'resize'}
			});
			var txt7 = new TINY.editor.edit('txt7', {
				id: 'agents_new_ticket_client_content',
				width: '100%',
				height:160,
				cssclass: 'tinyeditor',
				controlclass: 'tinyeditor-control',
				rowclass: 'tinyeditor-header',
				dividerclass: 'tinyeditor-divider',
				controls: ['bold', 'italic', 'underline', 'strikethrough', '|', 'orderedlist',
					'unorderedlist', '|', 'outdent', 'indent', '|', 'leftalign', 'centeralign',
					'rightalign', 'blockjustify', '|', 'image', 'link', 'unlink'],
				footer: true,
				xhtml: true,
				cssfile: '<?php echo asset_url(); ?>css/tinyeditor.css',
				bodyid: 'editor',
				footerclass: 'tinyeditor-footer',
				toggle: {text: 'source', activetext: 'wysiwyg', cssclass: 'toggle'},
				resize: {cssclass: 'resize'}
			});
			var txt8 = new TINY.editor.edit('txt8', {
				id: 'agents_new_bug_guest_content',
				width: '100%',
				height:160,
				cssclass: 'tinyeditor',
				controlclass: 'tinyeditor-control',
				rowclass: 'tinyeditor-header',
				dividerclass: 'tinyeditor-divider',
				controls: ['bold', 'italic', 'underline', 'strikethrough', '|', 'orderedlist',
					'unorderedlist', '|', 'outdent', 'indent', '|', 'leftalign', 'centeralign',
					'rightalign', 'blockjustify', '|', 'image', 'link', 'unlink'],
				footer: true,
				xhtml: true,
				cssfile: '<?php echo asset_url(); ?>css/tinyeditor.css',
				bodyid: 'editor',
				footerclass: 'tinyeditor-footer',
				toggle: {text: 'source', activetext: 'wysiwyg', cssclass: 'toggle'},
				resize: {cssclass: 'resize'}
			});
			var txt9 = new TINY.editor.edit('txt9', {
				id: 'agents_new_bug_client_content',
				width: '100%',
				height:160,
				cssclass: 'tinyeditor',
				controlclass: 'tinyeditor-control',
				rowclass: 'tinyeditor-header',
				dividerclass: 'tinyeditor-divider',
				controls: ['bold', 'italic', 'underline', 'strikethrough', '|', 'orderedlist',
					'unorderedlist', '|', 'outdent', 'indent', '|', 'leftalign', 'centeralign',
					'rightalign', 'blockjustify', '|', 'image', 'link', 'unlink'],
				footer: true,
				xhtml: true,
				cssfile: '<?php echo asset_url(); ?>css/tinyeditor.css',
				bodyid: 'editor',
				footerclass: 'tinyeditor-footer',
				toggle: {text: 'source', activetext: 'wysiwyg', cssclass: 'toggle'},
				resize: {cssclass: 'resize'}
			});
			var txt10 = new TINY.editor.edit('txt10', {
				id: 'agent_new_ticket_reply_content',
				width: '100%',
				height:160,
				cssclass: 'tinyeditor',
				controlclass: 'tinyeditor-control',
				rowclass: 'tinyeditor-header',
				dividerclass: 'tinyeditor-divider',
				controls: ['bold', 'italic', 'underline', 'strikethrough', '|', 'orderedlist',
					'unorderedlist', '|', 'outdent', 'indent', '|', 'leftalign', 'centeralign',
					'rightalign', 'blockjustify', '|', 'image', 'link', 'unlink'],
				footer: true,
				xhtml: true,
				cssfile: '<?php echo asset_url(); ?>css/tinyeditor.css',
				bodyid: 'editor',
				footerclass: 'tinyeditor-footer',
				toggle: {text: 'source', activetext: 'wysiwyg', cssclass: 'toggle'},
				resize: {cssclass: 'resize'}
			});
		});
	</script>
</body>
</html>