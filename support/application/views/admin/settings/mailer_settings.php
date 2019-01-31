<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
	<div class="content">
		<div class="page-title-cont clearfix">
			<h3>Mailer Settings</h3>
		</div>
		
		<div class="row">
			<div class="col col-xs-12">
				<div class="cont clearfix">
					<div class="top clearfix">
						<h4 class="pull-left">Mailer Settings</h4>
					</div>
					
					<p class="bg-danger" style="display:none;"></p>
					
					<form method="POST" action="<?php echo $base_url; ?>panel/admin/mailer-settings/action" name="mailer-settings">
						<div class="row min-bottom-margin">
							<div class="col col-md-6">
								<div class="form-group">
									<label for="radios">Mailing</label>
									<span class="label_desc">If you enable mailing, you'll need to give us some information below.</span>
									<div class="radio">
										<input type="radio" name="mailing" id="radio_1" class="green" value="1" <?php if($settings->mailing == '1') echo 'checked '; ?>/>
										<label for="radio_1">Enabled</label>
									</div>
									<div class="radio">
										<input type="radio" name="mailing" id="radio_2" class="gray" value="0" <?php if($settings->mailing == '0') echo 'checked'; ?>/>
										<label for="radio_2">Disabled</label>
									</div>
								</div>

								<div class="hide-cont1" style="display:none">
									<div class="form-group">
										<label for="email_from_address">From Email Address</label>
										<span class="label_desc">When sending an email, this is the email address that will appear as "from"</span>
										<input type="text" name="email_from_address" id="email_from_address" value="<?php echo $settings->email_from_address; ?>" />
									</div>
									
									<div class="form-group">
										<label for="email_from_name">From Name</label>
										<span class="label_desc">When sending an email, this is the name that will appear as "from"</span>
										<input type="text" name="email_from_name" id="email_from_name" value="<?php echo $settings->email_from_name; ?>" />
									</div>
									
									<div class="form-group">
										<label for="email_cc">CC</label>
										<span class="label_desc">Do you want to send a copy of each email sent to another email address? Write it here</span>
										<input type="text" name="email_cc" id="email_cc" value="<?php echo $settings->email_cc; ?>" />
									</div>
								</div>
							</div>
							
							<div class="col col-md-6">
								<div class="hide-cont1" style="display:none">
									<div class="form-group">
										<label for="radios">Mailing Method</label>
										<span class="label_desc">Choose the mailing method you want to use</span>
										<div class="radio">
											<input type="radio" name="mailing_method" id="radio_3" class="green" value="1" <?php if($settings->mailer_method == '1') echo 'checked '; ?>/>
											<label for="radio_3">SMTP</label>
										</div>
										<div class="radio">
											<input type="radio" name="mailing_method" id="radio_4" class="green" value="2" <?php if($settings->mailer_method == '2') echo 'checked '; ?>/>
											<label for="radio_4">Sendmail</label>
										</div>
									</div>
									<div class="hide-cont2" style="display:none">
										<div class="form-group">
											<label for="smtp_host">SMTP Host</label>
											<span class="label_desc">Write here the SMTP Host</span>
											<input type="text" name="smtp_host" id="smtp_host" value="<?php echo $settings->smtp_host; ?>" />
										</div>
										
										<div class="form-group">
											<label for="smtp_port">SMTP Port</label>
											<span class="label_desc">Write here the SMTP Port</span>
											<input type="text" name="smtp_port" id="smtp_port" value="<?php echo $settings->smtp_port; ?>" />
										</div>
										
										<div class="form-group">
											<label for="smtp_user">SMTP Username</label>
											<span class="label_desc">Write here the SMTP Username</span>
											<input type="text" name="smtp_user" id="smtp_user" value="<?php echo $settings->smtp_user; ?>" />
										</div>
										
										<div class="form-group">
											<label for="smtp_pass">SMTP Password</label>
											<span class="label_desc">Write here the SMTP Password</span>
											<input type="password" name="smtp_pass" id="smtp_pass" value="<?php echo $settings->smtp_pass; ?>" />
										</div>
										
										<div class="form-group">
											<label for="smtp_timeout">SMTP Timeout</label>
											<span class="label_desc">This is the maximum time the server will wait to connect to the SMTP server</span>
											<input type="text" name="smtp_timeout" id="smtp_timeout" value="<?php echo $settings->smtp_timeout; ?>" />
										</div>
									</div>
									
									<div class="hide-cont3" style="display:none">
										<div class="form-group">
											<label for="mailpath">Mailpath</label>
											<span class="label_desc">This is where Sendmail is located. Usually you don't have to change this.</span>
											<input type="text" name="mailpath" id="mailpath" value="<?php echo $settings->mailpath; ?>" />
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
			
			var mailing = false;
			var mailing_method = 1;
			
			if($('input#radio_1').is(':checked')) {
				$('.hide-cont1').slideDown(10);
				mailing = true;
			}
			if($('input#radio_3').is(':checked')) {
				mailing_method = 1;
				$('.hide-cont2').slideDown(10);
			}
			if($('input#radio_4').is(':checked')) {
				mailing_method = 2;
				$('.hide-cont3').slideDown(10);
			}
			
			$('input[name=mailing]').change(function() {
				if($('input#radio_1').is(':checked')) {
					mailing = true;
					$('.hide-cont1').slideDown(300);
				}else{
					mailing = false;
					$('.hide-cont1').slideUp(300);
				}
			});
			
			$('input[name=mailing_method').change(function() {
				if($('input#radio_3').is(':checked')) {
					mailing_method = 1;
					$('.hide-cont2').slideDown(300);
					$('.hide-cont3').slideUp(300);
				}else{
					mailing_method = 2;
					$('.hide-cont2').slideUp(300);
					$('.hide-cont3').slideDown(300);
				}
			});
			
			$('form[name=mailer-settings]').submit(function(evt) {
				var from_email = $('input[name=email_from_address]').val();
				var from_name = $('input[name=email_from_name]').val();
				var cc = $('input[name=email_cc]').val();
				var smtp_host = $('input[name=smtp_host]').val();
				var smtp_port = $('input[name=smtp_port]').val();
				var smtp_user = $('input[name=smtp_user]').val();
				var smtp_pass = $('input[name=smtp_pass]').val();
				var smtp_timeout = $('input[name=smtp_timeout]').val();
				var mailpath = $('input[name=mailpath]').val();
				
				// Is mailing enabled or disabled?
				if(mailing == true) {
					if(validateEmail(from_email) == false) {
						evt.preventDefault();
						error('Please insert a valid from email address', '[name=email_from_address]');
						return false;
					}
					if(from_name.length < 3) {
						evt.preventDefault();
						error('Please insert a valid from name', '[name=email_from_name]');
						return false;
					}
					if(cc != '') {
						if(validateEmail(cc) == false) {
							evt.preventDefault();
							error('Please insert a valid cc email', '[name=email_cc]');
							return false;
						}
					}
					
					if(mailing_method == 1) {
						if(smtp_host == '') {
							evt.preventDefault();
							error('Please insert the SMTP host', '[name=smtp_host]');
							return false;
						}
						if(smtp_port == '') {
							evt.preventDefault();
							error('Please insert the SMTP port', '[name=smtp_port]');
							return false;
						}
						var number_regex = /^\d*$/;
						if(number_regex.test(smtp_port) == false) {
							evt.preventDefault();
							error('Please insert a numeric SMTP port', '[name=smtp_port]');
							return false;
						}
						if(smtp_user == '') {
							evt.preventDefault();
							error('Please insert the SMTP username', '[name=smtp_user]');
							return false;
						}
						if(smtp_pass == '') {
							evt.preventDefault();
							error('Please insert the SMTP password', '[name=smtp_pass]');
							return false;
						}
						if(smtp_timeout == '') {
							evt.preventDefault();
							error('Please insert the SMTP timeout', '[name=smtp_timeout]');
							return false;
						}
						if(number_regex.test(smtp_timeout) == false) {
							evt.preventDefault();
							error('Please insert a numeric SMTP timeout', '[name=smtp_timeout]');
							return false;
						}
					}else if(mailing_method == 2) {
						if(mailpath == '') {
							evt.preventDefault();
							error('Please insert a mailpath', '[name=mailpath]');
							return false;
						}
					}
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
			
			function validateEmail(email) {
				var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
				return re.test(email);
			}
		});
	</script>
</body>
</html>