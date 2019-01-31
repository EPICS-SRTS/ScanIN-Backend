/*
 *
 * Here are stored the users
 *
*/
CREATE TABLE `tickerr_users`(
`id` INT NOT NULL AUTO_INCREMENT,
PRIMARY KEY(`id`),
`username` VARCHAR(100) NOT NULL,
`name` VARCHAR(150) NOT NULL,
`email` VARCHAR(40) NOT NULL,
`profile_img1x` VARCHAR(400) NOT NULL,
`profile_img2x` VARCHAR(400) NOT NULL,
`profile_img3x` VARCHAR(400) NOT NULL,
`date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`password` CHAR(32) NOT NULL,
`role` INT(1) NOT NULL,
`ticket_departments` VARCHAR(300) NOT NULL,
`bug_departments` VARCHAR(300) NOT NULL,
`email_on_tactivity` INT(1) NOT NULL DEFAULT 1,
`email_on_bactivity` INT(1) NOT NULL DEFAULT 1,
`email_confirmation` INT(1) NOT NULL DEFAULT 2,
`confirmation_str` VARCHAR(25) NOT NULL,
`recover_password_str` VARCHAR(35) NOT NULL
);


/*
 *
 * Here are stored the tickets' departments
 *
*/
CREATE TABLE `tickerr_ticket_departments`(
`id` INT NOT NULL AUTO_INCREMENT,
PRIMARY KEY(`id`),
`name` VARCHAR(200) NOT NULL,
`agents` INT NOT NULL,
`tickets` INT NOT NULL,
`date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`default` INT(1) NOT NULL DEFAULT 2
);


/*
 *
 * Here are stored the bug reports' departments
 *
*/
CREATE TABLE `tickerr_bug_departments`(
`id` INT NOT NULL AUTO_INCREMENT,
PRIMARY KEY(`id`),
`name` VARCHAR(200) NOT NULL,
`agents` INT NOT NULL,
`reports` INT NOT NULL,
`date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`default` INT(1) NOT NULL DEFAULT 2
);


/*
 *
 * Here are stored the tickets
 *
*/
CREATE TABLE `tickerr_tickets`(
`id` INT NOT NULL AUTO_INCREMENT,
PRIMARY KEY(`id`),
`department` INT NOT NULL,
`userid` INT NOT NULL,
`guest_name` VARCHAR(150) NOT NULL,
`guest_email` VARCHAR(40) NOT NULL,
`agentid` INT NOT NULL,
`access` VARCHAR(10) NOT NULL,
`status` INT(1) NOT NULL,
`priority` INT(1) NOT NULL,
`date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`last_update` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`subject` TEXT NOT NULL,
`content` TEXT NOT NULL,
`files` TEXT NOT NULL,
`transferred_from` INT NOT NULL,
`rating` DECIMAL(2,1) NOT NULL,
`rating_msg` TEXT NOT NULL
);


/*
 *
 * Here are stored the tickets' replies
 *
*/
CREATE TABLE `tickerr_ticket_replies`(
`id` INT NOT NULL AUTO_INCREMENT,
PRIMARY KEY(`id`),
`ticketid` INT NOT NULL,
`userid` INT NOT NULL,
`agentid` INT NOT NULL,
`content` TEXT NOT NULL,
`date` DATETIME NOT NULL,
`files` TEXT NOT NULL
);


