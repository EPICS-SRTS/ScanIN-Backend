<?php
session_start();

// First we need to check everything is in order..
if(!file_exists('core/checker.php') || !file_exists('core/post.php')) {
	header('Location: index.php');
	die();
}

require 'core/checker.php';
$checker = new Checker;
$checker->check_system();

if($checker->get_n_result() == 2) {
	header('Location: index.php');
	die();
}

// Post data? Proceed.
if(isset($_POST['sent']) && $_POST['sent'] == '1') {
	// Check received data
	$data = array(
		'mysql_host', 'mysql_username', 'mysql_password', 'mysql_database',
		'site_url', 'site_title',
		'account_username', 'account_name', 'account_email', 'account_pass', 'account_rpass'
	);
	foreach($data as $d) {
		if(!isset($_POST[$d])) {
			header('Location: install.php');
			die();
		}else
			$$d = $_POST[$d];
	}
	
	// Validate data
	if($mysql_host == ''
	   || $mysql_username == ''
	   || $mysql_database == ''
	   || $site_url == ''
	   || ((substr($site_url, 0, 7) != 'http://' && substr($site_url, 0, 8) != 'https://') || substr($site_url, -1) != '/')
	   || $site_title == ''
	   || $account_username == ''
	   || strlen($account_name) < 5
	   || $account_email == ''
	   || filter_var($account_email, FILTER_VALIDATE_EMAIL) == false
	   || strlen($account_pass) < 5
	   || $account_rpass == ''
	   || $account_pass != $account_rpass
	) {
		header('Location: install.php');
		die();
	}
	
	$error = false;
	
	// Try to connect to the MySQL server using PDO
	$dsn = "mysql:dbname=$mysql_database;host=$mysql_host";
	try {
		$pdo = new PDO($dsn, $mysql_username, $mysql_password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // PDO connection successfully made, continue..
		// Delete config.local.php (if it exists)
		if(file_exists('../config.local.php')) {
			$unlink = unlink('../config.local.php');
			if($unlink == false)
				$error = 'A config.local.php file was detected and it couldn\'t be deleted';
		}
		
		// All good, continue
		if($error == false) {
			// Get sample file and start saving new file...
			$file = fopen('../config.sample.php', 'r+');
			if($file == false)
				$error = 'The config.sample.php file couldn\'t be opened.';
		}
		
		// All good, continue
		if($error == false && $file != false) {
			$file_content = fread($file, filesize('../config.sample.php'));
			
			// Replace some lines
			$file_content = str_replace('%base_url%', $site_url, $file_content);
			$file_content = str_replace('%hostname%', $mysql_host, $file_content);
			$file_content = str_replace('%username%', $mysql_username, $file_content);
			$file_content = str_replace('%password%', $mysql_password, $file_content);
			$file_content = str_replace('%database%', $mysql_database, $file_content);
			
			// Close file and reopen it with write permissions
			fclose($file);
			
			$file_w = fopen('../config.sample.php', 'w');
			
			// Write new info, close file and rename it
			fwrite($file_w, $file_content);
			fclose($file_w);
			
			if(rename('../config.sample.php', '../config.local.php') == false)
				$error = 'Config.sample.php file has been modified, but it couldn\'t be renamed.';
		}
		
		if($error == false) {
			// Drop existing tables...
			$drop_tables = array('users','ticket_departments','bug_departments','tickets','ticket_replies','bugs','settings');
			foreach($drop_tables as $table) {
				$pdo->query("DROP TABLE IF EXISTS `tickerr_$table`");
			}
			
			// Create new tables
			$create_tables = array(
				"CREATE TABLE `tickerr_users`(`id` INT NOT NULL AUTO_INCREMENT, PRIMARY KEY(`id`), `username` VARCHAR(100) NOT NULL, `name` VARCHAR(150) NOT NULL, `email` VARCHAR(40) NOT NULL, `profile_img1x` VARCHAR(400) NOT NULL, `profile_img2x` VARCHAR(400) NOT NULL, `profile_img3x` VARCHAR(400) NOT NULL, `date` DATETIME NOT NULL DEFAULT '2019-01-01 00:00:00', `password` CHAR(32) NOT NULL, `role` INT(1) NOT NULL, `ticket_departments` VARCHAR(300) NOT NULL, `bug_departments` VARCHAR(300) NOT NULL, `email_on_tactivity` INT(1) NOT NULL DEFAULT 1, `email_on_bactivity` INT(1) NOT NULL DEFAULT 1, `email_confirmation` INT(1) NOT NULL DEFAULT 2, `confirmation_str` VARCHAR(25) NOT NULL, `recover_password_str` VARCHAR(35) NOT NULL)",
				"CREATE TABLE `tickerr_ticket_departments`(`id` INT NOT NULL AUTO_INCREMENT, PRIMARY KEY(`id`), `name` VARCHAR(200) NOT NULL, `agents` INT NOT NULL, `tickets` INT NOT NULL, `date` DATETIME NOT NULL DEFAULT '2019-01-01 00:00:00', `default` INT(1) NOT NULL DEFAULT 2)",
				"CREATE TABLE `tickerr_bug_departments`(`id` INT NOT NULL AUTO_INCREMENT, PRIMARY KEY(`id`), `name` VARCHAR(200) NOT NULL, `agents` INT NOT NULL, `reports` INT NOT NULL, `date` DATETIME NOT NULL DEFAULT '2019-01-01 00:00:00', `default` INT(1) NOT NULL DEFAULT 2)",
				"CREATE TABLE `tickerr_tickets`(`id` INT NOT NULL AUTO_INCREMENT, PRIMARY KEY(`id`), `department` INT NOT NULL, `userid` INT NOT NULL, `guest_name` VARCHAR(150) NOT NULL, `guest_email` VARCHAR(40) NOT NULL, `agentid` INT NOT NULL, `access` VARCHAR(10) NOT NULL, `status` INT(1) NOT NULL, `priority` INT(1) NOT NULL, `date` DATETIME NOT NULL DEFAULT '2019-01-01 00:00:00', `last_update` DATETIME NOT NULL DEFAULT '2019-01-01 00:00:00', `subject` TEXT NOT NULL, `content` TEXT NOT NULL, `files` TEXT NOT NULL, `transferred_from` INT NOT NULL, `rating` DECIMAL(2,1) NOT NULL, `rating_msg` TEXT NOT NULL)",
				"CREATE TABLE `tickerr_ticket_replies`(`id` INT NOT NULL AUTO_INCREMENT, PRIMARY KEY(`id`), `ticketid` INT NOT NULL, `userid` INT NOT NULL, `agentid` INT NOT NULL, `content` TEXT NOT NULL, `date` DATETIME NOT NULL, `files` TEXT NOT NULL)",
				"CREATE TABLE `tickerr_bugs`(`id` INT NOT NULL AUTO_INCREMENT, PRIMARY KEY(`id`), `department` INT NOT NULL, `userid` INT NOT NULL, `guest_name` VARCHAR(150) NOT NULL, `guest_email` VARCHAR(40) NOT NULL, `agentid` INT NOT NULL, `access` VARCHAR(10) NOT NULL, `status` INT(1) NOT NULL, `priority` INT(1) NOT NULL, `date` DATETIME NOT NULL DEFAULT '2019-01-01 00:00:00', `last_update` DATETIME NOT NULL DEFAULT '2019-01-01 00:00:00', `subject` TEXT NOT NULL, `content` TEXT NOT NULL, `files` TEXT NOT NULL, `transferred_from` INT NOT NULL, `agent_msg` TEXT NOT NULL)",
				"CREATE TABLE `tickerr_settings`(`id` INT NOT NULL AUTO_INCREMENT, PRIMARY KEY(`id`), `name` VARCHAR(300) NOT NULL, `value` TEXT NOT NULL)"
			);
			foreach($create_tables as $query) {
				if($error == false) {
					if($pdo->query($query) == false) {
						$error = "One of Tickerr tables couldn't be created. Please try again";
					}
				}
			}
		}
		
		// Once the tables where created, start inserting info...
		if($error == false) {
			$q1 = $pdo->prepare("INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('site_title', ?)");
			if($q1 == false)
				$error = "The installation couldn't be completed. Please try again";
			else {
				if($q1->execute(array($site_title)) == false)
					$error = "The installation couldn't be completed. Please try againnnn";
			}
		}
		
		// If the first thing was successfully inserted, continue with the rest...
		if($error == false ) {
			
			$queries = array(
				"INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('confirm_purchase_codes', '0')",
				"INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('confirm_purchase_codes_username', '')",
				"INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('confirm_purchase_codes_api', '')",
				"INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('allow_guest_bug_reports', '1')",
				"INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('allow_guest_tickets', '1')",
				"INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('allow_account_creations', '1')",
				"INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('allow_guest_file_uploads', '1')",
				"INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('allow_file_uploads', '1')",
				"INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('file_uploads_max_size', '100')",
				"INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('file_uploads_extensions', 'jpg|jpeg|png|gif|zip|pdf')",
				"INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('mailing', '0')",
				"INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('mailer_method', '1')",
				"INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('smtp_host', '')",
				"INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('smtp_port', '')",
				"INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('smtp_user', '')",
				"INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('smtp_pass', '')",
				"INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('smtp_timeout', '5')",
				"INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('mailpath', '/usr/bin/sendmail')",
				"INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('email_confirmation', '0')",
				"INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('email_from_address', '')",
				"INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('email_from_name', '')",
				"INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('email_cc', '')",
				"INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('send_email_ticket_guest_submitted', '1')",
				"INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('email_ticket_guest_submitted_type', 'html')",
				"INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('email_ticket_guest_submitted_title', 'Ticket Submitted')",
				"INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('email_ticket_guest_submitted_content', \"Hi, <strong>%user_name%</strong>.<br /><br />We are just letting you know that your ticket titled \\\"%ticket_subject%\\\" has been successfully created.<br />To take a look at it, click <a href=\\\"%ticket_url%\\\">here</a>\")",
				"INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('send_email_bug_guest_submitted', '1')",
				"INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('email_bug_guest_submitted_type', 'html')",
				"INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('email_bug_guest_submitted_title', 'Bug Report Submitted')",
				"INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('email_bug_guest_submitted_content', \"Hi, <strong>%user_name%</strong>.<br /><br />We are just letting you know that your bug report titled \\\"%report_subject%\\\" has been successfully created.<br />To take a look at it, click <a href=\\\"%report_url%\\\">here</a>\")",
				"INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('send_email_new_account', '1')",
				"INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('email_new_account_type', 'html')",
				"INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('email_new_account_title', 'New account has been created!')",
				"INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('email_new_account_content', \"Hi, <strong>%user_name%</strong>.<br /><br />We are just letting you know that your new account has been created! To login, click <a href=\\\"%site_url%\\\">here</a>\")",
				"INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('email_new_account_confirmation_type', 'html')",
				"INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('email_new_account_confirmation_title', 'Please confirm your new account')",
				"INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('email_new_account_confirmation_content', \"Hi, <strong>%user_name%</strong>.<br /><br />Your new account has been created! However, we need you to confirm this email address. To do so, click the following link: <a href=\\\"%confirmation_url%\\\">%confirmation_url%</a>\")",
				"INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('send_email_confirmed_account', '1')",
				"INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('email_confirmed_account_type', 'html')",
				"INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('email_confirmed_account_title', 'Your account has been confirmed')",
				"INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('email_confirmed_account_content', \"Hi, <strong>%user_name%</strong>.<br /><br />Your email address has been confirmed! Now you can use your account. To login, click <a href=\\\"%site_url%\\\">here</a>\")",
				"INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('allow_account_recovery', '1')",
				"INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('email_recover_type', 'html')",
				"INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('email_recover_title', 'Account recovery')",
				"INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('email_recover_content', \"Hi, <strong>%user_name%</strong>.<br /><br />Apparently you cannot remember your password, and you requested to change it. To do so, please click the following link to continue with the process:<br /><a href=\\\"%recovery_url%\\\">%recovery_url%</a>.<br /><br />If you didn't request this password change, just ignore this email.\")",
				"INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('send_email_recovery_done', '1')",
				"INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('email_recovery_done_type', 'html')",
				"INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('email_recovery_done_title', 'Your account has been recovered')",
				"INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('email_recovery_done_content', \"Hi, <strong>%user_name%</strong>.<br /><br />We are just letting you know that the password of your account has been successfully changed.\")",
				"INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('send_email_new_reply', '1')",
				"INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('email_new_reply_type', 'html')",
				"INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('email_new_reply_title', 'Your ticket has a new reply!')",
				"INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('email_new_reply_content', \"Hi, <strong>%user_name%</strong>.<br /><br />It seems that your Ticket titled \\\"%ticket_subject%\\\" has a new reply!. You can take a look at it by clicking <a href=\\\"%ticket_url%\\\">here</a>\")",
				"INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('send_email_bug_new_status', '1')",
				"INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('email_bug_new_status_type', 'html')",
				"INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('email_bug_new_status_title', 'Bug report updates!')",
				"INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('email_bug_new_status_content', \"Hi, <strong>%user_name%</strong>.<br /><br />It seems that your bug report titled \\\"%report_subject%\\\" has a new status!. You can take a look at it by clicking <a href=\\\"%report_url%\\\">here</a>\")",
				
				// New lines
				"INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('send_agents_email_ticket_guest_submitted', '1')",
				"INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('agents_email_ticket_guest_submitted_type', 'html')",
				"INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('agents_email_ticket_guest_submitted_title', 'Guest has submitted a new ticket')",
				"INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('agents_email_ticket_guest_submitted_content', \"Hi, <strong>%agent_user_name%</strong>.<br /><br />A guest has submitted a new ticket titled \\\"%ticket_subject%\\\" in the \\\"%ticket_department_name%\\\" department.<br />You can reply to it by clicking <a href=\\\"%ticket_url%\\\">here</a>\")",
				"INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('send_agents_email_ticket_client_submitted', '1')",
				"INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('agents_email_ticket_client_submitted_type', 'html')",
				"INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('agents_email_ticket_client_submitted_title', 'Client has submitted a new ticket')",
				"INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('agents_email_ticket_client_submitted_content', \"Hi, <strong>%agent_user_name%</strong>.<br /><br />%user_name% (%user_username%) has submitted a new ticket titled \\\"%ticket_subject%\\\" in the \\\"%ticket_department_name%\\\" department.<br />You can reply to it by clicking <a href=\\\"%ticket_url%\\\">here</a>\")",
				"INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('send_agents_email_bug_guest_submitted', '1')",
				"INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('agents_email_bug_guest_submitted_type', 'html')",
				"INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('agents_email_bug_guest_submitted_title', 'Guest has submitted a new bug report')",
				"INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('agents_email_bug_guest_submitted_content', \"Hi, <strong>%agent_user_name%</strong>.<br /><br />A guest has submitted a new bug report titled \\\"%report_subject%\\\" in the \\\"%report_department_name%\\\" department.<br />You can review it by clicking <a href=\\\"%ticket_url%\\\">here</a>\")",
				"INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('send_agents_email_bug_client_submitted', '1')",
				"INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('agents_email_bug_client_submitted_type', 'html')",
				"INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('agents_email_bug_client_submitted_title', 'Client has submitted a new bug report')",
				"INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('agents_email_bug_client_submitted_content', \"Hi, <strong>%agent_user_name%</strong>.<br /><br />%user_name% (%user_username%) has submitted a new bug report titled \\\"%report_subject%\\\" in the \\\"%report_department_name%\\\" department.<br />You can review it by clicking <a href=\\\"%ticket_url%\\\">here</a>\")",
				"INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('send_agent_email_new_reply', '1')",
				"INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('agent_email_new_reply_type', 'html')",
				"INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('agent_email_new_reply_title', 'Ticket has a new reply!')",
				"INSERT INTO `tickerr_settings`(`name`,`value`) VALUES ('agent_email_new_reply_content', \"Hi, <strong>%agent_user_name%</strong>.<br /><br />A ticket titled \\\"%ticket_subject%\\\" has a new reply. You can reply back by clicking <a href=\\\"%ticket_url%\\\">here</a>\")"
			);
			
			// Execute each query...
			foreach($queries as $query) {
				if($error == false) {
					$executed_query = $pdo->query($query);
					if($executed_query == false)
						$error = "The installation couldn't be completed. Please try again";
				}
			}
		}
		
		// If queries were successfully executed, create admin
		if($error == false) {
			$password = md5($account_pass);
			$date = date('Y-m-d H:i:s');
			
			$prepared = $pdo->prepare("INSERT INTO `tickerr_users`(`username`,`name`,`email`,`profile_img1x`,`profile_img2x`,`profile_img3x`,`date`,`password`,`role`,`ticket_departments`,`bug_departments`,`email_on_tactivity`,`email_on_bactivity`,`email_confirmation`,`confirmation_str`,`recover_password_str`) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
			if($prepared == false)
				$error = "The installation couldn't be completed. Please try again";
			else{
				$exec = $prepared->execute(array($account_username, $account_name, $account_email, 'fa-user@1x.png', 'fa-user@2x.png', 'fa-user@3x.png', $date, $password, 3, '1', '1', '1', '1', '2', '', ''));
				if($exec == false)
					$error = "The installation couldn't be completed. Please try again";
			}
		}
		
		// Now create default departments and we're done
		if($error == false) {
			$q1 = $pdo->query("INSERT INTO `tickerr_ticket_departments`(`name`,`agents`,`tickets`,`date`,`default`) VALUES('General', 1, 0, '$date', 1)");
			if($q1 == false)
				$error = "The installation couldn't be completed. Please try again";
			else{
				$q2 = $pdo->query("INSERT INTO `tickerr_bug_departments`(`name`,`agents`,`reports`,`date`,`default`) VALUES('General', 1, 0, '$date', 1)");
				if($q2 == false)
					$error = "The installation couldn't be completed. Please try again";
			}
			
		}
		
		if($error == false) {
			// Are we done? Success!!
			$_SESSION['tickerr_installation_success'] = true;
			header('Location: success.php');
			die();
		}
	} catch(PDOException $e) {
		$error = "Tickerr couldn't connect to your MySQL Host. Please check the details.<br />The error thrown is: ".$e->getMessage();
	}
}

// Get default base
$default_base = 'http://' . $_SERVER['SERVER_NAME'] . dirname($_SERVER['REQUEST_URI']);
if(substr($default_base, -8) == '/install')
	$default_base = substr($default_base, 0, -7);
elseif(substr($default_base, -9) == '/install/')
	$default_base = substr($default_base, 0, -8);

require 'core/post.php';
$post = new Post;
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta chartset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<title>Tickerr - Installation</title>
	
	<link href="../assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
	<link href="../assets/css/font-awesome.min.css" rel="stylesheet" />
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600,700|PT+Sans:400,700|Open+Sans:300,400,600,700,800|Roboto' rel='stylesheet' type='text/css'>
	<link href="style.css" rel="stylesheet" />

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body>
	<div id="container" class="installation">
		<a href="">
			<img src="../assets/img/logos/mainlogo@1x.png" srcset="../assets/img/logos/mainlogo@1x.png 1x, ../assets/img/logos/mainlogo@2x.png 2x, ../assets/img/logos/mainlogo@3x.png 3x" width="200" height="45" title="Tickerr" style="margin-top:-50px;" />
		</a>
		
		<div id="central-container" class="clearfix">
			<h3 class="center">INSTALLATION</h3>
			
			<?php
			if($checker->get_n_result() == 1)
				echo 'To install Tickerr, we need you to provide us with some details. Before continuing, please be sure you\'ve fully read the previous page.';
			else
				echo 'To install Tickerr, we need you to provide us with some details.';
			?>
			
			<?php
			if(!isset($error) || $error == false)
				echo '<p class="bg-danger bg-danger-fix" style="display:none;"></p>';
			else
				echo '<p class="bg-danger bg-danger-fix">'.$error.'</p>';
			?>
			
			<br /><br />
			
			<form method="POST" action="" name="install">
				<div class="form-group">
					<label for="mysql_host">MySQL Host</label>
					<input type="text" id="mysql_host" name="mysql_host" value="<?php echo $post->post_value('mysql_host', 'localhost'); ?>" />
				</div>
				
				<div class="form-group">
					<label for="mysql_username">MySQL Username</label>
					<input type="text" id="mysql_username" name="mysql_username" value="<?php echo $post->post_value('mysql_username'); ?>" />
				</div>
				
				<div class="form-group">
					<label for="mysql_password">MySQL Password</label>
					<input type="password" id="mysql_password" name="mysql_password" value="<?php echo $post->post_value('mysql_password'); ?>" />
				</div>
				
				<div class="form-group">
					<label for="mysql_database">MySQL Database Name</label>
					<input type="text" id="mysql_database" name="mysql_database" value="<?php echo $post->post_value('mysql_database'); ?>" />
				</div>
				
				<div style="height:3px; background-color:#e9e9e9; margin:25px -10px 18px -10px;"></div>
				
				<div class="form-group">
					<label for="site_url">Site URL</label>
					<span class="label_desc">Type here the URL where Tickerr is going to be installed. E.g. http://yoursite.com/<br />
					(A URL has been generated automatically, please check if it's correct).</span>
					<input type="text" id="site_url" name="site_url" value="<?php echo $post->post_value('site_url', $default_base); ?>" />
				</div>
				
				<div class="form-group">
					<label for="site_title">Site Title</label>
					<span class="label_desc">Type the title you want for your site</span>
					<input type="text" id="site_title" name="site_title" value="<?php echo $post->post_value('site_title', 'Tickerr - Support System'); ?>" />
				</div>
				
				<div style="height:3px; background-color:#e9e9e9; margin:25px -10px 18px -10px;"></div>
				
				<div class="form-group">
					<label for="account_username">Account Username</label>
					<span class="label_desc">This is going to be the username of your new Tickerr account.</span>
					<input type="text" id="account_username" name="account_username" value="<?php echo $post->post_value('account_username'); ?>" />
				</div>
				
				<div class="form-group">
					<label for="account_name">Account Name</label>
					<span class="label_desc">Type here your name for your new Tickerr account.</span>
					<input type="text" id="account_name" name="account_name" value="<?php echo $post->post_value('account_name'); ?>" />
				</div>
				
				<div class="form-group">
					<label for="account_email">Account Email</label>
					<span class="label_desc">Type here your email for your new Tickerr account.</span>
					<input type="text" id="account_email" name="account_email" value="<?php echo $post->post_value('account_email'); ?>" />
				</div>
				
				<div class="form-group">
					<label for="account_pass">Account Password</label>
					<span class="label_desc">This is going to be the password of your new Tickerr account.</span>
					<input type="password" id="account_pass" name="account_pass" value="<?php echo $post->post_value('account_pass'); ?>" />
				</div>
				
				<div class="form-group">
					<label for="account_rpass">Repeat Account Password</label>
					<span class="label_desc">Repeat the password of your new Tickerr account.</span>
					<input type="password" id="account_rpass" name="account_rpass" value="<?php echo $post->post_value('account_rpass'); ?>" />
				</div>
				
				<?php
				if($checker->get_n_result() == 1) {
				?>
				<div class="warning">
					As the previous page showed you, some errors were detected. The errors are not critical so you can continue with
					the installation, but as we said, you need to keep in mind that it goes on your own responsability. Old PHP versions
					could cause errors, and if a previous Tickerr installation is existing, data will be deleted.
				</div>
				<?php
				}
				?>
				
				<input type="hidden" name="sent" value="1" />

				<button type="submit" name="install" class="pull-right" style="margin-top:0px;">Install</button>
			</form>
		</div>
	</div>
	
	
	<script src="../assets/js/jquery-1.11.3.min.js"></script>
	<script type="text/javascript">
		$('document').ready(function() {
			$('form[name=install]').submit(function(evt) {
				var mysql_host = $('input[name=mysql_host]').val();
				var mysql_username = $('input[name=mysql_username]').val();
				var mysql_password = $('input[name=mysql_password]').val();
				var mysql_database = $('input[name=mysql_database]').val();
				var site_url = $('input[name=site_url]').val();
				var site_title = $('input[name=site_title]').val();
				var account_username = $('input[name=account_username]').val();
				var account_name = $('input[name=account_name]').val();
				var account_email = $('input[name=account_email]').val();
				var account_pass = $('input[name=account_pass]').val();
				var account_rpass = $('input[name=account_rpass]').val();
				
				if(mysql_host == '') {
					evt.preventDefault();
					error('Please type the MySQL Host', '[name=mysql_host]');
					return false;
				}
				if(mysql_username == '') {
					evt.preventDefault();
					error('Please type the MySQL Username', '[name=mysql_username]');
					return false;
				}
				if(mysql_database == '') {
					evt.preventDefault();
					error('Please type the MySQL Database Name', '[name=mysql_database]');
					return false;
				}
				
				if(site_url == '') {
					evt.preventDefault();
					error('Please type the Site URL', '[name=site_url]');
					return false;
				}
				
				if((site_url.substr(0,7) != 'http://' && site_url.substr(0,8) != 'https://') || site_url.substr(site_url.length - 1) != '/') {
					evt.preventDefault();
					error('Please type a valid Site URL. It must begin with http:// and end with /<br />E.g: http://mysite.com/', '[name=site_url]');
					return false;
				}
				
				if(site_title == '') {
					evt.preventDefault();
					error('Please type the Site Title', '[name=site_title]');
					return false;
				}
				
				if(account_username == '') {
					evt.preventDefault();
					error('Please type the username of your new account', '[name=account_username]');
					return false;
				}
				if(account_name == '') {
					evt.preventDefault();
					error('Please type your name', '[name=account_name]');
					return false;
				}
				if(account_name.length < 5) {
					evt.preventDefault();
					error('Your name must be at least 5 characters long', '[name=account_name]');
					return false;
				}
				if(account_email == '') {
					evt.preventDefault();
					error('Please type your email address', '[name=account_email]');
					return false;
				}
				if(validateEmail(account_email) == false) {
					evt.preventDefault();
					error('Please type a valid email address', '[name=account_email]');
					return false;
				}
				if(account_pass == '') {
					evt.preventDefault();
					error('Please type a password for your new account', '[name=account_pass]');
					return false;
				}
				if(account_pass < 5) {
					evt.preventDefault();
					error('The password for you new account must be at least 5 characters long', '[name=account_pass]');
					return false;
				}
				if(account_rpass == '') {
					evt.preventDefault();
					error('Please type your password again', '[name=account_rpass]');
					return false;
				}
				if(account_pass != account_rpass) {
					evt.preventDefault();
					error('Both password must match', '[name=password]', '[name=rpassword]');
					return false;
				}
			});
			
			var e_active = false;
			var e_active2 = false;
			function error(e, n, n2) {
				if(e_active != false)
					$(e_active).css('border-color', '#d0d0d0').removeClass('error');
				if(e_active2 != false)
					$(e_active2).css('border-color', '#d0d0d0').removeClass('error');
				
				$(n).css('border-color','#ff0000').addClass('error');
				e_active = n;
				
				if(n2 !== undefined) {
					$(n2).css('border-color','#ff0000').addClass('error');
					e_active2 = n2;
				}
					
				
				$('p.bg-danger').slideUp(200, function() {
					$('p.bg-danger').html(e).slideDown(200);
				});
			}
			
			function validateEmail(email) {
				var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
				return re.test(email);
			}
		});
	</script>
	
</body>
</html>