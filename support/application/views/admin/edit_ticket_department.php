<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
	<div class="content" id="ticket-department">
		<div class="page-title-cont clearfix">
			<h3>Edit Tickets Department</h3>
		</div>
		
		<div class="row">
			<div class="col margin-top col-md-4">
				<div class="row min-bottom-margin">
					<div class="col col-xs-12">
						<div class="cont">
							<div class="top clearfix">
								<h4 class="pull-left">Department Info</h4>
								<div class="btn btn-small btn-blue pull-right" style="margin-left:5px;" name="edit-department">EDIT</div>
								<?php
								if($dpt_info->default == '2')
									echo '<div class="btn btn-small btn-red pull-right" name="delete-department">DELETE</div>';
								?>
							</div>
							
							<table class="ticket-info">
								<tbody>
									<tr>
										<td>Department name</td>
										<td>
											<?php echo $dpt_info->name; ?>
										</td>
									</tr>
									<tr>
										<td>Responsible agents</td>
										<td>
											<?php echo $dpt_info->agents; ?>
										</td>
									</tr>
									<tr>
										<td>Submitted tickets</td>
										<td>
											<?php echo $dpt_info->tickets; ?>
										</td>
									</tr>
									<tr>
										<td>Tickets without agent</td>
										<td>
											<?php echo $dpt_info->no_agent_tickets; ?>
										</td>
									</tr>
									<tr>
										<td>Open tickets</td>
										<td>
											<?php echo $dpt_info->open_tickets; ?>
										</td>
									</tr>
									<tr>
										<td>Pending tickets</td>
										<td>
											<?php echo $dpt_info->pending_tickets; ?>
										</td>
									</tr>
									<tr>
										<td>Closed tickets</td>
										<td>
											<?php echo $dpt_info->closed_tickets; ?>
										</td>
									</tr>
									<tr>
										<td>Created on</td>
										<td><?php echo $created_on; ?></td>
									</tr>
									<tr>
										<td>Set as default</td>
										<td>
											<?php
											if($dpt_info->default == '1')
												echo 'Yes';
											else
												echo 'No';
											?>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			
			<div class="col margin-top no-bottom-padding col-md-8">
				<div class="row min-bottom-margin">
					<div class="col col-xs-12">
						<div class="cont clearfix">
							<div class="top">
								<h4>Edit department</h4>
							</div>
							
							<p class="bg-danger" style="display:none"></p>
							
							<form method="POST" action="<?php echo $base_url; ?>panel/admin/ticket-department/<?php echo $dpt_info->id; ?>/edit/action" name="edit-tdepartment">
								<div class="form-group">
									<label for="department_name">Department Name</label>
									<input type="text" name="department_name" id="department_name" value="<?php echo $dpt_info->name; ?>" />
								</div>
								
								<br />
								
								<div class="form-group">
									<label for="responsible_agents">Responsible Agents</label>
									<span class="label_desc">Agents that will have access to this ticket's department</span>
									
									<?php
									if(count($agents) == 0)
										echo 'No agents';
									else{
										$t_agents = count($agents);
										$d_agents = ceil($t_agents / 2);
									?>
									<div class="row">
										<?php
										$counter = 0;
										foreach($agents as $agent) {
											if($counter == 0)
												echo '<div class="col col-xs-12 col-md-6">';
											if($counter == $d_agents) {
												echo '</div>';
												echo '<div class="col col-xs-12 col-md-6">';
											}
											
											echo '<div class="checkbox">';
											if($agent->is_selected == true)
												echo '<input type="checkbox" id="checkbox_'.$counter.'" name="agents[]" value="'.$agent->id.'" Checked />';
											else
												echo '<input type="checkbox" id="checkbox_'.$counter.'" name="agents[]" value="'.$agent->id.'" />';
											echo '<label for="checkbox_'.$counter.'">'.$agent->name.'</label>';
											echo '</div>';
											
											if(($counter-1) == $t_agents)
												echo '</div>';
											$counter++;
										}
										?>
									</div>
									
									<?php
									}
									?>
								</div>
								
								<input type="submit" name="save-changes" class="btn btn-strong-blue pull-right" value="Save changes" />
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
			
			$('form[name=edit-tdepartment]').submit(function(evt) {
				var dpt_name = $(this).children('.form-group').children('input[name=department_name]').val();
				
				if(dpt_name == '') {
					evt.preventDefault();
					error('Department name cannot be empty', 'input[name=department_name]');
					return false;
				}
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