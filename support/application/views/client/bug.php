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
		</div>
		
		<div class="row">
			<div class="col margin-top col-md-4">
				<div class="row min-bottom-margin">
					<div class="col col-xs-12">
						<div class="cont">
							<div class="top clearfix">
								<h4 class="pull-left">Bug Report Info</h4>
							</div>
							
							<table class="ticket-info">
								<tbody>
									<tr>
										<td>Agent name</td>
										<td>
											<?php echo ($agent_info == false) ? 'N/A' : $agent_info->name; ?>
										</td>
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
				
				<div class="row">
					<div class="col col-xs-12">
						<div class="cont">
							<div class="top">
								<h4>Bug Status</h4>
							</div>
							<?php
							if($bug_info->status == 1)
								echo '<p class="bg-success">Your bug report has been received. Please wait until an agent takes it.</p>';
							elseif($bug_info->status == 2)
								echo '<p class="bg-warning">Your bug report has been taken by an agent. Please wait until the agent reviews it.</p>';
							elseif($bug_info->status == 3)
								echo '<p class="bg-success">The bug you reported has been solved. Thank you!</p>';
							elseif($bug_info->status == 4)
								echo '<p class="bg-primary">Your bug report has been reviewed by an agent. Please wait until it gets solved.</p>';
							elseif($bug_info->status == 5)
								echo '<p class="bg-danger">Sorry! The bug report you submitted appears to be insolvable.</p>';
							elseif($bug_info->status == 6)
								echo '<p class="bg-primary">Your bug report has an undefined status.</p>';
							
							if($bug_info->agent_msg != '') {
								echo '<strong>Message from the agent:</strong><br />' . $bug_info->agent_msg;
							}
							?>
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
		});
	</script>
</body>
</html>