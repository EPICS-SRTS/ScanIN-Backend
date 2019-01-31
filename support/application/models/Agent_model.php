<?php

class Agent_model extends CI_Model {
	private $agent_id = false;
	private $agent_tdepartments = false;
	private $agent_bdepartments = false;
	
	private $str_agent_tdepartments = '';
	private $str_agent_bdepartments = '';
	
	// Sets private var with agent ID and gets some important information
	public function set_agent_id($id) {
		$this->agent_id = $id;
		
		// Ticket departments
		$sql = "SELECT `ticket_departments` FROM `tickerr_users` WHERE `id`=$id";
		$query = $this->db->query($sql);
		$row = $query->row();
		if($row->ticket_departments != '') {
			$this->agent_tdepartments = explode('|', $row->ticket_departments);
			$c = 0;
			foreach($this->agent_tdepartments as $dpt) {
				if($c != 0)
					$this->str_agent_tdepartments .= " OR ";
				$this->str_agent_tdepartments .= "`department`=$dpt";
				$c++;
			}
		}else{
			$this->str_agent_tdepartments = '1=0';
		}
		
		// Bug departments
		$sql = "SELECT `bug_departments` FROM `tickerr_users` WHERE `id`=$id";
		$query = $this->db->query($sql);
		$row = $query->row();
		if($row->bug_departments != '') {
			$this->agent_bdepartments = explode('|', $row->bug_departments);
			$c = 0;
			foreach($this->agent_bdepartments as $dpt) {
				if($c != 0)
					$this->str_agent_bdepartments .= " OR ";
				$this->str_agent_bdepartments .= "`department`=$dpt";
				$c++;
			}
		}else{
			$this->str_agent_bdepartments = '1=0';
		}
	}
	
	// For statistics - Returns pending bug reports
	public function count_pending_bugs() {
		if($this->agent_bdepartments == false || count($this->agent_bdepartments) == 0)
			return 0;
		
		$sql = "SELECT COUNT(*) as c FROM `tickerr_bugs` WHERE ({$this->str_agent_bdepartments}) AND `status`=1";
		$query = $this->db->query($sql);
		$row = $query->row();
		return $row->c;
	}
	
	// For statistics - Returns pending tickets
	public function count_pending_tickets() {
		if($this->agent_tdepartments == false || count($this->agent_tdepartments) == 0)
			return 0;
			
		$sql = "SELECT COUNT(*) as c FROM `tickerr_tickets` WHERE ({$this->str_agent_tdepartments}) AND `agentid`={$this->agent_id} AND `status`=1";
		$query = $this->db->query($sql);
		$row = $query->row();
		return $row->c;
	}
	
	// For statistics - Returns tickets without agent
	public function count_no_agent_tickets() {
		if($this->agent_tdepartments == false || count($this->agent_tdepartments) == 0)
			return 0;
		
		$sql = "SELECT COUNT(*) as c FROM `tickerr_tickets` WHERE ({$this->str_agent_tdepartments}) AND `agentid`=0";
		$query = $this->db->query($sql);
		$row = $query->row();
		return $row->c;
	}
	
	// For statistics - Returns tickets where client hasn't replied yet
	public function count_pending_client_tickets() {
		if($this->agent_tdepartments == false || count($this->agent_tdepartments) == 0)
			return 0;
		
		$sql = "SELECT COUNT(*) as c FROM `tickerr_tickets` WHERE ({$this->str_agent_tdepartments}) AND `agentid`={$this->agent_id} AND `status`=2";
		$query = $this->db->query($sql);
		$row = $query->row();
		return $row->c;
	}
	
	// For statistics - Returns closed tickets
	public function count_solved_tickets() {
		if($this->agent_tdepartments == false || count($this->agent_tdepartments) == 0)
			return 0;
		
		$sql = "SELECT COUNT(*) as c FROM `tickerr_tickets` WHERE ({$this->str_agent_tdepartments}) AND `agentid`={$this->agent_id} AND `status`=3";
		$query = $this->db->query($sql);
		$row = $query->row();
		return $row->c;
	}
	
