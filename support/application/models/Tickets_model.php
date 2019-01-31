<?php

class Tickets_model extends CI_Model {
	// Get list of ticket departments
	public function get_ticket_departments() {
		$sql = $this->db->query("SELECT `id`,`name`,`default` FROM tickerr_ticket_departments ORDER BY `default` ASC");
		return $sql;
	}
	
	// Get department name
	public function get_department_name($id) {
		$sql = "SELECT `name` FROM `tickerr_ticket_departments` WHERE `id`=?";
		$query = $this->db->query($sql, (int)$id);
		$row = $query->row();
		return $row->name;
	}
	
	// Check if ticket exists and if it was created by a guest
	public function check_guest_ticket($code) {
		$userid = 0;
		$sql = "SELECT * FROM `tickerr_tickets` WHERE `userid`=? AND `access`=?";
		$query = $this->db->query($sql, array($userid, $code));
		return ($query->num_rows() == 0) ? false : true;
	}
	
	// Check if ticket exists
	public function ticket_exists($code) {
		$sql = "SELECT * FROM `tickerr_tickets` WHERE `access`=?";
		$query = $this->db->query($sql, $code);
		return ($query->num_rows() == 0) ? false : true;
	}
	
	// Get status of a ticket
	public function get_guest_ticket_status($code) {
		$sql = "SELECT `status` FROM `tickerr_tickets` WHERE `access`=?";
		$query = $this->db->query($sql, $code);
		$row = $query->row();
		return $row->status;
	}
	
	// Get information of a ticket
	public function guest_ticket_info($code) {
		$sql = "SELECT `tickerr_tickets`.*, `tickerr_ticket_departments`.name as department_name FROM `tickerr_tickets` INNER JOIN `tickerr_ticket_departments` ON `tickerr_tickets`.`department` = `tickerr_ticket_departments`.`id` WHERE `access`=?";
		$query = $this->db->query($sql, $code);
		return $query->row();
	}
	
	// Get ticket by its ID
	public function get_ticket_by_id($id) {
		$sql = "SELECT * FROM `tickerr_tickets` WHERE `id`=?";
		$query = $this->db->query($sql, $id);
		return $query->row();
	}
	
	// Get ticket replies by its ID
	public function get_ticket_reply_by_id($id) {
		$sql = "SELECT * FROM `tickerr_ticket_replies` WHERE `id`=?";
		$query = $this->db->query($sql, $id);
		return $query->row();
	}
	
	// Submit a new guest reply
	public function submit_guest_reply($code, $reply, $files) {
		// Get ticket id
		$sql = "SELECT `id` FROM `tickerr_tickets` WHERE `access`=?";
		$query = $this->db->query($sql, $code);
		$row = $query->row();
		$ticket_id = $row->id;
		
		$date = date('Y-m-d H:i:s');
		
		// Info to insert
		$reply = array(
			'`ticketid`' => (int)$ticket_id,
			'`userid`' => 0,
			'`agentid`' => 0,
			'`content`' => $reply,
			'`date`' => $date,
			'`files`' => $files
		);
		
		// Insert
		if($this->db->insert('tickerr_ticket_replies', $reply) == false)
			return false;
		
		// Update last update and ticket status
		$sql = "UPDATE `tickerr_tickets` SET `last_update`=?, `status`=1 WHERE `access`=?";
		$query = $this->db->query($sql, array($date, $code));
		return true;
	}
	
	// Close a ticket
	public function close_ticket($id) {
		$sql = "UPDATE `tickerr_tickets` SET `status`=3 WHERE `id`=?";
		$query = $this->db->query($sql, $id);
		return true;
	}
	
	// Transfer ticket to another department
	public function transfer_to_dept($id, $dept) {
		$date = date('Y-m-d H:i:s');
		$sql = "UPDATE `tickerr_tickets` SET `department`=?, `last_update`=?, `agentid`=0 WHERE `id`=?";
		
		$query = $this->db->query($sql, array($dept, $date, $id));
		return true;
	}
	
	// Change ticket's priority
	public function change_priority($id, $priority) {
		$sql = "UPDATE `tickerr_tickets` SET `priority`=? WHERE `id`=?";
		$this->db->query($sql, array((int)$priority, $id));
		return true;
	}
	
