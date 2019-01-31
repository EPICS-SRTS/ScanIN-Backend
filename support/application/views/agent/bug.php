<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
	<div class="content" id="bug" data-id="<?php echo $bug_info->id; ?>" data-access="<?php echo $bug_info->access; ?>">
		<div class="page-title-cont clearfix">
			<h3>Bug Report ID <?php echo $bug_info->id; ?></h3>
			<?php
			if($bug_info->status == 1)
				echo '<div class="badge green">SENT</div>';
			elseif($bug_info->status == 2)
				echo '<div class="badge yellow">TAKEN BY AGENT</div>';
			elseif($bug_info->status == 3)
				echo '<div class="badge green">SOLVED</div>';
			elseif($bug_info->status == 4)
				echo '<div class="badge green">REVIEWED</div>';
			elseif($bug_info->status == 5)
				echo '<div class="badge red">INSOLVABLE</div>';
			elseif($bug_info->status == 6)
				echo '<div class="badge gray">OTHER</div>';
			?>
			
			<?php
			if($bug_info->priority == '1')
				echo '<div class="badge red">HIGH PRIORITY</div>';
			elseif($bug_info->priority == '2')
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
								<h4 class="pull-left">Bug Info</h4>
								<div class="btn btn-small btn-red pull-right" name="delete-bug">DELETE BUG</div>
								
								<?php
								if($bug_info->agentid == '0')
									echo '<div class="btn btn-small btn-blue pull-right" style="margin-right:5px;" name="take-bug">TAKE BUG</div>';
								?>
							</div>
							
							<table class="ticket-info">
								<tbody>
									<tr>
										<td>Client name</td>
										<td>
											<?php
											if($bug_info->userid == 0)
												echo $bug_info->guest_name;
											else
												echo $users_model->get_user_info($bug_info->userid)->name;
											?>
										</td>
									</tr>
									<tr>
										<td>Client username</td>
										<td>
											<?php
											if($bug_info->userid == 0)
												echo 'N/A';
											else
												echo $users_model->get_user_info($bug_info->userid)->username;
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
											if($bug_info->status == 1)
												echo 'Sent';
											elseif($bug_info->status == 2)
												echo 'Taken by agent';
											elseif($bug_info->status == 3)
												echo 'Solved';
											elseif($bug_info->status == 4)
												echo 'Reviewed';
											elseif($bug_info->status == 5)
												echo 'Insolvable';
											elseif($bug_info->status == 6)
												echo 'Other';
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
								<h4><?php echo $bug_info->subject; ?></h4>
							</div>
							
							<div class="tb-content clearfix">
								<div class="profile-image">
									<img src="<?php echo asset_url(); ?>img/profile_img/fa-user@1x.png" srcset="<?php echo asset_url(); ?>img/profile_img/fa-user@1x.png 1x, <?php echo asset_url(); ?>img/profile_img/fa-user@2x.png 2x, <?php echo asset_url(); ?>img/profile_img/fa-user@3x.png 3x" width="68" height="68" />
								</div>
								<div class="tb-text">
									<?php echo $bug_info->content; ?>
									
									<?php
									if($bug_files != false) {
									?>
									<div class="files-holder clearfix">
										<?php
										foreach($bug_files as $file) {
										?>
										<a href="<?php echo base_url(); ?>file/3/<?php echo $bug_info->id; ?>/<?php echo $file[0]; ?>" class="file clearfix">
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
				
				<div class="row min-bottom-margin">
					<div class="col col-xs-12">
						<div class="cont clearfix">
							<div class="top">
								<h4>Mark bug report</h4>
							</div>
							
							<form method="POST" action="<?php echo $base_url . 'panel/bug/' . $bug_info->access . '/update-bug-status/'; ?>" name="update-bug">
								<div class="radio">
									<input type="radio" name="bug_status" id="radio_1" class="green" value="1"<?php if($bug_info->status == 3) echo ' checked'; ?> />
									<label for="radio_1">Mark as solved</label>
								</div>
								<div class="radio">
									<input type="radio" name="bug_status" id="radio_2" class="blue" value="2"<?php if($bug_info->status == 4) echo ' checked'; ?> />
									<label for="radio_2">Mark as reviewed</label>
								</div>
								<div class="radio">
									<input type="radio" name="bug_status" id="radio_3" class="red" value="3"<?php if($bug_info->status == 5) echo ' checked'; ?> />
									<label for="radio_3">Mark as insolvable</label>
								</div>
								<div class="radio">
									<input type="radio" name="bug_status" id="radio_4" class="gray" value="4"<?php if($bug_info->status == 6) echo ' checked'; ?> />
									<label for="radio_4">Other</label>
								</div>
								
								<label>Message to send to the client (optional):</label>
								<textarea name="reply" id="reply" class="nostyle margin-bottom"><?php echo $bug_info->agent_msg; ?></textarea>
								
								<input type="submit" name="submit" class="btn btn-strong-blue pull-right" value="Submit" />
							</form>
						</div>
					</div>
				</div>
				
				
				
				<div class="row min-bottom-margin">
					<div class="col col-xs-12">
						<div class="cont">
							<div class="top">
								<h4>Transfer bug report</h4>
							</div>
							
							<form method="POST" action="<?php echo $base_url . 'panel/bug/' . $bug_info->access . '/transfer-bug/'; ?>" name="transfer-bug">
								<div class="row" style="margin-top:15px; margin-bottom:15px;">
									<div class="col col-sm-6" style="margin-bottom:15px;">
										<div class="cont no-padding no-border">
											<div class="form-group">
												<label for="transferToDept">Transfer to Department:</label>
												<select id="transferToDept" class="form-control" name="transfer-to">
													<?php
													$c = 0;
													foreach($bug_departments->result() as $dpt) {
														if($dpt->id != $bug_info->department) {
															echo '<option value="'.$dpt->id.'">'.$dpt->name.'</option>';
															$c += 1;
														}
													}
													
													if($c == 0)
														echo '<option>--No more departments--</option>';
													?>
												</select>
											</div>
										</div>
									</div>
									
									<div class="col col-sm-6">
										<div class="cont no-padding no-border" style="margin-top:-9px;">
											<input type="submit" name="transfer" class="btn btn-strong-blue pull-right" value="Transfer" <?php if($c == 0) echo 'disabled '; ?>/>
										</div>
									</div>
									
								</div>
							</form>
						</div>
					</div>
				</div>
				
				<div class="row min-bottom-margin">
					<div class="col col-xs-12">
						<div class="cont">
							<div class="top"> 
								<h4>Change priority</h4>
							</div>
							
							<form method="POST" action="<?php echo $base_url . 'panel/bug/' . $bug_info->access . '/change-priority/'; ?>" name="transfer-bug">
								<div class="row" style="margin-top:15px; margin-bottom:15px;">
									<div class="col col-sm-6" style="margin-bottom:15px;">
										<div class="cont no-padding no-border">
											<div class="form-group">
												<label for="changePriority">Change priority:</label>
												<input type="hidden" name="previousPriority" value="<?php echo $bug_info->priority; ?>" />
												<select id="changePriority" name="changePriority" class="form-control">
													<option value="1"<?php if($bug_info->priority == '1') echo ' selected'; ?>>High</option>
													<option value="2"<?php if($bug_info->priority == '2') echo ' selected'; ?>>Medium</option>
													<option value="3"<?php if($bug_info->priority == '3') echo ' selected'; ?>>Low</option>
												</select>
											</div>
										</div>
									</div>
									
									<div class="col col-sm-6">
										<div class="cont no-padding no-border" style="margin-top:-9px;">
											<input type="submit" name="change-priority" class="btn btn-strong-blue pull-right" value="Change"/>
										</div>
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
			
			<?php
			if($is_agent || $is_admin) {
				if($bug_info->agentid == '0') {
			?>
			$('.btn[name=take-bug]').click(function() {
				location.href = '<?php echo $base_url; ?>panel/bug/<?php echo $bug_info->access; ?>/take';
			});
			
			<?php
				}
			?>
			
			$('.btn[name=delete-bug]').click(function() {
				location.href = '<?php echo $base_url; ?>panel/bug/<?php echo $bug_info->access; ?>/delete';
			});
			<?php
			}
			?>
			
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

			
			$('form[name=update-bug]').submit(function(evt) {
				editor.post();
			});
		});
	</script>
</body>
</html>