	// For statistics - Returns customer satisfaction of the current agent
	public function get_customer_satisfaction() {
		if($this->agent_tdepartments == false || count($this->agent_tdepartments) == 0)
			return 0;
			
		$sql = "SELECT `rating` FROM `tickerr_tickets` WHERE ({$this->str_agent_tdepartments}) AND `agentid`={$this->agent_id} AND `rating`!=0.0";
		$query = $this->db->query($sql);
		
		$rating_sum = 0;
		$ratings_n = 0;
		foreach($query->result() as $row) {
			$rating_sum += $row->rating;
			$ratings_n += 1;
		}
		if($ratings_n == 0) return 0;
		return ($rating_sum / $ratings_n);
	}
	
	// Returns tickets without agent
	public function get_tickets_no_agent() {
		if($this->agent_tdepartments == false || count($this->agent_tdepartments) == 0)
			return false;
		
		$sql = "SELECT * FROM `tickerr_tickets` WHERE ({$this->str_agent_tdepartments}) AND `agentid`=0 ORDER BY `last_update` DESC LIMIT 9";
		$query = $this->db->query($sql);
		return $query;
	}
	
	// Returns tickets awaiting agent's reply
	public function get_tickets_awaiting() {
		if($this->agent_tdepartments == false || count($this->agent_tdepartments) == 0)
			return false;
			
		$sql = "SELECT * FROM `tickerr_tickets` WHERE ({$this->str_agent_tdepartments}) AND `agentid`={$this->agent_id} AND `status`=1 ORDER BY `last_update` DESC LIMIT 9";
		$query = $this->db->query($sql);
		return $query;
	}
	
	// Returns pending bug reports
	public function get_pending_bugs() {
		if($this->agent_bdepartments == false || count($this->agent_bdepartments) == 0)
			return 0;
		
		$sql = "SELECT * FROM `tickerr_bugs` WHERE ({$this->str_agent_bdepartments}) AND `status`=1";
		$query = $this->db->query($sql);
		return $query;
	}
	
	/**************** STARTS BLOCK OF 3 FUNCTIONS ****************
	 * These functions help display tables with options to search and order
	 * data by column
	 *
	 * Purpose of this specific block:
	 * Get list of all tickets from the database
	 *
	*/
	public function get_all_tickets($rows = 20, $starting = 0, $order_by = 'last_update', $order = 'DESC', $search = false) {
		if($search == false) {
			$sql = "SELECT tickerr_tickets.*, tickerr_ticket_departments.name as department_name, CASE WHEN guest_name != '' THEN guest_name ELSE tickerr_users.name END AS client_final_name FROM tickerr_tickets INNER JOIN tickerr_ticket_departments ON tickerr_tickets.department=tickerr_ticket_departments.id LEFT JOIN tickerr_users ON tickerr_tickets.userid = tickerr_users.id WHERE `agentid`={$this->agent_id} OR `agentid`=0 ORDER BY `$order_by` $order LIMIT $starting,$rows";
			$query = $this->db->query($sql);
		}else{
			$search = "%$search%";
			$sql = "SELECT tickerr_tickets.*, tickerr_ticket_departments.name as department_name, CASE WHEN guest_name != '' THEN guest_name ELSE tickerr_users.name END AS client_final_name FROM tickerr_tickets INNER JOIN tickerr_ticket_departments ON tickerr_tickets.department=tickerr_ticket_departments.id LEFT JOIN tickerr_users ON tickerr_tickets.userid = tickerr_users.id WHERE (`agentid`=? OR `agentid`=0) AND (`tickerr_tickets`.`id` LIKE ? OR `tickerr_tickets`.`date` LIKE ? OR `last_update` LIKE ? OR `subject` LIKE ? OR `content` LIKE ? OR `tickerr_ticket_departments`.`name` LIKE ?) ORDER BY `$order_by` $order LIMIT $starting,$rows";
			$query = $this->db->query($sql, array($this->agent_id, $search, $search, $search, $search, $search, $search));
		}
		return $query;
	}
	
