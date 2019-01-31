<?php

class Bugs_model extends CI_Model {
	// Get bug department name
	public function get_department_name($id) {
		$sql = "SELECT `name` FROM `tickerr_bug_departments` WHERE `id`=?";
		$query = $this->db->query($sql, (int)$id);
		$row = $query->row();
		return $row->name;
	}
	
	// Delete bug report
	public function delete_bug($code, $dpt) {
		$sql = "DELETE FROM `tickerr_bugs` WHERE `access`=?";
		$query = $this->db->query($sql, array($code));
		
		// -1 to the department
		$dpt = (int)$dpt;
		$this->db->query("UPDATE `tickerr_bug_departments` SET `reports`=`reports`-1 WHERE `id`=$dpt AND `reports` > 0");
		
		return true;
	}
	
	// Check if bug department exists
	public function department_exists($id) {
		$sql = "SELECT COUNT(*) as c FROM `tickerr_bug_departments` WHERE `id`=?";
		$query = $this->db->query($sql, array((int)$id));
		return ($query->num_rows() == 0) ? false : true;
	}
	
	// Set agent ID to bug report
	public function take_bug($code, $agent_id) {
		$sql = "UPDATE `tickerr_bugs` SET `agentid`=? WHERE `access`=?";
		$query = $this->db->query($sql, array($agent_id, $code));
		return true;
	}
	
	// Change bug report's priority
	public function change_priority($id, $priority) {
		$sql = "UPDATE `tickerr_bugs` SET `priority`=? WHERE `id`=?";
		$this->db->query($sql, array((int)$priority, $id));
		return true;
	}
	
	// Update bug report status
	public function update_bug_status($code, $status) {
		$sql = "UPDATE `tickerr_bugs` SET `status`=? WHERE `access`=?";
		$query = $this->db->query($sql, array((int)$status, $code));
		return true;
	}
	
	// Update bug report agent's reply
	public function update_bug_reply($code, $reply) {
		$sql = "UPDATE `tickerr_bugs` SET `agent_msg`=? WHERE `access`=?";
		$query = $this->db->query($sql, array($reply, $code));
		return true;
	}
	
	// Assign bug report to an agent
	public function assign_agent($code, $agentid) {
		$sql = "UPDATE `tickerr_bugs` SET `agentid`=? WHERE `access`=?";
		$query = $this->db->query($sql, array((int)$agentid, $code));
		return true;
	}
	
	// Check if bug report exists
	public function bug_exists($code) {
		$sql = "SELECT COUNT(*) as c FROM `tickerr_bugs` WHERE `access`=?";
		$query = $this->db->query($sql, $code);
		return ($query->num_rows() == 0) ? false : true;
	}
	
	// Get list of all bug report departments
	public function get_bug_departments() {
		$sql = $this->db->query("SELECT `id`,`name`,`default` FROM tickerr_bug_departments ORDER BY `default` ASC");
		return $sql;
	}
	
	// Check if bug report exists
	public function check_guest_bug($code) {
		$userid = 0;
		$sql = "SELECT * FROM `tickerr_bugs` WHERE `userid`=? AND `access`=?";
		$query = $this->db->query($sql, array($userid, $code));
		return ($query->num_rows() == 0) ? false : true;
	}
	
	// Return bug report info
	public function guest_bug_info($code) {
		$sql = "SELECT `tickerr_bugs`.*, `tickerr_bug_departments`.name as department_name FROM `tickerr_bugs` INNER JOIN `tickerr_bug_departments` ON `tickerr_bugs`.`department` = `tickerr_bug_departments`.`id` WHERE `access`=?";
		$query = $this->db->query($sql, $code);
		return $query->row();
	}
	
	// Return bug report info (by ID)
	public function guest_bug_info_by_id($id) {
		$sql = "SELECT `tickerr_bugs`.*, `tickerr_bug_departments`.name as department_name FROM `tickerr_bugs` INNER JOIN `tickerr_bug_departments` ON `tickerr_bugs`.`department` = `tickerr_bug_departments`.`id` WHERE `tickerr_bugs`.`id`=?";
		$query = $this->db->query($sql, $id);
		return $query->row();
	}
	
	// Create new bug report
	public function new_bug_report($userid, $subject, $dpt, $message, $files) {
		// Create random ticket access
		$random_access_chars = 'ABCDEFGHIJKLMOPQRSTUVWXYZzbcdefghijklmnopqrstuvwxyz1234567890';
		$anon_access = '';
		for($i = 0; $i < 10; $i++)
			$anon_access .= $random_access_chars{rand(0, strlen($random_access_chars)-1)};
		
		$date = date('Y-m-d H:i:s');
		
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
			'`agent_msg`' => ''
		);
		
		if($this->db->insert('tickerr_bugs', $ticket) == false)
			return false;
		
		// Add one to the department
		$dpt = (int)$dpt;
		$this->db->query("UPDATE `tickerr_bug_departments` SET `reports`=`reports`+1 WHERE `id`=$dpt");
		
		return $anon_access;
	}
	
	// Transfer bug report to another department
	public function transfer_to($id, $dept) {
		$date = date('Y-m-d H:i:s');
		$sql = "UPDATE `tickerr_bugs` SET `department`=?, `last_update`=?, `agentid`=0 WHERE `id`=?";
		
		$query = $this->db->query($sql, array($dept, $date, $id));
		return true;
	}
}

