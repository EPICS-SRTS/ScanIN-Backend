<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
	<div class="sidebar-left" id="sidebar">
		<div class="top">
			<span class="big"><?php echo $user_info->name; ?></span>
			<span class="small">
				<?php
				if($user_info->role == '1') echo 'CLIENT';
				elseif($user_info->role == '2') echo 'AGENT';
				else echo 'ADMINISTRATOR';
				?>
			</span>
		</div>
		
		<?php
		// 2=Agent, 3=Admin
		if($user_info->role == '2' || $user_info->role == '3') {
		?>
		<span class="nav-title">AGENT DASHBOARD (MY ACCOUNT)</span>
		<ul class="navigation">
			<li<?php if($current_page == 1) echo ' class="active"'; ?>>
				<a href="<?php echo $base_url . 'panel/' ?>">
					<i class="fa fa-home"></i>Dashboard
				</a>
			</li>
			<li>
				<a href="javascript:;">
					<i class="fa fa-list"></i>Tickets
					<span class="badge pull-right"><?php echo $sidebar_agent_tickets; ?></span>
					<span class="arrow pull-right"><i class="fa fa-angle-right"></i></span>
				</a>
				
				<?php
				if($current_page == 2 || $current_page == 3 || $current_page == 4 || $current_page == 5 || $current_page == 6)
					echo '<ul class="dropdown open">';
				else
					echo '<ul class="dropdown">';
				?>
					<li<?php if($current_page == 2) echo ' class="active"'; ?>>
						<a href="<?php echo $base_url . 'panel/all-tickets' ?>">
							<i class="fa fa-list"></i>All
						</a>
					</li>
					<li<?php if($current_page == 3) echo ' class="active"'; ?>>
						<a href="<?php echo $base_url . 'panel/new-tickets' ?>">
							<i class="fa fa-exclamation-circle"></i>New
							<span class="badge pull-right"><?php echo $sidebar_agent_new_tickets; ?></span>
						</a>
					</li>
					<li<?php if($current_page == 4) echo ' class="active"'; ?>>
						<a href="<?php echo $base_url . 'panel/open-tickets' ?>">
							<i class="fa fa-mail-reply"></i>Open
							<span class="badge pull-right"><?php echo $sidebar_agent_open_tickets; ?></span>
						</a>
					</li>
					<li<?php if($current_page == 5) echo ' class="active"'; ?>>
						<a href="<?php echo $base_url . 'panel/closed-tickets' ?>">
							<i class="fa fa-lock"></i>Closed
						</a>
					</li>
					<li<?php if($current_page == 6) echo ' class="active"'; ?>>
						<a href="<?php echo $base_url . 'panel/pending-tickets' ?>">
							<i class="fa fa-mail-forward"></i>Pending
						</a>
					</li>
				</ul>
			</li>
			<li>
				<a href="javascript:;">
					<i class="fa fa-bug"></i>Bugs
					<span class="badge pull-right"><?php echo $sidebar_agent_bugs; ?></span>
					<span class="arrow pull-right"><i class="fa fa-angle-right"></i></span>
				</a>
				
				<?php
				if($current_page == 7 || $current_page == 8 || $current_page == 9)
					echo '<ul class="dropdown open">';
				else
					echo '<ul class="dropdown">';
				?>
					<li<?php if($current_page == 7) echo ' class="active"'; ?>>
						<a href="<?php echo $base_url . 'panel/free-bugs' ?>">
							<i class="fa fa-list"></i>Free
							<span class="badge pull-right"><?php echo $sidebar_agent_free_bugs; ?></span>
						</a>
					</li>
					<li<?php if($current_page == 8) echo ' class="active"'; ?>>
						<a href="<?php echo $base_url . 'panel/my-bugs' ?>">
							<i class="fa fa-list"></i>My bugs
							<span class="badge pull-right"><?php echo $sidebar_agent_my_bugs; ?></span>
						</a>
					</li>
					<li<?php if($current_page == 9) echo ' class="active"'; ?>>
						<a href="<?php echo $base_url . 'panel/solved-bugs' ?>">
							<i class="fa fa-check"></i>Solved
						</a>
					</li>
				</ul>
			</li>
			<li<?php if($current_page == 10) echo ' class="active"'; ?>>
				<a href="<?php echo $base_url . 'panel/account-settings' ?>">
					<i class="fa fa-gear"></i>Account Settings
				</a>
			</li>
			<li class="navbar-collapsed-link">
				<a href="home.html">
					<i class="fa fa-sign-out"></i>Logout
				</a>
			</li>
		</ul>
		<?php
		}
		?>
		
		
		<?php
		// 3=Admin
		if($user_info->role == '3') {
		?>
		<span class="nav-title second">ADMIN DASHBOARD (SITE)</span>
		<ul class="navigation">
			<li<?php if($current_page == 11) echo ' class="active"'; ?>>
				<a href="<?php echo $base_url . 'panel/admin/general-stats' ?>">
					<i class="fa fa-pie-chart"></i>General Statistics
				</a>
			</li>
			<li>
				<a href="javascript:;">
					<i class="fa fa-home"></i>Tickets
					<span class="badge pull-right"><?php echo $sidebar_admin_tickets; ?></span>
					<span class="arrow pull-right"><i class="fa fa-angle-right"></i></span>
				</a>
				
				<?php
				if($current_page == 12 || $current_page == 13 || $current_page == 14 || $current_page == 15 || $current_page == 16)
					echo '<ul class="dropdown open">';
				else
					echo '<ul class="dropdown">';
				?>
					<li<?php if($current_page == 12) echo ' class="active"'; ?>>
						<a href="<?php echo $base_url . 'panel/admin/all-tickets' ?>">
							<i class="fa fa-home"></i>All
						</a>
					</li>
					<li<?php if($current_page == 13) echo ' class="active"'; ?>>
						<a href="<?php echo $base_url . 'panel/admin/new-tickets' ?>">
							<i class="fa fa-exclamation-circle"></i>New
							<span class="badge pull-right"><?php echo $sidebar_admin_new_tickets; ?></span>
						</a>
					</li>
					<li<?php if($current_page == 14) echo ' class="active"'; ?>>
						<a href="<?php echo $base_url . 'panel/admin/open-tickets' ?>">
							<i class="fa fa-mail-reply"></i>Open
							<span class="badge pull-right"><?php echo $sidebar_admin_open_tickets; ?></span>
						</a>
					</li>
					<li<?php if($current_page == 15) echo ' class="active"'; ?>>
						<a href="<?php echo $base_url . 'panel/admin/closed-tickets' ?>">
							<i class="fa fa-lock"></i>Closed
						</a>
					</li>
					<li<?php if($current_page == 16) echo ' class="active"'; ?>>
						<a href="<?php echo $base_url . 'panel/admin/pending-tickets' ?>">
							<i class="fa fa-mail-forward"></i>Pending
						</a>
					</li>
				</ul>
			</li>
			<li<?php if($current_page == 17) echo ' class="active"'; ?>>
				<a href="<?php echo $base_url . 'panel/admin/ticket-departments' ?>">
					<i class="fa fa-list"></i>Tickets Departments
				</a>
			</li>
			<li>
				<a href="javascript:;">
					<i class="fa fa-bug"></i>Bugs
					<span class="badge pull-right"><?php echo $sidebar_admin_bugs; ?></span>
					<span class="arrow pull-right"><i class="fa fa-angle-right"></i></span>
				</a>
				
				<?php
				if($current_page == 18 || $current_page == 19 || $current_page == 20)
					echo '<ul class="dropdown open">';
				else
					echo '<ul class="dropdown">';
				?>
					<li<?php if($current_page == 18) echo ' class="active"'; ?>>
						<a href="<?php echo $base_url . 'panel/admin/free-bugs' ?>">
							<i class="fa fa-list"></i>Free
							<span class="badge pull-right"><?php echo $sidebar_admin_free_bugs; ?></span>
						</a>
					</li>
					<li<?php if($current_page == 19) echo ' class="active"'; ?>>
						<a href="<?php echo $base_url . 'panel/admin/all-bugs' ?>">
							<i class="fa fa-list"></i>All
						</a>
					</li>
					<li<?php if($current_page == 20) echo ' class="active"'; ?>>
						<a href="<?php echo $base_url . 'panel/admin/solved-bugs' ?>">
							<i class="fa fa-check"></i>Solved
						</a>
					</li>
				</ul>
			</li>
			<li<?php if($current_page == 21) echo ' class="active"'; ?>>
				<a href="<?php echo $base_url . 'panel/admin/bug-departments' ?>">
					<i class="fa fa-bug"></i>Bugs Departments
				</a>
			</li>
			<li<?php if($current_page == 22) echo ' class="active"'; ?>>
				<a href="<?php echo $base_url . 'panel/admin/all-users' ?>">
					<i class="fa fa-users"></i>Users
				</a>
			</li>
			<li>
				<a href="javascript:;">
					<i class="fa fa-gears"></i>Site Settings
					<span class="arrow pull-right"><i class="fa fa-angle-right"></i></span>
				</a>
				
				<?php
				if($current_page == 24 || $current_page == 25 || $current_page == 26 || $current_page == 27 || $current_page == 28 || $current_page == 35)
					echo '<ul class="dropdown open">';
				else
					echo '<ul class="dropdown">';
				?>
					<li<?php if($current_page == 24) echo ' class="active"'; ?>>
						<a href="<?php echo $base_url . 'panel/admin/general-settings' ?>">
							<i class="fa fa-gear"></i>General Settings
						</a>
					</li>
					<li<?php if($current_page == 35) echo ' class="active"'; ?>>
						<a href="<?php echo $base_url . 'panel/admin/logo-settings' ?>">
							<i class="fa fa-gear"></i>Logo Settings
						</a>
					</li>
					<li<?php if($current_page == 25) echo ' class="active"'; ?>>
						<a href="<?php echo $base_url . 'panel/admin/mailer-settings' ?>">
							<i class="fa fa-gear"></i>Mailer Settings
						</a>
					</li>
					<li<?php if($current_page == 26) echo ' class="active"'; ?>>
						<a href="<?php echo $base_url . 'panel/admin/econfirm-settings' ?>">
							<i class="fa fa-gear"></i>Email Confirmations
						</a>
					</li>
					<li<?php if($current_page == 27) echo ' class="active"'; ?>>
						<a href="<?php echo $base_url . 'panel/admin/arecovery-settings' ?>">
							<i class="fa fa-gear"></i>Account Recoveries
						</a>
					</li>
					<li<?php if($current_page == 28) echo ' class="active"'; ?>>
						<a href="<?php echo $base_url . 'panel/admin/emails-settings' ?>">
							<i class="fa fa-gear"></i>Emails Settings
						</a>
					</li>
				</ul>
			</li>
		</ul>
		<?php
		}
		?>
		
		
		<?php
		// 1=Client
		if($user_info->role == '1') {
		?>
		<span class="nav-title">CLIENT DASHBOARD</span>
		<ul class="navigation">
			<li<?php if($current_page == 24) echo ' class="active"'; ?>>
				<a href="<?php echo $base_url . 'panel/' ?>">
					<i class="fa fa-home"></i>Dashboard
				</a>
			</li>
			<li<?php if($current_page == 25) echo ' class="active"'; ?>>
				<a href="<?php echo $base_url . 'panel/new-ticket' ?>">
					<i class="fa fa-list"></i>New Ticket
				</a>
			</li>
			<li<?php if($current_page == 26) echo ' class="active"'; ?>>
				<a href="<?php echo $base_url . 'panel/new-bug-report' ?>">
					<i class="fa fa-bug"></i>New Bug Report
				</a>
			</li>
			<li>
				<a href="javascript:;">
					<i class="fa fa-list"></i>Tickets
					<span class="badge pull-right"><?php echo $sidebar_client_open_tickets; ?></span>
					<span class="arrow pull-right"><i class="fa fa-angle-right"></i></span>
				</a>
				
				<?php
				if($current_page == 27 || $current_page == 28 || $current_page == 29 || $current_page == 30 || $current_page == 31)
					echo '<ul class="dropdown open">';
				else
					echo '<ul class="dropdown">';
				?>
					<li<?php if($current_page == 27) echo ' class="active"'; ?>>
						<a href="<?php echo $base_url . 'panel/all-tickets' ?>">
							<i class="fa fa-list"></i>All
						</a>
					</li>
					<li<?php if($current_page == 28) echo ' class="active"'; ?>>
						<a href="<?php echo $base_url . 'panel/new-tickets' ?>">
							<i class="fa fa-exclamation-circle"></i>New
						</a>
					</li>
					<li<?php if($current_page == 29) echo ' class="active"'; ?>>
						<a href="<?php echo $base_url . 'panel/open-tickets' ?>">
							<i class="fa fa-mail-reply"></i>Open
							<span class="badge pull-right"><?php echo $sidebar_client_open_tickets; ?></span>
						</a>
					</li>
					<li<?php if($current_page == 30) echo ' class="active"'; ?>>
						<a href="<?php echo $base_url . 'panel/closed-tickets' ?>">
							<i class="fa fa-lock"></i>Closed
						</a>
					</li>
					<li<?php if($current_page == 31) echo ' class="active"'; ?>>
						<a href="<?php echo $base_url . 'panel/pending-tickets' ?>">
							<i class="fa fa-mail-forward"></i>Pending
						</a>
					</li>
				</ul>
			</li>
			<li>
				<a href="javascript:;">
					<i class="fa fa-bug"></i>Bugs
					<span class="arrow pull-right"><i class="fa fa-angle-right"></i></span>
				</a>
				
				<?php
				if($current_page == 32 || $current_page == 33 || $current_page == 34)
					echo '<ul class="dropdown open">';
				else
					echo '<ul class="dropdown">';
				?>
					<li<?php if($current_page == 32) echo ' class="active"'; ?>>
						<a href="<?php echo $base_url . 'panel/all-bugs' ?>">
							<i class="fa fa-list"></i>All
						</a>
					</li>
					<li<?php if($current_page == 33) echo ' class="active"'; ?>>
						<a href="<?php echo $base_url . 'panel/solved-bugs' ?>">
							<i class="fa fa-check"></i>Solved
						</a>
					</li>
				</ul>
			</li>
			<li<?php if($current_page == 10) echo ' class="active"'; ?>>
				<a href="<?php echo $base_url . 'panel/account-settings' ?>">
					<i class="fa fa-gear"></i>Account Settings
				</a>
			</li>
			<li class="navbar-collapsed-link">
				<a href="<?php echo $base_url . 'panel/logout' ?>">
					<i class="fa fa-sign-out"></i>Logout
				</a>
			</li>
		</ul>
		<?php
		}
		?>
	</div>