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
	<link href="<?php echo asset_url(); ?>css/dashboard.css" rel="stylesheet" />
	<link href="<?php echo asset_url(); ?>css/responsive-tables.css" rel="stylesheet" />
	<link href="<?php echo asset_url(); ?>css/forms.css" rel="stylesheet" />
	<link href="<?php echo asset_url(); ?>css/font-awesome.min.css" rel="stylesheet" />
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600,700|PT+Sans:400,700|Open+Sans:300,400,600,700,800|Roboto' rel='stylesheet' type='text/css'>
	<link href="<?php echo asset_url(); ?>css/tinyeditor.css" rel="stylesheet" />

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body>
	<nav class="navbar navbar-inverse navbar-fixed-top">
		<div class="container-fluid">
			<div class="navbar-header">
				<div class="navbar-toggle">
					<span class="sr-only">Toggle Navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</div>
				
				<a class="navbar-brand"><img src="<?php echo asset_url(); ?>img/logos/dashlogo@1x.png" srcset="<?php echo asset_url(); ?>img/logos/dashlogo@1x.png 1x, <?php echo asset_url(); ?>img/logos/dashlogo@2x.png 2x, <?php echo asset_url(); ?>img/logos/dashlogo@3x.png 3x" width="170" height="25" title="<?php echo $site_title; ?>" /></a>
			</div>
			
			<div class="navbar-collapse pull-right">
				<ul class="nav navbar-nav navbar-right">
					<li><a href="<?php echo $base_url . 'panel/logout/' ?>">LOGOUT</a></li>
				</ul>
			</div>
		</div>
	</nav>