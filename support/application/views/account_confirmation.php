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
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600,700|PT+Sans:400,700|Open+Sans:300,400,600,700,800|Roboto' rel='stylesheet' type='text/css'>

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body>
	<div id="container" class="ticket-submitted">
		<img src="<?php echo asset_url(); ?>img/logos/mainlogo@1x.png" srcset="<?php echo asset_url(); ?>img/logos/mainlogo@1x.png 1x, <?php echo asset_url(); ?>img/logos/mainlogo@2x.png 2x, <?php echo asset_url(); ?>img/logos/mainlogo@3x.png 3x" width="270" height="55" title="<?php echo $site_title; ?>" />
		
		<div id="central-container" class="clearfix">
			<h3 class="center">ACCOUNT ACTIVATED</h3>
			<p>
				Your account has been activated. To login, you can use the following link
			</p>
			<div class="blue-box text-center">
				<strong><a href="<?php echo $login_url; ?>"><?php echo $login_url; ?></a></strong>
			</div>
		</div>
	</div>
</body>
</html>