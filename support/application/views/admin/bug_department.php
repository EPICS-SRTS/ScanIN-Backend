<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
	<div class="content" id="ticket-department">
		<div class="page-title-cont clearfix">
			<h3>Bugs Department</h3>
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
										<td>Submitted bug reports</td>
										<td>
											<?php echo $dpt_info->reports; ?>
										</td>
									</tr>
									<tr>
										<td>Reports without agent</td>
										<td>
											<?php echo $dpt_info->free_bugs; ?>
										</td>
									</tr>
									<tr>
										<td>Solved reports</td>
										<td>
											<?php echo $dpt_info->solved_bugs; ?>
										</td>
									</tr>
									<tr>
										<td>Other reports</td>
										<td>
											<?php echo $dpt_info->other_bugs; ?>
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
				
				<div class="row min-bottom-margin">
					<div class="col col-xs-12">
						<div class="cont">
							<div class="top clearfix">
								<h4 class="pull-left">Responsible Agents</h4>
								<?php
								if($agents != false && count($agents) >= 8)
									echo '<div class="btn btn-small btn-green pull-right" name="drop" data-drop="agents-list">+ OPEN LIST</div>';
								?>
							</div>
							
							<?php
							if($agents == false)
								echo 'No agents';
							else {
								if(count($agents) >= 8)
									echo '<div class="dropdwn" name="dropdwn-agents-list">';
							?>
							<table class="table">
								<thead>
									<tr>
										<th width="80%">Agent</th>
										<th width="20%">Remove</th>
									</tr>
								</thead>
								<tbody>
									<?php
									foreach($agents as $agent) {
										echo '<tr>';
										echo '<td>'.$agent->name.'</td>';
										echo '<td><a href="'.$base_url.'panel/admin/bug-department/'.$dpt_info->id.'/remove-agent/'.$agent->id.'" title="Remove agent from department" name="remove-agent"><i class="fa fa-close"></i></a></td>';
										echo '</tr>';
									}
									?>
								</tbody>
							</table>
							<?php
								if(count($agents) >= 8)
									echo '</div>';
							}
							?>
						</div>
					</div>
				</div>
			</div>
			
			<div class="col margin-top no-bottom-padding col-md-8">
				<div class="row min-bottom-margin">
					<div class="col col-xs-12">
						<div class="cont">
							<div class="top">
								<h4>Reports without an agent</h4>
							</div>
							
							<?php
							if($count_free_bugs == 0)
								echo 'No bug reports';
							else{
							?>
							<table class="table tickets-w-agent">
								<thead>
									<tr>
										<th width="10%" data-sort="<?php echo $base_url; ?>panel/admin/bug-department/<?php echo $dpt_info->id; ?>/free-bugs/?sort=1&w=a"><i class="fa fa-sort"></i>ID</th>
										<th width="30%" data-sort="<?php echo $base_url; ?>panel/admin/bug-department/<?php echo $dpt_info->id; ?>/free-bugs/?sort=2&w=a"><i class="fa fa-sort"></i>Title</th>
										<th width="30%" data-sort="<?php echo $base_url; ?>panel/admin/bug-department/<?php echo $dpt_info->id; ?>/free-bugs/?sort=3&w=a"><i class="fa fa-sort"></i>Client</th>
										<th width="30%" data-sort="<?php echo $base_url; ?>panel/admin/bug-department/<?php echo $dpt_info->id; ?>/free-bugs/?sort=4&w=a"><i class="fa fa-sort hid"></i><i class="fa fa-sort-down"></i>Last Event</th>
									</tr>
								</thead>
								
								<tbody>
									<?php
									foreach($get_free_bugs->result() as $report) {
										echo '<tr data-href="' . $base_url . 'panel/bug/' . $report->access.'">';
										echo '<td>'.$report->id.'</td>';
										echo '<td>'.$report->subject.'</td>';
										echo '<td>'.$report->client_final_name.'</td>';
										echo '<td>'.date('M jS, Y \a\t H:i:s', strtotime($report->date)).'</td>';
										echo '</tr>';
									}
									?>
								</tbody>
							</table>
							
							<?php
							}
							
							if($count_free_bugs > 8)
								echo '<div class="load-more" name="load_more1">Load More...</div>';
							?>
						</div>
					</div>
				</div>
				
				<div class="row min-bottom-margin">
					<div class="col col-xs-12">
						<div class="cont">
							<div class="top">
								<h4>Solved reports</h4>
							</div>
							
							<?php
							if($count_solved_bugs == 0)
								echo 'No bug reports';
							else{
							?>
							<table class="table tickets-w-agent">
								<thead>
									<tr>
										<th width="10%" data-sort="<?php echo $base_url; ?>panel/admin/bug-department/<?php echo $dpt_info->id; ?>/solved-bugs/?sort=1&w=a"><i class="fa fa-sort"></i>ID</th>
										<th width="30%" data-sort="<?php echo $base_url; ?>panel/admin/bug-department/<?php echo $dpt_info->id; ?>/solved-bugs/?sort=2&w=a"><i class="fa fa-sort"></i>Title</th>
										<th width="30%" data-sort="<?php echo $base_url; ?>panel/admin/bug-department/<?php echo $dpt_info->id; ?>/solved-bugs/?sort=3&w=a"><i class="fa fa-sort"></i>Client</th>
										<th width="30%" data-sort="<?php echo $base_url; ?>panel/admin/bug-department/<?php echo $dpt_info->id; ?>/solved-bugs/?sort=4&w=a"><i class="fa fa-sort hid"></i><i class="fa fa-sort-down"></i>Last Event</th>
									</tr>
								</thead>
								
								<tbody>
									<?php
									foreach($get_solved_bugs->result() as $report) {
										echo '<tr data-href="' . $base_url . 'panel/bug/' . $report->access.'">';
										echo '<td>'.$report->id.'</td>';
										echo '<td>'.$report->subject.'</td>';
										echo '<td>'.$report->client_final_name.'</td>';
										echo '<td>'.date('M jS, Y \a\t H:i:s', strtotime($report->date)).'</td>';
										echo '</tr>';
									}
									?>
								</tbody>
							</table>
							
							<?php
							}
							
							if($count_solved_bugs > 8)
								echo '<div class="load-more" name="load_more2">Load More...</div>';
							?>
						</div>
					</div>
				</div>
				
				<div class="row min-bottom-margin">
					<div class="col col-xs-12">
						<div class="cont">
							<div class="top">
								<h4>Other bug reports</h4>
							</div>
							
							<?php
							if($count_other_bugs == 0)
								echo 'No bug reports';
							else{
							?>
							<table class="table tickets-w-agent">
								<thead>
									<tr>
										<th width="10%" data-sort="<?php echo $base_url; ?>panel/admin/bug-department/<?php echo $dpt_info->id; ?>/other-bugs/?sort=1&w=a"><i class="fa fa-sort"></i>ID</th>
										<th width="30%" data-sort="<?php echo $base_url; ?>panel/admin/bug-department/<?php echo $dpt_info->id; ?>/other-bugs/?sort=2&w=a"><i class="fa fa-sort"></i>Title</th>
										<th width="30%" data-sort="<?php echo $base_url; ?>panel/admin/bug-department/<?php echo $dpt_info->id; ?>/other-bugs/?sort=3&w=a"><i class="fa fa-sort"></i>Client</th>
										<th width="30%" data-sort="<?php echo $base_url; ?>panel/admin/bug-department/<?php echo $dpt_info->id; ?>/other-bugs/?sort=4&w=a"><i class="fa fa-sort hid"></i><i class="fa fa-sort-down"></i>Last Event</th>
									</tr>
								</thead>
								
								<tbody>
									<?php
									foreach($get_other_bugs->result() as $report) {
										echo '<tr data-href="' . $base_url . 'panel/bug/' . $report->access.'">';
										echo '<td>'.$report->id.'</td>';
										echo '<td>'.$report->subject.'</td>';
										echo '<td>'.$report->client_final_name.'</td>';
										echo '<td>'.date('M jS, Y \a\t H:i:s', strtotime($report->date)).'</td>';
										echo '</tr>';
									}
									?>
								</tbody>
							</table>
							
							<?php
							}
							
							if($count_other_bugs > 8)
								echo '<div class="load-more" name="load_more3">Load More...</div>';
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
			
			$('.load-more[name=load_more1]').click(function() {
				location.href = '<?php echo $base_url; ?>panel/admin/bug-department/<?php echo $dpt_info->id; ?>/free-bugs';
			});
			$('.load-more[name=load_more2]').click(function() {
				location.href = '<?php echo $base_url; ?>panel/admin/bug-department/<?php echo $dpt_info->id; ?>/solved-bugs';
			});
			$('.load-more[name=load_more3]').click(function() {
				location.href = '<?php echo $base_url; ?>panel/admin/bug-department/<?php echo $dpt_info->id; ?>/other-bugs';
			});
			
			$('a[name=remove-agent]').click(function(evt) {
				var c = confirm("Are you sure you want to remove this agent from this department? All his reports will change to the 'reports without agent' status.");
				if(c == false) {
					evt.preventDefault();
					return false;
				}
			});
			
			<?php
			if($dpt_info->default == '2') {
			?>
			$('.btn[name=delete-department]').click(function() {
				<?php
				if($dpt_info->default == '1')
					echo 'alert("Default departments cannot be deleted");';
				else{
				?>
				var c = confirm("Are you sure you want to delete this department? All bug reports related to it will be deleted!");
				if(c == true)
					location.href = '<?php echo $base_url; ?>panel/admin/bug-department/<?php echo $dpt_info->id; ?>/delete';
				<?php
				}
				?>
			});
			<?php
			}
			?>
			
			$('.btn[name=edit-department]').click(function() {
				location.href = '<?php echo $base_url; ?>panel/admin/bug-department/<?php echo $dpt_info->id; ?>/edit';
			});
			
			$('.btn[name=drop]').click(function(evt) {
				evt.preventDefault();
				var to = $(this).data('drop');
				$('.dropdwn[name=dropdwn-'+to+']').slideToggle(300);
				if($(this).html() == '+ OPEN LIST')
					$(this).html('- CLOSE LIST');
				else
					$(this).html('+ OPEN LIST');
			});
			
			$('thead tr th').click(function(evt) {
				if($(this).data('sort') !== undefined)
					location.href = $(this).data('sort');
			});
			
			$('tr').click(function(evt) {
				if($(this).data('href') !== undefined)
					location.href = $(this).data('href');
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