	public function count_search_all_tickets($search) {
		$search = "%$search%";
		$sql = "SELECT COUNT(*) as c, tickerr_tickets.*, tickerr_ticket_departments.name as department_name FROM tickerr_tickets INNER JOIN tickerr_ticket_departments ON tickerr_tickets.department=tickerr_ticket_departments.id WHERE (`agentid`=?) AND (`tickerr_tickets`.`id` LIKE ? OR `tickerr_tickets`.`date` LIKE ? OR `last_update` LIKE ? OR `subject` LIKE ? OR `content` LIKE ? OR `tickerr_ticket_departments`.`name` LIKE ?)";
		$query = $this->db->query($sql, array($this->agent_id, $search, $search, $search, $search, $search, $search));
		$row = $query->row();
		return $row->c;
	}
	
	public function count_all_tickets() {
		$sql = "SELECT COUNT(*) as c FROM `tickerr_tickets` WHERE `agentid`={$this->agent_id} OR `agentid`=0";
		$query = $this->db->query($sql);
		$row = $query->row();
		return $row->c;
	}
	/**************** ENDS BLOCK OF 3 FUNCTIONS ****************/
	
	
	/**************** STARTS BLOCK OF 3 FUNCTIONS ****************
	 * These functions help display tables with options to search and order
	 * data by column
	 *
	 * Purpose of this specific block:
	 * Get list of new tickets from the database
	 *
	*/
	public function get_new_tickets($rows = 20, $starting = 0, $order_by = 'last_update', $order = 'DESC', $search = false) {
		if($search == false) {
			$sql = "SELECT tickerr_tickets.*, tickerr_ticket_departments.name as department_name, CASE WHEN guest_name != '' THEN guest_name ELSE tickerr_users.name END AS client_final_name FROM tickerr_tickets INNER JOIN tickerr_ticket_departments ON tickerr_tickets.department=tickerr_ticket_departments.id LEFT JOIN tickerr_users ON tickerr_tickets.userid = tickerr_users.id WHERE ({$this->str_agent_tdepartments}) AND `agentid`=0 ORDER BY `$order_by` $order LIMIT $starting,$rows";			
			$query = $this->db->query($sql);
		}else{
			$search = "%$search%";
			$sql = "SELECT tickerr_tickets.*, tickerr_ticket_departments.name as department_name, CASE WHEN guest_name != '' THEN guest_name ELSE tickerr_users.name END AS client_final_name FROM tickerr_tickets INNER JOIN tickerr_ticket_departments ON tickerr_tickets.department=tickerr_ticket_departments.id LEFT JOIN tickerr_users ON tickerr_tickets.userid = tickerr_users.id WHERE (`agentid`=?) AND ({$this->str_agent_tdepartments}) AND (`tickerr_tickets`.`id` LIKE ? OR `tickerr_tickets`.`date` LIKE ? OR `last_update` LIKE ? OR `subject` LIKE ? OR `content` LIKE ? OR `tickerr_ticket_departments`.`name` LIKE ?) ORDER BY `$order_by` $order LIMIT $starting,$rows";
			$query = $this->db->query($sql, array(0, $search, $search, $search, $search, $search, $search));
		}
		return $query;
	}
	
	public function count_search_new_tickets($search) {
		$search = "%$search%";
		$sql = "SELECT COUNT(*) as c, tickerr_tickets.*, tickerr_ticket_departments.name as department_name FROM tickerr_tickets INNER JOIN tickerr_ticket_departments ON tickerr_tickets.department=tickerr_ticket_departments.id WHERE (`agentid`=?) AND ({$this->str_agent_tdepartments}) AND (`tickerr_tickets`.`id` LIKE ? OR `tickerr_tickets`.`date` LIKE ? OR `last_update` LIKE ? OR `subject` LIKE ? OR `content` LIKE ? OR `tickerr_ticket_departments`.`name` LIKE ?)";
		$query = $this->db->query($sql, array(0, $search, $search, $search, $search, $search, $search));
		$row = $query->row();
		return $row->c;
	}
	
