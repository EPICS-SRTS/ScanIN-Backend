<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
	<div class="content">
		<div class="page-title-cont clearfix">
			<h3>Create new bug report</h3>
		</div>
		
		<div class="row">
			<div class="col col-xs-12">
				<div class="cont clearfix">
					<div class="top clearfix">
						<h4 class="pull-left">Bug report details</h4>
					</div>
					
					<?php
					if(!isset($error))
						echo '<p class="bg-danger" style="display:none"></p>';
					else
						echo '<p class="bg-danger">'.$error.'</p>';
					?>
					
					<?php
					if($allow_files == true)
						echo '<form method="POST" action="" enctype="multipart/form-data" name="new-bug-report">';
					else
						echo '<form method="POST" action="" name="new-bug-report">';
					?>
						<div class="row min-bottom-margin">
							<div class="col col-md-6">
								<div class="form-group">
									<label for="subject">Subject</label>
									<span class="label_desc">Title of your report</span>
									<input type="text" id="subject" name="subject" value="<?php if(isset($_POST['subject'])) echo $_POST['subject']; ?>" />
								</div>
								
								<div class="form-group">
									<label for="department">Department</label>
									<span class="label_desc">Select a department for your report</span>
									<select name="department" id="department">
										<?php
										foreach($bug_departments->result() as $row) {
											if(isset($_POST['department']) && $_POST['department'] == $row->id)
												echo '<option value="'.$row->id.'" selected>'.$row->name.'</option>';
											else{
												if($row->default == '1')
													echo '<option value="'.$row->id.'" selected>'.$row->name.'</option>';
												else
													echo '<option value="'.$row->id.'">'.$row->name.'</option>';
											}
										}
										?>
									</select>
								</div>
							</div>
							
							<div class="col col-md-6">
								<div class="form-group">
									<label for="report_msg">Ticket Content</label>
									<span class="label_desc">Write here the content of your report. Please be as explicit as possible</span>
									<textarea name="report_msg" id="report_msg" class="nostyle margin-bottom"><?php if(isset($_POST['report_msg'])) echo $_POST['report_msg']; ?></textarea>
								</div>
								
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
							</div>
						</div>
						
						<input type="submit" name="submit" class="btn btn-strong-blue pull-right" value="Send report" />
					</form>
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
			
			var editor = new TINY.editor.edit('editor', {
				id: 'report_msg',
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
			
			
			$('form[name=new-bug-report]').submit(function(evt) {
				var name = $('input[name=name]').val();
				var email = $('input[name=email]').val();
				var subject = $('input[name=subject]').val();
				var department = $('select[name=department]').val();
				
				editor.post();
				var message = editor.t.value;
				
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
				if(message == '') {
					evt.preventDefault();
					error('Please insert the bug report message', '.tinyeditor');
					return false;
				}
				if(message.length <= 10) {
					evt.preventDefault();
					error('Bug report message must be more than 10 characters long', '.tinyeditor');
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