/*
 *
 * Here are stored the bug reports
 *
*/
CREATE TABLE `tickerr_bugs`(
`id` INT NOT NULL AUTO_INCREMENT,
PRIMARY KEY(`id`),
`department` INT NOT NULL,
`userid` INT NOT NULL,
`guest_name` VARCHAR(150) NOT NULL,
`guest_email` VARCHAR(40) NOT NULL,
`agentid` INT NOT NULL,
`access` VARCHAR(10) NOT NULL,
`status` INT(1) NOT NULL,
`priority` INT(1) NOT NULL,
`date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`last_update` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`subject` TEXT NOT NULL,
`content` TEXT NOT NULL,
`files` TEXT NOT NULL,
`transferred_from` INT NOT NULL,
`agent_msg` TEXT NOT NULL
);


/*
 *
 * Here are stored the settings
 *
*/
CREATE TABLE `tickerr_settings`(
`id` INT NOT NULL AUTO_INCREMENT,
PRIMARY KEY(`id`),
`name` VARCHAR(300) NOT NULL,
`value` TEXT NOT NULL
);


/*
 *
 * Create settings
 *
*/
INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('site_title', 'Tickerr - Support Tickets System');
INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('confirm_purchase_codes', '0');
INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('confirm_purchase_codes_username', '');
INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('confirm_purchase_codes_api', '');
INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('allow_guest_bug_reports', '1');
INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('allow_guest_tickets', '1');
INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('allow_account_creations', '1');
INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('allow_guest_file_uploads', '1');
INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('allow_file_uploads', '1');
INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('file_uploads_max_size', '10');
INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('file_uploads_extensions', 'jpg|jpeg|png|gif|zip|pdf');
INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('mailing', '0');
INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('mailer_method', '1');
INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('smtp_host', '');
INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('smtp_port', '');
INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('smtp_user', '');
INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('smtp_pass', '');
INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('smtp_timeout', '5');
INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('mailpath', '/usr/bin/sendmail');
INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('email_confirmation', '0');
INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('email_from_address', 'example@example.com');
INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('email_from_name', 'Your Name Here');
INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('email_cc', '');
INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('send_email_ticket_guest_submitted', '1');
INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('email_ticket_guest_submitted_type', 'html');
INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('email_ticket_guest_submitted_title', 'Ticket Submitted');
INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('email_ticket_guest_submitted_content', "Hi, <strong>%user_name%</strong>.<br /><br />We are just letting you know that your ticket titled \"%ticket_subject%\" has been successfully created.<br />To take a look at it, click <a href=\"%ticket_url%\">here</a>");
INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('send_email_bug_guest_submitted', '1');
INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('email_bug_guest_submitted_type', 'html');
INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('email_bug_guest_submitted_title', 'Bug Report Submitted');
INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('email_bug_guest_submitted_content', "Hi, <strong>%user_name%</strong>.<br /><br />We are just letting you know that your bug report titled \"%report_subject%\" has been successfully created.<br />To take a look at it, click <a href=\"%report_url%\">here</a>");
INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('send_email_new_account', '1');
INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('email_new_account_type', 'html');
INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('email_new_account_title', 'New account has been created!');
INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('email_new_account_content', "Hi, <strong>%user_name%</strong>.<br /><br />We are just letting you know that your new account has been created! To login, click <a href=\"%site_url%\">here</a>");
INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('email_new_account_confirmation_type', 'html');
INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('email_new_account_confirmation_title', 'Please confirm your new account');
INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('email_new_account_confirmation_content', "Hi, <strong>%user_name%</strong>.<br /><br />Your new account has been created! However, we need you to confirm this email address. To do so, click the following link: <a href=\"%confirmation_url%\">%confirmation_url%</a>");
INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('send_email_confirmed_account', '1');
INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('email_confirmed_account_type', 'html');
INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('email_confirmed_account_title', 'Your account has been confirmed');
INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('email_confirmed_account_content', "Hi, <strong>%user_name%</strong>.<br /><br />Your email address has been confirmed! Now you can use your account. To login, click <a href=\"%site_url%\">here</a>");
INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('allow_account_recovery', '1');
INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('email_recover_type', 'html');
INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('email_recover_title', 'Account recovery');
INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('email_recover_content', "Hi, <strong>%user_name%</strong>.<br /><br />Apparently you cannot remember your password, and you requested to change it. To do so, please click the following link to continue with the process:<br /><a href=\"%recovery_url%\">%recovery_url%</a>.<br /><br />If you didn't request this password change, just ignore this email.");
INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('send_email_recovery_done', '1');
INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('email_recovery_done_type', 'html');
INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('email_recovery_done_title', 'Your account has been recovered');
INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('email_recovery_done_content', "Hi, <strong>%user_name%</strong>.<br /><br />We are just letting you know that the password of your account has been successfully changed.");
INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('send_email_new_reply', '1');
INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('email_new_reply_type', 'html');
INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('email_new_reply_title', 'Your ticket has a new reply!');
INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('email_new_reply_content', "Hi, <strong>%user_name%</strong>.<br /><br />It seems that your Ticket titled \"%ticket_subject%\" has a new reply!. You can take a look at it by clicking <a href=\"%ticket_url%\">here</a>");
INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('send_email_bug_new_status', '1');
INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('email_bug_new_status_type', 'html');
INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('email_bug_new_status_title', 'Bug report updates!');
INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('email_bug_new_status_content', "Hi, <strong>%user_name%</strong>.<br /><br />It seems that your bug report titled \"%report_subject%\" has a new status!. You can take a look at it by clicking <a href=\"%report_url%\">here</a>");


/* Email to agents when guest creates new ticket */
INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('send_agents_email_ticket_guest_submitted', '1');
INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('agents_email_ticket_guest_submitted_type', 'html');
INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('agents_email_ticket_guest_submitted_title', 'Guest has submitted a new ticket');
INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('agents_email_ticket_guest_submitted_content', "Hi, <strong>%agent_user_name%</strong>.<br /><br />A guest has submitted a new ticket titled \"%ticket_subject%\" in the \"%ticket_department_name%\" department.<br />You can reply to it by clicking <a href=\"%ticket_url%\">here</a>");

/* Email to agents when client creates new ticket */
INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('send_agents_email_ticket_client_submitted', '1');
INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('agents_email_ticket_client_submitted_type', 'html');
INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('agents_email_ticket_client_submitted_title', 'Client has submitted a new ticket');
INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('agents_email_ticket_client_submitted_content', "Hi, <strong>%agent_user_name%</strong>.<br /><br />%user_name% (%user_username%) has submitted a new ticket titled \"%ticket_subject%\" in the \"%ticket_department_name%\" department.<br />You can reply to it by clicking <a href=\"%ticket_url%\">here</a>");

/* Email to agents when guest creates new bug report */
INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('send_agents_email_bug_guest_submitted', '1');
INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('agents_email_bug_guest_submitted_type', 'html');
INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('agents_email_bug_guest_submitted_title', 'Guest has submitted a new bug report');
INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('agents_email_bug_guest_submitted_content', "Hi, <strong>%agent_user_name%</strong>.<br /><br />A guest has submitted a new bug report titled \"%report_subject%\" in the \"%report_department_name%\" department.<br />You can review it by clicking <a href=\"%ticket_url%\">here</a>");

/* Email to agents when client creates new bug report */
INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('send_agents_email_bug_client_submitted', '1');
INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('agents_email_bug_client_submitted_type', 'html');
INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('agents_email_bug_client_submitted_title', 'Client has submitted a new bug report');
INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('agents_email_bug_client_submitted_content', "Hi, <strong>%agent_user_name%</strong>.<br /><br />%user_name% (%user_username%) has submitted a new bug report titled \"%report_subject%\" in the \"%report_department_name%\" department.<br />You can review it by clicking <a href=\"%ticket_url%\">here</a>");

/* Email to current agent when ticket has new reply */
INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('send_agent_email_new_reply', '1');
INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('agent_email_new_reply_type', 'html');
INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('agent_email_new_reply_title', 'Ticket has a new reply!');
INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('agent_email_new_reply_content', "Hi, <strong>%agent_user_name%</strong>.<br /><br />A ticket titled \"%ticket_subject%\" has a new reply. You can reply back by clicking <a href=\"%ticket_url%\">here</a>");