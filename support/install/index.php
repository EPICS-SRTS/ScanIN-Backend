<?php
session_start();
if(isset($_SESSION['tickerr_installation_success'])) unset($_SESSION['tickerr_installation_success']);

// First we need to check everything is in order..
if(!file_exists('core/checker.php')) {
	$messages = array(array('danger', 'Checker file doesn\'t exist. Please remove the <strong>install</strong> folder and export it again from your .zip file.'));
	$proceed = false;
	$warning = true;
}else{
	require 'core/checker.php';
	$checker = new Checker;
	
	$messages = $checker->check_system();
	$proceed = $checker->get_proceed_var();
	$warning = $checker->get_warning_var();
}
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
	<div id="container" class="installation-check">
		<a href="">
			<img src="../assets/img/logos/mainlogo@1x.png" srcset="../assets/img/logos/mainlogo@1x.png 1x, ../assets/img/logos/mainlogo@2x.png 2x, ../assets/img/logos/mainlogo@3x.png 3x" width="200" height="45" title="Tickerr" style="margin-top:-50px;" />
		</a>
		
		<div id="central-container" class="clearfix">
			<h3 class="center">INSTALLATION CHECK</h3>
			Before installing Tickerr, the system will check if your server meets the requirements. This is the result:
			
			<?php
			foreach($messages as $msg) {
				echo '<p class="bg-'.$msg[0].'">'.$msg[1].'</p>';
			}
			
			if($warning == true && $proceed == true) {
			?>
			<div class="warning">
				Some errors were detected. The errors are not critical so you can continue with the installation, but you need
				to keep in mind that it goes on your own responsability.
			</div>
			<?php
			}elseif($warning == true && $proceed == false) {
			?>
			<div class="warning">
				Some errors were detected. You need to solve those things in order to continue with the installation.
			</div>
			<?php
			}
			
			if($proceed == true) {
				echo '<button type="submit" name="proceed" class="pull-right" style="margin-top:0px;">Continue...</button>';
			}
			?>
		</div>
	</div>
	
	
	<script src="../assets/js/jquery-1.11.3.min.js"></script>
	<script type="text/javascript">
		$('document').ready(function() {
			$('button[name=proceed]').click(function(evt) {
				evt.preventDefault();
				location.href = 'install.php';
			});
		});
	</script>
	
</body>
</html>