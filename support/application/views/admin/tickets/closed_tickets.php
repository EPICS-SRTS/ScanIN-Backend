<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
	<div class="content">
		<div class="page-title-cont clearfix">
			<h3>Closed Tickets</h3>
		</div>
		
		<div class="row">
			<div class="col margin-top col-sm-12">
				<div class="cont clearfix">
					<div class="head clearfix">
						<h4 class="pull-left">Tickets that have been solved</h4>
						<div class="pull-right search">
							<form method="get" action="" name="search-1">
								<input type="text" name="search" placeholder="Enter search query and press enter" <?php if(isset($_GET['search'])) echo 'value="'.$_GET['search'].'"'; ?>/>
							</form>
						</div>
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
									'width' => '5%',
									'title' => 'ID'
								),
								array(
									'c' => 2,
									'width' => '25%',
									'title' => 'TItle'
								),
								array(
									'c' => 3,
									'width' => '12%',
									'title' => 'Priority'
								),
								array(
									'c' => 4,
									'width' => '16%',
									'title' => 'Client'
								),
								array(
									'c' => 5,
									'width' => '20%',
									'title' => 'Department'
								),
								array(
									'c' => 6,
									'width' => '22%',
									'title' => 'Last Event'
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
									echo '<th width="'.$sorted['width'].'" data-sort="'.$base_url . 'panel/admin/closed-tickets/?sort='.$sorted['c'].'&w='.$direction.$s.'">';
									echo '<i class="fa fa-sort hid"></i>'.$arrow.$sorted['title'];
									echo '</th>';
								}else{
									echo '<th width="'.$sorted['width'].'" data-sort="'.$base_url . 'panel/admin/closed-tickets/?sort='.$sorted['c'].'&w=d'.$s.'">';
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
									<?php echo $row->client_final_name; ?>
								</td>
								<td>
									<?php echo $row->department_name; ?>
								</td>
								<td>
									<?php
									if($row->last_update == '0000-00-00 00:00:00')
										echo date('M jS, Y \a\t H:i:s', strtotime($row->date));
									else
										echo date('M jS, Y \a\t H:i:s', strtotime($row->last_update));
									?>
								</td>
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
								echo '<a href="' . $base_url . 'panel/admin/closed-tickets/?page=' . ($page-1) . '&search='.$_GET['search'].$srt.'" class="prev"><i class="fa fa-caret-left"></i></a>';
							else
								echo '<a href="' . $base_url . 'panel/admin/closed-tickets/?page=' . ($page-1) . $srt .'" class="prev"><i class="fa fa-caret-left"></i></a>';
						}
						if($total_pages > $page) {
							if(isset($_GET['search']))
								echo '<a href="' . $base_url . 'panel/admin/closed-tickets/?page=' . ($page+1) . '&search='.$_GET['search'].$srt.'" class="next"><i class="fa fa-caret-right"></i></a>';
							else
								echo '<a href="' . $base_url . 'panel/admin/closed-tickets/?page=' . ($page+1) . $srt .'" class="next"><i class="fa fa-caret-right"></i></a>';
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