	// Asign ticket to an agent
	public function assign_ticket_agent($id, $agentid) {
		$sql = "UPDATE `tickerr_tickets` SET `agentid`=?WHERE `id`=?";
		$query = $this->db->query($sql, array($agentid, $id));
		return true;
	}
	
	// Submit client reply
	public function submit_client_reply($id, $user_id, $reply, $files) {
		$ticket_id = $id;
		
		$date = date('Y-m-d H:i:s');
		
		// Info to insert
		$reply = array(
			'`ticketid`' => (int)$ticket_id,
			'`userid`' => $user_id,
			'`agentid`' => 0,
			'`content`' => $reply,
			'`date`' => $date,
			'`files`' => $files
		);
		
		// Insert
		if($this->db->insert('tickerr_ticket_replies', $reply) == false)
			return false;
		
		// Update last update and ticket status
		$sql = "UPDATE `tickerr_tickets` SET `last_update`=?, `status`=1 WHERE `id`=?";
		$query = $this->db->query($sql, array($date, $id));
		return true;
	}
	
	// Submit agent reply
	public function submit_agent_reply($id, $agent_id, $reply, $files) {
		$ticket_id = $id;
		
		$date = date('Y-m-d H:i:s');
		
		// Info to insert
		$reply = array(
			'`ticketid`' => (int)$ticket_id,
			'`userid`' => 0,
			'`agentid`' => $agent_id,
			'`content`' => $reply,
			'`date`' => $date,
			'`files`' => $files
		);
		
		// Insert
		if($this->db->insert('tickerr_ticket_replies', $reply) == false)
			return false;
		
		// Update last update and ticket status
		$sql = "UPDATE `tickerr_tickets` SET `last_update`=?, `status`=2 WHERE `id`=?";
		$query = $this->db->query($sql, array($date, $id));
		return true;
	}
	
	// Submit new guest rating
	public function submit_guest_rating($code, $rating, $msg) {
		$sql = "UPDATE `tickerr_tickets` SET `rating`=?, `rating_msg`=? WHERE `access`=?";
		$query = $this->db->query($sql, array($rating,  $msg, $code));
		return true;
	}
	
	// Delete a ticket
	public function delete_ticket($code, $dpt) {
		$sql = "DELETE FROM `tickerr_tickets` WHERE `access`=?";
		$query = $this->db->query($sql, $code);
		
		// -1 to the department
		$dpt = (int)$dpt;
		$this->db->query("UPDATE `tickerr_ticket_departments` SET `tickets`=`tickets`-1 WHERE `id`=$dpt AND `tickets` > 0");
		
		return true;
	}
	
	// Count replies of a ticket
	public function count_ticket_replies($code) {
		// Get ticket id
		$sql = "SELECT `id` FROM `tickerr_tickets` WHERE `access`=?";
		$query = $this->db->query($sql, $code);
		$row = $query->row();
		$ticket_id = $row->id;
		
		$sql = "SELECT id FROM `tickerr_ticket_replies` WHERE `ticketid`=?";
		$query = $this->db->query($sql, $ticket_id);
		return $query->num_rows();
	}
	
	// Get replies of a ticket
	public function get_ticket_replies($code) {
		// Get ticket id
		$sql = "SELECT `id` FROM `tickerr_tickets` WHERE `access`=?";
		$query = $this->db->query($sql, $code);
		$row = $query->row();
		$ticket_id = $row->id;
		
		$sql = "SELECT * FROM `tickerr_ticket_replies` WHERE `ticketid`=?";
		$query = $this->db->query($sql, $ticket_id);
		return $query;
	}
	
	// Create new ticket
	public function new_ticket($userid, $subject, $dpt, $message, $files) {
		// Create random ticket access
		$random_access_chars = 'ABCDEFGHIJKLMOPQRSTUVWXYZzbcdefghijklmnopqrstuvwxyz1234567890';
		$anon_access = '';
		for($i = 0; $i < 10; $i++)
			$anon_access .= $random_access_chars{rand(0, strlen($random_access_chars)-1)};
		
		$date = date('Y-m-d H:i:s');
		
		// Insert ticket information
		$ticket = array(
			'`department`' => (int)$dpt,
			'`userid`' => $userid,
			'`guest_name`' => '',
			'`guest_email`' => '',
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
}