	public function count_new_tickets() {
		$sql = "SELECT COUNT(*) as c FROM `tickerr_tickets` WHERE `agentid`=0 AND ({$this->str_agent_tdepartments})";
		$query = $this->db->query($sql);
		$row = $query->row();
		return $row->c;
	}
	/**************** ENDS BLOCK OF 3 FUNCTIONS ****************/
	
	
	/**************** STARTS BLOCK OF 3 FUNCTIONS ****************
	 * These functions help display tables with options to search and order
	 * data by column
	 *
	 * Purpose of this specific block:
	 * Get list of open tickets from the database
	 *
	*/
	public function get_open_tickets($rows = 20, $starting = 0, $order_by = 'last_update', $order = 'DESC', $search = false) {
		if($search == false) {
			$sql = "SELECT tickerr_tickets.*, tickerr_ticket_departments.name as department_name, CASE WHEN guest_name != '' THEN guest_name ELSE tickerr_users.name END AS client_final_name FROM tickerr_tickets INNER JOIN tickerr_ticket_departments ON tickerr_tickets.department=tickerr_ticket_departments.id LEFT JOIN tickerr_users ON tickerr_tickets.userid = tickerr_users.id WHERE `agentid`={$this->agent_id} AND `status`=1 ORDER BY `$order_by` $order LIMIT $starting,$rows";
			$query = $this->db->query($sql);
		}else{
			$search = "%$search%";
			$sql = "SELECT tickerr_tickets.*, tickerr_ticket_departments.name as department_name, CASE WHEN guest_name != '' THEN guest_name ELSE tickerr_users.name END AS client_final_name FROM tickerr_tickets INNER JOIN tickerr_ticket_departments ON tickerr_tickets.department=tickerr_ticket_departments.id LEFT JOIN tickerr_users ON tickerr_tickets.userid = tickerr_users.id WHERE (`agentid`=?) AND `status`=1 AND (`tickerr_tickets`.`id` LIKE ? OR `tickerr_tickets`.`date` LIKE ? OR `last_update` LIKE ? OR `subject` LIKE ? OR `content` LIKE ? OR `tickerr_ticket_departments`.`name` LIKE ?) ORDER BY `$order_by` $order LIMIT $starting,$rows";
			$query = $this->db->query($sql, array($this->agent_id, $search, $search, $search, $search, $search, $search));
		}
		return $query;
	}
	
	public function count_search_open_tickets($search) {
		$search = "%$search%";
		$sql = "SELECT COUNT(*) as c, tickerr_tickets.*, tickerr_ticket_departments.name as department_name FROM tickerr_tickets INNER JOIN tickerr_ticket_departments ON tickerr_tickets.department=tickerr_ticket_departments.id WHERE (`agentid`=?) AND `status`=1 AND (`tickerr_tickets`.`id` LIKE ? OR `tickerr_tickets`.`date` LIKE ? OR `last_update` LIKE ? OR `subject` LIKE ? OR `content` LIKE ? OR `tickerr_ticket_departments`.`name` LIKE ?)";
		$query = $this->db->query($sql, array($this->agent_id, $search, $search, $search, $search, $search, $search));
		$row = $query->row();
		return $row->c;
	}
	
