<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
	<div class="content">
		<div class="page-title-cont clearfix">
			<h3>Bugs Departments</h3>
		</div>
		
		<div class="row">
			<div class="col margin-top col-sm-12">
				<div class="cont clearfix">
					<div class="head clearfix">
						<h4 class="pull-left">Departments</h4>
						<div class="pull-right">
							<div class="clearfix">
								<div class="pull-right search">
									<form method="get" action="" name="search-1">
										<input type="text" name="search" placeholder="Enter search query and press enter" <?php if(isset($_GET['search'])) echo 'value="'.$_GET['search'].'"'; ?>/>
									</form>
								</div>
								<div class="pull-right">
									<button class="btn btn-green besides-search" name="drop" data-drop="new-department">New Department</button>
								</div>
							</div>
							
							<div class="dropdwn clearfix" name="dropdwn-new-department">
								<form method="post" action="<?php echo $base_url; ?>panel/admin/bug-departments/new-department" name="new-department">
									<label id="department">Create department</label>
									<input type="text" name="department" placeholder="Department name" />
									<input type="submit" class="btn btn-blue pull-right" name="send" value="Create" />
								</form>
							</div>
						</div>
					</div>
					
					<?php
					if($all_departments_count == 0)
						echo 'No departments';
					else{
					?>
					<table class="table t-departments">
						<thead>
							<tr>
							<?php
							$sorting = array(
								array(
									'c' => 1,
									'width' => '7%',
									'title' => 'ID'
								),
								array(
									'c' => 2,
									'width' => '19%',
									'title' => 'Department'
								),
								array(
									'c' => 3,
									'width' => '15%',
									'title' => 'Responsible Agents'
								),
								array(
									'c' => 4,
									'width' => '13%',
									'title' => 'Reports'
								),
								array(
									'c' => 5,
									'width' => '21%',
									'title' => 'Created On'
								),
								array(
									'c' => 6,
									'width' => '13%',
									'title' => 'Default'
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
									echo '<th width="'.$sorted['width'].'" data-sort="'.$base_url . 'panel/admin/bug-departments/?sort='.$sorted['c'].'&w='.$direction.$s.'">';
									echo '<i class="fa fa-sort hid"></i>'.$arrow.$sorted['title'];
									echo '</th>';
								}else{
									echo '<th width="'.$sorted['width'].'" data-sort="'.$base_url . 'panel/admin/bug-departments/?sort='.$sorted['c'].'&w=d'.$s.'">';
									echo '<i class="fa fa-sort"></i>'.$sorted['title'];
									echo '</th>';
								}
							}
							?>
								<th width="12%">Actions</th>
							</tr>
						</thead>
						
						<tbody>
							<?php
							$self = $base_url . 'panel/admin/';
							foreach($all_departments->result() as $row) {
							?>
							<tr data-href="<?php echo $base_url . 'panel/admin/bug-department/' . $row->id; ?>">
								<td><?php echo $row->id; ?></td>
								<td><?php echo $row->name; ?></td>
								<td><?php echo $row->agents; ?></td>
								<td><?php echo $row->reports; ?></td>
								<td><?php echo date('M jS, Y \a\t H:i:s', strtotime($row->date)); ?></td>
								<td>
									<?php
									if($row->default == '1')
										echo '<i class="fa fa-check" style="color:#c0c0c0;"></i>';
									?>
								</td>
								<td>
									<?php
									// Set as default
									if($row->default == '2')
										echo '<a href="'.$self . 'bug-department/' . $row->id . '/delete'.'" title="Delete department" name="delete-department"><i class="fa fa-close"></i></a>';
									?>
									<a href="<?php echo $self . 'bug-department/' . $row->id . '/edit'; ?>" title="Edit department"><i class="fa fa-pencil"></i></a>
									<?php
									if($row->default == '2')
										echo '<a href="'.$self.'bug-department/'.$row->id.'/default" title="Set department as default"><i class="fa fa-anchor"></i></a>';
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
								echo '<a href="' . $base_url . 'panel/admin/bug-departments/?page=' . ($page-1) . '&search='.$_GET['search'].$srt.'" class="prev"><i class="fa fa-caret-left"></i></a>';
							else
								echo '<a href="' . $base_url . 'panel/admin/bug-departments/?page=' . ($page-1) . $srt .'" class="prev"><i class="fa fa-caret-left"></i></a>';
						}
						if($total_pages > $page) {
							if(isset($_GET['search']))
								echo '<a href="' . $base_url . 'panel/admin/bug-departments/?page=' . ($page+1) . '&search='.$_GET['search'].$srt.'" class="next"><i class="fa fa-caret-right"></i></a>';
							else
								echo '<a href="' . $base_url . 'panel/admin/bug-departments/?page=' . ($page+1) . $srt .'" class="next"><i class="fa fa-caret-right"></i></a>';
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
			
			$('form[name=new-department]').submit(function(evt) {
				var inpt = $(this).children('input[name=department]');
				
				if(inpt.val() == '') {
					evt.preventDefault();
					$(inpt).addClass('error');
				}
			});
			
			$('button[name=drop]').click(function(evt) {
				evt.preventDefault();
				var to = $(this).data('drop');
				
				$('.dropdwn[name=dropdwn-'+to+']').slideToggle(300);
			});
			
			$('a[name=delete-department]').click(function(evt) {
				var c = confirm("Are you sure you want to delete this department? All this department's bug reports will be deleted!");
				if(c == false) {
					evt.preventDefault();
					return false;
				}
			});
		});
	</script>
</body>
</html>