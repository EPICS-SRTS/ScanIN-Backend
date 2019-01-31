<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta chartset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<title><?php echo $site_title; ?></title>
	
	<link href="<?php echo asset_url(); ?>bootstrap/css/bootstrap.min.css" rel="stylesheet" />
	<link href="<?php echo asset_url(); ?>css/main/main.css" rel="stylesheet" />
	<link href="<?php echo asset_url(); ?>css/main/ticket-bug.css" rel="stylesheet" />
	<link href="<?php echo asset_url(); ?>css/tinyeditor.css" rel="stylesheet" />
	<link href="<?php echo asset_url(); ?>css/font-awesome.min.css" rel="stylesheet" />
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600,700|PT+Sans:400,700|Open+Sans:300,400,600,700,800|Roboto' rel='stylesheet' type='text/css'>

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body>
	<div id="container" class="create-bug-report">
		<img src="<?php echo asset_url(); ?>img/logos/mainlogo@1x.png" srcset="<?php echo asset_url(); ?>img/logos/mainlogo@1x.png 1x, <?php echo asset_url(); ?>img/logos/mainlogo@2x.png 2x, <?php echo asset_url(); ?>img/logos/mainlogo@3x.png 3x" width="270" height="55" title="<?php echo $site_title; ?>" />
		
		<div id="central-container" class="clearfix">
			<h3 class="center">CREATE ACCOUNT</h3>
			
			<?php
			if($error != false)
				echo '<div style="display:block" id="error">'.$error.'</div>';
			else
				echo '<div id="error"></div>';
			?>
			
			<form method="POST" name="new-account" action="">
				<label for="name">YOUR NAME</label>
				<input type="text" name="name" id="name" placeholder="Type your name..." value="<?php echo $this->input->post('name'); ?>" />
				
				<label for="username">YOUR USERNAME</label>
				<input type="text" name="username" id="username" placeholder="Type your username..." value="<?php echo $this->input->post('username'); ?>" />
				
				<label for="email">YOUR EMAIL</label>
				<input type="text" name="email" id="email" placeholder="Type your email..." value="<?php echo $this->input->post('email'); ?>" />
				
				<label for="password">PASSWORD</label>
				<input type="password" name="password" id="password" placeholder="Type your password..." value="<?php echo $this->input->post('password'); ?>" />
				
				<label for="rpassword">REPEAT YOUR PASSWORD</label>
				<input type="password" name="rpassword" id="rpassword" placeholder="Repeat your password..." value="<?php echo $this->input->post('rpassword'); ?>" />
				
				<input type="submit" name="submit" class="pull-right" value="CREATE ACCOUNT" />
			</form>
		</div>
	</div>
	
	
	<script src="<?php echo asset_url(); ?>js/jquery-1.11.3.min.js"></script>
	<script type="text/javascript">
		$('document').ready(function() {
			$('form[name=new-account]').submit(function(evt) {
				var name = $('input[name=name]').val();
				var username = $('input[name=username]').val();
				var email = $('input[name=email]').val();
				var password = $('input[name=password]').val();
				var rpassword = $('input[name=rpassword]').val();
				
				if(name == '') {
					evt.preventDefault();
					error('Please insert your name', '[name=name]');
					return false;
				}
				if(name.length < 5) {
					evt.preventDefault();
					error('Your name must be at least 5 characters long', '[name=name]');
					return false;
				}
				if(username == '') {
					evt.preventDefault();
					error('Please insert your username', '[name=username]');
					return false;
				}
				if(/\s/.test(username)) {
					evt.preventDefault();
					error('Your username cannot contain spaces', '[name=username]');
					return false;
				}
				if(name.length < 5) {
					evt.preventDefault();
					error('Your username must be at least 5 characters long', '[name=username]');
					return false;
				}
				if(email == '') {
					evt.preventDefault();
					error('Please insert your email address', '[name=email]');
					return false;
				}
				if(validateEmail(email) == false) {
					evt.preventDefault();
					error('Please insert a valid email address', '[name=email]');
					return false;
				}
				if(password == '') {
					evt.preventDefault();
					error('Please insert a password', '[name=password]');
					return false;
				}
				if(/\s/.test(password)) {
					evt.preventDefault();
					error('Your password cannot contain spaces', '[name=password]');
					return false;
				}
				if(password.length < 5) {
					evt.preventDefault();
					error('Password must be at least 5 characters long', '[name=password]');
					return false;
				}
				if(rpassword == '') {
					evt.preventDefault();
					error('Please insert your password again', '[name=rpassword]');
					return false;
				}
				if(password != rpassword){
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
					
				
				$('#error').slideUp(200, function() {
					$('#error').html(e).slideDown(200);
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