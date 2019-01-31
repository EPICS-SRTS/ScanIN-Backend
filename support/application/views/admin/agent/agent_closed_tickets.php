<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
	<div class="content">
		<div class="page-title-cont clearfix">
			<h3>All agent's closed tickets</h3>
		</div>
		
		<div class="row">
			<div class="col margin-top col-sm-12">
				<div class="cont clearfix">
					<div class="head clearfix">
						<h4 class="pull-left">Closed Tickets</h4>
					</div>
					
					<?php
					if($all_tickets_count == 0)
						echo 'No tickets';
					else{
					?>
					<table class="table tickets-w-agent">
						<thead>
							<tr>
							<?php
							$sorting = array(
								array(
									'c' => 1,
									'width' => '10%',
									'title' => 'ID'
								),
								array(
									'c' => 2,
									'width' => '30%',
									'title' => 'Title'
								),
								array(
									'c' => 3,
									'width' => '30%',
									'title' => 'Department'
								),
								array(
									'c' => 4,
									'width' => '30%',
									'title' => 'Created On'
								)
							);
							
							foreach($sorting as $sorted) {
								// If is set a search...
								if(isset($_GET['search']) && $_GET['search'] != '')
									$s = '&search='.$_GET['search'];
								else
									$s = '';
									
								if($sort == $sorted['c']) {
									if($sort_direction == 'DESC') {
										$arrow = '<i class="fa fa-sort-down"></i>';
										$direction = 'a';
									}else{
										$arrow = '<i class="fa fa-sort-up"></i>';
										$direction = 'd';
									}
									echo '<th width="'.$sorted['width'].'" data-sort="'.$base_url . 'panel/admin/user/'.$current_user_info->id.'/closed-tickets/?sort='.$sorted['c'].'&w='.$direction.$s.'">';
									echo '<i class="fa fa-sort hid"></i>'.$arrow.$sorted['title'];
									echo '</th>';
								}else{
									echo '<th width="'.$sorted['width'].'" data-sort="'.$base_url . 'panel/admin/user/'.$current_user_info->id.'/closed-tickets/?sort='.$sorted['c'].'&w=d'.$s.'">';
									echo '<i class="fa fa-sort"></i>'.$sorted['title'];
									echo '</th>';
								}
							}
							?>
							</tr>
						</thead>
						
						<tbody>
							<?php
							foreach($all_tickets->result() as $row) {
							?>
							<tr data-href="<?php echo $base_url . 'panel/ticket/' . $row->access; ?>">
								<td><?php echo $row->id; ?></td>
								<td><?php echo $row->subject; ?></td>
								<td><?php echo $row->department_name; ?></td>
								<td><?php echo date('M jS, Y \a\t H:i:s', strtotime($row->date)); ?></td>
							</tr>
							<?php
							}
							?>
						</tbody>
					</table>
					
					<div id="pagination">
						<?php
						// If is set a sort..
						if(isset($_GET['sort']) && $_GET['sort'] != '' && isset($_GET['w']) && $_GET['w'] != '')
							$srt = '&sort='.$_GET['sort'].'&w='.$_GET['w'];
						else
							$srt = '';
						
						if($page > 1) {
							if(isset($_GET['search']))
								echo '<a href="' . $base_url . 'panel/admin/user/'.$current_user_info->id.'/closed-tickets/?page=' . ($page-1) . '&search='.$_GET['search'].$srt.'" class="prev"><i class="fa fa-caret-left"></i></a>';
							else
								echo '<a href="' . $base_url . 'panel/admin/user/'.$current_user_info->id.'/closed-tickets/?page=' . ($page-1) . $srt .'" class="prev"><i class="fa fa-caret-left"></i></a>';
						}
						if($total_pages > $page) {
							if(isset($_GET['search']))
								echo '<a href="' . $base_url . 'panel/admin/user/'.$current_user_info->id.'/closed-tickets/?page=' . ($page+1) . '&search='.$_GET['search'].$srt.'" class="next"><i class="fa fa-caret-right"></i></a>';
							else
								echo '<a href="' . $base_url . 'panel/admin/user/'.$current_user_info->id.'/closed-tickets/?page=' . ($page+1) . $srt .'" class="next"><i class="fa fa-caret-right"></i></a>';
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