	public function count_open_tickets() {
		$sql = "SELECT COUNT(*) as c FROM `tickerr_tickets` WHERE `agentid`={$this->agent_id} AND `status`=1";
		$query = $this->db->query($sql);
		$row = $query->row();
		return $row->c;
	}
	/**************** ENDS BLOCK OF 3 FUNCTIONS ****************/
	
	
	/**************** STARTS BLOCK OF 3 FUNCTIONS ****************
	 * These functions help display tables with options to search and order
	 * data by column
	 *
	 * Purpose of this specific block:
	 * Get list of closed tickets from the database
	 *
	*/
	public function get_closed_tickets($rows = 20, $starting = 0, $order_by = 'last_update', $order = 'DESC', $search = false) {
		if($search == false) {
			$sql = "SELECT tickerr_tickets.*, tickerr_ticket_departments.name as department_name, CASE WHEN guest_name != '' THEN guest_name ELSE tickerr_users.name END AS client_final_name FROM tickerr_tickets INNER JOIN tickerr_ticket_departments ON tickerr_tickets.department=tickerr_ticket_departments.id LEFT JOIN tickerr_users ON tickerr_tickets.userid = tickerr_users.id WHERE `agentid`={$this->agent_id} AND `status`=3 ORDER BY `$order_by` $order LIMIT $starting,$rows";
			$query = $this->db->query($sql);
		}else{
			$search = "%$search%";
			$sql = "SELECT tickerr_tickets.*, tickerr_ticket_departments.name as department_name, CASE WHEN guest_name != '' THEN guest_name ELSE tickerr_users.name END AS client_final_name FROM tickerr_tickets INNER JOIN tickerr_ticket_departments ON tickerr_tickets.department=tickerr_ticket_departments.id LEFT JOIN tickerr_users ON tickerr_tickets.userid = tickerr_users.id WHERE (`agentid`=?) AND `status`=3 AND (`tickerr_tickets`.`id` LIKE ? OR `tickerr_tickets`.`date` LIKE ? OR `last_update` LIKE ? OR `subject` LIKE ? OR `content` LIKE ? OR `tickerr_ticket_departments`.`name` LIKE ?) ORDER BY `$order_by` $order LIMIT $starting,$rows";
			$query = $this->db->query($sql, array($this->agent_id, $search, $search, $search, $search, $search, $search));
		}
		return $query;
	}
	
	public function count_search_closed_tickets($search) {
		$search = "%$search%";
		$sql = "SELECT COUNT(*) as c, tickerr_tickets.*, tickerr_ticket_departments.name as department_name FROM tickerr_tickets INNER JOIN tickerr_ticket_departments ON tickerr_tickets.department=tickerr_ticket_departments.id WHERE (`agentid`=?) AND `status`=3 AND (`tickerr_tickets`.`id` LIKE ? OR `tickerr_tickets`.`date` LIKE ? OR `last_update` LIKE ? OR `subject` LIKE ? OR `content` LIKE ? OR `tickerr_ticket_departments`.`name` LIKE ?)";
		$query = $this->db->query($sql, array($this->agent_id, $search, $search, $search, $search, $search, $search));
		$row = $query->row();
		return $row->c;
	}
	
	public function count_closed_tickets() {
		$sql = "SELECT COUNT(*) as c FROM `tickerr_tickets` WHERE `agentid`={$this->agent_id} AND `status`=3";
		$query = $this->db->query($sql);
		$row = $query->row();
		return $row->c;
	}
	/**************** ENDS BLOCK OF 3 FUNCTIONS ****************/
	
	
	/**************** STARTS BLOCK OF 3 FUNCTIONS ****************
	 * These functions help display tables with options to search and order
	 * data by column
	 *
	 * Purpose of this specific block:
	 * Get list of pending tickets from the database
	 *
	*/
	public function get_pending_tickets($rows = 20, $starting = 0, $order_by = 'last_update', $order = 'DESC', $search = false) {
		if($search == false) {
			$sql = "SELECT tickerr_tickets.*, tickerr_ticket_departments.name as department_name, CASE WHEN guest_name != '' THEN guest_name ELSE tickerr_users.name END AS client_final_name FROM tickerr_tickets INNER JOIN tickerr_ticket_departments ON tickerr_tickets.department=tickerr_ticket_departments.id LEFT JOIN tickerr_users ON tickerr_tickets.userid = tickerr_users.id WHERE `agentid`={$this->agent_id} AND `status`=2 ORDER BY `$order_by` $order LIMIT $starting,$rows";
			$query = $this->db->query($sql);
		}else{
			$search = "%$search%";
			$sql = "SELECT tickerr_tickets.*, tickerr_ticket_departments.name as department_name, CASE WHEN guest_name != '' THEN guest_name ELSE tickerr_users.name END AS client_final_name FROM tickerr_tickets INNER JOIN tickerr_ticket_departments ON tickerr_tickets.department=tickerr_ticket_departments.id LEFT JOIN tickerr_users ON tickerr_tickets.userid = tickerr_users.id WHERE (`agentid`=?) AND `status`=2 AND (`tickerr_tickets`.`id` LIKE ? OR `tickerr_tickets`.`date` LIKE ? OR `last_update` LIKE ? OR `subject` LIKE ? OR `content` LIKE ? OR `tickerr_ticket_departments`.`name` LIKE ?) ORDER BY `$order_by` $order LIMIT $starting,$rows";
			$query = $this->db->query($sql, array($this->agent_id, $search, $search, $search, $search, $search, $search));
		}
		return $query;
	}
	
