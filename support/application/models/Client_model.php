<?php

class Client_model extends CI_Model {
	private $client_id = false;
	
	// Sets the client ID to an internal var
	public function set_client_id($id) {
		$this->client_id = (int)$id;
	}
	
	
	/**************** STARTS BLOCK OF 3 FUNCTIONS ****************
	 * These functions help display tables with options to search and order
	 * data by column
	 *
	 * Purpose of this specific block:
	 * Get list of all tickets
	 *
	*/
	public function get_all_tickets($rows = 20, $starting = 0, $order_by = 'last_update', $order = 'DESC', $search = false) {
		if($search == false) {
			$sql = "SELECT tickerr_tickets.*, tickerr_ticket_departments.name as department_name FROM tickerr_tickets INNER JOIN tickerr_ticket_departments ON tickerr_tickets.department=tickerr_ticket_departments.id WHERE `userid`={$this->client_id} ORDER BY `$order_by` $order LIMIT $starting,$rows";
			$query = $this->db->query($sql);
		}else{
			$search = "%$search%";
			$sql = "SELECT tickerr_tickets.*, tickerr_ticket_departments.name as department_name FROM tickerr_tickets INNER JOIN tickerr_ticket_departments ON tickerr_tickets.department=tickerr_ticket_departments.id WHERE (`userid`=?) AND (`tickerr_tickets`.`id` LIKE ? OR `tickerr_tickets`.`date` LIKE ? OR `last_update` LIKE ? OR `subject` LIKE ? OR `content` LIKE ? OR `tickerr_ticket_departments`.`name` LIKE ?) ORDER BY `$order_by` $order LIMIT $starting,$rows";
			$query = $this->db->query($sql, array($this->client_id, $search, $search, $search, $search, $search, $search));
		}
		return $query;
	}
	public function count_search_all_tickets($search) {
		$search = "%$search%";
		$sql = "SELECT COUNT(*) as c, tickerr_tickets.*, tickerr_ticket_departments.name as department_name FROM tickerr_tickets INNER JOIN tickerr_ticket_departments ON tickerr_tickets.department=tickerr_ticket_departments.id WHERE (`userid`=?) AND (`tickerr_tickets`.`id` LIKE ? OR `tickerr_tickets`.`date` LIKE ? OR `last_update` LIKE ? OR `subject` LIKE ? OR `content` LIKE ? OR `tickerr_ticket_departments`.`name` LIKE ?)";
		$query = $this->db->query($sql, array($this->client_id, $search, $search, $search, $search, $search, $search));
		$row = $query->row();
		return $row->c;
	}
	public function count_all_tickets() {
		$sql = "SELECT COUNT(*) as c FROM `tickerr_tickets` WHERE `userid`={$this->client_id}";
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
	 * Get list of new tickets
	 *
	*/
	public function get_new_tickets($rows = 20, $starting = 0, $order_by = 'last_update', $order = 'DESC', $search = false) {
		if($search == false) {
			$sql = "SELECT tickerr_tickets.*, tickerr_ticket_departments.name as department_name FROM tickerr_tickets INNER JOIN tickerr_ticket_departments ON tickerr_tickets.department=tickerr_ticket_departments.id WHERE `userid`={$this->client_id} AND `agentid`=0 ORDER BY `$order_by` $order LIMIT $starting,$rows";
			$query = $this->db->query($sql);
		}else{
			$search = "%$search%";
			$sql = "SELECT tickerr_tickets.*, tickerr_ticket_departments.name as department_name FROM tickerr_tickets INNER JOIN tickerr_ticket_departments ON tickerr_tickets.department=tickerr_ticket_departments.id WHERE (`userid`=?) AND `agentid`=0 AND (`tickerr_tickets`.`id` LIKE ? OR `tickerr_tickets`.`date` LIKE ? OR `last_update` LIKE ? OR `subject` LIKE ? OR `content` LIKE ? OR `tickerr_ticket_departments`.`name` LIKE ?) ORDER BY `$order_by` $order LIMIT $starting,$rows";
			$query = $this->db->query($sql, array($this->client_id, $search, $search, $search, $search, $search, $search));
		}
		return $query;
	}
	public function count_search_new_tickets($search) {
		$search = "%$search%";
		$sql = "SELECT COUNT(*) as c, tickerr_tickets.*, tickerr_ticket_departments.name as department_name FROM tickerr_tickets INNER JOIN tickerr_ticket_departments ON tickerr_tickets.department=tickerr_ticket_departments.id WHERE (`userid`=?) AND `agentid`=0 AND (`tickerr_tickets`.`id` LIKE ? OR `tickerr_tickets`.`date` LIKE ? OR `last_update` LIKE ? OR `subject` LIKE ? OR `content` LIKE ? OR `tickerr_ticket_departments`.`name` LIKE ?)";
		$query = $this->db->query($sql, array($this->client_id, $search, $search, $search, $search, $search, $search));
		$row = $query->row();
		return $row->c;
	}
	public function count_new_tickets() {
		$sql = "SELECT COUNT(*) as c FROM `tickerr_tickets` WHERE `userid`={$this->client_id} AND `agentid`=0";
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
	 * Get list of open tickets
	 *
	*/
	public function get_open_tickets($rows = 20, $starting = 0, $order_by = 'last_update', $order = 'DESC', $search = false) {
		if($search == false) {
			$sql = "SELECT tickerr_tickets.*, tickerr_ticket_departments.name as department_name FROM tickerr_tickets INNER JOIN tickerr_ticket_departments ON tickerr_tickets.department=tickerr_ticket_departments.id WHERE `userid`={$this->client_id} AND `status`=2 ORDER BY `$order_by` $order LIMIT $starting,$rows";
			$query = $this->db->query($sql);
		}else{
			$search = "%$search%";
			$sql = "SELECT tickerr_tickets.*, tickerr_ticket_departments.name as department_name FROM tickerr_tickets INNER JOIN tickerr_ticket_departments ON tickerr_tickets.department=tickerr_ticket_departments.id WHERE (`userid`=?) AND `status`=2 AND (`tickerr_tickets`.`id` LIKE ? OR `tickerr_tickets`.`date` LIKE ? OR `last_update` LIKE ? OR `subject` LIKE ? OR `content` LIKE ? OR `tickerr_ticket_departments`.`name` LIKE ?) ORDER BY `$order_by` $order LIMIT $starting,$rows";
			$query = $this->db->query($sql, array($this->client_id, $search, $search, $search, $search, $search, $search));
		}
		return $query;
	}
	public function count_search_open_tickets($search) {
		$search = "%$search%";
		$sql = "SELECT COUNT(*) as c, tickerr_tickets.*, tickerr_ticket_departments.name as department_name FROM tickerr_tickets INNER JOIN tickerr_ticket_departments ON tickerr_tickets.department=tickerr_ticket_departments.id WHERE (`userid`=?) AND `status`=2 AND (`tickerr_tickets`.`id` LIKE ? OR `tickerr_tickets`.`date` LIKE ? OR `last_update` LIKE ? OR `subject` LIKE ? OR `content` LIKE ? OR `tickerr_ticket_departments`.`name` LIKE ?)";
		$query = $this->db->query($sql, array($this->client_id, $search, $search, $search, $search, $search, $search));
		$row = $query->row();
		return $row->c;
	}
	public function count_open_tickets() {
		$sql = "SELECT COUNT(*) as c FROM `tickerr_tickets` WHERE `userid`={$this->client_id} AND `status`=2";
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
	 * Get list of closed tickets
	 *
	*/
	public function get_closed_tickets($rows = 20, $starting = 0, $order_by = 'last_update', $order = 'DESC', $search = false) {
		if($search == false) {
			$sql = "SELECT tickerr_tickets.*, tickerr_ticket_departments.name as department_name FROM tickerr_tickets INNER JOIN tickerr_ticket_departments ON tickerr_tickets.department=tickerr_ticket_departments.id WHERE `userid`={$this->client_id} AND `status`=3 ORDER BY `$order_by` $order LIMIT $starting,$rows";
			$query = $this->db->query($sql);
		}else{
			$search = "%$search%";
			$sql = "SELECT tickerr_tickets.*, tickerr_ticket_departments.name as department_name FROM tickerr_tickets INNER JOIN tickerr_ticket_departments ON tickerr_tickets.department=tickerr_ticket_departments.id WHERE (`userid`=?) AND `status`=3 AND (`tickerr_tickets`.`id` LIKE ? OR `tickerr_tickets`.`date` LIKE ? OR `last_update` LIKE ? OR `subject` LIKE ? OR `content` LIKE ? OR `tickerr_ticket_departments`.`name` LIKE ?) ORDER BY `$order_by` $order LIMIT $starting,$rows";
			$query = $this->db->query($sql, array($this->client_id, $search, $search, $search, $search, $search, $search));
		}
		return $query;
	}
	public function count_search_closed_tickets($search) {
		$search = "%$search%";
		$sql = "SELECT COUNT(*) as c, tickerr_tickets.*, tickerr_ticket_departments.name as department_name FROM tickerr_tickets INNER JOIN tickerr_ticket_departments ON tickerr_tickets.department=tickerr_ticket_departments.id WHERE (`userid`=?) AND `status`=3 AND (`tickerr_tickets`.`id` LIKE ? OR `tickerr_tickets`.`date` LIKE ? OR `last_update` LIKE ? OR `subject` LIKE ? OR `content` LIKE ? OR `tickerr_ticket_departments`.`name` LIKE ?)";
		$query = $this->db->query($sql, array($this->client_id, $search, $search, $search, $search, $search, $search));
		$row = $query->row();
		return $row->c;
	}
	public function count_closed_tickets() {
		$sql = "SELECT COUNT(*) as c FROM `tickerr_tickets` WHERE `userid`={$this->client_id} AND `status`=3";
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
	 * Get list of pending tickets
	 *
	*/
	public function get_pending_tickets($rows = 20, $starting = 0, $order_by = 'last_update', $order = 'DESC', $search = false) {
		if($search == false) {
			$sql = "SELECT tickerr_tickets.*, tickerr_ticket_departments.name as department_name FROM tickerr_tickets INNER JOIN tickerr_ticket_departments ON tickerr_tickets.department=tickerr_ticket_departments.id WHERE `userid`={$this->client_id} AND `status`=1 AND `agentid`!=0 ORDER BY `$order_by` $order LIMIT $starting,$rows";
			$query = $this->db->query($sql);
		}else{
			$search = "%$search%";
			$sql = "SELECT tickerr_tickets.*, tickerr_ticket_departments.name as department_name FROM tickerr_tickets INNER JOIN tickerr_ticket_departments ON tickerr_tickets.department=tickerr_ticket_departments.id WHERE (`userid`=?) AND `status`=1 AND `agentid`!=0 AND (`tickerr_tickets`.`id` LIKE ? OR `tickerr_tickets`.`date` LIKE ? OR `last_update` LIKE ? OR `subject` LIKE ? OR `content` LIKE ? OR `tickerr_ticket_departments`.`name` LIKE ?) ORDER BY `$order_by` $order LIMIT $starting,$rows";
			$query = $this->db->query($sql, array($this->client_id, $search, $search, $search, $search, $search, $search));
		}
		return $query;
	}
	public function count_search_pending_tickets($search) {
		$search = "%$search%";
		$sql = "SELECT COUNT(*) as c, tickerr_tickets.*, tickerr_ticket_departments.name as department_name FROM tickerr_tickets INNER JOIN tickerr_ticket_departments ON tickerr_tickets.department=tickerr_ticket_departments.id WHERE (`userid`=?) AND `status`=1 AND `agentid`!=0 AND (`tickerr_tickets`.`id` LIKE ? OR `tickerr_tickets`.`date` LIKE ? OR `last_update` LIKE ? OR `subject` LIKE ? OR `content` LIKE ? OR `tickerr_ticket_departments`.`name` LIKE ?)";
		$query = $this->db->query($sql, array($this->client_id, $search, $search, $search, $search, $search, $search));
		$row = $query->row();
		return $row->c;
	}
	public function count_pending_tickets() {
		$sql = "SELECT COUNT(*) as c FROM `tickerr_tickets` WHERE `userid`={$this->client_id} AND `status`=1 AND `agentid`!=0";
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
	 * Get list of all bug reports
	 *
	*/
	public function get_all_bugs($rows = 20, $starting = 0, $order_by = 'last_update', $order = 'DESC', $search = false) {
		if($search == false) {
			$sql = "SELECT tickerr_bugs.*, tickerr_bug_departments.name as department_name, CASE WHEN agentid != '0' THEN tickerr_users.name ELSE 'N/A' END AS agent_final_name FROM tickerr_bugs INNER JOIN tickerr_bug_departments ON tickerr_bugs.department=tickerr_bug_departments.id LEFT JOIN tickerr_users ON tickerr_bugs.agentid = tickerr_users.id WHERE `userid`={$this->client_id} ORDER BY `$order_by` $order LIMIT $starting,$rows";
			$query = $this->db->query($sql);
		}else{
			$search = "%$search%";
			$sql = "SELECT tickerr_bugs.*, tickerr_bug_departments.name as department_name, CASE WHEN agentid != '0' THEN tickerr_users.name ELSE 'N/A' END AS agent_final_name FROM tickerr_bugs INNER JOIN tickerr_bug_departments ON tickerr_bugs.department=tickerr_bug_departments.id LEFT JOIN tickerr_users ON tickerr_bugs.agentid = tickerr_users.id WHERE (`userid`=?) AND (`tickerr_bugs`.`id` LIKE ? OR `tickerr_bugs`.`date` LIKE ? OR `last_update` LIKE ? OR `subject` LIKE ? OR `content` LIKE ? OR `tickerr_bug_departments`.`name` LIKE ?) ORDER BY `$order_by` $order LIMIT $starting,$rows";
			$query = $this->db->query($sql, array($this->client_id, $search, $search, $search, $search, $search, $search));
		}
		return $query;
	}
	public function count_search_all_bugs($search) {
		$search = "%$search%";
		$sql = "SELECT COUNT(*) as c, tickerr_bugs.*, tickerr_bug_departments.name as department_name FROM tickerr_bugs INNER JOIN tickerr_bug_departments ON tickerr_bugs.department=tickerr_bug_departments.id WHERE (`userid`=?) AND (`tickerr_tickets`.`id` LIKE ? OR `tickerr_bugs`.`date` LIKE ? OR `last_update` LIKE ? OR `subject` LIKE ? OR `content` LIKE ? OR `tickerr_bug_departments`.`name` LIKE ?)";
		$query = $this->db->query($sql, array($this->client_id, $search, $search, $search, $search, $search, $search));
		$row = $query->row();
		return $row->c;
	}
	public function count_all_bugs() {
		$sql = "SELECT COUNT(*) as c FROM `tickerr_bugs` WHERE `userid`={$this->client_id}";
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
	 * Get list of solved bug reports
	 *
	*/
	public function get_solved_bugs($rows = 20, $starting = 0, $order_by = 'last_update', $order = 'DESC', $search = false) {
		if($search == false) {
			$sql = "SELECT tickerr_bugs.*, tickerr_bug_departments.name as department_name, CASE WHEN agentid != '0' THEN tickerr_users.name ELSE 'N/A' END AS agent_final_name FROM tickerr_bugs INNER JOIN tickerr_bug_departments ON tickerr_bugs.department=tickerr_bug_departments.id LEFT JOIN tickerr_users ON tickerr_bugs.agentid = tickerr_users.id WHERE `userid`={$this->client_id} AND `status`=3 ORDER BY `$order_by` $order LIMIT $starting,$rows";
			$query = $this->db->query($sql);
		}else{
			$search = "%$search%";
			$sql = "SELECT tickerr_bugs.*, tickerr_bug_departments.name as department_name, CASE WHEN agentid != '0' THEN tickerr_users.name ELSE 'N/A' END AS agent_final_name FROM tickerr_bugs INNER JOIN tickerr_bug_departments ON tickerr_bugs.department=tickerr_bug_departments.id LEFT JOIN tickerr_users ON tickerr_bugs.agentid = tickerr_users.id WHERE (`userid`=?) AND `status`=3 AND (`tickerr_bugs`.`id` LIKE ? OR `tickerr_bugs`.`date` LIKE ? OR `last_update` LIKE ? OR `subject` LIKE ? OR `content` LIKE ? OR `tickerr_bug_departments`.`name` LIKE ?) ORDER BY `$order_by` $order LIMIT $starting,$rows";
			$query = $this->db->query($sql, array($this->client_id, $search, $search, $search, $search, $search, $search));
		}
		return $query;
	}
	public function count_search_solved_bugs($search) {
		$search = "%$search%";
		$sql = "SELECT COUNT(*) as c, tickerr_bugs.*, tickerr_bug_departments.name as department_name FROM tickerr_bugs INNER JOIN tickerr_bug_departments ON tickerr_bugs.department=tickerr_bug_departments.id WHERE (`userid`=?) AND `status`=3 AND (`tickerr_bugs`.`id` LIKE ? OR `tickerr_bugs`.`date` LIKE ? OR `last_update` LIKE ? OR `subject` LIKE ? OR `content` LIKE ? OR `tickerr_bug_departments`.`name` LIKE ?)";
		$query = $this->db->query($sql, array($this->client_id, $search, $search, $search, $search, $search, $search));
		$row = $query->row();
		return $row->c;
	}
	public function count_solved_bugs() {
		$sql = "SELECT COUNT(*) as c FROM `tickerr_bugs` WHERE `userid`={$this->client_id} AND `status`=3";
		$query = $this->db->query($sql);
		$row = $query->row();
		return $row->c;
	}
	/**************** ENDS BLOCK OF 3 FUNCTIONS ****************/
	
	
	// Count tickets that doesn't have agent yet
	public function count_no_agent_tickets() {
		$sql = "SELECT COUNT(*) as c FROM `tickerr_tickets` WHERE `userid`={$this->client_id} AND `agentid`=0";
		$query = $this->db->query($sql);
		$row = $query->row();
		return $row->c;
	}
	
	// Count tickets where agent hasn't replied
	public function count_pending_agent_tickets() {
		$sql = "SELECT COUNT(*) as c FROM `tickerr_tickets` WHERE `userid`={$this->client_id} AND `status`=1 AND `agentid`!=0";
		$query = $this->db->query($sql);
		$row = $query->row();
		return $row->c;
	}
	
	// Count solved tickets
	public function count_solved_tickets() {
		$sql = "SELECT COUNT(*) as c FROM `tickerr_tickets` WHERE `userid`={$this->client_id} AND `status`=3";
		$query = $this->db->query($sql);
		$row = $query->row();
		return $row->c;
	}
	
	// Get tickets awaiting client's reply
	public function get_tickets_awaiting() {
		$sql = "SELECT tickerr_tickets.*, CASE WHEN agentid != '0' THEN username ELSE 'N/A' END AS agent_final_name FROM `tickerr_tickets` LEFT JOIN tickerr_users ON tickerr_tickets.agentid = tickerr_users.id WHERE `userid`={$this->client_id} AND `status`=2 ORDER BY `last_update` DESC LIMIT 9";
		$query = $this->db->query($sql);
		return $query;
	}
	
	// Get tickets awaiting agent's reply
	public function get_tickets_awaiting_agent() {
		$sql = "SELECT tickerr_tickets.*, CASE WHEN agentid != '0' THEN username ELSE 'N/A' END AS agent_final_name FROM `tickerr_tickets` LEFT JOIN tickerr_users ON tickerr_tickets.agentid = tickerr_users.id WHERE `userid`={$this->client_id} AND `status`=1 AND `agentid`=0 ORDER BY `last_update` DESC LIMIT 9";
		$query = $this->db->query($sql);
		return $query;
	}
	
	// Get tickets without an agent
	public function get_tickets_without_agent() {
		$sql = "SELECT tickerr_tickets.*, CASE WHEN agentid != '0' THEN username ELSE 'N/A' END AS agent_final_name FROM `tickerr_tickets` LEFT JOIN tickerr_users ON tickerr_tickets.agentid = tickerr_users.id WHERE `userid`={$this->client_id} AND `agentid`=0 ORDER BY `last_update` DESC LIMIT 9";
		$query = $this->db->query($sql);
		return $query;
	}
	
	// Count bug reports without agent
	public function count_pending_bugs() {
		$sql = "SELECT COUNT(*) as c FROM `tickerr_bugs` WHERE `userid`={$this->client_id} AND `status`=1";
		$query = $this->db->query($sql);
		$row = $query->row();
		return $row->c;
	}
	
	// Get bug reports without agent
	public function get_pending_bugs() {
		$sql = "SELECT * FROM `tickerr_bugs` WHERE `userid`={$this->client_id} AND `status`=1 ORDER BY `last_update` DESC LIMIT 9";
		$query = $this->db->query($sql);
		return $query;
	}
	
	// Count pending tickets (agent hasn't replied)
	public function count_pending_c_tickets() {
		$sql = "SELECT COUNT(*) as c FROM `tickerr_tickets` WHERE `userid`={$this->client_id} AND `status`=2";
		$query = $this->db->query($sql);
		$row = $query->row();
		return $row->c;
	}
}