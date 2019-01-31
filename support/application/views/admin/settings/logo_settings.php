<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
	<div class="content">
		<div class="page-title-cont clearfix">
			<h3>Logo Settings</h3>
		</div>
		
		<div class="row">
			<div class="col col-xs-12">
				<div class="cont clearfix">
					<div class="top clearfix">
						<h4 class="pull-left">Logo Settings</h4>
					</div>
					
					<?php
					if(isset($logo_error) && $logo_error != false) {
						if($logo_error == 1)
							echo '<p class="bg-danger">Your new login page logo must be a PNG file</p>';
						else if($logo_error == 2)
							echo '<p class="bg-danger">Your new dashboard logo must be a PNG file</p>';
						else if($logo_error == 3)
							echo '<p class="bg-danger">Your new login page logo must be 810px * 165px</p>';
						else if($logo_error == 4)
							echo '<p class="bg-danger">Your new dashboard logo must be 510px * 75px</p>';
						else if($logo_error == 5)
							echo '<p class="bg-danger">The new login page logo couldn\'t be uploaded</p>';
						else if($logo_error == 6)
							echo '<p class="bg-danger">The new dashboard logo couldn\'t be uploaded</p>';
						else
							echo '<p class="bg-danger" style="display:none;"></p>';
					}else{
						echo '<p class="bg-danger" style="display:none;"></p>';
					}
					?>
					
					<form method="post" action="<?php echo $base_url; ?>panel/admin/logo-settings/action" enctype="multipart/form-data" name="logo-settings">
						<div class="row no-bottom-margin">
							<div class="col col-md-6">
								<div class="form-group">
									<label for="current_login_logo">Current login page logo</label>
									<span class="label_desc">Current logo shown in the login page</span>
									<br />
									
									<div style="background-color:#f2f5f7; margin:auto; width:auto; display:inline-block; margin-left:0; padding:10px 15px;">
										<img src="<?php echo $base_url; ?>assets/img/logos/mainlogo@1x.png" />
									</div>
								</div>
							</div>
							
							<div class="col col-md-6">
								<div class="form-group">
									<label for="new_login_logo">Upload new logo</label>
									<span class="label_desc">
										Your new logo <strong>must be a  810px * 165px (w*h) png file</strong>. Once uploaded, your logo will be resized to 3 different sizes, so they'll be
										optimized for optimal viewing on desktops and high DPI screens (smartphones, TVs, etc.).
									</span>
									
									
									<div class="upload-files">
										<div class="file">
											<button name="selected_file" class="btn btn-upload-file btn-light-blue">Select file to upload...</button>
											<input type="file" name="file_login_logo" style="display:none;" />
										</div>
									</div>
									
								</div>
							</div>
						</div>
						
						<div style="width:100%; height:1px; background-color:#ddd; margin:0px 0 20px 0;"></div>
						
						<div class="row no-bottom-margin">
							<div class="col col-md-6">
								<div class="form-group">
									<label for="current_dash_logo">Current dashboard logo</label>
									<span class="label_desc">Current logo shown in the header in the dashboard</span>
									<br />
									
									<div style="background-color:#2c4960; margin:auto; width:auto; display:inline-block; margin-left:0; padding:12px 10px;">
										<img src="<?php echo $base_url; ?>assets/img/logos/dashlogo@1x.png" />
									</div>
								</div>
							</div>
							
							<div class="col col-md-6">
								<div class="form-group">
									<label for="new_dash_logo">Upload new dashboard logo</label>
									<span class="label_desc">
										Your new dashboard logo <strong>must be a  510px * 75px (w*h) png file</strong>. Once uploaded, your logo will be resized to 3 different sizes,
										so they'll be optimized for optimal viewing on desktops and high DPI screens (smartphones, TVs, etc.).
									</span>
									
									
									<div class="upload-files">
										<div class="file">
											<button name="selected_file" class="btn btn-upload-file btn-light-blue">Select file to upload...</button>
											<input type="file" name="file_dash_logo" style="display:none;" />
										</div>
									</div>
									
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
			var images_fail = [false, false];
			
			$(document).delegate('button[name=selected_file]', 'click', function(evt) {
				// Bug fixer
				if(evt.clientX != 0 && evt.clientY != 0) {
					evt.preventDefault();
					$(this).parent().children('input[type=file]').click();
				}
			});
			
			$(document).delegate('input[type=file]', 'change', function(evt) {			
				var val = $(this).val().split('\\').pop();
				var input_name = $(this).attr('name');
				
				if(input_name == 'file_login_logo') {
					var dimensions = [810, 165];
					var index_fail = 0;
				}else{
					var dimensions = [510, 75];
					var index_fail = 1;
				}
				
				// Get extension and check if it's allowed...
				var ext = val.toLowerCase().split('.').pop();
				if(ext != 'png') {
					alert(ext+' is not a valid file extension. Your logo must be a PNG file.');
				}else{
					// Valid extension, it's an image, so let's check its dimensions
					var file = this.files[0];
					var reader = new FileReader();
					var img = new Image();
					
					reader.onload = function(e) {
						img.src = e.target.result;
						img.onload = function() {
							if(this.width != dimensions[0] || this.height != dimensions[1]) {
								images_fail[index_fail] = true;
								alert('Image must be '+dimensions[0]+'px * '+dimensions[1]+'px (w*h). Selected is '+this.width+'px * '+this.height+'px');
							}else{
								images_fail[index_fail] = false;
							}
						};
					};
					reader.readAsDataURL(file);
				}

				$(this).parent().children('button[name=selected_file]').html(val);
			});

			
			$('form[name=logo-settings]').submit(function(evt) {
				var login_logo = $('input[type=file][name=file_login_logo]').val();
				var dash_logo = $('input[type=file][name=file_dash_logo]').val();
				
				// Get extension and check if it's allowed
				if(login_logo != '') {
					var login_logo_ext = login_logo.toLowerCase().split('.').pop();
					if(login_logo_ext != 'png') {
						error('Your logo must be a PNG file');
						evt.preventDefault();
						return false;
					}
				}
				
				if(dash_logo != '') {
					var dash_logo_ext = dash_logo.toLowerCase().split('.').pop();
					if(dash_logo_ext != 'png') {
						error('Your logo must be a PNG file');
						evt.preventDefault();
						return false;
					}
				}
				
				if(images_fail[0] == true) {
					error('Image must be 810px * 165px (w*h)');
					evt.preventDefault();
					return false;
				}else if(images_fail[1] == true) {
					error('Image must be 510px * 75px (w*h)');
					evt.preventDefault();
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