	public function count_search_pending_tickets($search) {
		$search = "%$search%";
		$sql = "SELECT COUNT(*) as c, tickerr_tickets.*, tickerr_ticket_departments.name as department_name FROM tickerr_tickets INNER JOIN tickerr_ticket_departments ON tickerr_tickets.department=tickerr_ticket_departments.id WHERE (`agentid`=?) AND `status`=2 AND (`tickerr_tickets`.`id` LIKE ? OR `tickerr_tickets`.`date` LIKE ? OR `last_update` LIKE ? OR `subject` LIKE ? OR `content` LIKE ? OR `tickerr_ticket_departments`.`name` LIKE ?)";
		$query = $this->db->query($sql, array($this->agent_id, $search, $search, $search, $search, $search, $search));
		$row = $query->row();
		return $row->c;
	}
	
	public function count_pending_tickets_() {
		$sql = "SELECT COUNT(*) as c FROM `tickerr_tickets` WHERE `agentid`={$this->agent_id} AND `status`=2";
		$query = $this->db->query($sql);
		$row = $query->row();
		return $row->c;
	}
	/**************** ENDS BLOCK OF 3 FUNCTIONS ****************/
	
	
	/**************** STARTS BLOCK OF 3 FUNCTIONS ****************
	 * These functions help display tables with options to search and order
	 * data by column
	 *
	 * Purpose of this specific block:
	 * Get list of free bug reports from the database
	 *
	*/
	public function get_free_bugs($rows = 20, $starting = 0, $order_by = 'last_update', $order = 'DESC', $search = false) {
		if($search == false) {
			$sql = "SELECT tickerr_bugs.*, tickerr_bug_departments.name as department_name, CASE WHEN guest_name != '' THEN guest_name ELSE tickerr_users.name END AS client_final_name FROM tickerr_bugs INNER JOIN tickerr_bug_departments ON tickerr_bugs.department=tickerr_bug_departments.id LEFT JOIN tickerr_users ON tickerr_bugs.userid = tickerr_users.id WHERE ({$this->str_agent_bdepartments}) AND `agentid`=0 ORDER BY `$order_by` $order LIMIT $starting,$rows";
			$query = $this->db->query($sql);
		}else{
			$search = "%$search%";
			$sql = "SELECT tickerr_bugs.*, tickerr_bug_departments.name as department_name, CASE WHEN guest_name != '' THEN guest_name ELSE tickerr_users.name END AS client_final_name FROM tickerr_bugs INNER JOIN tickerr_bug_departments ON tickerr_bugs.department=tickerr_bug_departments.id LEFT JOIN tickerr_users ON tickerr_bugs.userid = tickerr_users.id WHERE ({$this->str_agent_bdepartments}) AND (`agentid`=?) AND (`tickerr_bugs`.`id` LIKE ? OR `tickerr_bugs`.`date` LIKE ? OR `last_update` LIKE ? OR `subject` LIKE ? OR `content` LIKE ? OR `tickerr_bug_departments`.`name` LIKE ?) ORDER BY `$order_by` $order LIMIT $starting,$rows";
			$query = $this->db->query($sql, array(0, $search, $search, $search, $search, $search, $search));
		}
		return $query;
	}
	
