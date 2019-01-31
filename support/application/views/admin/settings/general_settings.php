<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
	<div class="content">
		<div class="page-title-cont clearfix">
			<h3>General Settings</h3>
		</div>
		
		<div class="row">
			<div class="col col-xs-12">
				<div class="cont clearfix">
					<div class="top clearfix">
						<h4 class="pull-left">Basic Settings</h4>
					</div>
					
					<?php
					if($envato_error == true)
						echo '<p class="bg-danger">The Envato Username or Envato API you entered is invalid.</p>';
					else
						echo '<p class="bg-danger" style="display:none;"></p>';
					?>
					
					<form method="post" action="<?php echo $base_url; ?>panel/admin/general-settings/action" name="general-settings">
						<div class="row no-bottom-margin">
							<div class="col col-md-6">
								<div class="form-group">
									<label for="site_title">Site Title</label>
									<span class="label_desc">Title of your site</span>
									<input type="text" name="site_title" id="site_title" value="<?php echo $settings->site_title; ?>" />
								</div>
							</div>
							
							<div class="col col-md-6">
								<div class="form-group">
									<label for="envato_username">Envato Username</label>
									<span class="label_desc">
										If you want Tickerr to automatically detect Envato Purchase Codes and validate them, write
										here your Envato username and your API Key below. If not, leave boxes empty. For more information,
										read the Documentation.
									</span>
									<?php
									if($envato_error == true)
										echo '<input type="text" name="envato_username" id="envato_username" class="error" value="'.$settings->confirm_purchase_codes_username.'" />';
									else
										echo '<input type="text" name="envato_username" id="envato_username" value="'.$settings->confirm_purchase_codes_username.'" />';
									?>
								</div>
								
								<div class="form-group">
									<label for="envato_api">Envato API Key</label>
									<?php
									if($envato_error == true)
										echo '<input type="text" name="envato_api" id="envato_api" class="error" value="'.$settings->confirm_purchase_codes_api.'" />';
									else
										echo '<input type="text" name="envato_api" id="envato_api" value="'.$settings->confirm_purchase_codes_api.'" />';
									?>
								</div>
							</div>
						</div>
						
						<div style="width:100%; height:1px; background-color:#ddd; margin:10px 0 20px 0;"></div>
						
						<div class="row no-bottom-margin">
							<div class="col col-md-6">
								<div class="form-group">
									<label for="radios">Allow guest bug reports</label>
									<span class="label_desc">Enable this option to allow guests to create bug reports.</span>
									<div class="radio">
										<input type="radio" name="allow_guest_bug_reports" id="radio_1" class="green" value="1" <?php if($settings->allow_guest_bug_reports == '1') echo 'checked '; ?>/>
										<label for="radio_1">Enabled</label>
									</div>
									<div class="radio">
										<input type="radio" name="allow_guest_bug_reports" id="radio_2" class="gray" value="0" <?php if($settings->allow_guest_bug_reports == '0') echo 'checked'; ?>/>
										<label for="radio_2">Disabled</label>
									</div>
								</div>
								
								<div class="form-group">
									<label for="radios">Allow guest tickets</label>
									<span class="label_desc">Enable this option to allow guests to create tickets</span>
									<div class="radio">
										<input type="radio" name="allow_guest_tickets" id="radio_3" class="green" value="1" <?php if($settings->allow_guest_tickets == '1') echo 'checked '; ?>/>
										<label for="radio_3">Enabled</label>
									</div>
									<div class="radio">
										<input type="radio" name="allow_guest_tickets" id="radio_4" class="gray" value="0" <?php if($settings->allow_guest_tickets == '0') echo 'checked'; ?>/>
										<label for="radio_4">Disabled</label>
									</div>
								</div>
							</div>
							
							<div class="col col-md-6">
								<div class="form-group">
									<label for="radios">Allow accounts creation</label>
									<span class="label_desc">Enable this option to allow users to register</span>
									<div class="radio">
										<input type="radio" name="allow_account_creations" id="radio_5" class="green" value="1" <?php if($settings->allow_account_creations == '1') echo 'checked '; ?>/>
										<label for="radio_5">Enabled</label>
									</div>
									<div class="radio">
										<input type="radio" name="allow_account_creations" id="radio_6" class="gray" value="0" <?php if($settings->allow_account_creations == '0') echo 'checked'; ?>/>
										<label for="radio_6">Disabled</label>
									</div>
								</div>
							</div>
						</div>
						
						<div style="width:100%; height:1px; background-color:#ddd; margin:0px 0 20px 0;"></div>
						
						<div class="row min-bottom-margin">
							<div class="col col-md-6">
								<div class="form-group">
									<label for="radios">Allow guests to upload files</label>
									<span class="label_desc">Enable this option to allow guests to upload files when submitting tickets or bug reports.</span>
									<div class="radio">
										<input type="radio" name="allow_guest_file_uploads" id="radio_7" class="green" value="1" <?php if($settings->allow_guest_file_uploads == '1') echo 'checked '; ?>/>
										<label for="radio_7">Enabled</label>
									</div>
									<div class="radio">
										<input type="radio" name="allow_guest_file_uploads" id="radio_8" class="gray" value="0" <?php if($settings->allow_guest_file_uploads == '0') echo 'checked'; ?>/>
										<label for="radio_8">Disabled</label>
									</div>
								</div>
								
								<div class="form-group">
									<label for="file_uploads_max_size">Max file size</label>
									<span class="label_desc">
										Set here the max file size allowed in MB. Integer numbers only.
										<?php if($ini_max_file_size != 0) { ?>
										NOTE: The size limit in your PHP.ini file is <?php echo $ini_max_file_size; ?> MB.
										If you want to set this limit higher, change the upload_max_filesize and
										post_max_size vars in your PHP.ini file.
										<?php } ?>
									</span>
									<input type="text" name="file_uploads_max_size" id="file_uploads_max_size" value="<?php echo $settings->file_uploads_max_size; ?>" />
								</div>
							</div>
							
							<div class="col col-md-6">
								<div class="form-group">
									<label for="radios">Allow users to upload files</label>
									<span class="label_desc">Enable this option to allow registered users to upload files when submitting tickets or bug reports.</span>
									<div class="radio">
										<input type="radio" name="allow_file_uploads" id="radio_9" class="green" value="1" <?php if($settings->allow_file_uploads == '1') echo 'checked '; ?>/>
										<label for="radio_9">Enabled</label>
									</div>
									<div class="radio">
										<input type="radio" name="allow_file_uploads" id="radio_10" class="gray" value="0" <?php if($settings->allow_file_uploads == '0') echo 'checked'; ?>/>
										<label for="radio_10">Disabled</label>
									</div>
								</div>
								
								<div class="form-group">
									<label for="file_uploads_extensions">Allowed extensions</label>
									<span class="label_desc">Type here the allowed file extensions. Separate each one by comma and space. e.g.: gif, png, jpg<br />To allow all extensions, leave this empty.</span>
									<input type="text" name="file_uploads_extensions" id="file_uploads_extensions" value="<?php echo $settings->file_uploads_extensions; ?>" />
								</div>
							</div>
						</div>
						
						<input type="submit" name="submit" class="btn btn-strong-blue pull-right" value="Save" />
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
			
			$('form[name=general-settings]').submit(function(evt) {
				var site_title = $('input[name=site_title]').val();
				var envato_username = $('input[name=envato_username]').val();
				var envato_api = $('input[name=envato_api]').val();
				var file_uploads_max_size = $('input[name=file_uploads_max_size]').val();
				
				if(site_title == '') {
					evt.preventDefault();
					error('Please insert the site title', '[name=site_title]');
					return false;
				}
				
				if(envato_username != '' && envato_api == '') {
					evt.preventDefault();
					error('Please insert your Envato API.', '[name=envato_api]');
					return false;
				}
				
				if(envato_api != '' && envato_username == '') {
					evt.preventDefault();
					error('Please insert your Envato Username.', '[name=envato_username]');
					return false;
				}
				
				var number_regex = /^\d*$/;
				if(number_regex.test(file_uploads_max_size) == false) {
					evt.preventDefault();
					error('Max file size must be an integer value.', '[name=file_uploads_max_size]');
					return false;
				}
			});

			var e_active = false;
			function error(e, n) {
				if(e_active != false) {
					$(e_active).removeClass('error');
				}
				
				$(n).addClass('error');
				e_active = n;
				
				$('p.bg-danger').slideUp(200, function() {
					$('p.bg-danger').html(e).slideDown(200);
				});
			}
		});
	</script>
</body>
</html>