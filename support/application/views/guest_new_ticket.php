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
	<link href="<?php echo asset_url(); ?>css/main/main.css" rel="stylesheet" />
	<link href="<?php echo asset_url(); ?>css/main/ticket-bug.css" rel="stylesheet" />
	<link href="<?php echo asset_url(); ?>css/tinyeditor.css" rel="stylesheet" />
	<link href="<?php echo asset_url(); ?>css/font-awesome.min.css" rel="stylesheet" />
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600,700|PT+Sans:400,700|Open+Sans:300,400,600,700,800|Roboto' rel='stylesheet' type='text/css'>

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body>
	<div id="container" class="create-ticket">
		<a href="">
			<img src="<?php echo asset_url(); ?>img/logos/mainlogo@1x.png" srcset="<?php echo asset_url(); ?>img/logos/mainlogo@1x.png 1x, <?php echo asset_url(); ?>img/logos/mainlogo@2x.png 2x, <?php echo asset_url(); ?>img/logos/mainlogo@3x.png 3x" width="270" height="55" title="<?php echo $site_title; ?>" />
		</a>
		
		<div id="central-container" class="clearfix">
			<h3 class="center">CREATE TICKET</h3>
			<?php
			if($file_error != false)
				echo '<div id="error" style="display:block;">'.$file_error.'</div>';
			else
				echo '<div id="error"></div>';
			?>
			
			<?php
			if($allow_files == true)
				echo '<form method="POST" enctype="multipart/form-data" name="new-ticket" action="">';
			else
				echo '<form method="POST" name="new-ticket" action="">';
			?>
				<label for="name">YOUR NAME</label>
				<input type="text" name="name" id="name" placeholder="Type your name..." value="<?php echo $this->input->post('name'); ?>" />
				
				<label for="email">YOUR EMAIL</label>
				<input type="text" name="email" id="email" placeholder="Type your email..." value="<?php echo $this->input->post('email'); ?>" />
				
				<label for="subject">SUBJECT*</label>
				<input type="text" name="subject" id="subject" placeholder="Ticket subject..." value="<?php echo $this->input->post('subject'); ?>" />
				
				<label for="department">DEPARTMENT*</label>
				<select name="department">
					<?php
					foreach($departments->result() as $dep) {
						if($this->input->post('department') == NULL) {
							if($dep->default == 1)
								echo '<option value="'.$dep->id.'" selected>'.$dep->name.'</option>';
							else
								echo '<option value="'.$dep->id.'">'.$dep->name.'</option>';
						}else{
							if($this->input->post('department') == $dep->id)
								echo '<option value="'.$dep->id.'" selected>'.$dep->name.'</option>';
							else
								echo '<option value="'.$dep->id.'">'.$dep->name.'</option>';
						}
					}
					?>
				</select>
				
				<label for="message">TICKET CONTENT (MESSAGE)*</label>
				<textarea name="message" id="message" class="nostyle"></textarea>
				
				<?php
				if($allow_files == true) {
				?>
				<div class="upload-files">
					<div class="file">
						<button name="selected_file" class="btn btn-upload-file btn-light-blue">Select file to upload...</button>
						<button name="delete_file" class="btn btn-upload-file btn-red btn-delete"><i class="fa fa-close"></i></button>
						<input type="file" name="files[]" style="display:none;" />
					</div>
					<button name="upload_file" class="btn btn-upload-file btn-strong-blue">New file</button>
				</div>
				<?php
				}
				?>
				
				<input type="submit" name="submit" class="pull-right" value="SUBMIT TICKET" />
			</form>
		</div>
	</div>
	
	
	<script src="<?php echo asset_url(); ?>js/jquery-1.11.3.min.js"></script>
	<script src="<?php echo asset_url(); ?>js/tinyeditor.min.js"></script>
	<script type="text/javascript">
		$('document').ready(function() {
			<?php
			if($allow_files == true) {
			?>
			
			<?php
			if($all_extensions_allowed == true) {
				echo 'var all_extensions_allowed = true;' . "\r\n";
				echo '			var allowed_extensions = [];' . "\r\n";
			}else{
				$ext = explode('|', $allowed_extensions);
				$final_extensions = array();
				foreach($ext as $e) {
					$final_extensions[] = "'$e'";
				}
				echo 'var all_extensions_allowed = false;' . "\r\n";
				echo '			var allowed_extensions = ['.implode(',', $final_extensions).'];' . "\r\n";
			}
			?>
			$('button[name=upload_file]').click(function(evt) {
				evt.preventDefault();
				
				var new_file = '<div class="file">';
				new_file += '<button name="selected_file" class="btn btn-upload-file btn-light-blue">Select file to upload...</button>';
				new_file += ' <button name="delete_file" class="btn btn-upload-file btn-red btn-delete"><i class="fa fa-close"></i></button>';
				new_file += '<input type="file" name="files[]" style="display:none;" />';
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
				
				// Get extension and check if it's allowed...
				var ext = val.toLowerCase().split('.').pop();
				if(all_extensions_allowed == false) {
					if(allowed_extensions.indexOf(ext) == -1) {
						var allowed_ext = allowed_extensions.join(', ');
						alert(ext+' is not a valid file extension. You can only upload the following: '+allowed_ext);
					}
				}
				
				$(this).parent().children('button[name=selected_file]').html(val);
			});
			
			$(document).delegate('button[name=delete_file]', 'click', function(evt) {
				evt.preventDefault();
				$(this).parent().remove();
			});
			<?php
			}
			?>
			
			var editor = new TINY.editor.edit('editor', {
				id: 'message',
				width: '100%',
				height:200,
				cssclass: 'tinyeditor',
				controlclass: 'tinyeditor-control',
				rowclass: 'tinyeditor-header',
				dividerclass: 'tinyeditor-divider',
				content: '<?php echo $this->input->post('message'); ?>',
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
			
			
			$('form[name=new-ticket]').submit(function(evt) {
				var name = $('input[name=name]').val();
				var email = $('input[name=email]').val();
				var subject = $('input[name=subject]').val();
				var department = $('select[name=department]').val();
				
				editor.post();
				var message = editor.t.value;
				
				
				if(name == '') {
					evt.preventDefault();
					error('Please insert your name', '[name=name]');
					return false;
				}
				if(name.length < 5) {
					evt.preventDefault();
					error('Your name must be at least 5 characters long', '[name=name]');
					return false;
				}
				if(email == '') {
					evt.preventDefault();
					error('Please insert your email', '[name=email]');
					return false;
				}
				if(validateEmail(email) == false) {
					evt.preventDefault();
					error('Please insert a vaild email address', '[name=email]');
					return false;
				}
				if(subject == '') {
					evt.preventDefault();
					error('Please insert a subject', '[name=subject]');
					return false;
				}
				if(subject.length < 5) {
					evt.preventDefault();
					error('Subject must be at least 5 characters long', '[name=subject]');
					return false;
				}
				if(department == 'default') {
					evt.preventDefault();
					error('Please select a department', '[name=department]');
					return false;
				}
				if(message == '') {
					evt.preventDefault();
					error('Please insert the ticket message', '.tinyeditor');
					return false;
				}
				if(message.length <= 10) {
					evt.preventDefault();
					error('Ticket message must be more than 10 characters long', '.tinyeditor');
					return false;
				}
				
				<?php
				if($allow_files == true) {
				?>
				// Delete empty files
				var nfiles = $('input[type=file]').length;
				var astr = [];
				for(var i = 1; i <= nfiles; i++) {					
					var val = $('.file:nth-child('+i+') input[type=file]').val();
					if(val == '')
						astr.push('.file:nth-child('+i+')');
					else{
						// Get extension and check if it's allowed...
						var ext = val.toLowerCase().split('.').pop();
						if(all_extensions_allowed == false) {
							if(allowed_extensions.indexOf(ext) == -1) {
								var allowed_ext = allowed_extensions.join(', ');
								error('One or more files have an invalid extension. The only allowed extensions are: '+allowed_ext);
								evt.preventDefault();
								return false;
							}
						}
					}
				}
				var str = astr.join(', ');
				$(str).remove();
				<?php
				}
				?>
			});
			
			var e_active = false;
			function error(e, n) {
				if(e_active != false) {
					$(e_active).removeClass('error');
				}
				
				$(n).addClass('error');
				e_active = n;
				
				$('#error').slideUp(200, function() {
					$('#error').html(e).slideDown(200);
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