	public function count_search_free_bugs($search) {
		$search = "%$search%";
		$sql = "SELECT COUNT(*) as c, tickerr_bugs.*, tickerr_bug_departments.name as department_name FROM tickerr_bugs INNER JOIN tickerr_bug_departments ON tickerr_bugs.department=tickerr_bug_departments.id WHERE ({$this->str_agent_bdepartments}) AND (`agentid`=?) AND (`tickerr_bugs`.`id` LIKE ? OR `tickerr_bugs`.`date` LIKE ? OR `last_update` LIKE ? OR `subject` LIKE ? OR `content` LIKE ? OR `tickerr_bug_departments`.`name` LIKE ?)";
		$query = $this->db->query($sql, array(0, $search, $search, $search, $search, $search, $search));
		$row = $query->row();
		return $row->c;
	}
	
	public function count_free_bugs() {
		$sql = "SELECT COUNT(*) as c FROM `tickerr_bugs` WHERE ({$this->str_agent_bdepartments}) AND `agentid`=0";
		$query = $this->db->query($sql);
		$row = $query->row();
		return $row->c;
	}
	/**************** ENDS BLOCK OF 3 FUNCTIONS ****************/
	
	
	/**************** STARTS BLOCK OF 3 FUNCTIONS ****************
	 * These functions help display tables with options to search and order
	 * data by column
	 *
	 * Purpose of this specific block:
	 * Get list of this agent's taken bug reports
	 *
	*/
	public function get_my_bugs($rows = 20, $starting = 0, $order_by = 'last_update', $order = 'DESC', $search = false) {
		if($search == false) {
			$sql = "SELECT tickerr_bugs.*, tickerr_bug_departments.name as department_name, CASE WHEN guest_name != '' THEN guest_name ELSE tickerr_users.name END AS client_final_name FROM tickerr_bugs INNER JOIN tickerr_bug_departments ON tickerr_bugs.department=tickerr_bug_departments.id LEFT JOIN tickerr_users ON tickerr_bugs.userid = tickerr_users.id WHERE `agentid`={$this->agent_id} ORDER BY `$order_by` $order LIMIT $starting,$rows";
			$query = $this->db->query($sql);
		}else{
			$search = "%$search%";
			$sql = "SELECT tickerr_bugs.*, tickerr_bug_departments.name as department_name, CASE WHEN guest_name != '' THEN guest_name ELSE tickerr_users.name END AS client_final_name FROM tickerr_bugs INNER JOIN tickerr_bug_departments ON tickerr_bugs.department=tickerr_bug_departments.id LEFT JOIN tickerr_users ON tickerr_bugs.userid = tickerr_users.id WHERE `agentid`=? AND (`tickerr_bugs`.`id` LIKE ? OR `tickerr_bugs`.`date` LIKE ? OR `last_update` LIKE ? OR `subject` LIKE ? OR `content` LIKE ? OR `tickerr_bug_departments`.`name` LIKE ?) ORDER BY `$order_by` $order LIMIT $starting,$rows";
			$query = $this->db->query($sql, array($this->agent_id, $search, $search, $search, $search, $search, $search));
		}
		return $query;
	}
	
