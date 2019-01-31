<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
	<div class="content">
		<div class="page-title-cont clearfix">
			<h3>Email Confirmations Settings</h3>
		</div>
		
		<div class="row">
			<div class="col col-xs-12">
				<div class="cont clearfix">
					<div class="top clearfix">
						<h4 class="pull-left">Email Confirmations Settings</h4>
					</div>
					
					<p class="bg-danger" style="display:none;"></p>
					
					<?php
					if($settings->mailing == false)
						echo 'To have access to these settings, first you need to enable mailing. To do so, follow this URL: <a href="'.$base_url.'panel/admin/mailer-settings">'.$base_url.'panel/admin/mailer-settings</a>';
					else{
					?>
					<form method="POST" action="<?php echo $base_url; ?>panel/admin/econfirm-settings/action" name="econfirm-settings">
						<div class="row no-bottom-margin">
							<div class="col col-md-6">
								<div class="form-group">
									<label for="radios">Email Confirmation</label>
									<span class="label_desc">If you enable this option, when a user is registered, email confirmation will be required. When you enable this option, you'll have options to customize the emails sent.</span>
									<div class="radio">
										<input type="radio" name="email_confirmation" id="radio_1" class="green" value="1" <?php if($settings->email_confirmation == '1') echo 'checked '; ?>/>
										<label for="radio_1">Enabled</label>
									</div>
									<div class="radio">
										<input type="radio" name="email_confirmation" id="radio_2" class="gray" value="0" <?php if($settings->email_confirmation == '0') echo 'checked'; ?>/>
										<label for="radio_2">Disabled</label>
									</div>
								</div>
							</div>
							
							<div class="col col-md-6 toggle-hide1" style="display:none">
								<div class="from-group">
									<label for="radios">Confirmation Email Type</label>
									<span class="label_desc">Type of the email</span>
									<div class="radio">
										<input type="radio" name="n_account_confirmation_type" id="radio_3" class="green" value="text" <?php if($settings->email_new_account_confirmation_type == 'text') echo 'checked '; ?>/>
										<label for="radio_3">Text</label>
									</div>
									<div class="radio">
										<input type="radio" name="n_account_confirmation_type" id="radio_4" class="green" value="html" <?php if($settings->email_new_account_confirmation_type == 'html') echo 'checked '; ?>/>
										<label for="radio_4">HTML</label>
									</div>
								</div>
							</div>
						</div>
							
						<div class="row min-bottom-margin toggle-hide1" style="display:none">
							<div class="col col-md-6">
								<div class="hide-cont1">
									<div class="form-group">
										<label for="n_account_confirmation_title">Confirmation Email Title</label>
										<span class="label_desc">Title of the email sent explaining the user that he needs to confirm his email address</span>
										<input type="text" name="n_account_confirmation_title" id="n_account_confirmation_title" value="<?php echo $settings->email_new_account_confirmation_title; ?>" />
									</div>
								</div>
							</div>
							
							<div class="col col-md-6">
								<div class="form-group">
									<label for="n_caccount_confirmation_content">Confirmation Email Content</label>
									<span class="label_desc">
										Content of the email. You can use the following keywords:<br />
										<strong>%site_title%</strong> - Title of the site<br />
										<strong>%site_url%</strong> - URL of the site<br />
										<strong>%user_name%</strong> - Name of the user<br />
										<strong>%user_username%</strong> - Username of the user<br />
										<strong>%confirmation_url%</strong> - URL of the page to confirm the account<br />
									</span>
									<textarea name="n_caccount_confirmation_content" id="n_caccount_confirmation_content" class="nostyle margin-bottom tinyedit"><?php echo $settings->email_new_account_confirmation_content; ?></textarea>
								</div>
							</div>
						</div>
						
						<div style="width:100%; height:1px; background-color:#ddd; margin:30px 0 40px 0;"></div>
						
						<div class="row no-bottom-margin">
							<div class="col col-md-6">
								<div class="form-group">
									<label for="radios">Confirmed Account Email</label>
									<span class="label_desc">Send an email to the user when his email address is confirmed</span>
									<div class="radio">
										<input type="radio" name="email_confirmed" id="radio_5" class="green" value="1" <?php if($settings->send_email_confirmed_account == '1') echo 'checked '; ?>/>
										<label for="radio_5">Enabled</label>
									</div>
									<div class="radio">
										<input type="radio" name="email_confirmed" id="radio_6" class="gray" value="0" <?php if($settings->send_email_confirmed_account == '0') echo 'checked'; ?>/>
										<label for="radio_6">Disabled</label>
									</div>
								</div>
							</div>
							
							<div class="col col-md-6 toggle-hide2" style="display:none">
								<div class="from-group">
									<label for="radios">Confirmed Account Email Type</label>
									<span class="label_desc">Type of the email</span>
									<div class="radio">
										<input type="radio" name="c_account_type" id="radio_7" class="green" value="text" <?php if($settings->email_confirmed_account_type == 'text') echo 'checked '; ?>/>
										<label for="radio_7">Text</label>
									</div>
									<div class="radio">
										<input type="radio" name="c_account_type" id="radio_8" class="green" value="html" <?php if($settings->email_confirmed_account_type == 'html') echo 'checked '; ?>/>
										<label for="radio_8">HTML</label>
									</div>
								</div>
							</div>
						</div>
							
						<div class="row min-bottom-margin toggle-hide2" style="display:none">
							<div class="col col-md-6">
								<div class="hide-cont1">
									<div class="form-group">
										<label for="c_account_title">Confirmed Account Email Title</label>
										<span class="label_desc">Title of the email sent explaining the user that his email address has been confirmed</span>
										<input type="text" name="c_account_title" id="c_account_title" value="<?php echo $settings->email_confirmed_account_title; ?>" />
									</div>
								</div>
							</div>
							
							<div class="col col-md-6">
								<div class="form-group">
									<label for="c_account_content">Confirmed Account Email Content</label>
									<span class="label_desc">
										Content of the email. You can use the following keywords:<br />
										<strong>%site_title%</strong> - Title of the site<br />
										<strong>%site_url%</strong> - URL of the site<br />
										<strong>%user_email%</strong> - Email address of the user<br />
									</span>
									<textarea name="c_account_content" id="c_account_content" class="nostyle margin-bottom tinyedit"><?php echo $settings->email_confirmed_account_content; ?></textarea>
								</div>
							</div>
						</div>
						
						<input type="submit" name="submit" class="btn btn-strong-blue pull-right" value="Save" />
					</form>
					
					<?php
					}
					?>
				</div>
			</div>
		</div>
	</div>
	
	
	<script src="<?php echo asset_url(); ?>js/jquery-1.11.3.min.js"></script>
	<script src="<?php echo asset_url(); ?>js/tickerr_core.js"></script>
	<script src="<?php echo asset_url(); ?>js/tinyeditor.min.js"></script>
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

			var email_confirmation = false;
			var email_confirmed = false;
			
			if($('input#radio_1').is(':checked')) {
				$('.toggle-hide1').slideDown(10);
				email_confirmation = true;
			}
			if($('input#radio_5').is(':checked')) {
				$('.toggle-hide2').slideDown(10);
				email_confirmed = true;
			}
			
			$('input[name=email_confirmation]').change(function() {
				if($('input#radio_1').is(':checked')) {
					$('.toggle-hide1').slideDown(300);
					email_confirmation = true;
				}else{
					$('.toggle-hide1').slideUp(300);
					email_confirmation = false;
				}
			});
			
			$('input[name=email_confirmed]').change(function() {
				if($('input#radio_5').is(':checked')) {
					$('.toggle-hide2').slideDown(300);
					email_confirmed = true;
				}else{
					$('.toggle-hide2').slideUp(300);
					email_confirmed = false;
				}
			});
			
			$('form[name=econfirm-settings]').submit(function(evt) {
				var n_account_confirmation_title = $('input[name=n_account_confirmation_title]').val();
				var c_account_title = $('input[name=c_account_title]').val();
				
				txt1.post();
				txt2.post();
				
				// First group enabled or disabled?
				if(email_confirmation == true) {
					if(n_account_confirmation_title == '') {
						evt.preventDefault();
						error('Please insert the confirmation email title', '[name=n_account_confirmation_title]');
						return false;
					}
				}
				
				// Second group enabled?
				if(email_confirmed == true) {
					if(c_account_title == '') {
						evt.preventDefault();
						error('Please insert the confirmed account email title', '[name=c_account_title]');
						return false;
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
			
			var txt1 = new TINY.editor.edit('txt1', {
				id: 'n_caccount_confirmation_content',
				width: '100%',
				height:160,
				cssclass: 'tinyeditor',
				controlclass: 'tinyeditor-control',
				rowclass: 'tinyeditor-header',
				dividerclass: 'tinyeditor-divider',
				controls: ['bold', 'italic', 'underline', 'strikethrough', '|', 'orderedlist',
					'unorderedlist', '|', 'outdent', 'indent', '|', 'leftalign', 'centeralign',
					'rightalign', 'blockjustify', '|', 'image', 'link', 'unlink'],
				footer: true,
				xhtml: true,
				cssfile: '<?php echo asset_url(); ?>css/tinyeditor.css',
				bodyid: 'editor',
				footerclass: 'tinyeditor-footer',
				toggle: {text: 'source', activetext: 'wysiwyg', cssclass: 'toggle'},
				resize: {cssclass: 'resize'}
			});
			var txt2 = new TINY.editor.edit('txt2', {
				id: 'c_account_content',
				width: '100%',
				height:160,
				cssclass: 'tinyeditor',
				controlclass: 'tinyeditor-control',
				rowclass: 'tinyeditor-header',
				dividerclass: 'tinyeditor-divider',
				controls: ['bold', 'italic', 'underline', 'strikethrough', '|', 'orderedlist',
					'unorderedlist', '|', 'outdent', 'indent', '|', 'leftalign', 'centeralign',
					'rightalign', 'blockjustify', '|', 'image', 'link', 'unlink'],
				footer: true,
				xhtml: true,
				cssfile: '<?php echo asset_url(); ?>css/tinyeditor.css',
				bodyid: 'editor',
				footerclass: 'tinyeditor-footer',
				toggle: {text: 'source', activetext: 'wysiwyg', cssclass: 'toggle'},
				resize: {cssclass: 'resize'}
			});
		});
	</script>
</body>
</html>