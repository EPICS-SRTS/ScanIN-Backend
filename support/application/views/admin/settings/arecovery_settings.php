<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
	<div class="content">
		<div class="page-title-cont clearfix">
			<h3>Account Recovery Settings</h3>
		</div>
		
		<div class="row">
			<div class="col col-xs-12">
				<div class="cont clearfix">
					<div class="top clearfix">
						<h4 class="pull-left">Account Recovery Settings</h4>
					</div>
					
					<p class="bg-danger" style="display:none;"></p>
					
					<?php
					if($settings->mailing == false)
						echo 'To have access to these settings, first you need to enable mailing. To do so, follow this URL: <a href="'.$base_url.'panel/admin/mailer-settings">'.$base_url.'panel/admin/mailer-settings</a>';
					else{
					?>
					<form method="POST" action="<?php echo $base_url; ?>panel/admin/arecovery-settings/action" name="arecovery-settings">
						<div class="row no-bottom-margin">
							<div class="col col-md-6">
								<div class="form-group">
									<label for="radios">Account Recovery</label>
									<span class="label_desc">Enable this option to allow users to recover their accounts (when they forget the password).</span>
									<div class="radio">
										<input type="radio" name="allow_account_recovery" id="radio_1" class="green" value="1" <?php if($settings->allow_account_recovery == '1') echo 'checked '; ?>/>
										<label for="radio_1">Enabled</label>
									</div>
									<div class="radio">
										<input type="radio" name="allow_account_recovery" id="radio_2" class="gray" value="0" <?php if($settings->allow_account_recovery == '0') echo 'checked'; ?>/>
										<label for="radio_2">Disabled</label>
									</div>
								</div>
							</div>
							
							<div class="col col-md-6 toggle-hide1" style="display:none">
								<div class="from-group">
									<label for="radios">Account Recovery Email Type</label>
									<span class="label_desc">Type of the email sent when an user wants to recover his account</span>
									<div class="radio">
										<input type="radio" name="email_recover_type" id="radio_3" class="green" value="text" <?php if($settings->email_recover_type == 'text') echo 'checked '; ?>/>
										<label for="radio_3">Text</label>
									</div>
									<div class="radio">
										<input type="radio" name="email_recover_type" id="radio_4" class="green" value="html" <?php if($settings->email_recover_type == 'html') echo 'checked '; ?>/>
										<label for="radio_4">HTML</label>
									</div>
								</div>
							</div>
						</div>
							
						<div class="row min-bottom-margin toggle-hide1" style="display:none">
							<div class="col col-md-6">
								<div class="hide-cont1">
									<div class="form-group">
										<label for="email_recover_title">Account Recovery Email Title</label>
										<span class="label_desc">Title of the email sent when an user wants to recover his account</span>
										<input type="text" name="email_recover_title" id="email_recover_title" value="<?php echo $settings->email_recover_title; ?>" />
									</div>
								</div>
							</div>
							
							<div class="col col-md-6">
								<div class="form-group">
									<label for="email_recover_content">Account Recovery Email Content</label>
									<span class="label_desc">
										Content of the email sent when an user wants to recover his account. You can use the following keywords:<br />
										<strong>%site_title%</strong> - Title of the site<br />
										<strong>%site_url%</strong> - URL of the site<br />
										<strong>%user_email%</strong> - Email of the user<br />
										<strong>%recovery_code%</strong> - Code to recover the user's account<br />
										<strong>%recovery_url%</strong> - URL of the page to recover the user's the account<br />
									</span>
									<textarea name="email_recover_content" id="email_recover_content" class="nostyle margin-bottom tinyedit"><?php echo $settings->email_recover_content; ?></textarea>
								</div>
							</div>
						</div>
						
						<div style="width:100%; height:1px; background-color:#ddd; margin:30px 0 40px 0;"></div>
						
						<div class="row no-bottom-margin">
							<div class="col col-md-6">
								<div class="form-group">
									<label for="radios">Account Recovered Email</label>
									<span class="label_desc">Send an email to the user when his account has been recovered</span>
									<div class="radio">
										<input type="radio" name="recovery_done" id="radio_5" class="green" value="1" <?php if($settings->send_email_recovery_done == '1') echo 'checked '; ?>/>
										<label for="radio_5">Enabled</label>
									</div>
									<div class="radio">
										<input type="radio" name="recovery_done" id="radio_6" class="gray" value="0" <?php if($settings->send_email_recovery_done == '0') echo 'checked'; ?>/>
										<label for="radio_6">Disabled</label>
									</div>
								</div>
							</div>
							
							<div class="col col-md-6 toggle-hide2" style="display:none">
								<div class="from-group">
									<label for="radios">Account Recovered Email Type</label>
									<span class="label_desc">Type of the email</span>
									<div class="radio">
										<input type="radio" name="recovery_done_type" id="radio_7" class="green" value="text" <?php if($settings->email_recovery_done_type == 'text') echo 'checked '; ?>/>
										<label for="radio_7">Text</label>
									</div>
									<div class="radio">
										<input type="radio" name="recovery_done_type" id="radio_8" class="green" value="html" <?php if($settings->email_recovery_done_type == 'html') echo 'checked '; ?>/>
										<label for="radio_8">HTML</label>
									</div>
								</div>
							</div>
						</div>
							
						<div class="row min-bottom-margin toggle-hide2" style="display:none">
							<div class="col col-md-6">
								<div class="hide-cont1">
									<div class="form-group">
										<label for="recovery_done_title">Account Recovered Email Title</label>
										<span class="label_desc">Title of the email sent explaining the user his account has been recovered</span>
										<input type="text" name="recovery_done_title" id="recovery_done_title" value="<?php echo $settings->email_recovery_done_title; ?>" />
									</div>
								</div>
							</div>
							
							<div class="col col-md-6">
								<div class="form-group">
									<label for="recovery_done_content">Account Recovered Email Content</label>
									<span class="label_desc">
										Content of the email. You can use the following keywords:<br />
										<strong>%site_title%</strong> - Title of the site<br />
										<strong>%site_url%</strong> - URL of the site<br />
										<strong>%user_email%</strong> - Email address of the user<br />
									</span>
									<textarea name="recovery_done_content" id="recovery_done_content" class="nostyle margin-bottom tinyedit"><?php echo $settings->email_recovery_done_content; ?></textarea>
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

			var allow_account_recovery = false;
			var recovery_done = false;
			
			if($('input#radio_1').is(':checked')) {
				$('.toggle-hide1').slideDown(10);
				allow_account_recovery = true;
			}
			if($('input#radio_5').is(':checked')) {
				$('.toggle-hide2').slideDown(10);
				recovery_done = true;
			}
			
			$('input[name=allow_account_recovery]').change(function() {
				if($('input#radio_1').is(':checked')) {
					$('.toggle-hide1').slideDown(300);
					allow_account_recovery = true;
				}else{
					$('.toggle-hide1').slideUp(300);
					allow_account_recovery = false;
				}
			});
			
			$('input[name=recovery_done]').change(function() {
				if($('input#radio_5').is(':checked')) {
					$('.toggle-hide2').slideDown(300);
					recovery_done = true;
				}else{
					$('.toggle-hide2').slideUp(300);
					recovery_done = false;
				}
			});
			
			$('form[name=arecovery-settings]').submit(function(evt) {
				var email_recover_title = $('input[name=email_recover_title]').val();
				var recovery_done_title = $('input[name=recovery_done_title]').val();
				
				txt1.post();
				txt2.post();
				
				// First group enabled or disabled?
				if(allow_account_recovery == true) {
					if(email_recover_title == '') {
						evt.preventDefault();
						error('Please insert the account recovery email title', '[name=email_recover_title]');
						return false;
					}
				}
				
				// Second group enabled?
				if(recovery_done == true) {
					if(recovery_done_title == '') {
						evt.preventDefault();
						error('Please insert the account recovered email title', '[name=recovery_done_title]');
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
				id: 'email_recover_content',
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
				id: 'recovery_done_content',
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