	public function count_search_my_bugs($search) {
		$search = "%$search%";
		$sql = "SELECT COUNT(*) as c, tickerr_bugs.*, tickerr_bug_departments.name as department_name FROM tickerr_bugs INNER JOIN tickerr_bug_departments ON tickerr_bugs.department=tickerr_bug_departments.id WHERE (`agentid`=?) AND (`tickerr_bugs`.`id` LIKE ? OR `tickerr_bugs`.`date` LIKE ? OR `last_update` LIKE ? OR `subject` LIKE ? OR `content` LIKE ? OR `tickerr_bug_departments`.`name` LIKE ?)";
		$query = $this->db->query($sql, array($this->agent_id, $search, $search, $search, $search, $search, $search));
		$row = $query->row();
		return $row->c;
	}
	
	public function count_my_bugs() {
		$sql = "SELECT COUNT(*) as c FROM `tickerr_bugs` WHERE `agentid`={$this->agent_id}";
		$query = $this->db->query($sql);
		$row = $query->row();
		return $row->c;
	}
	/**************** ENDS BLOCK OF 3 FUNCTIONS ****************/
	
	
	/**************** STARTS BLOCK OF 3 FUNCTIONS ****************
	 * These functions help display tables with options to search and order
	 * data by column
	 *
	 * Purpose of this specific block:
	 * Get list of solved bug reports from the database
	 *
	*/
	public function get_solved_bugs($rows = 20, $starting = 0, $order_by = 'last_update', $order = 'DESC', $search = false) {
		if($search == false) {
			$sql = "SELECT tickerr_bugs.*, tickerr_bug_departments.name as department_name, CASE WHEN guest_name != '' THEN guest_name ELSE tickerr_users.name END AS client_final_name FROM tickerr_bugs INNER JOIN tickerr_bug_departments ON tickerr_bugs.department=tickerr_bug_departments.id LEFT JOIN tickerr_users ON tickerr_bugs.userid = tickerr_users.id WHERE `agentid`={$this->agent_id} AND `status`=3 ORDER BY `$order_by` $order LIMIT $starting,$rows";
			$query = $this->db->query($sql);
		}else{
			$search = "%$search%";
			$sql = "SELECT tickerr_bugs.*, tickerr_bug_departments.name as department_name, CASE WHEN guest_name != '' THEN guest_name ELSE tickerr_users.name END AS client_final_name FROM tickerr_bugs INNER JOIN tickerr_bug_departments ON tickerr_bugs.department=tickerr_bug_departments.id LEFT JOIN tickerr_users ON tickerr_bugs.userid = tickerr_users.id WHERE `agentid`={$this->agent_id} AND `status`=3 AND (`tickerr_bugs`.`id` LIKE ? OR `tickerr_bugs`.`date` LIKE ? OR `last_update` LIKE ? OR `subject` LIKE ? OR `content` LIKE ? OR `tickerr_bug_departments`.`name` LIKE ?) ORDER BY `$order_by` $order LIMIT $starting,$rows";
			$query = $this->db->query($sql, array(0, $search, $search, $search, $search, $search, $search));
		}
		return $query;
	}
	
	public function count_search_solved_bugs($search) {
		$search = "%$search%";
		$sql = "SELECT COUNT(*) as c, tickerr_bugs.*, tickerr_bug_departments.name as department_name FROM tickerr_bugs INNER JOIN tickerr_bug_departments ON tickerr_bugs.department=tickerr_bug_departments.id WHERE (`agentid`=?) AND `status`=3 AND (`tickerr_tickets`.`id` LIKE ? OR `tickerr_bugs`.`date` LIKE ? OR `last_update` LIKE ? OR `subject` LIKE ? OR `content` LIKE ? OR `tickerr_bug_departments`.`name` LIKE ?)";
		$query = $this->db->query($sql, array($this->agent_id, $search, $search, $search, $search, $search, $search));
		$row = $query->row();
		return $row->c;
	}
	
	public function count_solved_bugs() {
		$sql = "SELECT COUNT(*) as c FROM `tickerr_bugs` WHERE `agentid`={$this->agent_id} AND `status`=3";
		$query = $this->db->query($sql);
		$row = $query->row();
		return $row->c;
	}
	/**************** ENDS BLOCK OF 3 FUNCTIONS ****************/
}