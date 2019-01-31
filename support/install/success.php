<?php
session_start();

if(!isset($_SESSION['tickerr_installation_success'])) {
	header('Location: index.php');
	die();
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
	<div id="container" class="installation">
		<a href="">
			<img src="../assets/img/logos/mainlogo@1x.png" srcset="../assets/img/logos/mainlogo@1x.png 1x, ../assets/img/logos/mainlogo@2x.png 2x, ../assets/img/logos/mainlogo@3x.png 3x" width="200" height="45" title="Tickerr" style="margin-top:-50px;" />
		</a>
		
		<div id="central-container" class="clearfix">
			<h3 class="center">TICKERR SUCCESSFULLY INSTALLED!</h3>
			
			<p class="bg-success" style="margin-bottom:8px !important;">
				Tickerr has been successfully installed!
			</p>
			We highly recommend you to delete this directory (install) for security reasons.
			<br />
			To start using your new Tickerr system, you can click <a href="../">here</a>
		</div>
	</div>
	
	
	<script src="../assets/js/jquery-1.11.3.min.js"></script>
	<script type="text/javascript">
		$('document').ready(function() {
		});
	</script>
</body>
</html>