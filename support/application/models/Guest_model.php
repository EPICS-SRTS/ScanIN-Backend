<?php

class Guest_model extends CI_Model {
	private $last_confirmation_str = '';
	
	// Get list of ticket departments
	public function get_ticket_departments() {
		$sql = $this->db->query("SELECT `id`,`name`,`default` FROM tickerr_ticket_departments ORDER BY `default` ASC");
		return $sql;
	}
	
	// Get list of bug report departments
	public function get_bug_departments() {
		$sql = $this->db->query("SELECT `id`,`name`,`default` FROM tickerr_bug_departments ORDER BY `default` ASC");
		return $sql->result();
	}
	
	// Check if username exists
	public function check_existing_username($u) {
		$sql = "SELECT `id` FROM `tickerr_users` WHERE `username`=?";
		$query = $this->db->query($sql, $u);
		return ($query->num_rows() == 0) ? false : true;
	}
	
	// Check if email address exists
	public function check_existing_email($e) {
		$sql = "SELECT `id` FROM `tickerr_users` WHERE `email`=?";
		$query = $this->db->query($sql, $e);
		return ($query->num_rows() == 0) ? false : true;
	}
	
	// Link tickets of a certain email to the user ID
	public function link_tickets($userid, $email) {
		$nl = '';
		$sql = "UPDATE `tickerr_tickets` SET `userid`=?, `guest_name`=?, `guest_email`=? WHERE `guest_email`=?";
		$query = $this->db->query($sql, array($userid, $nl, $nl, $email));
		return $query;
	}
	
	// Link bug reports of a certain email to the user ID
	public function link_bug_reports($userid, $email) {
		$nl = '';
		$sql = "UPDATE `tickerr_bugs` SET `userid`=?, `guest_name`=?, `guest_email`=? WHERE `guest_email`=?";
		$query = $this->db->query($sql, array($userid, $nl, $nl, $email));
		return $query;
	}
	
	// Create new ticket as guest
	public function new_guest_ticket($name, $email, $subject, $dpt, $message, $files) {
		// Create random ticket access
		$random_access_chars = 'ABCDEFGHIJKLMOPQRSTUVWXYZzbcdefghijklmnopqrstuvwxyz1234567890';
		$anon_access = '';
		for($i = 0; $i < 10; $i++)
			$anon_access .= $random_access_chars{rand(0, strlen($random_access_chars)-1)};
		
		$date = date('Y-m-d H:i:s');
		
		// Insert ticket information
		$ticket = array(
			'`department`' => (int)$dpt,
			'`userid`' => 0,
			'`guest_name`' => $name,
			'`guest_email`' => $email,
			'`agentid`' => 0,
			'`access`' => $anon_access,
			'`status`' => 1,
			'`priority`' => 3,
			'`date`' => $date,
			'`last_update`' => $date,
			'`subject`' => $subject,
			'`content`' => $message,
			'`files`' => $files,
			'`transferred_from`' => 0,
			'`rating`' => '0.0',
			'`rating_msg`' => ''
		);
		
		if($this->db->insert('tickerr_tickets', $ticket) == false)
			return false;
		
		// Add one to the department
		$dpt = (int)$dpt;
		$this->db->query("UPDATE `tickerr_ticket_departments` SET `tickets`=`tickets`+1 WHERE `id`=$dpt");
		
		return $anon_access;
	}
	
	// Create new account
	public function new_account($name, $username, $email, $password, $e_confirmation) {
		// Email confirmation not needed, so let's confirm the account (2)
		$e_confirmation = ($e_confirmation == false) ? 2 : 1;
		
		// If email confirmation is needed, generate random confirm code
		$confirmation_str = '';
		if($e_confirmation == true) {
			$random_access_chars = 'ABCDEFGHIJKLMOPQRSTUVWXYZzbcdefghijklmnopqrstuvwxyz1234567890';
			for($i = 0; $i < 24; $i++)
				$confirmation_str .= $random_access_chars{rand(0, strlen($random_access_chars)-1)};
		}
		
		// Data to insert
		$user = array(
			'`username`' => $username,
			'`name`' => $name,
			'`email`' => $email,
			'`profile_img1x`' => 'fa-user@1x.png',
			'`profile_img2x`' => 'fa-user@2x.png',
			'`profile_img3x`' => 'fa-user@3x.png',
			'`date`' => date('Y-m-d H:i:s'),
			'`password`' => md5($password),
			'`role`' => 1,
			'`ticket_departments`' => '',
			'`bug_departments`' => '',
			'`email_on_tactivity`' => 1,
			'`email_on_bactivity`' => 1,
			'`email_confirmation`' => $e_confirmation,
			'`confirmation_str`' => $confirmation_str,
			'`recover_password_str`' => ''
		);
		
		// Create user and return ID
		if($this->db->insert('tickerr_users', $user) == true) {
			// save confirmation and return user id
			$this->last_confirmation_str = $confirmation_str;
			return $this->db->insert_id();
		}
		return false;
	}
	
	// Return last confirmation string
	public function last_confirmation_str() { return $this->last_confirmation_str; }
	
	// Create new bug report as guest
	public function new_guest_bug($name, $email, $subject, $dpt, $message, $files) {
		// Create random ticket access
		$random_access_chars = 'ABCDEFGHIJKLMOPQRSTUVWXYZzbcdefghijklmnopqrstuvwxyz1234567890';
		$anon_access = '';
		for($i = 0; $i < 10; $i++)
			$anon_access .= $random_access_chars{rand(0, strlen($random_access_chars)-1)};
		
		$date = date('Y-m-d H:i:s');
		
		$ticket = array(
			'`department`' => (int)$dpt,
			'`userid`' => 0,
			'`guest_name`' => $name,
			'`guest_email`' => $email,
			'`agentid`' => 0,
			'`access`' => $anon_access,
			'`status`' => 1,
			'`priority`' => 3,
			'`date`' => $date,
			'`last_update`' => $date,
			'`subject`' => $subject,
			'`content`' => $message,
			'`files`' => $files,
			'`transferred_from`' => 0,
			'`agent_msg`' => ''
		);
		
		if($this->db->insert('tickerr_bugs', $ticket) == false)
			return false;
			
		// Add one to the department
		$dpt = (int)$dpt;
		$this->db->query("UPDATE `tickerr_bug_departments` SET `reports`=`reports`+1 WHERE `id`=$dpt");
		
		return $anon_access;
	}
}