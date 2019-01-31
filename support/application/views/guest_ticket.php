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
			<span class="big"><?php echo $ticket_info->guest_name; ?></span>
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
	
	<div class="content" id="ticket" data-id="<?php echo $ticket_info->id; ?>" data-access="<?php echo $ticket_info->access; ?>">
		<div class="page-title-cont clearfix">
			<h3>Ticket ID <?php echo $ticket_info->id; ?></h3>
			<?php
			if($ticket_info->status == 1 || $ticket_info->status == 2)
				echo '<div class="badge green">OPEN</div>';
			else
				echo '<div class="badge red">CLOSED</div>';
			?>
		</div>
		
		<div class="row">
			<div class="col margin-top col-md-4">
				<div class="row min-bottom-margin">
					<div class="col col-xs-12">
						<div class="cont">
							<div class="top clearfix">
								<h4 class="pull-left">Ticket Info</h4>
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
											if($ticket_info->status == 1 || $ticket_info->status == 2)
												echo 'Open';
											else
												echo 'Closed';
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
								<h4><?php echo $ticket_info->subject; ?></h4>
							</div>
							
							<div class="tb-content clearfix">
								<div class="profile-image">
									<img src="<?php echo asset_url(); ?>img/profile_img/fa-user@1x.png" srcset="<?php echo asset_url(); ?>img/profile_img/fa-user@1x.png 1x, <?php echo asset_url(); ?>img/profile_img/fa-user@2x.png 2x, <?php echo asset_url(); ?>img/profile_img/fa-user@3x.png 3x" width="68" height="68" />
								</div>
								<div class="tb-text">
									<?php echo $ticket_info->content; ?>
									
									<?php
									if($ticket_files != false) {
									?>
									<div class="files-holder clearfix">
										<?php
										foreach($ticket_files as $file) {
										?>
										<a href="<?php echo base_url(); ?>file/1/<?php echo $ticket_info->id; ?>/<?php echo $file[0]; ?>" class="file clearfix">
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
								<h4>Ticket Replies</h4>
							</div>
							<?php
							if($ticket_replies == false)
								echo 'No replies yet.';
							else {
								$number_replies_ = 1;
								foreach($ticket_replies->result() as $reply) {
									// Guest reply
									if($reply->userid == '0' && $reply->agentid == '0') {
									?>
										<div class="ticket-message message-right<?php if($n_ticket_replies == $number_replies_) echo ' last'; if($number_replies_ == '1') echo ' first'; ?> clearfix">
											<div class="text">
												<div class="message">
													<?php echo $reply->content; ?>
													<?php
													if($reply->files != '') {
														echo '<div class="files-holder clearfix">';
														$files = separate_files($reply->files);
														foreach($files as $file) {
															$file_ = explode('*', $file);
															$_file = check_file($file_);
															if($_file != false) {
																echo '<a href="' . base_url() . 'file/2/' . $reply->id . '/' . $file_[0] . '" class="file clearfix">';
																echo '<i class="fa fa-file-o"></i>';
																echo '<div class="fileinfo">';
																echo '<span class="filename">'.$file_[1].'</span>';
																echo '<span class="filesize">'.$_file.'</span>';
																echo '</div>';
																echo '</a>';
															}
														}
														echo '</div>';
													}
													?>
												</div>
												<div class="date"><?php echo date('M jS, Y \a\t H:i:s', strtotime($reply->date)); ?></div>
											</div>
											<div class="profile-image">
												<img src="<?php echo asset_url(); ?>img/profile_img/fa-user@1x.png" srcset="<?php echo asset_url(); ?>img/profile_img/fa-user@1x.png 1x, <?php echo asset_url(); ?>img/profile_img/fa-user@2x.png 2x, <?php echo asset_url(); ?>img/profile_img/fa-user@3x.png 3x" width="68" height="68" />
												<?php echo $ticket_info->guest_name; ?><br />
												Guest
											</div>
										</div>
									<?php
									}elseif($reply->agentid != '0') {
									?>
										<div class="ticket-message message-left<?php if($n_ticket_replies == $number_replies_) echo ' last'; if($number_replies_ == '1') echo ' first'; ?> clearfix">
											<div class="profile-image">
												<?php
												$agent_info = $users_model->get_user_info($reply->agentid);
												if($agent_info != false) {
													$_1x = asset_url() . 'img/profile_img/' . $agent_info->profile_img1x;
													$_2x = asset_url() . 'img/profile_img/' . $agent_info->profile_img2x;
													$_3x = asset_url() . 'img/profile_img/' . $agent_info->profile_img3x;
													echo '<img src="'.$_1x.'" srcset="'.$_1x.' 1x, '.$_2x.' 2x, '.$_3x.' 3x" width="68" height="68" />';
													
												}else
													echo '<img src="' . asset_url() . 'img/profile_img/fa-user@1x.png" srcset="' . asset_url() . 'img/profile_img/fa-user@1x.png 1x, ' . asset_url() . 'img/profile_img/fa-user@2x.png 2x, ' . asset_url() . 'img/profile_img/fa-user@3x.png 3x" width="68" height="68" />';
												
												if($agent_info != false)
													echo $agent_info->name.'<br />';
												else
													echo 'Deleted<br />';
												
												if($agent_info != false) {
													if($agent_info->role == 2) echo 'Agent';
													if($agent_info->role == 3) echo 'Admin';
												}
												?>
											</div>
											<div class="text">
												<div class="message">
													<?php echo $reply->content; ?>
													<?php
													if($reply->files != '') {
														echo '<div class="files-holder clearfix">';
														$files = separate_files($reply->files);
														foreach($files as $file) {
															$file_ = explode('*', $file);
															$_file = check_file($file_);
															if($_file != false) {
																echo '<a href="' . base_url() . 'file/2/' . $reply->id . '/' . $file_[0] . '" class="file clearfix">';
																echo '<i class="fa fa-file-o"></i>';
																echo '<div class="fileinfo">';
																echo '<span class="filename">'.$file_[1].'</span>';
																echo '<span class="filesize">'.$_file.'</span>';
																echo '</div>';
																echo '</a>';
															}
														}
														echo '</div>';
													}
													?>
												</div>
												<div class="date"><?php echo date('M jS, Y \a\t H:i:s', strtotime($reply->date)); ?></div>
											</div>
										</div>
									<?php
									}
									$number_replies_ += 1;
								}
							}
							?>
						</div>
					</div>
				</div>
				
				<?php
				if($ticket_info->status == 3) {
				?>
				
				<div class="row min-bottom-margin">
					<div class="col col-xs-12">
						<div class="cont clearfix">
							<div class="top">
								<h4>Rate our support</h4>
							</div>

							<?php
							if($error_rate == false)
								echo '<p class="bg-danger" id="rating" style="display:none;margin-bottom:18px;"></p>';
							else
								echo '<p class="bg-danger" id="rating" style="margin-bottom:18px;">' . $error_rate . '</p>';
							?>
							
							<form method="POST" action="<?php echo $base_url . 'ticket/' . $ticket_info->access . '/rate' ?>" enctype="multipart/form-data" name="rating">
								<div id="rt" class="clearfix" style="margin-top:-7px; width:auto;">
									<div id="rate_stars" class="normal-stars pull-left clearfix" style="width:auto;">
										<?php
										if($ticket_info->rating == '0.0') {
										?>
										<div class="star divided-star">
											<div class="piece-left"><i class="fa fa-star" data-star="1"></i></div>
											<div class="piece-right"><i class="fa fa-star" data-star="2"></i></div>
										</div>
										<div class="star divided-star">
											<div class="piece-left"><i class="fa fa-star" data-star="3"></i></div>
											<div class="piece-right"><i class="fa fa-star" data-star="4"></i></div>
										</div>
										<div class="star divided-star">
											<div class="piece-left"><i class="fa fa-star" data-star="5"></i></div>
											<div class="piece-right"><i class="fa fa-star" data-star="6"></i></div>
										</div>
										<div class="star divided-star">
											<div class="piece-left"><i class="fa fa-star" data-star="7"></i></div>
											<div class="piece-right"><i class="fa fa-star" data-star="8"></i></div>
										</div>
										<div class="star divided-star">
											<div class="piece-left"><i class="fa fa-star" data-star="9"></i></div>
											<div class="piece-right"><i class="fa fa-star" data-star="10"></i></div>
										</div>
										
										<?php
										}else{
											$rating = $ticket_info->rating * 2;
											for($i = 2; $i <= 10; $i+2) {
												echo "\r\n".'<div class="star divided-star">';
												if($i-1 <= $rating)
													echo '<div class="piece-left"><i class="fa fa-star active" data-star="' . ($i-1) . '"></i></div> ';
												else
													echo '<div class="piece-left"><i class="fa fa-star" data-star="' . ($i-1) . '"></i></div> ';
												
												if($i <= $rating)
													echo '<div class="piece-right"><i class="fa fa-star active" data-star="' . $i . '"></i></div> ';
												else
													echo '<div class="piece-right"><i class="fa fa-star" data-star="' . $i . '"></i></div> ';
												echo '</div>';
												
												$i = $i+2;
											}
										}
										?>
									</div>
								</div>
								
								<?php
								if($ticket_info->rating == '0.0')
									echo '<input type="hidden" name="rating" value="0" />';
								else
									echo '<input type="hidden" name="rating" value="' . $ticket_info->rating*2 . '" />';
								?>
								
								<label for="rate_text">How was our support?</label>
								<textarea name="rate_text" id="rate_text" rows="5"><?php if($ticket_info->rating_msg != '') echo $ticket_info->rating_msg; ?></textarea>
								<input type="submit" name="submit" class="pull-right" value="SUBMIT RATING" />
							</form>
						</div>
					</div>
				</div>
				
				<?php
				}
				?>
				
				
				<div class="row min-bottom-margin">
					<div class="col col-xs-12">
						<div class="cont clearfix">
							<div class="top">
								<?php
								if($ticket_info->status == 1 || $ticket_info->status == 2)
									echo '<h4>New Reply</h4>';
								else
									echo '<h4>New Reply (this will reopen the ticket)</h4>';
								?>
							</div>

							<?php
							if($error == false)
								echo '<p class="bg-danger" style="display:none"></p>';
							else
								echo '<p class="bg-danger">' . $error . '</p>';
							?>
							
							<form method="POST" action="<?php echo $base_url . 'ticket/' . $ticket_info->access . '/new-reply' ?>" enctype="multipart/form-data" name="new-reply">
								<textarea name="reply" id="reply" class="nostyle margin-bottom"></textarea>

								<div class="upload-files">
									<div class="file">
										<button name="selected_file" class="btn btn-upload-file btn-light-blue">Select file to upload...</button>
										<button name="delete_file" class="btn btn-upload-file btn-red btn-delete"><i class="fa fa-close"></i></button>
										<input type="file" name="files[]" style="display:none;" />
									</div>
									<button name="upload_file" class="btn btn-upload-file btn-strong-blue">New file</button>
								</div>
								
								<input type="submit" name="submit" class="pull-right" value="SUBMIT REPLY" />
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div id="tooltip"></div>
	
	
	<script src="<?php echo asset_url(); ?>js/jquery-1.11.3.min.js"></script>
	<script src="<?php echo asset_url(); ?>js/tickerr_core.js"></script>
	<script src="<?php echo asset_url(); ?>js/tinyeditor.min.js"></script>
	<script type="text/javascript">
		$('document').ready(function() {
			// Enable sidebar
			enable_sidebar();
			
			$('#rate_stars i.fa-star').mouseover(function(evt) {
				var n = $(this).data('star');
				
				// Turn off all stars
				$('#rate_stars i.fa-star.active').removeClass('active');
				
				// Light up every star
				for(var i = n; i > 0; i--) {
					$('#rate_stars i.fa-star[data-star="'+i+'"]').addClass('active');
				}
			});
			
			$('i.fa-star').click(function() {
				var n = $(this).data('star');
				$('input[type=hidden][name=rating]').val(n);
			});
			
			$('#rt').mouseout(function(evt) {
				var to = $(evt.toElement);
				var rating = $('input[type=hidden][name=rating]').val();
				if(!to.hasClass('divided-star') && !to.hasClass('normal-stars') && !to.hasClass('piece-left')) {
					$('#rate_stars i.fa-star.active').removeClass('active');
				
					if(rating != '0') {
						for(var i = rating; i > 0; i--) {
							$('#rate_stars i.fa-star[data-star="'+i+'"]').addClass('active');
						}
					}
				}
			});
			
			var editor = new TINY.editor.edit('editor', {
				id: 'reply',
				width: '100%',
				height:160,
				cssclass: 'tinyeditor',
				controlclass: 'tinyeditor-control',
				rowclass: 'tinyeditor-header',
				dividerclass: 'tinyeditor-divider',
				controls: ['bold', 'italic', 'underline', 'strikethrough', '|', 'orderedlist',
					'unorderedlist', '|', 'outdent', 'indent', '|', 'leftalign', 'centeralign',
					'rightalign', 'blockjustify', '|', 'link', 'unlink'],
				footer: false,
				xhtml: true,
				cssfile: '<?php echo asset_url(); ?>css/tinyeditor.css',
				bodyid: 'editor',
				footerclass: 'tinyeditor-footer',
				toggle: {text: 'source', activetext: 'wysiwyg', cssclass: 'toggle'},
				resize: {cssclass: 'resize'}
			});
			
			$('button[name=upload_file]').click(function(evt) {
				evt.preventDefault();
				
				var new_file = '<div class="file">';
				new_file += '	<button name="selected_file" class="btn btn-upload-file btn-light-blue">Select file to upload...</button>';
				new_file += '	<button name="delete_file" class="btn btn-upload-file btn-red btn-delete"><i class="fa fa-close"></i></button>';
				new_file += '	<input type="file" name="files[]" style="display:none;" />';
				new_file += '</div>';
				
				$(this).before(new_file);
			});
			
			$(document).delegate('button[name=selected_file]', 'click', function(evt) {
				// Bug fixer
				if(evt.clientX != 0 && evt.clientY != 0) {
					evt.preventDefault();
					$(this).parent().children('input[type=file]').click();
				}
			});
			
			$(document).delegate('input[type=file]', 'change', function(evt) {			
				var val = $(this).val().split('\\').pop();
				
				$(this).parent().children('button[name=selected_file]').html(val);
			});
			
			$(document).delegate('button[name=delete_file]', 'click', function(evt) {
				evt.preventDefault();
				$(this).parent().remove();
			});

			
			$('form[name=new-reply]').submit(function(evt) {
				editor.post();
				var message = editor.t.value;

				if(message.length <= 10) {
					evt.preventDefault();
					error('Ticket message must be more than 10 characters long', '.tinyeditor');
					return false;
				}
				
				// Delete empty files
				var nfiles = $('input[type=file]').length;
				var astr = [];
				for(var i = 1; i <= nfiles; i++) {
					var val = $('.file:nth-child('+i+') input[type=file]').val();
					if(val == '')
						astr.push('.file:nth-child('+i+')');
				}
				var str = astr.join(', ');
				$(str).remove();
			});
			
			$('form[name=rating]').submit(function(evt) {
				var rating = $('input[type=hidden][name=rating]').val();
				var msg = $('textarea[name=rate_text]');
				
				if(rating == '0') {
					$('p.bg-danger#rating').slideUp(300, function() {
						$('p.bg-danger#rating').html('Please select a rating');
					}).slideDown(300);
					evt.preventDefault();
					return false;
				}
			});
			
			var e_active = false;
			function error(e, n) {
				if(e_active != false) {
					$(e_active).css('border-color', '#d0d0d0').removeClass('error');
				}
				
				$(n).css('border-color','#ff0000').addClass('error');
				e_active = n;
				
				$('p.bg-danger').slideUp(200, function() {
					$('p.bg-danger').html(e).slideDown(200);
				});
			}
		});
	</script>
</body>
</html>