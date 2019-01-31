<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
	<div class="content">
		<div class="page-title-cont clearfix">
			<h3>Account Settings</h3>
		</div>
		
		<div class="row">
			<div class="col col-xs-12">
				<div class="cont clearfix">
					<div class="top clearfix">
						<h4 class="pull-left">Basic Settings</h4>
					</div>
					
					<p class="bg-danger bg-danger1" style="display:none;"></p>
					
					<form method="POST" action="<?php echo $base_url; ?>panel/account-settings/basic-settings" name="basic-settings">
						<div class="row no-bottom-margin">
							<div class="col col-md-6">
								<div class="form-group">
									<label for="user_name">Name</label>
									<span class="label_desc">Here goes your name</span>
									<input type="text" name="user_name" id="user_name" value="<?php echo $user_info->name; ?>" />
								</div>
							</div>
							
							<div class="col col-md-6">
								<div class="form-group">
									<label for="user_email">Email</label>
									<span class="label_desc">Here goes your email address</span>
									<input type="text" name="user_email" id="user_email" value="<?php echo $user_info->email; ?>" />
								</div>
							</div>
						</div>
						
						<div class="row no-bottom-margin" style="margin-top:10px;">
							<div class="col col-md-6">
								<div class="form-group">
									<label for="radios">Receive email on ticket activity</label>
									<span class="label_desc">Enable this option to receive an email when a ticket of mine has a new reply</span>
									<div class="radio">
										<input type="radio" name="email_on_tactivity" id="radio_1" class="green" value="1" <?php if($user_info->email_on_tactivity == '1') echo 'checked '; ?>/>
										<label for="radio_1">Enabled</label>
									</div>
									<div class="radio">
										<input type="radio" name="email_on_tactivity" id="radio_2" class="gray" value="0" <?php if($user_info->email_on_tactivity == '0') echo 'checked '; ?>/>
										<label for="radio_2">Disabled</label>
									</div>
								</div>
							</div>
							
							<div class="col col-md-6">
								<div class="form-group">
									<label for="radios">Receive email on bug report activity</label>
									<span class="label_desc">Enable this option to receive an email when a bug report of mine has a new status</span>
									<div class="radio">
										<input type="radio" name="email_on_bactivity" id="radio_3" class="green" value="1" <?php if($user_info->email_on_bactivity == '1') echo 'checked '; ?>/>
										<label for="radio_3">Enabled</label>
									</div>
									<div class="radio">
										<input type="radio" name="email_on_bactivity" id="radio_4" class="gray" value="0" <?php if($user_info->email_on_bactivity == '0') echo 'checked '; ?>/>
										<label for="radio_4">Disabled</label>
									</div>
								</div>
							</div>
						</div>
						
						
						<input type="submit" name="submit" class="btn btn-strong-blue pull-right" value="Save" />
					</form>
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col col-xs-12">
				<div class="cont clearfix">
					<div class="top clearfix">
						<h4 class="pull-left">Change Profile Picture</h4>
					</div>
					
					<p class="bg-danger bg-danger2" style="display:none;"></p>
					
					<form method="POST" action="<?php echo $base_url; ?>panel/account-settings/change-pp" name="change-pp" enctype="multipart/form-data">
						<div class="row no-bottom-margin">
							<div class="col col-md-6">
								<div class="form-group">
									<label for="user_name">Current Picture</label>
									<?php
									$_2x = asset_url() . 'img/profile_img/' . $user_info->profile_img2x;
									$_3x = asset_url() . 'img/profile_img/' . $user_info->profile_img3x;
									?>
									<img src="<?php echo $_2x; ?>" srcset="<?php echo $_2x; ?> 1x, <?php echo $_2x; ?> 2x, <?php echo $_3x; ?> 3x" width="90" height="90" />
								</div>
							</div>
							
							<div class="col col-md-6">
								<div class="form-group">
									<label for="user_email">Upload new image</label>
									<span class="label_desc">Use this button to upload a new profile picture. The picture's dimensions MUST BE square. Recommended sizes are: 68x68, 136x136 or 204x204 (best).</span>
									
									<div class="file" style="margin-top:5px;">
										<button name="select_profile_picture" class="btn btn-upload-file btn-light-blue">Select file to upload...</button>
										<input type="file" name="new_profile_picture" style="display:none;" />
									</div>
								</div>
							</div>
						</div>
						
						<input type="submit" name="submit" class="btn btn-strong-blue pull-right" value="Upload and save" />
					</form>
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col col-xs-12">
				<div class="cont clearfix">
					<div class="top clearfix">
						<h4 class="pull-left">Change Password</h4>
					</div>
					
					<?php
					if($change_password_error == true)
						echo '<p class="bg-danger bg-danger3">The current password you typed is not correct</p>';
					elseif($change_password_success == true)
						echo '<p class="bg-success">Password successfully changed!</p>';
					else
						echo '<p class="bg-danger bg-danger3" style="display:none;"></p>';
					?>
					
					<form method="POST" action="<?php echo $base_url; ?>panel/account-settings/change-password" name="change-password">
						<div class="row no-bottom-margin">
							<div class="col col-md-6">
								<div class="form-group">
									<label for="current_password">Current Password</label>
									<span class="label_desc">Type here your current password</span>
									<?php
									if($change_password_error == true)
										echo '<input type="password" name="current_password" id="current_password" class="error" />';
									else
										echo '<input type="password" name="current_password" id="current_password" />';
									?>
								</div>
							</div>
							
							<div class="col col-md-6">
								<div class="form-group">
									<label for="new_password">New Password</label>
									<span class="label_desc">Type here your new password. It must be at least 5 characters long</span>
									<input type="password" name="new_password" id="new_password" />
								</div>
								
								<div class="form-group">
									<label for="new_password">Repeat Password</label>
									<span class="label_desc">Repeat your new password.</span>
									<input type="password" name="new_rpassword" id="new_rpassword" />
								</div>
							</div>
						</div>
						
						<input type="submit" name="submit" class="btn btn-strong-blue pull-right" value="Change Password" />
					</form>
				</div>
			</div>
		</div>
	</div>
	
	
	<script src="<?php echo asset_url(); ?>js/jquery-1.11.3.min.js"></script>
	<script src="<?php echo asset_url(); ?>js/tickerr_core.js"></script>
	<script type="text/javascript">
		$('document').ready(function() {
			// Enable sidebar
			enable_sidebar();
			
			$('thead tr th').on('mouseover', function() {
				$(this).children('i.fa-sort').addClass('active');
				$(this).children('.hid').css('visibility','visible');
			}).on('mouseout', function() {
				$(this).children('i.fa-sort').removeClass('active');
				$(this).children('.hid').css('visibility','hidden');
			});

			$('thead tr th').click(function(evt) {
				if($(this).data('sort') !== undefined)
					location.href = $(this).data('sort');
			});
			
			$('tr').click(function(evt) {
				if($(this).data('href') !== undefined)
					location.href = $(this).data('href');
			});
			
			$('button[name=select_profile_picture]').click(function(evt) {
				evt.preventDefault();
				$(this).parent().children('input[type=file]').click();
			});
			
			$('input[type=file]').on('change', function(evt) {			
				var val = $(this).val().split('\\').pop();
				
				$(this).parent().children('button[name=select_profile_picture]').html(val);
			});
			
			$('form[name=basic-settings]').submit(function(evt) {
				var user_name = $('input[name=user_name]').val();
				var user_email = $('input[name=user_email]').val();
				
				if(user_name == '') {
					evt.preventDefault();
					error1('Please type your name', '[name=user_name]');
					return false;
				}
				if(validateEmail(user_email) == false) {
					evt.preventDefault();
					error1('Please insert a valid email address', '[name=user_email]');
					return false;
				}
			});
			
			$('form[name=change-pp]').submit(function(evt) {
				var file = $('input[type=file][name=new_profile_picture]').val();
				
				if(file == '') {
					evt.preventDefault();
					error2('Please select a new image to upload');
					return false;
				}
			});
			
			$('form[name=change-password]').submit(function(evt) {
				var cpass = $('input[name=current_password]').val();
				var pass = $('input[name=new_password]').val();
				var rpass = $('input[name=new_rpassword]').val();
				
				if(cpass == '') {
					evt.preventDefault();
					error3('Please type your current password', '[name=current_password]');
					return false;
				}
				if(pass.length < 5) {
					evt.preventDefault();
					error3('Password must be at least 5 characters long', '[name=new_password]');
					return false;
				}
				if(pass == '') {
					evt.preventDefault();
					error3('Please type your new password', '[name=new_password]');
					return false;
				}
				if(rpass == '') {
					evt.preventDefault();
					error3('Please type your new password again', '[name=new_rpassword]');
					return false;
				}
				if(pass != rpass) {
					evt.preventDefault();
					error3('Both passwords must match', '[name=new_password]', '[name=new_rpassword]');
					return false;
				}
			});
			
			var e_active1 = false;
			var e_active2 = false;
			var e_active3b = false;
			var e_active3x = false;
			function error1(e, n) {
				if(e_active1 != false) {
					$(e_active1).removeClass('error');
				}
				
				$(n).addClass('error');
				e_active1 = n;
				
				$('p.bg-danger1').slideUp(200, function() {
					$('p.bg-danger1').html(e).slideDown(200);
				});
			}
			function error2(e, n) {
				if(e_active2 != false) {
					$(e_active2).removeClass('error');
				}
				
				$(n).addClass('error');
				e_active2 = n;
				
				$('p.bg-danger2').slideUp(200, function() {
					$('p.bg-danger2').html(e).slideDown(200);
				});
			}
			function error3(e, n, x) {
				if(e_active3b != false) {
					$(e_active3b).removeClass('error');
				}
				if(e_active3x != false) {
					$(e_active3x).removeClass('error');
				}
				
				$(n).addClass('error');
				e_active3b = n;
				if(x != false) {
					$(x).addClass('error');
					e_active3x = x;
				}
				
				$('p.bg-danger3').slideUp(200, function() {
					$('p.bg-danger3').html(e).slideDown(200);
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