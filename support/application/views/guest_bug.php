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
					<li><a href="<?php echo $base_url . 'guest/new-account/'; ?>">REGISTER</a></li>
					<li><a href="<?php echo $base_url ?>">LOGIN</a></li>
				</ul>
			</div>
		</div>
	</nav>
	
	
	<div class="sidebar-left" id="sidebar">
		<div class="top">
			<span class="big"><?php echo $bug_info->guest_name; ?></span>
			<span class="small">GUEST</span>
		</div>
		
		<span class="nav-title">GUEST DASHBOARD</span>
		<ul class="navigation">
			<li class="active">
				<a href="">
					<i class="fa fa-home"></i>Ticket
				</a>
			</li>
			<li>
				<a href="<?php echo $base_url . 'guest/new-account/'; ?>">
					<i class="fa fa-home"></i>Register
				</a>
			</li>
			<li>
				<a href="<?php echo $base_url ?>">
					<i class="fa fa-home"></i>Login
				</a>
			</li>
		</ul>
	</div>
	
	<div class="content" id="bug" data-id="<?php echo $bug_info->id; ?>" data-access="<?php echo $bug_info->access; ?>">
		<div class="page-title-cont clearfix">
			<h3>Bug Report ID <?php echo $bug_info->id; ?></h3>
			<?php
			if($bug_info->status == 1)
				echo '<div class="badge green">SENT</div>';
			elseif($bug_info->status == 2)
				echo '<div class="badge yellow">TAKEN BY AGENT</div>';
			elseif($bug_info->status == 3)
				echo '<div class="badge green">SOLVED</div>';
			elseif($bug_info->status == 4)
				echo '<div class="badge green">REVIEWED</div>';
			elseif($bug_info->status == 5)
				echo '<div class="badge red">INSOLVABLE</div>';
			elseif($bug_info->status == 6)
				echo '<div class="badge gray">OTHER</div>';
			?>
		</div>
		
		<div class="row">
			<div class="col margin-top col-md-4">
				<div class="row min-bottom-margin">
					<div class="col col-xs-12">
						<div class="cont">
							<div class="top clearfix">
								<h4 class="pull-left">Bug Report Info</h4>
							</div>
							
							<table class="ticket-info">
								<tbody>
									<tr>
										<td>Agent name</td>
										<td>
											<?php echo ($agent_info == false) ? 'N/A' : $agent_info->name; ?>
										</td>
									</tr>
									<tr>
										<td>Agent username</td>
										<td>
											<?php echo ($agent_info == false) ? 'N/A' : $agent_info->username; ?>
										</td>
									</tr>
									<tr>
										<td>Status</td>
										<td>
											<?php
											if($bug_info->status == 1)
												echo 'Sent';
											elseif($bug_info->status == 2)
												echo 'Taken by agent';
											elseif($bug_info->status == 3)
												echo 'Solved';
											elseif($bug_info->status == 4)
												echo 'Reviewed';
											elseif($bug_info->status == 5)
												echo 'Insolvable';
											elseif($bug_info->status == 6)
												echo 'Other';
											?>
										</td>
									</tr>
									<tr>
										<td>Department</td>
										<td><?php echo $department_name; ?></td>
									</tr>
									<tr>
										<td>Created on</td>
										<td><?php echo $created_on; ?></td>
									</tr>
									<tr>
										<td>Last update</td>
										<td><?php echo $last_update; ?></td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			
			<div class="col margin-top no-bottom-padding col-md-8 ticket">
				<div class="row min-bottom-margin">
					<div class="col col-xs-12">
						<div class="cont">
							<div class="top">
								<h4><?php echo $bug_info->subject; ?></h4>
							</div>
							
							<div class="tb-content clearfix">
								<div class="profile-image">
									<img src="<?php echo asset_url(); ?>img/profile_img/fa-user@1x.png" srcset="<?php echo asset_url(); ?>img/profile_img/fa-user@1x.png 1x, <?php echo asset_url(); ?>img/profile_img/fa-user@2x.png 2x, <?php echo asset_url(); ?>img/profile_img/fa-user@3x.png 3x" width="68" height="68" />
								</div>
								<div class="tb-text">
									<?php echo $bug_info->content; ?>
									
									<?php
									if($bug_files != false) {
									?>
									<div class="files-holder clearfix">
										<?php
										foreach($bug_files as $file) {
										?>
										<a href="<?php echo base_url(); ?>file/3/<?php echo $bug_info->id; ?>/<?php echo $file[0]; ?>" class="file clearfix">
											<i class="fa fa-file-o"></i>
											<div class="fileinfo">
												<span class="filename"><?php echo $file[1]; ?></span>
												<span class="filesize"><?php echo $file[2]; ?></span>
											</div>
										</a>
										<?php
										}
										?>
									</div>
									<?php
									}
									?>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col col-xs-12">
						<div class="cont">
							<div class="top">
								<h4>Bug Status</h4>
							</div>
							<?php
							if($bug_info->status == 1)
								echo '<p class="bg-success">Your bug report has been received. Please wait until an agent takes it.</p>';
							elseif($bug_info->status == 2)
								echo '<p class="bg-warning">Your bug report has been taken by an agent. Please wait until the agent reviews it.</p>';
							elseif($bug_info->status == 3)
								echo '<p class="bg-success">The bug you reported has been solved. Thank you!</p>';
							elseif($bug_info->status == 4)
								echo '<p class="bg-primary">Your bug report has been reviewed by an agent. Please wait until it gets solved.</p>';
							elseif($bug_info->status == 5)
								echo '<p class="bg-danger">Sorry! The bug report you submitted appears to be insolvable.</p>';
							elseif($bug_info->status == 6)
								echo '<p class="bg-primary">Your bug report has an undefined status.</p>';
							
							if($bug_info->agent_msg != '') {
								echo '<strong>Message from the agent:</strong><br />' . $bug_info->agent_msg;
							}
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div id="tooltip"></div>
	
	
	<script src="<?php echo asset_url(); ?>js/jquery-1.11.3.min.js"></script>
	<script src="<?php echo asset_url(); ?>js/tinyeditor.min.js"></script>
	<script type="text/javascript">
		$('document').ready(function() {
			/* Sidebar */
			$('.navbar-toggle').click(function(evt) {
				if($('.sidebar-left').hasClass('shown')) {
					$('.sidebar-left').animate({'left':'-300px'}, 300).removeClass('shown');
					$('.navbar-toggle').removeClass('active');
				}else{
					$('.sidebar-left').animate({'left':'0px'}, 300).addClass('shown');
					$('.navbar-toggle').addClass('active');
				}
			});
		
			$('ul.navigation > li > a').click(function(evt) {
				var t = $(this);
				
				if($(this).next().hasClass('dropdown') === false)
					return;
				
				evt.preventDefault();
				
				// Is this one open? Just close it
				if($(this).next().is(':visible')) {
					t.next().removeClass('animated');
					t.next().slideUp(300);
					t.children('span.arrow').children('i.fa-angle-down').removeClass('fa-angle-down').addClass('fa-angle-right');
					return;
				}
				
				// Close all dropdowns
				$('ul.navigation ul.dropdown.animated').removeClass('animated');
				$('ul.navigation ul.dropdown').slideUp(300);
				$('ul.navigation i.fa-angle-down').removeClass('fa-angle-down').addClass('fa-angle-right');
				
				// Open new
				$(this).next().slideDown(300, function() {
					t.next().addClass('open');
					t.children('span.arrow').children('i.fa-angle-right').removeClass('fa-angle-right').addClass('fa-angle-down');
					t.next().addClass('animated');
				});
			});
		});
	</script>
</body>
</html>