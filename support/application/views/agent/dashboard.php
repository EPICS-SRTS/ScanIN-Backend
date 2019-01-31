<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
	<div class="content">
		<div class="page-title-cont clearfix">
			<h3>Dashboard</h3>
		</div>
		
		<div class="row dash-stats">
			<div class="cl col-xs-6 col-sm-4 col-lg-2">
				<div class="box red">
					<span class="big"><?php echo $top_counter['pending_bugs']; ?></span>
					<span class="down">bugs pending to be attended</span>
				</div>
			</div>
			<div class="cl col-xs-6 col-sm-4 col-lg-2">
				<div class="box yellow">
					<span class="big"><?php echo $top_counter['pending_tickets']; ?></span>
					<span class="down">tickets awaiting your response</span>
				</div>
			</div>
			<div class="cl col-xs-6 col-sm-4 col-lg-2">
				<div class="box red">
					<span class="big"><?php echo $top_counter['no_agent_tickets']; ?></span>
					<span class="down">tickets without an agent</span>
				</div>
			</div>
			<div class="cl col-xs-6 col-sm-4 col-lg-2">
				<div class="box light-blue">
					<span class="big"><?php echo $top_counter['pending_client_tickets']; ?></span>
					<span class="down">tickets awaiting client's response</span>
				</div>
			</div>
			<div class="cl col-xs-6 col-sm-4 col-lg-2">
				<div class="box green">
					<span class="big"><?php echo $top_counter['solved_tickets']; ?></span>
					<span class="down">solved tickets</span>
				</div>
			</div>
			<div class="cl col-xs-6 col-sm-4 col-lg-2">
				<div class="box blue">
					<span class="big"><?php echo $top_counter['customer_satisfaction']; ?>/5</span>
					<span class="down">customer satisfaction</span>
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col no-bottom-padding col-sm-12">
				<div class="cont">
					<div class="head clearfix">
						<h4 class="pull-left">Tickets without an agent</h4>
						<div class="pull-right search">
							<form method="get" action="<?php echo $base_url; ?>panel/new-tickets" name="search-1">
								<input type="text" name="search" placeholder="Enter search query and press enter" />
							</form>
						</div>
					</div>
					
					<?php
					if($top_counter['no_agent_tickets'] == 0)
						echo 'No tickets';
					else {
					?>
					<table class="table tickets-w-agent">
						<thead>
							<tr>
								<th width="9%" data-sort="<?php echo $base_url . 'panel/tickets/?type=1&sort=1&w=a' ?>"><i class="fa fa-sort"></i>ID</th>
								<th width="25%" data-sort="<?php echo $base_url . 'panel/ticket/?type=1sort=2&w=a' ?>"><i class="fa fa-sort"></i>Title</th>
								<th width="17%" data-sort="<?php echo $base_url . 'panel/ticket/?type=1sort=3&w=a' ?>"><i class="fa fa-sort"></i>Client</th>
								<th width="21%" data-sort="<?php echo $base_url . 'panel/ticket/?type=1sort=4&w=a' ?>"><i class="fa fa-sort"></i>Department</th>
								<th width="28%" data-sort="<?php echo $base_url . 'panel/ticket/?type=1sort=5&w=a' ?>"><i class="fa fa-sort hid"></i><i class="fa fa-sort-down"></i>Last Event</th>
							</tr>
						</thead>
						
						<tbody>
							<?php
							foreach($tickets_no_agent->result() as $row) {
							?>
							<tr data-href="<?php echo $base_url . 'panel/ticket/' . $row->access; ?>">
								<td><?php echo $row->id; ?></td>
								<td><?php echo $row->subject; ?></td>
								<td>
									<?php
									if($row->userid == '0') echo $row->guest_name;
									else{
										$user_info = $users_model->get_user_info($row->userid);
										echo $user_info->name;
									}
									?>
								</td>
								<td>
									<?php echo $tickets_model->get_department_name($row->department); ?>
								</td>
								<td>
									<?php
									if($row->last_update == '0000-00-00 00:00:00')
										echo date('M jS, Y \a\t H:i:s', strtotime($row->date));
									else
										echo date('M jS, Y \a\t H:i:s', strtotime($row->last_update));
									?>
								</td >
							</tr>
							<?php
							}
							?>
						</tbody>
					</table>
					
					<?php
					if($top_counter['no_agent_tickets'] > 9)
						echo '<div class="load-more">Load More...</div>';
					}
					?>
				</div>
			</div>
		</div>
		
		<div class="row padding-fix">
			<div class="col no-bottom-padding col-sm-12">
				<div class="cont">
					<div class="head clearfix">
						<h4 class="pull-left">Tickets awaiting your reply</h4>
						<div class="pull-right search">
							<form method="get" action="<?php echo $base_url; ?>panel/open-tickets" name="search-2">
								<input type="text" name="search" placeholder="Enter search query and press enter" />
							</form>
						</div>
					</div>
					
					<?php
					if($top_counter['pending_tickets'] == 0)
						echo 'No tickets';
					else {
					?>
					<table class="table tickets">
						<thead>
							<tr>
								<th width="5%" data-sort="<?php echo $base_url . 'panel/tickets/?type=2sort=1&w=a' ?>"><i class="fa fa-sort"></i>ID</th>
								<th width="25%" data-sort="<?php echo $base_url . 'panel/tickets/?type=2sort=2&w=a' ?>"><i class="fa fa-sort"></i>Title</th>
								<th width="12%" data-sort="<?php echo $base_url . 'panel/tickets/?type=2sort=3&w=a' ?>"><i class="fa fa-sort hid"></i><i class="fa fa-sort-down"></i>Priority</th>
								<th width="16%" data-sort="<?php echo $base_url . 'panel/tickets/?type=2sort=4&w=a' ?>"><i class="fa fa-sort"></i>Client</th>
								<th width="20%" data-sort="<?php echo $base_url . 'panel/tickets/?type=2sort=5&w=a' ?>"><i class="fa fa-sort"></i>Department</th>
								<th width="22%" data-sort="<?php echo $base_url . 'panel/tickets/?type=2sort=6&w=a' ?>" class="md-hide"><i class="fa fa-sort"></i>Last Event</th>
							</tr>
						</thead>
						
						<tbody>
							<?php
							foreach($tickets_awaiting->result() as $row) {
							?>
							<tr data-href="<?php echo $base_url . 'panel/ticket/' . $row->access; ?>">
								<td><?php echo $row->id; ?></td>
								<td><?php echo $row->subject; ?></td>
								<td>
									<?php
									if($row->priority == '1')
										echo '<div class="badge red">HIGH</div>';
									elseif($row->priority == '2')
										echo '<div class="badge yellow">MEDIUM</div>';
									else
										echo '<div class="badge green">LOW</div>';
									?>
								</td>
								<td>
									<?php
									if($row->userid == '0') echo $row->guest_name;
									else{
										$user_info = $users_model->get_user_info($row->userid);
										echo $user_info->name;
									}
									?>
								</td>
								<td>
									<?php echo $tickets_model->get_department_name($row->department); ?>
								</td>
								<td>
									<?php
									if($row->last_update == '0000-00-00 00:00:00')
										echo date('M jS, Y \a\t H:i:s', strtotime($row->date));
									else
										echo date('M jS, Y \a\t H:i:s', strtotime($row->last_update));
									?>
								</td >
							</tr>
							<?php
							}
							?>
						</tbody>
					</table>
					
					<?php
					if($top_counter['pending_tickets'] > 9)
						echo '<div class="load-more">Load More...</div>';
					}
					?>
				</div>
			</div>
		</div>
		
		<div class="row padding-fix">
			<div class="col no-bottom-padding col-sm-12">
				<div class="cont">
					<div class="head clearfix">
						<h4 class="pull-left">Bugs pending to be attended</h4>
						<div class="pull-right search">
							<form method="get" action="<?php echo $base_url; ?>panel/free-bugs" name="search-3">
								<input type="text" name="search" placeholder="Enter search query and press enter" />
							</form>
						</div>
					</div>
					
					<?php
					if($top_counter['pending_bugs'] == 0)
						echo 'No bug reports';
					else {
					?>
					<table class="table bugs">
						<thead>
							<tr>
								<th width="5%" data-sort="<?php echo $base_url . 'panel/bugs/?type=1sort=1&w=a' ?>"><i class="fa fa-sort"></i>ID</th>
								<th width="25%" data-sort="<?php echo $base_url . 'panel/bugs/?type=1sort=2&w=a' ?>"><i class="fa fa-sort"></i>Title</th>
								<th width="12%" data-sort="<?php echo $base_url . 'panel/bugs/?type=1sort=3&w=a' ?>"><i class="fa fa-sort hid"></i><i class="fa fa-sort-down"></i>Priority</th>
								<th width="16%" data-sort="<?php echo $base_url . 'panel/bugs/?type=1sort=4&w=a' ?>"><i class="fa fa-sort"></i>Client</th>
								<th width="20%" data-sort="<?php echo $base_url . 'panel/bugs/?type=1sort=5&w=a' ?>"><i class="fa fa-sort"></i>Department</th>
								<th width="22%" data-sort="<?php echo $base_url . 'panel/bugs/?type=1sort=6&w=a' ?>" class="md-hide"><i class="fa fa-sort"></i>Created On</th>
							</tr>
						</thead>
						
						<tbody>
							<?php
							foreach($pending_bugs->result() as $row) {
							?>
							<tr data-href="<?php echo $base_url . 'panel/bug/' . $row->access; ?>">
								<td><?php echo $row->id; ?></td>
								<td><?php echo $row->subject; ?></td>
								<td>
									<?php
									if($row->priority == '1')
										echo '<div class="badge red">HIGH</div>';
									elseif($row->priority == '2')
										echo '<div class="badge yellow">MEDIUM</div>';
									else
										echo '<div class="badge green">LOW</div>';
									?>
								</td>
								<td>
									<?php
									if($row->userid == '0') echo $row->guest_name;
									else{
										$user_info = $users_model->get_user_info($row->userid);
										echo $user_info->name;
									}
									?>
								</td>
								<td>
									<?php echo $bugs_model->get_department_name($row->department); ?>
								</td>
								<td>
									<?php echo date('M jS, Y \a\t H:i:s', strtotime($row->date)); ?>
								</td>
							</tr>
							<?php
							}
							?>
						</tbody>
					</table>
					
					<?php
					if($top_counter['pending_bugs'] > 9)
						echo '<div class="load-more">Load More...</div>';
					}
					?>
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
		});
	</script>
</body>
</html>