<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
	<div class="content" id="ticket" data-id="<?php echo $ticket_info->id; ?>" data-access="<?php echo $ticket_info->access; ?>">
		<div class="page-title-cont clearfix">
			<h3>Ticket ID <?php echo $ticket_info->id; ?></h3>
			<?php
			if($ticket_info->status == 1 || $ticket_info->status == 2)
				echo '<div class="badge green">OPEN</div>';
			else
				echo '<div class="badge red">CLOSED</div>';
			?>
			
			<?php
			if($ticket_info->priority == '1')
				echo '<div class="badge red">HIGH PRIORITY</div>';
			elseif($ticket_info->priority == '2')
				echo '<div class="badge yellow">MEDIUM PRIORITY</div>';
			else
				echo '<div class="badge green">LOW PRIORITY</div>';
			?>
		</div>
		
		<div class="row">
			<div class="col margin-top col-md-4">
				<div class="row min-bottom-margin">
					<div class="col col-xs-12">
						<div class="cont">
							<div class="top clearfix">
								<h4 class="pull-left">Ticket Info</h4>
								<div class="btn btn-small btn-red pull-right" name="delete-ticket">DELETE TICKET</div>
							</div>
							
							<table class="ticket-info">
								<tbody>
									<tr>
										<td>Client name</td>
										<td>
											<?php
											if($ticket_info->userid == 0)
												echo $ticket_info->guest_name;
											else
												echo $users_model->get_user_info($ticket_info->userid)->name;
											?>
										</td>
									</tr>
									<tr>
										<td>Client username</td>
										<td>
											<?php
											if($ticket_info->userid == 0)
												echo 'N/A';
											else
												echo $users_model->get_user_info($ticket_info->userid)->username;
											?>
										</td>
									</tr>
									<tr>
										<td>Agent name</td>
										<?php if(isset($self_agent) && $self_agent == true) { ?>
										<td style="text-decoration:underline;">
											<?php echo ($agent_info == false) ? 'N/A' : $agent_info->name; ?>
											<strong>(Me)</strong>
										</td>
										<?php }else{ ?>
										<td>
											<?php echo ($agent_info == false) ? 'N/A' : $agent_info->name; ?>
										</td>
										<?php } ?>
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
				
				<div class="row">
					<div class="col col-xs-12">
						<div class="cont clearfix">
							<div class="top">
								<h4>Client's Rating</h4>
							</div>
							
							<?php
							if($ticket_info->rating == '0.0') {
							?>
							<strong class="small">
								When you close this ticket, your client will have the
								option to rate your support and give you a suggestion.
								Both things will appear here.
							</strong>
							
							<?php
							}else{
							?>
							
							<div id="rate_stars" class="normal-stars clearfix" style="width:auto;">
								<div class="normal-stars pull-left clearfix">
									<?php
									$rating = $ticket_info->rating * 2;
									for($i = 2; $i <= 10; $i+2) {
										echo "\r\n".'<div class="star divided-star">';
										if($i-1 <= $rating)
											echo '<div class="piece-left"><i class="fa fa-star active"></i></div> ';
										else
											echo '<div class="piece-left"><i class="fa fa-star"></i></div> ';
										
										if($i <= $rating)
											echo '<div class="piece-right"><i class="fa fa-star active"></i></div> ';
										else
											echo '<div class="piece-right"><i class="fa fa-star"></i></div> ';
										echo '</div>';
										
										$i = $i+2;
									}
									?>
									
									<h3 class="pull-right" style="margin-top:2px;"><?php echo $rating / 2; ?>/5</h3>
								</div>
							</div>
							<?php
								echo $ticket_info->rating_msg;
							}
							?>
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
									// Check purchase codes
									if($confirm_purchase_codes == true) {
										$message = $reply->content;
										preg_match_all('/.{8}-.{4}-.{4}-.{4}-.{12}/', $message, $matches);
										if(count($matches[0]) > 0) {
											foreach($matches[0] as $code) {
												if(verify_envato_purchase_code($confirm_purchase_codes_username, $confirm_purchase_codes_api, $code) == true)
													$reply->content = str_replace($code, '<span class="envato-verified">'.$code.' <i class="fa fa-check-circle"></i></span>', $reply->content);
												else
													$reply->content = str_replace($code, '<span class="envato-unverified">'.$code.'</span>', $reply->content);
											}
										}
									}
									
									// Guest reply or client
									if($reply->agentid == '0') {
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

				<div class="row min-bottom-margin">
					<div class="col col-xs-12">
						<div class="cont clearfix">
							<div class="top">
								<h4>New Reply</h4>
							</div>

							<?php
							if($error == false)
								echo '<p class="bg-danger" style="display:none"></p>';
							else
								echo '<p class="bg-danger">' . $error . '</p>';
							?>
							
							<?php
							if($allow_files == true)
								echo '<form method="POST" action="'.$base_url . 'panel/ticket/' . $ticket_info->access . '/new-agent-reply" enctype="multipart/form-data" name="new-reply">';
							else
								echo '<form method="POST" action="'.$base_url . 'panel/ticket/' . $ticket_info->access . '/new-agent-reply" name="new-reply">';
							?>
								<textarea name="reply" id="reply" class="nostyle margin-bottom"><?php if(isset($textarea_cont)) echo $textarea_cont; ?></textarea>

								<div class="row" style="margin-top:15px; margin-bottom:15px;">
									<div class="col col-sm-6" style="margin-bottom:15px;">
										<div class="cont no-padding no-border">
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
									
									<div class="col col-sm-6" style="margin-top:6px;">
										<div class="cont no-padding no-border">
											<div class="form-group">
												<label for="transferToDept">Transfer to Department:</label>
												
												<?php if($ticket_info->rating != '0.0') { ?>
												This ticket cannot be transferred as it has been closed at least once and you have a rating.
												<input type="hidden" name="transferToDept" value="none" />
												<?php
												}else{
												?>
												<select id="transferToDept" name="transferToDept" class="form-control">
													<option value="none">Select department to transfer to...</option>
													<?php
													foreach($departments->result() as $dpt) {
														if($dpt->id != $ticket_info->department)
															echo "<option value=\"{$dpt->id}\">{$dpt->name}</option>";
													}
													?>
												</select>
												<?php } ?>
											</div>
											
											<div class="form-group">
												<label for="changePriority">Change priority:</label>
												<input type="hidden" name="previousPriority" value="<?php echo $ticket_info->priority; ?>" />
												<select id="changePriority" name="changePriority" class="form-control">
													<option value="1"<?php if($ticket_info->priority == '1') echo ' selected'; ?>>High</option>
													<option value="2"<?php if($ticket_info->priority == '2') echo ' selected'; ?>>Medium</option>
													<option value="3"<?php if($ticket_info->priority == '3') echo ' selected'; ?>>Low</option>
												</select>
											</div>
											
											<?php
											if($ticket_info->status != 3) {
											?>
											<div class="checkbox pull-right">
												<input type="checkbox" id="checkbox_1" name="close" value="1" />
												<label for="checkbox_1">Mark ticket as closed</label>
											</div>
											<?php
											}
											?>
										</div>
										
										<input type="submit" name="submit-reply" class="btn btn-strong-blue pull-right" value="Submit reply" />
									</div>
								</div>
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
			
			$('form[name=new-reply]').submit(function(evt) {
				editor.post();
				var message = editor.t.value;
				var transfer_to = $('select[name=transferToDept]').val();
				var close = $('input[type=checkbox][name=close]');
				
				// Message empty? Check other stuff..
				if(message.length <= 10) {
					if(transfer_to == 'none' && !close.is(':checked')) {
						evt.preventDefault();
						error('Ticket message must be more than 10 characters long', '.tinyeditor');
						return false;
					}
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
			
			$('.btn[name=delete-ticket]').click(function() {
				location.href = '<?php echo $base_url; ?>panel/ticket/<?php echo $ticket_info->access; ?>/delete';
			});
			
			/* Envato verification code */
			$('span.envato-verified').on('mouseover', function(evt) {
				$('#tooltip').html('This Envato Purchase Code has been verified').fadeIn(50);
			});
			$('span.envato-verified').on('mousemove', function(evt) {
				$('#tooltip').css({
					'left': (evt.pageX+8)+'px',
					'top': (evt.pageY-46)+'px'
				});
			});
			$('span.envato-verified').on('mouseout', function(evt) {
				$('#tooltip').html('This Envato Purchase Code has been verified').fadeOut(50);
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