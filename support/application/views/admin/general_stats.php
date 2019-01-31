<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
	<div class="content">
		<div class="page-title-cont clearfix">
			<h3>General System Statistics</h3>
		</div>
		
		<div class="row dash-stats">
			<div class="cl col-xs-6 col-sm-4 col-lg-2">
				<div class="box red">
					<span class="big"><?php echo $top_counter['pending_bugs']; ?></span>
					<span class="down">bugs pending to be solved</span>
				</div>
			</div>
			<div class="cl col-xs-6 col-sm-4 col-lg-2">
				<div class="box yellow">
					<span class="big"><?php echo $top_counter['pending_tickets']; ?></span>
					<span class="down">tickets awaiting agent's response</span>
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
						<div class="pull-left chart-labels">
							<ul class="labels">
								<li class="label-orange">Submitted Tickets</li>
								<li class="label-green">Solved Tickets</li>
							</ul>
						</div>
						<div class="pull-right chart_interval">
							<div class="dropdown">
								<button class="btn btn-bright-blue btn-dropdown dropdown-toggle" type="button" id="chartInterval" data-toggle="dropdown" aria-expanded="true">
									LAST 7 DAYS
									<span class="caret"></span>
								</button>
								<ul class="dropdown-menu" data-parentgraph="first_graph" role="menu" aria-labelledby="chartInterval">
									<li role="presentation" data-graph="last-7-days" data-selected="1"><a role="menuitem" tabindex="-1" href="#">LAST 7 DAYS</a></li>
									<li role="presentation" data-graph="this-month" data-selected="0"><a role="menuitem" tabindex="-1" href="#">THIS MONTH</a></li>
									<li role="presentation" data-graph="this-year" data-selected="0"><a role="menuitem" tabindex="-1" href="#">THIS YEAR</a></li>
									<li role="presentation" data-graph="last-5-years" data-selected="0"><a role="menuitem" tabindex="-1" href="#">LAST 5 YEARS</a></li>
								</ul>
							</div>
						</div>
					</div>
					
					<div class="graphs-container" id="first_graph" style="width:100%; height:320px; overflow:hidden;">
						<div class="last-7-days" style="width:100%; height:320px; overflow:hidden;">
							<div class="graph" style="width:100%; height:320px;"></div>
						</div>
						<div class="this-month" style="width:100%; height:320px; overflow:hidden;">
							<div class="graph" style="width:100%; height:320px;"></div>
						</div>
						<div class="this-year" style="width:100%; height:320px; overflow:hidden;">
							<div class="graph" style="width:100%; height:320px;"></div>
						</div>
						<div class="last-5-years" style="width:100%; height:320px; overflow:hidden;">
							<div class="graph" style="width:100%; height:320px;"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col no-bottom-padding col-sm-12">
				<div class="cont">
					<div class="head clearfix">
						<div class="pull-left chart-labels">
							<ul class="labels">
								<li class="label-blue">Reported Bugs</li>
								<li class="label-green">Solved Bugs</li>
							</ul>
						</div>
						<div class="pull-right chart_interval">
							<div class="dropdown">
								<button class="btn btn-bright-blue btn-dropdown dropdown-toggle" type="button" id="chartInterval" data-toggle="dropdown" aria-expanded="true">
									LAST 7 DAYS
									<span class="caret"></span>
								</button>
								<ul class="dropdown-menu" data-parentgraph="second_graph" role="menu" aria-labelledby="chartInterval">
									<li role="presentation" data-graph="last-7-days" data-selected="1"><a role="menuitem" tabindex="-1" href="#">LAST 7 DAYS</a></li>
									<li role="presentation" data-graph="this-month" data-selected="0"><a role="menuitem" tabindex="-1" href="#">THIS MONTH</a></li>
									<li role="presentation" data-graph="this-year" data-selected="0"><a role="menuitem" tabindex="-1" href="#">THIS YEAR</a></li>
									<li role="presentation" data-graph="last-5-years" data-selected="0"><a role="menuitem" tabindex="-1" href="#">LAST 5 YEARS</a></li>
								</ul>
							</div>
						</div>
					</div>
					
					<div class="graphs-container" id="second_graph" style="width:100%; height:320px; overflow:hidden;">
						<div class="last-7-days" style="width:100%; height:320px; overflow:hidden;">
							<div class="graph" style="width:100%; height:320px;"></div>
						</div>
						<div class="this-month" style="width:100%; height:320px; overflow:hidden;">
							<div class="graph" style="width:100%; height:320px;"></div>
						</div>
						<div class="this-year" style="width:100%; height:320px; overflow:hidden;">
							<div class="graph" style="width:100%; height:320px;"></div>
						</div>
						<div class="last-5-years" style="width:100%; height:320px; overflow:hidden;">
							<div class="graph" style="width:100%; height:320px;"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<div class="row padding-fix customer-satisfaction text-center">
			<div class="col no-bottom-padding col-sm-12">
				<div class="cont">
					<h1>Customers' Satisfaction</h1>
					
					<div class="big-stars">
						<?php
						$general_satisfaction = $top_counter['customer_satisfaction'];
						
						// Get nearest integer, which is the number of full stars
						$full_stars = (int)floor($general_satisfaction);
						
						// Remove integer from the original number and convert to integer
						// This will be the percentage of fill of the last "semi-full" star
						$percentage = (int)(($general_satisfaction - $full_stars)*100);
						
						// Number of stars we need to have.
						$all_stars = 5;
						
						// Put full stars first
						for($i = 1; $i <= $full_stars; $i++) {
							echo ' <div class="star full-star"><i class="fa fa-star"></i></div>';
							$all_stars -= 1;
						}
						
						// Put semi-full star (if we need to)
						if($full_stars < 5) {
							echo ' <div class="star divided-star">';
							echo '<div class="piece-left" style="width:'. $percentage . '%">';
							echo '<i class="fa fa-star"></i>';
							echo '</div>';
							echo '<div class="piece-right"><i class="fa fa-star"></i></div>';
							echo '</div>';
							$all_stars -= 1;
						}
						
						// Rest of stars
						for($i = $all_stars; $i > 0; $i--) {
							echo ' <div class="star full-gray-star"><i class="fa fa-star"></i></div>';
						}
						?>
					</div>
					
					<h1 class="light"><?php echo $general_satisfaction; ?>/5</h1>
				</div>
			</div>
		</div>
		
		<div class="row padding-fix top-rated-agents text-center">
			<div class="col no-bottom-padding col-sm-12">
				<div class="cont">
					<h1>Top-Rated Agents</h1><br />
					
					<div class="row padding-fix text-center">
						<?php
						$agents = 0;
						foreach($top_agents->result() as $agent) {
							if($agent->rating != '') {
						?>
						<div class="col top-agent no-bottom-padding col-xs-6 col-sm-3">
							<?php
								echo '<img src="'.asset_url().'img/profile_img/'.$agent->profile_img1x.'" srcset="'.asset_url().'img/profile_img/'.$agent->profile_img1x.' 1x, '.asset_url().'img/profile_img/'.$agent->profile_img2x.' 2x, '.asset_url().'img/profile_img/'.$agent->profile_img3x.' 3x" />';
							?>
							
							<span class="name"><?php echo $agent->name; ?></span>
							<span class="username"><?php echo $agent->username; ?></span>
							<div class="normal-stars">
								<?php
								$general_satisfaction = $agent->rating;
								$full_stars = (int)floor($general_satisfaction);
								$percentage = (int)(($general_satisfaction - $full_stars)*100);
								$all_stars = 5;
								for($i = 1; $i <= $full_stars; $i++) {
									echo ' <div class="star full-star"><i class="fa fa-star"></i></div>';
									$all_stars -= 1;
								}
								if($full_stars < 5) {
									echo ' <div class="star divided-star">';
									echo '<div class="piece-left" style="width:'. $percentage . '%">';
									echo '<i class="fa fa-star"></i>';
									echo '</div>';
									echo '<div class="piece-right"><i class="fa fa-star"></i></div>';
									echo '</div>';
									$all_stars -= 1;
								}
								for($i = $all_stars; $i > 0; $i--) {
									echo ' <div class="star full-gray-star"><i class="fa fa-star"></i></div>';
								}
								?>
							</div>
							<span class="rate"><?php echo $agent->rating; ?>/5</span>
						</div>
						<?php
							$agents += 1;
							}
						}
						
						for($i = $agents; $i < 4; $i++) {
						?>
							<div class="col top-agent no-agent no-bottom-padding col-xs-6 col-sm-3">
								<div class="no-agent-cont">
									NO MORE AGENTS
								</div>
							</div>
						<?php
						}
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div id="tooltip"></div>
	
	<script src="<?php echo asset_url(); ?>js/jquery-1.11.3.min.js"></script>
	<script src="<?php echo asset_url(); ?>js/tickerr_plot.js"></script>
	<script src="<?php echo asset_url(); ?>js/tickerr_core.js"></script>
	<script src="<?php echo asset_url(); ?>bootstrap/js/bootstrap.min.js"></script>
	<script src="<?php echo asset_url(); ?>js/flot/jquery.flot.min.js"></script>
	<script type="text/javascript">
		$('document').ready(function() {
			enable_dropdown('.dropdown-toggle');
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

			<?php
			$labels_arr = array();
			$responsive_labels_arr = array();
			foreach($first_graph_1['dates'] as $dates) {
				$labels_arr[] = "'{$dates[0]}'";
				$responsive_labels_arr[] = "'{$dates[1]}'";
			}
			$labels_arr[] = "''";
			$responsive_labels_arr[] = "''";
			echo 'var labels=[' . implode($labels_arr, ',') . '];';
			echo "\r\n			";
			echo 'var responsiveLabels=[' . implode($responsive_labels_arr, ',') . '];';
			echo "\r\n";
			
			$c = 0; $data1 = array(); $data2 = array();
			while($c < count($first_graph_1['submitted_tickets'])) {
				$data1[] = "[{$c},{$first_graph_1['solved_tickets'][$c]}]";
				$data2[] = "[{$c},{$first_graph_1['submitted_tickets'][$c]}]";
				$c += 1;
			}
			$c -= 1;
			$data1[] = "[$c.2,]";
			$data2[] = "[$c.2,]";
			$data1_str = '[' . implode($data1, ',') . ']';
			$data2_str = '[' . implode($data2, ',') . ']';
			?>
			var blabels = ['Solved tickets','Submitted tickets'];
			var data = [
				{
					data: <?php echo $data1_str; ?>,
					color:'#40e281', backgroundColor:'#40e281',
					lines: { fill:true }
				},{
					data: <?php echo $data2_str; ?>,
					color:'#ea9c21', backgroundColor:'#69bcf6',
					lines: { fill:true }
				}
			];
			var plot1 = new TickerrPlot();
			plot1.setLabels(labels);
			plot1.setResponsiveLabels(responsiveLabels);
			plot1.setBLabels(blabels);
			plot1.setData(data);
			plot1.createPlot('.graphs-container#first_graph .last-7-days .graph');
			plot1.bindHover();
			
			
			<?php
			$labels_arr = array();
			$responsive_labels_arr = array();
			foreach($first_graph_2['dates'] as $dates) {
				$labels_arr[] = "'{$dates[0]}'";
				$responsive_labels_arr[] = "'{$dates[1]}'";
			}
			$labels_arr[] = "''";
			$responsive_labels_arr[] = "''";
			echo 'var labels2=[' . implode($labels_arr, ',') . '];';
			echo "\r\n			";
			echo 'var responsiveLabels2=[' . implode($responsive_labels_arr, ',') . '];';
			echo "\r\n";
			
			$c = 0; $data1 = array(); $data2 = array();
			while($c < count($first_graph_2['submitted_tickets'])) {
				$data1[] = "[{$c},{$first_graph_2['solved_tickets'][$c]}]";
				$data2[] = "[{$c},{$first_graph_2['submitted_tickets'][$c]}]";
				$c += 1;
			}
			$c -= 1;
			$data1[] = "[$c.2,]";
			$data2[] = "[$c.2,]";
			$data1_str = '[' . implode($data1, ',') . ']';
			$data2_str = '[' . implode($data2, ',') . ']';
			?>
			var blabels2 = ['Solved tickets','Submitted tickets'];
			var data2 = [
				{
					data: <?php echo $data1_str; ?>,
					color:'#40e281', backgroundColor:'#e76e4e',
					lines: { fill:true }
				},{
					data: <?php echo $data2_str; ?>,
					color:'#ea9c21', backgroundColor:'#ea9c21',
					lines: { fill:true }
				}
			];
			var plot2 = new TickerrPlot();
			plot2.setLabels(labels2);
			plot2.setResponsiveLabels(responsiveLabels2);
			plot2.setBLabels(blabels2);
			plot2.setData(data2);
			plot2.createPlot('.graphs-container#first_graph .this-month .graph');
			plot2.bindHover();
			
			
			<?php
			$labels_arr = array();
			$responsive_labels_arr = array();
			foreach($first_graph_3['dates'] as $dates) {
				$labels_arr[] = "'{$dates[0]}'";
				$responsive_labels_arr[] = "'{$dates[1]}'";
			}
			$labels_arr[] = "''";
			$responsive_labels_arr[] = "''";
			echo 'var labels3=[' . implode($labels_arr, ',') . '];';
			echo "\r\n			";
			echo 'var responsiveLabels3=[' . implode($responsive_labels_arr, ',') . '];';
			echo "\r\n";
			
			$c = 0; $data1 = array(); $data2 = array();
			while($c < count($first_graph_3['submitted_tickets'])) {
				$data1[] = "[{$c},{$first_graph_3['solved_tickets'][$c]}]";
				$data2[] = "[{$c},{$first_graph_3['submitted_tickets'][$c]}]";
				$c += 1;
			}
			$c -= 1;
			$data1[] = "[$c.2,]";
			$data2[] = "[$c.2,]";
			$data1_str = '[' . implode($data1, ',') . ']';
			$data2_str = '[' . implode($data2, ',') . ']';
			?>
			var blabels3 = ['Solved tickets','Submitted tickets'];
			var data3 = [
				{
					data: <?php echo $data1_str; ?>,
					color:'#40e281', backgroundColor:'#e76e4e',
					lines: { fill:true }
				},{
					data: <?php echo $data2_str; ?>,
					color:'#ea9c21', backgroundColor:'#ea9c21',
					lines: { fill:true }
				}
			];
			var plot3 = new TickerrPlot();
			plot3.setLabels(labels3);
			plot3.setResponsiveLabels(responsiveLabels3);
			plot3.setBLabels(blabels3);
			plot3.setData(data3);
			plot3.createPlot('.graphs-container#first_graph .this-year .graph');
			plot3.bindHover();
			
			
			<?php
			$labels_arr = $first_graph_4['years'];
			$labels_arr[] = "''";
			echo 'var labels4=[' . implode($labels_arr, ',') . '];';
			echo "\r\n			";
			echo 'var responsiveLabels4=[' . implode($labels_arr, ',') . '];';
			echo "\r\n";
			
			$c = 0; $data1 = array(); $data2 = array();
			while($c < count($first_graph_4['submitted_tickets'])) {
				$data1[] = "[{$c},{$first_graph_4['solved_tickets'][$c]}]";
				$data2[] = "[{$c},{$first_graph_4['submitted_tickets'][$c]}]";
				$c += 1;
			}
			$c -= 1;
			$data1[] = "[$c.2,]";
			$data2[] = "[$c.2,]";
			$data1_str = '[' . implode($data1, ',') . ']';
			$data2_str = '[' . implode($data2, ',') . ']';
			?>
			var blabels4 = ['Solved tickets','Submitted tickets'];
			var data4 = [
				{
					data: <?php echo $data1_str; ?>,
					color:'#40e281', backgroundColor:'#e76e4e',
					lines: { fill:true }
				},{
					data: <?php echo $data2_str; ?>,
					color:'#ea9c21', backgroundColor:'#ea9c21',
					lines: { fill:true }
				}
			];
			var plot4 = new TickerrPlot();
			plot4.setLabels(labels4);
			plot4.setBLabels(blabels4);
			plot4.setData(data4);
			plot4.createPlot('.graphs-container#first_graph .last-5-years .graph');
			plot4.bindHover();
			
			
			<?php
			$labels_arr = array();
			$responsive_labels_arr = array();
			foreach($second_graph_1['dates'] as $dates) {
				$labels_arr[] = "'{$dates[0]}'";
				$responsive_labels_arr[] = "'{$dates[1]}'";
			}
			$labels_arr[] = "''";
			$responsive_labels_arr[] = "''";
			echo 'var labels5=[' . implode($labels_arr, ',') . '];';
			echo "\r\n			";
			echo 'var responsiveLabels5=[' . implode($responsive_labels_arr, ',') . '];';
			echo "\r\n";
			
			$c = 0; $data1 = array(); $data2 = array();
			while($c < count($second_graph_1['reported_bugs'])) {
				$data1[] = "[{$c},{$second_graph_1['solved_bugs'][$c]}]";
				$data2[] = "[{$c},{$second_graph_1['reported_bugs'][$c]}]";
				$c += 1;
			}
			$c -= 1;
			$data1[] = "[$c.2,]";
			$data2[] = "[$c.2,]";
			$data1_str = '[' . implode($data1, ',') . ']';
			$data2_str = '[' . implode($data2, ',') . ']';
			?>
			var blabels5 = ['Solved Bugs','Reported Bugs'];
			var data5 = [
				{
					data: <?php echo $data1_str; ?>,
					color:'#40e281', backgroundColor:'#40e281',
					lines: { fill:true }
				},{
					data: <?php echo $data2_str; ?>,
					color:'#69bcf6', backgroundColor:'#69bcf6',
					lines: { fill:true }
				}
			];
			var plot5 = new TickerrPlot();
			plot5.setLabels(labels5);
			plot5.setResponsiveLabels(responsiveLabels5);
			plot5.setBLabels(blabels5);
			plot5.setData(data5);
			plot5.createPlot('.graphs-container#second_graph .last-7-days .graph');
			plot5.bindHover();
			
			
			<?php
			$labels_arr = array();
			$responsive_labels_arr = array();
			foreach($second_graph_2['dates'] as $dates) {
				$labels_arr[] = "'{$dates[0]}'";
				$responsive_labels_arr[] = "'{$dates[1]}'";
			}
			$labels_arr[] = "''";
			$responsive_labels_arr[] = "''";
			echo 'var labels6=[' . implode($labels_arr, ',') . '];';
			echo "\r\n			";
			echo 'var responsiveLabels6=[' . implode($responsive_labels_arr, ',') . '];';
			echo "\r\n";
			
			$c = 0; $data1 = array(); $data2 = array();
			while($c < count($second_graph_2['reported_bugs'])) {
				$data1[] = "[{$c},{$second_graph_2['solved_bugs'][$c]}]";
				$data2[] = "[{$c},{$second_graph_2['reported_bugs'][$c]}]";
				$c += 1;
			}
			$c -= 1;
			$data1[] = "[$c.2,]";
			$data2[] = "[$c.2,]";
			$data1_str = '[' . implode($data1, ',') . ']';
			$data2_str = '[' . implode($data2, ',') . ']';
			?>
			var blabels6 = ['Solved Bugs','Reported Bugs'];
			var data6 = [
				{
					data: <?php echo $data1_str; ?>,
					color:'#40e281', backgroundColor:'#e76e4e',
					lines: { fill:true }
				},{
					data: <?php echo $data2_str; ?>,
					color:'#ea9c21', backgroundColor:'#ea9c21',
					lines: { fill:true }
				}
			];
			var plot6 = new TickerrPlot();
			plot6.setLabels(labels6);
			plot6.setResponsiveLabels(responsiveLabels6);
			plot6.setBLabels(blabels6);
			plot6.setData(data6);
			plot6.createPlot('.graphs-container#second_graph .this-month .graph');
			plot6.bindHover();
			
			
			<?php
			$labels_arr = array();
			$responsive_labels_arr = array();
			foreach($second_graph_3['dates'] as $dates) {
				$labels_arr[] = "'{$dates[0]}'";
				$responsive_labels_arr[] = "'{$dates[1]}'";
			}
			$labels_arr[] = "''";
			$responsive_labels_arr[] = "''";
			echo 'var labels7=[' . implode($labels_arr, ',') . '];';
			echo "\r\n			";
			echo 'var responsiveLabels7=[' . implode($responsive_labels_arr, ',') . '];';
			echo "\r\n";
			
			$c = 0; $data1 = array(); $data2 = array();
			while($c < count($second_graph_3['reported_bugs'])) {
				$data1[] = "[{$c},{$second_graph_3['solved_bugs'][$c]}]";
				$data2[] = "[{$c},{$second_graph_3['reported_bugs'][$c]}]";
				$c += 1;
			}
			$c -= 1;
			$data1[] = "[$c.2,]";
			$data2[] = "[$c.2,]";
			$data1_str = '[' . implode($data1, ',') . ']';
			$data2_str = '[' . implode($data2, ',') . ']';
			?>
			var blabels7 = ['Solved Bugs','Reported Bugs'];
			var data7 = [
				{
					data: <?php echo $data1_str; ?>,
					color:'#40e281', backgroundColor:'#e76e4e',
					lines: { fill:true }
				},{
					data: <?php echo $data2_str; ?>,
					color:'#ea9c21', backgroundColor:'#ea9c21',
					lines: { fill:true }
				}
			];
			var plot7 = new TickerrPlot();
			plot7.setLabels(labels7);
			plot7.setResponsiveLabels(responsiveLabels7);
			plot7.setBLabels(blabels7);
			plot7.setData(data7);
			plot7.createPlot('.graphs-container#second_graph .this-year .graph');
			plot7.bindHover();
			
			
			<?php
			$labels_arr = $second_graph_4['years'];
			$labels_arr[] = "''";
			echo 'var labels8=[' . implode($labels_arr, ',') . '];';
			echo "\r\n			";
			echo 'var responsiveLabels8=[' . implode($labels_arr, ',') . '];';
			echo "\r\n";
			
			$c = 0; $data1 = array(); $data2 = array();
			while($c < count($second_graph_4['reported_bugs'])) {
				$data1[] = "[{$c},{$second_graph_4['solved_bugs'][$c]}]";
				$data2[] = "[{$c},{$second_graph_4['reported_bugs'][$c]}]";
				$c += 1;
			}
			$c -= 1;
			$data1[] = "[$c.2,]";
			$data2[] = "[$c.2,]";
			$data1_str = '[' . implode($data1, ',') . ']';
			$data2_str = '[' . implode($data2, ',') . ']';
			?>
			var blabels8 = ['Solved Bugs','Reported Bugs'];
			var data8 = [
				{
					data: <?php echo $data1_str; ?>,
					color:'#40e281', backgroundColor:'#e76e4e',
					lines: { fill:true }
				},{
					data: <?php echo $data2_str; ?>,
					color:'#ea9c21', backgroundColor:'#ea9c21',
					lines: { fill:true }
				}
			];
			var plot8 = new TickerrPlot();
			plot8.setLabels(labels8);
			plot8.setBLabels(blabels8);
			plot8.setData(data8);
			plot8.createPlot('.graphs-container#second_graph .last-5-years .graph');
			plot8.bindHover();
			
			
			$('ul.dropdown-menu li').click(function(evt) {
				evt.preventDefault();
				var parent_graph = $(this).parent().data('parentgraph');
				var graph = $(this).data('graph');
				var selected = $(this).data('selected');
				var val = $(this).children('a').html();
				
				if(selected == 0) {
					$(this).parent().parent().children('button').html(val+"<span class=\"caret\"></span>");
					$(this).parent().children('li').data('selected','0');
					$(this).data('selected','1');
					
					// Hide all graphs
					$('.graphs-container#'+parent_graph+' > div').fadeOut(300, function() {
						$('.graphs-container#'+parent_graph+' .'+graph).fadeIn(300);
					});
				}
			});
		});
	</script>
</body>
</html>