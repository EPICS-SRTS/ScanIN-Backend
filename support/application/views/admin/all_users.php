<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
	<div class="content">
		<div class="page-title-cont clearfix">
			<h3>All Users</h3>
		</div>
		
		<div class="row">
			<div class="col margin-top col-sm-12">
				<div class="cont clearfix">
					<div class="head clearfix">
						<h4 class="pull-left">List of all users</h4>
						
						<div class="pull-right">
							<div class="clearfix">
								<div class="pull-right search">
									<form method="get" action="" name="search-1">
										<input type="text" name="search" placeholder="Enter search query and press enter" <?php if(isset($_GET['search'])) echo 'value="'.$_GET['search'].'"'; ?>/>
									</form>
								</div>
								<div class="pull-right">
									<button class="btn btn-green besides-search" name="drop" data-drop="new-user">New User</button>
								</div>
							</div><?php
							if($username_error || $email_error)
								echo '<div class="dropdwn clearfix" name="dropdwn-new-user" style="display:block;">';
							else
								echo '<div class="dropdwn clearfix" name="dropdwn-new-user">';
							?><?php
							if($username_error)
								echo '<p class="bg-danger" style="margin-top:-5px;">This username already exists</p>';
							elseif($email_error)
								echo '<p class="bg-danger" style="margin-top:-5px;">This email already exists</p>';
							else
								echo '<p class="bg-danger" style="margin-top:-5px; display:none"></p>';
							?>
								
								<form method="post" action="<?php echo $base_url; ?>panel/admin/new-user" name="new-user">
									<label id="username">Name</label>
									<input type="text" name="user-name" placeholder="User name" value="<?php echo $_POST['user-name']; ?>" />
									<br /><br />
									
									<label id="user-username">Username</label>
									<?php
									if($username_error)
										echo '<input type="text" name="user-username" placeholder="User username" class="error" value="'.$_POST['user-username'].'" />';
									else
										echo '<input type="text" name="user-username" placeholder="User username" value="'.$_POST['user-username'].'" />';
									?>
									<br /><br />
									
									<label id="user-email">Email</label>
									<?php
									if($email_error)
										echo '<input type="text" name="user-email" placeholder="User email" class="error" value="'.$_POST['user-email'].'" />';
									else
										echo '<input type="text" name="user-email" placeholder="User email" value="'.$_POST['user-email'].'" />';
									?>
									<br /><br />
									
									<label id="user-password">Password</label>
									<input type="password" name="user-password" placeholder="User password" />
									<br /><br />
									
									<label id="user-rpassword">Repeat password</label>
									<input type="password" name="user-rpassword" placeholder="Repeat password" />
									<br /><br />
									
									<label id="user-role">Role</label>
									<div class="radio">
										<input type="radio" name="user-role" id="user-role1" class="blue" value="1"<?php if($_POST['user-role'] == '1') echo ' checked'; ?> />
										<label for="user-role1">Client</label>
									</div>
									<div class="radio">
										<input type="radio" name="user-role" id="user-role2" class="blue" value="2"<?php if($_POST['user-role'] == '2') echo ' checked'; ?> />
										<label for="user-role2">Agent</label>
									</div>
									<div class="radio">
										<input type="radio" name="user-role" id="user-role3" class="blue" value="3"<?php if($_POST['user-role'] == '3') echo ' checked'; ?> />
										<label for="user-role3">Admin</label>
									</div>
									
									<input type="hidden" name="from" value="all-users" />
									
									<input type="submit" class="btn btn-blue pull-right" name="send" value="Create User" />
								</form>
							</div>
						</div>
					</div>
					
					<table class="table t-departments">
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
									'width' => '20%',
									'title' => 'Name'
								),
								array(
									'c' => 3,
									'width' => '20%',
									'title' => 'Username'
								),
								array(
									'c' => 4,
									'width' => '15%',
									'title' => 'Role'
								),
								array(
									'c' => 5,
									'width' => '20%',
									'title' => 'Member Since'
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
									echo '<th width="'.$sorted['width'].'" data-sort="'.$base_url . 'panel/admin/all-users/?sort='.$sorted['c'].'&w='.$direction.$s.'">';
									echo '<i class="fa fa-sort hid"></i>'.$arrow.$sorted['title'];
									echo '</th>';
								}else{
									echo '<th width="'.$sorted['width'].'" data-sort="'.$base_url . 'panel/admin/all-users/?sort='.$sorted['c'].'&w=d'.$s.'">';
									echo '<i class="fa fa-sort"></i>'.$sorted['title'];
									echo '</th>';
								}
							}
							?>
								<th width="15%">Actions</th>
							</tr>
						</thead>
						
						<tbody>
							<?php
							foreach($all_users->result() as $row) {
							?>
							<tr data-href="<?php echo $base_url . 'panel/admin/user/' . $row->id; ?>">
								<td><?php echo $row->id; ?></td>
								<?php
								if($row->id == $user_info->id)
									echo '<td><strong>'.$row->name.' (you)</strong></td>';
								else
									echo '<td>'.$row->name.'</td>';
								?>
								<td><?php echo $row->username; ?></td>
								<td>
									<?php
									if($row->role == '1')
										echo 'Client';
									elseif($row->role == '2')
										echo 'Agent';
									else
										echo 'Admin';
									?>
								</td>
								<td>
									<?php echo date('M jS, Y \a\t H:i:s', strtotime($row->date)); ?>
								</td>
								<td>
									<?php
									if($row->id != $user_info->id) {
										if($row->role == '1')
											echo '<a href="'.$base_url.'panel/admin/user/'.$row->id.'/delete" title="Delete user" name="delete-client"><i class="fa fa-close"></i></a>';
										elseif($row->role == '2')
											echo '<a href="'.$base_url.'panel/admin/user/'.$row->id.'/delete" title="Delete user" name="delete-agent"><i class="fa fa-close"></i></a>';
										else
											echo '<a href="'.$base_url.'panel/admin/user/'.$row->id.'/delete" title="Delete user" name="delete-admin"><i class="fa fa-close"></i></a>';
									}
									?>
									<a href="<?php echo $base_url; ?>panel/admin/user/<?php echo $row->id; ?>/edit" title="Edit user" name="edit-user"><i class="fa fa-pencil"></i></a>
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
								echo '<a href="' . $base_url . 'panel/admin/all-users/?page=' . ($page-1) . '&search='.$_GET['search'].$srt.'" class="prev"><i class="fa fa-caret-left"></i></a>';
							else
								echo '<a href="' . $base_url . 'panel/admin/all-users/?page=' . ($page-1) . $srt .'" class="prev"><i class="fa fa-caret-left"></i></a>';
						}
						if($total_pages > $page) {
							if(isset($_GET['search']))
								echo '<a href="' . $base_url . 'panel/admin/all-users/?page=' . ($page+1) . '&search='.$_GET['search'].$srt.'" class="next"><i class="fa fa-caret-right"></i></a>';
							else
								echo '<a href="' . $base_url . 'panel/admin/all-users/?page=' . ($page+1) . $srt .'" class="next"><i class="fa fa-caret-right"></i></a>';
						}
						?>
					</div>
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
			
			$('a[name=delete-client]').click(function(evt) {
				var c = confirm("Are you sure you want to delete this client? ALL his tickets/bug reports will be deleted!");
				if(c == false) {
					evt.preventDefault();
					return false;
				}
			});
			
			$('a[name=delete-agent]').click(function(evt) {
				var c = confirm("Are you sure you want to delete this agent? ALL the tickets/bug reports he's responsible of will change to a 'without agent' status!");
				if(c == false) {
					evt.preventDefault();
					return false;
				}
			});
			
			$('a[name=delete-admin]').click(function(evt) {
				var c = confirm("Are you sure you want to delete this admin? ALL the tickets/bug reports he's responsible of will change to a 'without agent' status!");
				if(c == false) {
					evt.preventDefault();
					return false;
				}
			});
			
			$('form[name=new-user]').submit(function(evt) {
				var name = $('input[name=user-name]').val();
				var username = $('input[name=user-username]').val();
				var email = $('input[name=user-email]').val();
				var password = $('input[name=user-password]').val();
				var rpassword = $('input[name=user-rpassword]').val();
				
				if(name == '') {
					evt.preventDefault();
					error('Please insert the user\'s name', '[name=user-name]');
					return false;
				}
				if(name.length < 5) {
					evt.preventDefault();
					error('User\'s name must be at least 5 characters long', '[name=user-name]');
					return false;
				}
				if(username == '') {
					evt.preventDefault();
					error('Please insert the user\'s username', '[name=user-username]');
					return false;
				}
				if(/\s/.test(username)) {
					evt.preventDefault();
					error('The user\'s username cannot contain spaces', '[name=user-username]');
					return false;
				}
				if(username.length < 5) {
					evt.preventDefault();
					error('The user\'s username must be at least 5 characters long', '[name=user-username]');
					return false;
				}
				if(email == '') {
					evt.preventDefault();
					error('Please insert the user\'s email address', '[name=user-email]');
					return false;
				}
				if(validateEmail(email) == false) {
					evt.preventDefault();
					error('Please insert a valid email address', '[name=user-email]');
					return false;
				}
				if(password == '') {
					evt.preventDefault();
					error('Please insert a password', '[name=user-password]');
					return false;
				}
				if(/\s/.test(password)) {
					evt.preventDefault();
					error('The user\'s password cannot contain spaces', '[name=user-password]');
					return false;
				}
				if(password.length < 5) {
					evt.preventDefault();
					error('Password must be at least 5 characters long', '[name=user-password]');
					return false;
				}
				if(rpassword == '') {
					evt.preventDefault();
					error('Please insert the user\'s password again', '[name=user-rpassword]');
					return false;
				}
				if(password != rpassword){
					evt.preventDefault();
					error('Both password must match', '[name=user-password]', '[name=user-rpassword]');
					return false;
				}
			});
			
			$('button[name=drop]').click(function(evt) {
				evt.preventDefault();
				var to = $(this).data('drop');
				
				$('.dropdwn[name=dropdwn-'+to+']').slideToggle(300);
			});
			
			var e_active = false;
			function error(e, n) {
				if(e_active != false) {
					$(e_active).removeClass('error');
				}
				
				$(n).addClass('error');
				e_active = n;
				
				$('p.bg-danger').slideUp(200, function() {
					$('p.bg-danger').html(e).slideDown(200);
				});
			}
			
			function validateEmail(email) {
				var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
				return re.test(email);
			}

		});
	</script>
</body>
</html>