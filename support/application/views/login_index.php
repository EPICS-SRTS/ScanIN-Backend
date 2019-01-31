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
	<link href="<?php echo asset_url(); ?>css/main/login.css" rel="stylesheet" />
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600,700|PT+Sans|Open+Sans:300,400,600,700,800|Roboto' rel='stylesheet' type='text/css'>

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body>
	<div id="container">
		<img src="<?php echo asset_url(); ?>img/logos/mainlogo@1x.png" srcset="<?php echo asset_url(); ?>img/logos/mainlogo@1x.png 1x, <?php echo asset_url(); ?>img/logos/mainlogo@2x.png 2x, <?php echo asset_url(); ?>img/logos/mainlogo@3x.png 3x" width="270" height="55" title="<?php echo $site_title; ?>" />
		
		<div id="social-buttons" class="clearfix">
			<?php
			if($allow_tickets == true)
				echo '<a href="'.$siteurl.'guest/new-ticket" alt="Create ticket as guest" id="tguest" class="pull-right">Create ticket as guest</a>';
			if($allow_bug_reports == true)
				echo '<a href="'.$siteurl.'guest/new-bug-report" alt="Leave bug report as guest" id="bguest" class="pull-right">Leave bug report as guest</a>';
			if($allow_accounts == true)
				echo '<a href="'.$siteurl.'guest/new-account" alt="Create account" id="account" class="pull-right">CREATE ACCOUNT</a>';
			?>
		</div>
		
		<div id="central-container" class="clearfix">
			<div id="error"></div>
			
			<form name="login" action="">
				<label for="username">USERNAME</label>
				<input type="text" name="username" id="username" placeholder="Type your username..." />

				<label for="password">PASSWORD</label>
				<input type="password" name="password" id="password" placeholder="Type your password..." />
				<?php
				if($account_recovery == true)
					echo '<a href="'.$siteurl.'login/password-recovery" alt="I forgot my password" class="small pull-right">I FORGOT MY PASSWORD</a>';
				?>
				<br />
				
				<input type="submit" name="login" class="pull-right" value="LOGIN" />
			</form>
		</div>
	</div>
	
	<script src="<?php echo asset_url(); ?>js/jquery-1.11.3.min.js"></script>
	<script type="text/javascript">
		$('document').ready(function() {
			$('form[name=login]').submit(function(evt) {
				evt.preventDefault();
				
				var user = $('input[name=username]').val();
				var pass = $('input[name=password]').val();
				
				if(user == '' || user.length < 5) {
					error('input[name=username]', 'Please insert a valid username');
					return false;
				}
				if(pass == '' || pass.length < 5) {
					error('input[name=password]', 'Please insert a valid password');
					return false;
				}
				
				$.post('<?php echo $this->config->base_url().'login/login_action/'; ?>', {
					user: user,
					pass: pass
				}, function(data) {
					if(data == 1){
						error('input[name=username]', 'Please insert a valid username');
					}else if(data == 2) {
						error('input[name=password]', 'Please insert a valid password');
					}else if(data == 3) {
						location.reload();
					}else{
						error('input[name=password]', 'Wrong username or password');
					}
				});
			});
			
			
			function error(input, e) {
				$('input.error').removeClass('error');
				$('#error').slideUp(200, function() {
					$('#error').html(e).slideDown(300);
				});
			}
		});
	</script>
</body>
</html>