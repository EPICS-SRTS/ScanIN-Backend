<?php

class Admin_model extends CI_Model {
	private $last_confirmation_str = '';
	
	// Return last confirmation str
	public function last_confirmation_str() {
		return $this->last_confirmation_str;
	}
	
	// For statistics - Returns pending bugs
	public function count_pending_bugs() {
		$sql = "SELECT COUNT(*) as c FROM `tickerr_bugs` WHERE `status`=1";
		$query = $this->db->query($sql);
		$row = $query->row();
		return $row->c;
	}
	
	// For statistics - Returns pending tickets
	public function count_pending_tickets() {			
		$sql = "SELECT COUNT(*) as c FROM `tickerr_tickets` WHERE `status`=1";
		$query = $this->db->query($sql);
		$row = $query->row();
		return $row->c;
	}
	
	// For statistics - Returns tickets that doesn't have any agent yet
	public function count_no_agent_tickets() {
		$sql = "SELECT COUNT(*) as c FROM `tickerr_tickets` WHERE `agentid`=0";
		$query = $this->db->query($sql);
		$row = $query->row();
		return $row->c;
	}
	
	// For statistics - Returns tickets where the client hasn't replied
	public function count_pending_client_tickets() {
		$sql = "SELECT COUNT(*) as c FROM `tickerr_tickets` WHERE `status`=2";
		$query = $this->db->query($sql);
		$row = $query->row();
		return $row->c;
	}
	
	// For statistics - Returns solved tickets
	public function count_solved_tickets() {
		$sql = "SELECT COUNT(*) as c FROM `tickerr_tickets` WHERE `status`=3";
		$query = $this->db->query($sql);
		$row = $query->row();
		return $row->c;
	}
	
	// For statistics - Returns general customer satisfaction
	public function get_customer_satisfaction() {
		$sql = "SELECT `rating` FROM `tickerr_tickets` WHERE `rating`!=0.0";
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
	
	// For statistics - Returns top agents based on rating
	public function get_top_agents() {
		$sql = "SELECT * FROM `tickerr_users` LEFT JOIN (SELECT agentid, ROUND(AVG(`rating`), 2) as rating FROM `tickerr_tickets` WHERE `rating` != '' && `rating` != '0' GROUP BY agentid) tt ON `tickerr_users`.`id` = `tt`.`agentid` WHERE `role`='2' OR `role`=3 ORDER BY `rating` DESC LIMIT 4";
		
		$query = $this->db->query($sql);
		return $query;
	}
	
	/******* CODE THAT MAKES GRAPHS WORK *******/
	public function get_first_graph($type) {
		if($type == 'LAST 7 DAYS') {
			// Var declarations
			$search_days_1 = array();
			$search_days_2 = array();
			$labels = array();
			$responsive_labels = array();
			
			// Loop to assign search values
			for($i = 6; $i >= 0; $i--) {
				if($i == 0) {
					$search_days_1[] = date('Y-m-d');
					$search_days_2[] = date('Y-m-d');
					$labels[] = date('d F');
					$responsive_labels[] = date('d');
				}else{
					$search_days_1[] = date('Y-m-d', strtotime($i . ' days ago'));
					$search_days_2[] = date('Y-m-d', strtotime($i . ' days ago'));
					$labels[] = date('d F', strtotime($i . ' days ago'));
					$responsive_labels[] = date('d', strtotime($i . ' days ago'));
				}
			}
			
			// Generate query 1 (to select submitted tickets)
			foreach($search_days_1 as $i => $d)
				$search_days_1[$i] = "SELECT '$d' as `date`, COUNT(*) as c FROM `tickerr_tickets` WHERE DATE(`date`)='$d'";
			$sql1 = implode($search_days_1, ' UNION ');
			$query1 = $this->db->query($sql1);
			
			// Generate query 2 (to select solved tickets)
			foreach($search_days_2 as $i => $d)
				$search_days_2[$i] = "SELECT '$d' as `date`, COUNT(*) as c FROM `tickerr_tickets` WHERE DATE(`last_update`)='$d' AND `status`=3";
			$sql2 = implode($search_days_2, ' UNION ');
			$query2 = $this->db->query($sql2);
			
			$result = array();
			$c = 0;
			foreach($query1->result() as $row) {
				$result['dates'][] = array($labels[$c], $responsive_labels[$c]);
				$result['submitted_tickets'][] = $row->c;
				$c++;
			}
			$c = 0;
			foreach($query2->result() as $row) {
				$result['solved_tickets'][] = $row->c;
				$c++;
			}
			
			return $result;
			
		}elseif($type == 'THIS MONTH') {
			// Var declarations
			$last_day = date('d', strtotime('last day of this month'));
			$search_days_1 = array();
			$search_days_2 = array();
			$labels = array();
			$responsive_labels = array();
			
			// Assign values
			for($i = 1; $i <= $last_day; $i++) {
				if(strlen($i) == 1) $i = "0$i";
				$search_days_1[] = date("Y-m-$i");
				$search_days_2[] = date("Y-m-$i");
				$labels[] = date("$i F");
				$responsive_labels[] = $i;
			}
			
			// Generate query to get submitted tickets
			foreach($search_days_1 as $i => $d)
				$search_days_1[$i] = "SELECT '$d' as `date`, COUNT(*) as c FROM `tickerr_tickets` WHERE DATE(`date`)='$d'";
			$sql1 = implode($search_days_1, ' UNION ');
			$query1 = $this->db->query($sql1);
			
			// Generate query to get solved tickets
			foreach($search_days_2 as $i => $d)
				$search_days_2[$i] = "SELECT '$d' as `date`, COUNT(*) as c FROM `tickerr_tickets` WHERE DATE(`last_update`)='$d' AND `status`=3";
			$sql2 = implode($search_days_2, ' UNION ');
			$query2 = $this->db->query($sql2);
			
			$result = array();
			$c = 0;
			foreach($query1->result() as $row) {
				$result['dates'][] = array($labels[$c], $responsive_labels[$c]);
				$result['submitted_tickets'][] = $row->c;
				$c++;
			}
			$c = 0;
			foreach($query2->result() as $row) {
				$result['solved_tickets'][] = $row->c;
				$c++;
			}
			
			return $result;
		}elseif($type == 'THIS YEAR') {
			// Var declarations
			$year = date('Y');
			$months = array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
			$responsive_labels = array('01','02','03','04','05','06','07','08','09','10','11','12');
			
			// Generate query to get submitted tickets
			foreach($responsive_labels as $month) {
				if($month == '01')
					$sql1 = "SELECT '$month' as `date`, COUNT(*) as c FROM `tickerr_tickets` WHERE YEAR(`date`)=$year AND MONTH(`date`)=$month";
				else
					$sql1 .= " UNION SELECT '$month' as `date`, COUNT(*) as c FROM `tickerr_tickets` WHERE YEAR(`date`)=$year AND MONTH(`date`)=$month";
			}
			$query1 = $this->db->query($sql1);
			
			// Generate query to get solved tickets
			foreach($responsive_labels as $month) {
				if($month == '01')
					$sql2 = "SELECT '$month' as `date`, COUNT(*) as c FROM `tickerr_tickets` WHERE YEAR(`last_update`)=$year AND MONTH(`last_update`)=$month AND `status`=3";
				else
					$sql2 .= " UNION SELECT '$month' as `date`, COUNT(*) as c FROM `tickerr_tickets` WHERE YEAR(`last_update`)=$year AND MONTH(`last_update`)=$month AND `status`=3";
			}
			$query2 = $this->db->query($sql2);
			
			$result = array();
			$c = 0;
			foreach($query1->result() as $row) {
				$result['dates'][] = array($months[$c], $responsive_labels[$c]);
				$result['submitted_tickets'][] = $row->c;
				$c++;
			}
			$c = 0;
			foreach($query2->result() as $row) {
				$result['solved_tickets'][] = $row->c;
				$c++;
			}
			
			return $result;
		}elseif($type == 'LAST 5 YEARS') {
			$years = array();
			for($i = (date('Y')-4); $i <= date('Y'); $i++)
				$years[] = $i;
			
			// Generate query to get submitted tickets
			foreach($years as $year) {
				if($year == date('Y')-4)
					$sql1 = "SELECT '$year' as `date`, COUNT(*) as c FROM `tickerr_tickets` WHERE YEAR(`date`)=$year";
				else
					$sql1 .= " UNION SELECT '$year' as `date`, COUNT(*) as c FROM `tickerr_tickets` WHERE YEAR(`date`)=$year";
			}
			$query1 = $this->db->query($sql1);
			
			// Generate query to get solved tickets
			foreach($years as $year) {
				if($year == date('Y')-4)
					$sql2 = "SELECT '$year' as `date`, COUNT(*) as c FROM `tickerr_tickets` WHERE YEAR(`date`)=$year AND `status`=3";
				else
					$sql2 .= " UNION SELECT '$year' as `date`, COUNT(*) as c FROM `tickerr_tickets` WHERE YEAR(`date`)=$year AND `status`=3";
			}
			$query2 = $this->db->query($sql2);
			
			$result = array();
			$c = 0;
			foreach($query1->result() as $row) {
				$result['years'][] = $years[$c];
				$result['submitted_tickets'][] = $row->c;
				$c++;
			}
			$c = 0;
			foreach($query2->result() as $row) {
				$result['solved_tickets'][] = $row->c;
				$c++;
			}
			
			return $result;
		}
	}
	
	/******* CODE THAT MAKES GRAPHS WORK *******/
	public function get_second_graph($type) {
		if($type == 'LAST 7 DAYS') {
			// Var declarations
			$search_days_1 = array();
			$search_days_2 = array();
			$labels = array();
			$responsive_labels = array();
			
			// Assign values
			for($i = 6; $i >= 0; $i--) {
				if($i == 0) {
					$search_days_1[] = date('Y-m-d');
					$search_days_2[] = date('Y-m-d');
					$labels[] = date('d F');
					$responsive_labels[] = date('d');
				}else{
					$search_days_1[] = date('Y-m-d', strtotime($i . ' days ago'));
					$search_days_2[] = date('Y-m-d', strtotime($i . ' days ago'));
					$labels[] = date('d F', strtotime($i . ' days ago'));
					$responsive_labels[] = date('d', strtotime($i . ' days ago'));
				}
			}
			
			// Generate query that gets reported bugs
			foreach($search_days_1 as $i => $d)
				$search_days_1[$i] = "SELECT '$d' as `date`, COUNT(*) as c FROM `tickerr_bugs` WHERE DATE(`date`)='$d'";
			$sql1 = implode($search_days_1, ' UNION ');
			$query1 = $this->db->query($sql1);
			
			// Generate query that gets solved bugs
			foreach($search_days_2 as $i => $d)
				$search_days_2[$i] = "SELECT '$d' as `date`, COUNT(*) as c FROM `tickerr_bugs` WHERE DATE(`last_update`)='$d' AND `status`=3";
			$sql2 = implode($search_days_2, ' UNION ');
			$query2 = $this->db->query($sql2);
			
			$result = array();
			$c = 0;
			foreach($query1->result() as $row) {
				$result['dates'][] = array($labels[$c], $responsive_labels[$c]);
				$result['reported_bugs'][] = $row->c;
				$c++;
			}
			$c = 0;
			foreach($query2->result() as $row) {
				$result['solved_bugs'][] = $row->c;
				$c++;
			}
			
			return $result;
			
		}elseif($type == 'THIS MONTH') {
			// Var declarations
			$last_day = date('d', strtotime('last day of this month'));
			$search_days_1 = array();
			$search_days_2 = array();
			$labels = array();
			$responsive_labels = array();
			
			// Assign values
			for($i = 1; $i <= $last_day; $i++) {
				if(strlen($i) == 1) $i = "0$i";
				$search_days_1[] = date("Y-m-$i");
				$search_days_2[] = date("Y-m-$i");
				$labels[] = date("$i F");
				$responsive_labels[] = $i;
			}
			
			// Generate query that gets reported bugs
			foreach($search_days_1 as $i => $d)
				$search_days_1[$i] = "SELECT '$d' as `date`, COUNT(*) as c FROM `tickerr_bugs` WHERE DATE(`date`)='$d'";
			$sql1 = implode($search_days_1, ' UNION ');
			$query1 = $this->db->query($sql1);
			
			// Generate query that gets solved bugs
			foreach($search_days_2 as $i => $d)
				$search_days_2[$i] = "SELECT '$d' as `date`, COUNT(*) as c FROM `tickerr_bugs` WHERE DATE(`last_update`)='$d' AND `status`=3";
			$sql2 = implode($search_days_2, ' UNION ');
			$query2 = $this->db->query($sql2);
			
			$result = array();
			$c = 0;
			foreach($query1->result() as $row) {
				$result['dates'][] = array($labels[$c], $responsive_labels[$c]);
				$result['reported_bugs'][] = $row->c;
				$c++;
			}
			$c = 0;
			foreach($query2->result() as $row) {
				$result['solved_bugs'][] = $row->c;
				$c++;
			}
			
			return $result;
		}elseif($type == 'THIS YEAR') {
			// Var declarations
			$year = date('Y');
			$months = array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
			$responsive_labels = array('01','02','03','04','05','06','07','08','09','10','11','12');
			
			// Generate query that gets reported bugs
			foreach($responsive_labels as $month) {
				if($month == '01')
					$sql1 = "SELECT '$month' as `date`, COUNT(*) as c FROM `tickerr_bugs` WHERE YEAR(`date`)=$year AND MONTH(`date`)=$month";
				else
					$sql1 .= " UNION SELECT '$month' as `date`, COUNT(*) as c FROM `tickerr_bugs` WHERE YEAR(`date`)=$year AND MONTH(`date`)=$month";
			}
			$query1 = $this->db->query($sql1);
			
			// Generate query that gets solved bugs
			foreach($responsive_labels as $month) {
				if($month == '01')
					$sql2 = "SELECT '$month' as `date`, COUNT(*) as c FROM `tickerr_bugs` WHERE YEAR(`last_update`)=$year AND MONTH(`last_update`)=$month AND `status`=3";
				else
					$sql2 .= " UNION SELECT '$month' as `date`, COUNT(*) as c FROM `tickerr_bugs` WHERE YEAR(`last_update`)=$year AND MONTH(`last_update`)=$month AND `status`=3";
			}
			$query2 = $this->db->query($sql2);
			
			$result = array();
			$c = 0;
			foreach($query1->result() as $row) {
				$result['dates'][] = array($months[$c], $responsive_labels[$c]);
				$result['reported_bugs'][] = $row->c;
				$c++;
			}
			$c = 0;
			foreach($query2->result() as $row) {
				$result['solved_bugs'][] = $row->c;
				$c++;
			}
			
			return $result;
		}elseif($type == 'LAST 5 YEARS') {
			$years = array();
			for($i = (date('Y')-4); $i <= date('Y'); $i++)
				$years[] = $i;
			
			// Generate query that gets reported bugs
			foreach($years as $year) {
				if($year == date('Y')-4)
					$sql1 = "SELECT '$year' as `date`, COUNT(*) as c FROM `tickerr_bugs` WHERE YEAR(`date`)=$year";
				else
					$sql1 .= " UNION SELECT '$year' as `date`, COUNT(*) as c FROM `tickerr_bugs` WHERE YEAR(`date`)=$year";
			}
			$query1 = $this->db->query($sql1);
			
			// Generate query that gets solved bugs
			foreach($years as $year) {
				if($year == date('Y')-4)
					$sql2 = "SELECT '$year' as `date`, COUNT(*) as c FROM `tickerr_bugs` WHERE YEAR(`date`)=$year AND `status`=3";
				else
					$sql2 .= " UNION SELECT '$year' as `date`, COUNT(*) as c FROM `tickerr_bugs` WHERE YEAR(`date`)=$year AND `status`=3";
			}
			$query2 = $this->db->query($sql2);
			
			$result = array();
			$c = 0;
			foreach($query1->result() as $row) {
				$result['years'][] = $years[$c];
				$result['reported_bugs'][] = $row->c;
				$c++;
			}
			$c = 0;
			foreach($query2->result() as $row) {
				$result['solved_bugs'][] = $row->c;
				$c++;
			}
			
			return $result;
		}
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
			$sql = "SELECT tickerr_tickets.*, tickerr_ticket_departments.name as department_name, CASE WHEN guest_name != '' THEN guest_name ELSE tickerr_users.name END AS client_final_name FROM tickerr_tickets INNER JOIN tickerr_ticket_departments ON tickerr_tickets.department=tickerr_ticket_departments.id LEFT JOIN tickerr_users ON tickerr_tickets.userid = tickerr_users.id ORDER BY `$order_by` $order LIMIT $starting,$rows";
			$query = $this->db->query($sql);
		}else{
			$search = "%$search%";
			$sql = "SELECT tickerr_tickets.*, tickerr_ticket_departments.name as department_name, CASE WHEN guest_name != '' THEN guest_name ELSE tickerr_users.name END AS client_final_name FROM tickerr_tickets INNER JOIN tickerr_ticket_departments ON tickerr_tickets.department=tickerr_ticket_departments.id LEFT JOIN tickerr_users ON tickerr_tickets.userid = tickerr_users.id WHERE `tickerr_tickets`.`id` LIKE ? OR `tickerr_tickets`.`date` LIKE ? OR `last_update` LIKE ? OR `subject` LIKE ? OR `content` LIKE ? OR `tickerr_ticket_departments`.`name` LIKE ? OR (CASE WHEN guest_name != '' THEN guest_name ELSE tickerr_users.name END) LIKE ? OR `tickerr_users`.`username` LIKE ? ORDER BY `$order_by` $order LIMIT $starting,$rows";
			$query = $this->db->query($sql, array($search, $search, $search, $search, $search, $search, $search, $search));
		}
		return $query;
	}
	public function count_search_all_tickets($search) {
		$search = "%$search%";
		$sql = "SELECT COUNT(*) as c, tickerr_tickets.*, tickerr_ticket_departments.name as department_name FROM tickerr_tickets INNER JOIN tickerr_ticket_departments ON tickerr_tickets.department=tickerr_ticket_departments.id LEFT JOIN `tickerr_users` ON `tickerr_tickets`.`userid`=`tickerr_users`.`id` WHERE `tickerr_tickets`.`id` LIKE ? OR `tickerr_tickets`.`date` LIKE ? OR `last_update` LIKE ? OR `subject` LIKE ? OR `content` LIKE ? OR `tickerr_ticket_departments`.`name` LIKE ? OR (CASE WHEN guest_name != '' THEN guest_name ELSE tickerr_users.name END) LIKE ? OR `tickerr_users`.`username` LIKE ?";
		$query = $this->db->query($sql, array($search, $search, $search, $search, $search, $search, $search, $search));
		$row = $query->row();
		return $row->c;
	}
	public function count_all_tickets() {
		$sql = "SELECT COUNT(*) as c FROM `tickerr_tickets`";
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
	public function get_department_new_tickets($dpt, $rows = 20, $starting = 0, $order_by = 'last_update', $order = 'DESC', $search = false) {
		if($search == false) {
			$sql = "SELECT tickerr_tickets.*, tickerr_ticket_departments.name as department_name, CASE WHEN guest_name != '' THEN guest_name ELSE tickerr_users.name END AS client_final_name FROM tickerr_tickets INNER JOIN tickerr_ticket_departments ON tickerr_tickets.department=tickerr_ticket_departments.id LEFT JOIN tickerr_users ON tickerr_tickets.userid = tickerr_users.id WHERE `department`=$dpt AND `agentid`=0 ORDER BY `$order_by` $order LIMIT $starting,$rows";			
			$query = $this->db->query($sql);
		}else{
			$search = "%$search%";
			$sql = "SELECT tickerr_tickets.*, tickerr_ticket_departments.name as department_name, CASE WHEN guest_name != '' THEN guest_name ELSE tickerr_users.name END AS client_final_name FROM tickerr_tickets INNER JOIN tickerr_ticket_departments ON tickerr_tickets.department=tickerr_ticket_departments.id LEFT JOIN tickerr_users ON tickerr_tickets.userid = tickerr_users.id WHERE `department`=$dpt AND `agentid`=0 AND (`tickerr_tickets`.`id` LIKE ? OR `tickerr_tickets`.`date` LIKE ? OR `last_update` LIKE ? OR `subject` LIKE ? OR `content` LIKE ? OR `tickerr_ticket_departments`.`name` LIKE ? OR (CASE WHEN guest_name != '' THEN guest_name ELSE tickerr_users.name END) LIKE ? OR `tickerr_users`.`username` LIKE ?) ORDER BY `$order_by` $order LIMIT $starting,$rows";
			$query = $this->db->query($sql, array($search, $search, $search, $search, $search, $search, $search, $search));
		}
		return $query;
	}
	
	public function count_search_department_new_tickets($dpt, $search) {
		$search = "%$search%";
		$sql = "SELECT COUNT(*) as c, tickerr_tickets.*, tickerr_ticket_departments.name as department_name FROM tickerr_tickets INNER JOIN tickerr_ticket_departments ON tickerr_tickets.department=tickerr_ticket_departments.id LEFT JOIN `tickerr_users` ON `tickerr_tickets`.`userid`=`tickerr_users`.`id` WHERE `department`=$dpt AND `agentid`=0 AND (`tickerr_tickets`.`id` LIKE ? OR `tickerr_tickets`.`date` LIKE ? OR `last_update` LIKE ? OR `subject` LIKE ? OR `content` LIKE ? OR `tickerr_ticket_departments`.`name` LIKE ? OR (CASE WHEN guest_name != '' THEN guest_name ELSE tickerr_users.name END) LIKE ? OR `tickerr_users`.`username` LIKE ?)";
		$query = $this->db->query($sql, array($search, $search, $search, $search, $search, $search, $search, $search));
		$row = $query->row();
		return $row->c;
	}
	
	public function count_department_new_tickets($dpt) {
		$sql = "SELECT COUNT(*) as c FROM `tickerr_tickets` WHERE `department`=$dpt AND `agentid`=0";
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
			$sql = "SELECT tickerr_tickets.*, tickerr_ticket_departments.name as department_name, CASE WHEN guest_name != '' THEN guest_name ELSE tickerr_users.name END AS client_final_name FROM tickerr_tickets INNER JOIN tickerr_ticket_departments ON tickerr_tickets.department=tickerr_ticket_departments.id LEFT JOIN tickerr_users ON tickerr_tickets.userid = tickerr_users.id WHERE `agentid`=0 ORDER BY `$order_by` $order LIMIT $starting,$rows";			
			$query = $this->db->query($sql);
		}else{
			$search = "%$search%";
			$sql = "SELECT tickerr_tickets.*, tickerr_ticket_departments.name as department_name, CASE WHEN guest_name != '' THEN guest_name ELSE tickerr_users.name END AS client_final_name FROM tickerr_tickets INNER JOIN tickerr_ticket_departments ON tickerr_tickets.department=tickerr_ticket_departments.id LEFT JOIN tickerr_users ON tickerr_tickets.userid = tickerr_users.id WHERE `agentid`=0 AND (`tickerr_tickets`.`id` LIKE ? OR `tickerr_tickets`.`date` LIKE ? OR `last_update` LIKE ? OR `subject` LIKE ? OR `content` LIKE ? OR `tickerr_ticket_departments`.`name` LIKE ? OR (CASE WHEN guest_name != '' THEN guest_name ELSE tickerr_users.name END) LIKE ? OR `tickerr_users`.`username` LIKE ?) ORDER BY `$order_by` $order LIMIT $starting,$rows";
			$query = $this->db->query($sql, array($search, $search, $search, $search, $search, $search, $search, $search));
		}
		return $query;
	}
	
	public function count_search_new_tickets($search) {
		$search = "%$search%";
		$sql = "SELECT COUNT(*) as c, tickerr_tickets.*, tickerr_ticket_departments.name as department_name FROM tickerr_tickets INNER JOIN tickerr_ticket_departments ON tickerr_tickets.department=tickerr_ticket_departments.id LEFT JOIN `tickerr_users` ON `tickerr_tickets`.`userid`=`tickerr_users`.`id` WHERE `agentid`=0 AND (`tickerr_tickets`.`id` LIKE ? OR `tickerr_tickets`.`date` LIKE ? OR `last_update` LIKE ? OR `subject` LIKE ? OR `content` LIKE ? OR `tickerr_ticket_departments`.`name` LIKE ? OR (CASE WHEN guest_name != '' THEN guest_name ELSE tickerr_users.name END) LIKE ? OR `tickerr_users`.`username` LIKE ?)";
		$query = $this->db->query($sql, array($search, $search, $search, $search, $search, $search, $search, $search));
		$row = $query->row();
		return $row->c;
	}
	
	public function count_new_tickets() {
		$sql = "SELECT COUNT(*) as c FROM `tickerr_tickets` WHERE `agentid`=0";
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
			$sql = "SELECT tickerr_tickets.*, tickerr_users2.name as agent_name, tickerr_ticket_departments.name as department_name, CASE WHEN guest_name != '' THEN guest_name ELSE tickerr_users1.name END AS client_final_name FROM tickerr_tickets INNER JOIN tickerr_ticket_departments ON tickerr_tickets.department=tickerr_ticket_departments.id LEFT JOIN tickerr_users tickerr_users1 ON tickerr_tickets.userid = tickerr_users1.id LEFT JOIN tickerr_users tickerr_users2 ON tickerr_tickets.agentid = tickerr_users2.id WHERE `status`=1 AND `agentid`!=0 ORDER BY `$order_by` $order LIMIT $starting,$rows";
			$query = $this->db->query($sql);
		}else{
			$search = "%$search%";
			$sql = "SELECT tickerr_tickets.*, tickerr_users2.name as agent_name, tickerr_ticket_departments.name as department_name, CASE WHEN guest_name != '' THEN guest_name ELSE tickerr_users1.name END AS client_final_name FROM tickerr_tickets INNER JOIN tickerr_ticket_departments ON tickerr_tickets.department=tickerr_ticket_departments.id LEFT JOIN tickerr_users tickerr_users2 ON tickerr_tickets.userid = tickerr_user12.id LEFT JOIN tickerr_users tickerr_users2 ON tickerr_tickets.agentid = tickerr_users2.id WHERE `status`=1 AND `agentid`!=0 (`tickerr_tickets`.`id` LIKE ? OR `tickerr_tickets`.`date` LIKE ? OR `last_update` LIKE ? OR `subject` LIKE ? OR `content` LIKE ? OR `tickerr_ticket_departments`.`name` LIKE ? OR (CASE WHEN guest_name != '' THEN guest_name ELSE tickerr_users.name END) LIKE ? OR `tickerr_users`.`username` LIKE ?) ORDER BY `$order_by` $order LIMIT $starting,$rows";
			$query = $this->db->query($sql, array($search, $search, $search, $search, $search, $search, $search, $search));
		}
		return $query;
	}
	
	public function count_search_open_tickets($search) {
		$search = "%$search%";
		$sql = "SELECT COUNT(*) as c, tickerr_tickets.*, tickerr_ticket_departments.name as department_name FROM tickerr_tickets INNER JOIN tickerr_ticket_departments ON tickerr_tickets.department=tickerr_ticket_departments.id LEFT JOIN `tickerr_users` ON `tickerr_tickets`.`userid`=`tickerr_users`.`id` WHERE `status`=1 AND `agentid`!=0 AND (`tickerr_tickets`.`id` LIKE ? OR `tickerr_tickets`.`date` LIKE ? OR `last_update` LIKE ? OR `subject` LIKE ? OR `content` LIKE ? OR `tickerr_ticket_departments`.`name` LIKE ? OR (CASE WHEN guest_name != '' THEN guest_name ELSE tickerr_users.name END) LIKE ? OR `tickerr_users`.`username` LIKE ?)";
		$query = $this->db->query($sql, array($search, $search, $search, $search, $search, $search, $search, $search));
		$row = $query->row();
		return $row->c;
	}
	
	public function count_open_tickets() {
		$sql = "SELECT COUNT(*) as c FROM `tickerr_tickets` WHERE `status`=1 AND `agentid`!=0";
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
			$sql = "SELECT tickerr_tickets.*, tickerr_ticket_departments.name as department_name, CASE WHEN guest_name != '' THEN guest_name ELSE tickerr_users.name END AS client_final_name FROM tickerr_tickets INNER JOIN tickerr_ticket_departments ON tickerr_tickets.department=tickerr_ticket_departments.id LEFT JOIN tickerr_users ON tickerr_tickets.userid = tickerr_users.id WHERE `status`=3 ORDER BY `$order_by` $order LIMIT $starting,$rows";
			$query = $this->db->query($sql);
		}else{
			$search = "%$search%";
			$sql = "SELECT tickerr_tickets.*, tickerr_ticket_departments.name as department_name, CASE WHEN guest_name != '' THEN guest_name ELSE tickerr_users.name END AS client_final_name FROM tickerr_tickets INNER JOIN tickerr_ticket_departments ON tickerr_tickets.department=tickerr_ticket_departments.id LEFT JOIN tickerr_users ON tickerr_tickets.userid = tickerr_users.id WHERE `status`=3 AND (`tickerr_tickets`.`id` LIKE ? OR `tickerr_tickets`.`date` LIKE ? OR `last_update` LIKE ? OR `subject` LIKE ? OR `content` LIKE ? OR `tickerr_ticket_departments`.`name` LIKE ? OR (CASE WHEN guest_name != '' THEN guest_name ELSE tickerr_users.name END) LIKE ? OR `tickerr_users`.`username` LIKE ?) ORDER BY `$order_by` $order LIMIT $starting,$rows";
			
			$query = $this->db->query($sql, array($search, $search, $search, $search, $search, $search, $search, $search));
		}
		return $query;
	}
	
	public function count_search_closed_tickets($search) {
		$search = "%$search%";
		$sql = "SELECT COUNT(*) as c, tickerr_tickets.*, tickerr_ticket_departments.name as department_name FROM tickerr_tickets INNER JOIN tickerr_ticket_departments ON tickerr_tickets.department=tickerr_ticket_departments.id LEFT JOIN `tickerr_users` ON `tickerr_tickets`.`userid`=`tickerr_users`.`id` WHERE `status`=3 AND (`tickerr_tickets`.`id` LIKE ? OR `tickerr_tickets`.`date` LIKE ? OR `last_update` LIKE ? OR `subject` LIKE ? OR `content` LIKE ? OR `tickerr_ticket_departments`.`name` LIKE ? OR (CASE WHEN guest_name != '' THEN guest_name ELSE tickerr_users.name END) LIKE ? OR `tickerr_users`.`username` LIKE ?)";
		$query = $this->db->query($sql, array($search, $search, $search, $search, $search, $search, $search, $search));
		$row = $query->row();
		return $row->c;
	}
	
	public function count_closed_tickets() {
		$sql = "SELECT COUNT(*) as c FROM `tickerr_tickets` WHERE `status`=3";
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
			$sql = "SELECT tickerr_tickets.*, tickerr_ticket_departments.name as department_name, CASE WHEN guest_name != '' THEN guest_name ELSE tickerr_users.name END AS client_final_name FROM tickerr_tickets INNER JOIN tickerr_ticket_departments ON tickerr_tickets.department=tickerr_ticket_departments.id LEFT JOIN tickerr_users ON tickerr_tickets.userid = tickerr_users.id WHERE `status`=2 ORDER BY `$order_by` $order LIMIT $starting,$rows";
			$query = $this->db->query($sql);
		}else{
			$search = "%$search%";
			$sql = "SELECT tickerr_tickets.*, tickerr_ticket_departments.name as department_name, CASE WHEN guest_name != '' THEN guest_name ELSE tickerr_users.name END AS client_final_name FROM tickerr_tickets INNER JOIN tickerr_ticket_departments ON tickerr_tickets.department=tickerr_ticket_departments.id LEFT JOIN tickerr_users ON tickerr_tickets.userid = tickerr_users.id WHERE `status`=2 AND (`tickerr_tickets`.`id` LIKE ? OR `tickerr_tickets`.`date` LIKE ? OR `last_update` LIKE ? OR `subject` LIKE ? OR `content` LIKE ? OR `tickerr_ticket_departments`.`name` LIKE ? OR (CASE WHEN guest_name != '' THEN guest_name ELSE tickerr_users.name END) LIKE ? OR `tickerr_users`.`username` LIKE ?) ORDER BY `$order_by` $order LIMIT $starting,$rows";
			$query = $this->db->query($sql, array($search, $search, $search, $search, $search, $search, $search, $search));
		}
		return $query;
	}
	
	public function count_search_pending_tickets($search) {
		$search = "%$search%";
		$sql = "SELECT COUNT(*) as c, tickerr_tickets.*, tickerr_ticket_departments.name as department_name FROM tickerr_tickets INNER JOIN tickerr_ticket_departments ON tickerr_tickets.department=tickerr_ticket_departments.id LEFT JOIN `tickerr_users` ON `tickerr_tickets`.`userid`=`tickerr_users`.`id` WHERE `status`=2 AND (`tickerr_tickets`.`id` LIKE ? OR `tickerr_tickets`.`date` LIKE ? OR `last_update` LIKE ? OR `subject` LIKE ? OR `content` LIKE ? OR `tickerr_ticket_departments`.`name` LIKE ? OR (CASE WHEN guest_name != '' THEN guest_name ELSE tickerr_users.name END) LIKE ? OR `tickerr_users`.`username` LIKE ?)";
		$query = $this->db->query($sql, array($search, $search, $search, $search, $search, $search, $search, $search));
		$row = $query->row();
		return $row->c;
	}
	
	public function count_pending_tickets_() {
		$sql = "SELECT COUNT(*) as c FROM `tickerr_tickets` WHERE `status`=2";
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
	 * Get list of all bug reports from the database
	 *
	*/
	public function get_all_bugs($rows = 20, $starting = 0, $order_by = 'last_update', $order = 'DESC', $search = false) {
		if($search == false) {			
			$sql = "SELECT tickerr_bugs.*, tickerr_bug_departments.name as department_name, CASE WHEN guest_name != '' THEN guest_name ELSE tickerr_users.name END AS client_final_name FROM tickerr_bugs INNER JOIN tickerr_bug_departments ON tickerr_bugs.department=tickerr_bug_departments.id LEFT JOIN tickerr_users ON tickerr_bugs.userid = tickerr_users.id ORDER BY `$order_by` $order LIMIT $starting,$rows";
			$query = $this->db->query($sql);
		}else{
			$search = "%$search%";
			$sql = "SELECT tickerr_bugs.*, tickerr_bug_departments.name as department_name, CASE WHEN guest_name != '' THEN guest_name ELSE tickerr_users.name END AS client_final_name FROM tickerr_bugs INNER JOIN tickerr_bug_departments ON tickerr_bugs.department=tickerr_bug_departments.id LEFT JOIN tickerr_users ON tickerr_bugs.userid = tickerr_users.id WHERE (`tickerr_bugs`.`id` LIKE ? OR `tickerr_bugs`.`date` LIKE ? OR `last_update` LIKE ? OR `subject` LIKE ? OR `content` LIKE ? OR `tickerr_bug_departments`.`name` LIKE ?) ORDER BY `$order_by` $order LIMIT $starting,$rows";
			$query = $this->db->query($sql, array(0, $search, $search, $search, $search, $search, $search));
		}
		return $query;
	}
	
	public function count_search_all_bugs($search) {
		$search = "%$search%";
		$sql = "SELECT COUNT(*) as c, tickerr_bugs.*, tickerr_bug_departments.name as department_name FROM tickerr_bugs INNER JOIN tickerr_bug_departments ON tickerr_bugs.department=tickerr_bug_departments.id WHERE (`tickerr_bugs`.`id` LIKE ? OR `tickerr_bugs`.`date` LIKE ? OR `last_update` LIKE ? OR `subject` LIKE ? OR `content` LIKE ? OR `tickerr_bug_departments`.`name` LIKE ?)";
		$query = $this->db->query($sql, array($search, $search, $search, $search, $search, $search));
		$row = $query->row();
		return $row->c;
	}
	
	public function count_all_bugs() {
		$sql = "SELECT COUNT(*) as c FROM `tickerr_bugs`";
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
			$sql = "SELECT tickerr_bugs.*, tickerr_bug_departments.name as department_name, CASE WHEN guest_name != '' THEN guest_name ELSE tickerr_users.name END AS client_final_name FROM tickerr_bugs INNER JOIN tickerr_bug_departments ON tickerr_bugs.department=tickerr_bug_departments.id LEFT JOIN tickerr_users ON tickerr_bugs.userid = tickerr_users.id WHERE `agentid`=0 ORDER BY `$order_by` $order LIMIT $starting,$rows";
			$query = $this->db->query($sql);
		}else{
			$search = "%$search%";
			$sql = "SELECT tickerr_bugs.*, tickerr_bug_departments.name as department_name, CASE WHEN guest_name != '' THEN guest_name ELSE tickerr_users.name END AS client_final_name FROM tickerr_bugs INNER JOIN tickerr_bug_departments ON tickerr_bugs.department=tickerr_bug_departments.id LEFT JOIN tickerr_users ON tickerr_bugs.userid = tickerr_users.id WHERE (`agentid`=?) AND (`tickerr_bugs`.`id` LIKE ? OR `tickerr_bugs`.`date` LIKE ? OR `last_update` LIKE ? OR `subject` LIKE ? OR `content` LIKE ? OR `tickerr_bug_departments`.`name` LIKE ?) ORDER BY `$order_by` $order LIMIT $starting,$rows";
			$query = $this->db->query($sql, array(0, $search, $search, $search, $search, $search, $search));
		}
		return $query;
	}
	
	public function count_search_free_bugs($search) {
		$search = "%$search%";
		$sql = "SELECT COUNT(*) as c, tickerr_bugs.*, tickerr_bug_departments.name as department_name FROM tickerr_bugs INNER JOIN tickerr_bug_departments ON tickerr_bugs.department=tickerr_bug_departments.id WHERE (`agentid`=?) AND (`tickerr_bugs`.`id` LIKE ? OR `tickerr_bugs`.`date` LIKE ? OR `last_update` LIKE ? OR `subject` LIKE ? OR `content` LIKE ? OR `tickerr_bug_departments`.`name` LIKE ?)";
		$query = $this->db->query($sql, array(0, $search, $search, $search, $search, $search, $search));
		$row = $query->row();
		return $row->c;
	}
	
	public function count_free_bugs() {
		$sql = "SELECT COUNT(*) as c FROM `tickerr_bugs` WHERE `agentid`=0";
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
			$sql = "SELECT tickerr_bugs.*, tickerr_bug_departments.name as department_name, CASE WHEN guest_name != '' THEN guest_name ELSE tickerr_users.name END AS client_final_name FROM tickerr_bugs INNER JOIN tickerr_bug_departments ON tickerr_bugs.department=tickerr_bug_departments.id LEFT JOIN tickerr_users ON tickerr_bugs.userid = tickerr_users.id WHERE `status`=3 ORDER BY `$order_by` $order LIMIT $starting,$rows";
			$query = $this->db->query($sql);
		}else{
			$search = "%$search%";
			$sql = "SELECT tickerr_bugs.*, tickerr_bug_departments.name as department_name, CASE WHEN guest_name != '' THEN guest_name ELSE tickerr_users.name END AS client_final_name FROM tickerr_bugs INNER JOIN tickerr_bug_departments ON tickerr_bugs.department=tickerr_bug_departments.id LEFT JOIN tickerr_users ON tickerr_bugs.userid = tickerr_users.id WHERE `status`=3 AND (`tickerr_bugs`.`id` LIKE ? OR `tickerr_bugs`.`date` LIKE ? OR `last_update` LIKE ? OR `subject` LIKE ? OR `content` LIKE ? OR `tickerr_bug_departments`.`name` LIKE ?) ORDER BY `$order_by` $order LIMIT $starting,$rows";
			$query = $this->db->query($sql, array(0, $search, $search, $search, $search, $search, $search));
		}
		return $query;
	}
	
	public function count_search_solved_bugs($search) {
		$search = "%$search%";
		$sql = "SELECT COUNT(*) as c, tickerr_bugs.*, tickerr_bug_departments.name as department_name FROM tickerr_bugs INNER JOIN tickerr_bug_departments ON tickerr_bugs.department=tickerr_bug_departments.id WHERE `status`=3 AND (`tickerr_bugs`.`id` LIKE ? OR `tickerr_bugs`.`date` LIKE ? OR `last_update` LIKE ? OR `subject` LIKE ? OR `content` LIKE ? OR `tickerr_bug_departments`.`name` LIKE ?)";
		$query = $this->db->query($sql, array($search, $search, $search, $search, $search, $search));
		$row = $query->row();
		return $row->c;
	}
	
	public function count_solved_bugs() {
		$sql = "SELECT COUNT(*) as c FROM `tickerr_bugs` WHERE `status`=3";
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
	 * Get list of all users from the database
	 *
	*/
	public function get_all_users($rows = 20, $starting = 0, $order_by = 'date', $order = 'DESC', $search = false) {
		if($search == false) {			
			$sql = "SELECT * FROM tickerr_users ORDER BY `$order_by` $order LIMIT $starting,$rows";
			$query = $this->db->query($sql);
		}else{
			$search = "%$search%";
			$sql = "SELECT * FROM tickerr_users WHERE `id` LIKE ? OR `username` LIKE ? OR `name` LIKE ? OR `email` LIKE ? OR `date` LIKE ? ORDER BY `$order_by` $order LIMIT $starting,$rows";
			$query = $this->db->query($sql, array(0, $search, $search, $search, $search, $search));
		}
		return $query;
	}
	
	public function count_search_all_users($search) {
		$search = "%$search%";
		$sql = "SELECT COUNT(*) as c FROM tickerr_users WHERE `id` LIKE ? OR `username` LIKE ? OR `name` LIKE ? OR `email` LIKE ? OR `date` LIKE ?";
		$query = $this->db->query($sql, array($search, $search, $search, $search, $search));
		$row = $query->row();
		return $row->c;
	}
	
	public function count_all_users() {
		$sql = "SELECT COUNT(*) as c FROM `tickerr_users`";
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
	 * Get list of all ticket departments from the database
	 *
	*/
	public function get_tdepartments($rows = 20, $starting = 0, $order_by = 'date', $order = 'DESC', $search = false) {
		if($search == false) {
			$sql = "SELECT * FROM tickerr_ticket_departments ORDER BY `$order_by` $order LIMIT $starting,$rows";
			$query = $this->db->query($sql);
		}else{
			$search = "%$search%";
			$sql = "SELECT * FROM tickerr_ticket_departments WHERE `id` LIKE ? OR `name` LIKE ? OR `date` LIKE ? ORDER BY `$order_by` $order LIMIT $starting,$rows";
			$query = $this->db->query($sql, array($search, $search, $search));
		}
		return $query;
	}
	
	public function count_search_tdepartments($search) {
		$search = "%$search%";
		$sql = "SELECT COUNT(*) as c FROM tickerr_ticket_departments WHERE `id` LIKE ? OR `name` LIKE ? OR `date` LIKE ?";
		$query = $this->db->query($sql, array($search, $search, $search));
		$row = $query->row();
		return $row->c;
	}
	
	public function count_tdepartments() {
		$sql = "SELECT COUNT(*) as c FROM `tickerr_ticket_departments`";
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
	 * Get list of all bug report departments from the database
	 *
	*/
	public function get_bdepartments($rows = 20, $starting = 0, $order_by = 'date', $order = 'DESC', $search = false) {
		if($search == false) {
			$sql = "SELECT * FROM tickerr_bug_departments ORDER BY `$order_by` $order LIMIT $starting,$rows";
			$query = $this->db->query($sql);
		}else{
			$search = "%$search%";
			$sql = "SELECT * FROM tickerr_bug_departments WHERE `id` LIKE ? OR `name` LIKE ? OR `date` LIKE ? ORDER BY `$order_by` $order LIMIT $starting,$rows";
			$query = $this->db->query($sql, array($search, $search, $search));
		}
		return $query;
	}
	
	public function count_search_bdepartments($search) {
		$search = "%$search%";
		$sql = "SELECT COUNT(*) as c FROM tickerr_bug_departments WHERE `id` LIKE ? OR `name` LIKE ? OR `date` LIKE ?";
		$query = $this->db->query($sql, array($search, $search, $search));
		$row = $query->row();
		return $row->c;
	}
	
	public function count_bdepartments() {
		$sql = "SELECT COUNT(*) as c FROM `tickerr_bug_departments`";
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
	 * Get list of all open tickets from a department
	 *
	*/
	public function get_department_open_tickets($dpt, $rows = 20, $starting = 0, $order_by = 'last_update', $order = 'DESC', $search = false) {
		if($search == false) {
			$sql = "SELECT tickerr_tickets.*, tickerr_users2.name as agent_name, tickerr_ticket_departments.name as department_name, CASE WHEN guest_name != '' THEN guest_name ELSE tickerr_users1.name END AS client_final_name FROM tickerr_tickets INNER JOIN tickerr_ticket_departments ON tickerr_tickets.department=tickerr_ticket_departments.id LEFT JOIN tickerr_users tickerr_users1 ON tickerr_tickets.userid = tickerr_users1.id LEFT JOIN tickerr_users tickerr_users2 ON tickerr_tickets.agentid = tickerr_users2.id WHERE `department`=$dpt AND `status`=1 AND `agentid`!=0 ORDER BY `$order_by` $order LIMIT $starting,$rows";
			$query = $this->db->query($sql);
		}else{
			$search = "%$search%";
			$sql = "SELECT tickerr_tickets.*, tickerr_users2.name as agent_name, tickerr_ticket_departments.name as department_name, CASE WHEN guest_name != '' THEN guest_name ELSE tickerr_users1.name END AS client_final_name FROM tickerr_tickets INNER JOIN tickerr_ticket_departments ON tickerr_tickets.department=tickerr_ticket_departments.id LEFT JOIN tickerr_users tickerr_users2 ON tickerr_tickets.userid = tickerr_user12.id LEFT JOIN tickerr_users tickerr_users2 ON tickerr_tickets.agentid = tickerr_users2.id WHERE `department`=$dpt AND `status`=1 AND `agentid`!=0 (`tickerr_tickets`.`id` LIKE ? OR `tickerr_tickets`.`date` LIKE ? OR `last_update` LIKE ? OR `subject` LIKE ? OR `content` LIKE ? OR `tickerr_ticket_departments`.`name` LIKE ? OR (CASE WHEN guest_name != '' THEN guest_name ELSE tickerr_users.name END) LIKE ? OR `tickerr_users`.`username` LIKE ?) ORDER BY `$order_by` $order LIMIT $starting,$rows";
			$query = $this->db->query($sql, array($search, $search, $search, $search, $search, $search, $search, $search));
		}
		return $query;
	}
	
	public function count_search_department_open_tickets($dpt, $search) {
		$search = "%$search%";
		$sql = "SELECT COUNT(*) as c, tickerr_tickets.*, tickerr_ticket_departments.name as department_name FROM tickerr_tickets INNER JOIN tickerr_ticket_departments ON tickerr_tickets.department=tickerr_ticket_departments.id LEFT JOIN `tickerr_users` ON `tickerr_tickets`.`userid`=`tickerr_users`.`id` WHERE `department`=$dpt AND `status`=1 AND `agentid`!=0 AND (`tickerr_tickets`.`id` LIKE ? OR `tickerr_tickets`.`date` LIKE ? OR `last_update` LIKE ? OR `subject` LIKE ? OR `content` LIKE ? OR `tickerr_ticket_departments`.`name` LIKE ? OR (CASE WHEN guest_name != '' THEN guest_name ELSE tickerr_users.name END) LIKE ? OR `tickerr_users`.`username` LIKE ?)";
		$query = $this->db->query($sql, array($search, $search, $search, $search, $search, $search, $search, $search));
		$row = $query->row();
		return $row->c;
	}
	
	public function count_department_open_tickets($dpt) {
		$sql = "SELECT COUNT(*) as c FROM `tickerr_tickets` WHERE `department`=$dpt AND `status`=1 AND `agentid`!=0";
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
	 * Get list of all pending tickets from a department
	 *
	*/
	public function get_department_pending_tickets($dpt, $rows = 20, $starting = 0, $order_by = 'last_update', $order = 'DESC', $search = false) {
		if($search == false) {
			$sql = "SELECT tickerr_tickets.*, tickerr_ticket_departments.name as department_name, CASE WHEN guest_name != '' THEN guest_name ELSE tickerr_users.name END AS client_final_name FROM tickerr_tickets INNER JOIN tickerr_ticket_departments ON tickerr_tickets.department=tickerr_ticket_departments.id LEFT JOIN tickerr_users ON tickerr_tickets.userid = tickerr_users.id WHERE `department`=$dpt AND `status`=2 ORDER BY `$order_by` $order LIMIT $starting,$rows";
			$query = $this->db->query($sql);
		}else{
			$search = "%$search%";
			$sql = "SELECT tickerr_tickets.*, tickerr_ticket_departments.name as department_name, CASE WHEN guest_name != '' THEN guest_name ELSE tickerr_users.name END AS client_final_name FROM tickerr_tickets INNER JOIN tickerr_ticket_departments ON tickerr_tickets.department=tickerr_ticket_departments.id LEFT JOIN tickerr_users ON tickerr_tickets.userid = tickerr_users.id WHERE `department`=$dpt AND `status`=2 AND (`tickerr_tickets`.`id` LIKE ? OR `tickerr_tickets`.`date` LIKE ? OR `last_update` LIKE ? OR `subject` LIKE ? OR `content` LIKE ? OR `tickerr_ticket_departments`.`name` LIKE ? OR (CASE WHEN guest_name != '' THEN guest_name ELSE tickerr_users.name END) LIKE ? OR `tickerr_users`.`username` LIKE ?) ORDER BY `$order_by` $order LIMIT $starting,$rows";
			$query = $this->db->query($sql, array($search, $search, $search, $search, $search, $search, $search, $search));
		}
		return $query;
	}
	
	public function count_search_department_pending_tickets($dpt, $search) {
		$search = "%$search%";
		$sql = "SELECT COUNT(*) as c, tickerr_tickets.*, tickerr_ticket_departments.name as department_name FROM tickerr_tickets INNER JOIN tickerr_ticket_departments ON tickerr_tickets.department=tickerr_ticket_departments.id LEFT JOIN `tickerr_users` ON `tickerr_tickets`.`userid`=`tickerr_users`.`id` WHERE `department`=$dpt AND `status`=2 AND (`tickerr_tickets`.`id` LIKE ? OR `tickerr_tickets`.`date` LIKE ? OR `last_update` LIKE ? OR `subject` LIKE ? OR `content` LIKE ? OR `tickerr_ticket_departments`.`name` LIKE ? OR (CASE WHEN guest_name != '' THEN guest_name ELSE tickerr_users.name END) LIKE ? OR `tickerr_users`.`username` LIKE ?)";
		$query = $this->db->query($sql, array($search, $search, $search, $search, $search, $search, $search, $search));
		$row = $query->row();
		return $row->c;
	}
	
	public function count_department_pending_tickets_($dpt) {
		$sql = "SELECT COUNT(*) as c FROM `tickerr_tickets` WHERE `department`=$dpt AND `status`=2";
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
	 * Get list of all free bug reports from a department
	 *
	*/
	public function get_department_free_bugs($dpt, $rows = 20, $starting = 0, $order_by = 'last_update', $order = 'DESC', $search = false) {
		if($search == false) {			
			$sql = "SELECT tickerr_bugs.*, tickerr_bug_departments.name as department_name, CASE WHEN guest_name != '' THEN guest_name ELSE tickerr_users.name END AS client_final_name FROM tickerr_bugs INNER JOIN tickerr_bug_departments ON tickerr_bugs.department=tickerr_bug_departments.id LEFT JOIN tickerr_users ON tickerr_bugs.userid = tickerr_users.id WHERE `department`=$dpt AND `agentid`=0 ORDER BY `$order_by` $order LIMIT $starting,$rows";
			$query = $this->db->query($sql);
		}else{
			$search = "%$search%";
			$sql = "SELECT tickerr_bugs.*, tickerr_bug_departments.name as department_name, CASE WHEN guest_name != '' THEN guest_name ELSE tickerr_users.name END AS client_final_name FROM tickerr_bugs INNER JOIN tickerr_bug_departments ON tickerr_bugs.department=tickerr_bug_departments.id LEFT JOIN tickerr_users ON tickerr_bugs.userid = tickerr_users.id WHERE `department`=$dpt AND (`agentid`=?) AND (`tickerr_bugs`.`id` LIKE ? OR `tickerr_bugs`.`date` LIKE ? OR `last_update` LIKE ? OR `subject` LIKE ? OR `content` LIKE ? OR `tickerr_bug_departments`.`name` LIKE ?) ORDER BY `$order_by` $order LIMIT $starting,$rows";
			$query = $this->db->query($sql, array(0, $search, $search, $search, $search, $search, $search));
		}
		return $query;
	}
	
	public function count_department_search_free_bugs($dpt, $search) {
		$search = "%$search%";
		$sql = "SELECT COUNT(*) as c, tickerr_bugs.*, tickerr_bug_departments.name as department_name FROM tickerr_bugs INNER JOIN tickerr_bug_departments ON tickerr_bugs.department=tickerr_bug_departments.id WHERE `department`=$dpt AND (`agentid`=?) AND (`tickerr_bugs`.`id` LIKE ? OR `tickerr_bugs`.`date` LIKE ? OR `last_update` LIKE ? OR `subject` LIKE ? OR `content` LIKE ? OR `tickerr_bug_departments`.`name` LIKE ?)";
		$query = $this->db->query($sql, array(0, $search, $search, $search, $search, $search, $search));
		$row = $query->row();
		return $row->c;
	}
	
	public function count_department_free_bugs($dpt) {
		$sql = "SELECT COUNT(*) as c FROM `tickerr_bugs` WHERE `department`=$dpt AND `agentid`=0";
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
	 * Get list of all solved bug reports from a department
	 *
	*/
	public function get_department_solved_bugs($dpt, $rows = 20, $starting = 0, $order_by = 'last_update', $order = 'DESC', $search = false) {
		if($search == false) {			
			$sql = "SELECT tickerr_bugs.*, tickerr_bug_departments.name as department_name, CASE WHEN guest_name != '' THEN guest_name ELSE tickerr_users.name END AS client_final_name FROM tickerr_bugs INNER JOIN tickerr_bug_departments ON tickerr_bugs.department=tickerr_bug_departments.id LEFT JOIN tickerr_users ON tickerr_bugs.userid = tickerr_users.id WHERE `department`=$dpt AND `status`=3 ORDER BY `$order_by` $order LIMIT $starting,$rows";
			$query = $this->db->query($sql);
		}else{
			$search = "%$search%";
			$sql = "SELECT tickerr_bugs.*, tickerr_bug_departments.name as department_name, CASE WHEN guest_name != '' THEN guest_name ELSE tickerr_users.name END AS client_final_name FROM tickerr_bugs INNER JOIN tickerr_bug_departments ON tickerr_bugs.department=tickerr_bug_departments.id LEFT JOIN tickerr_users ON tickerr_bugs.userid = tickerr_users.id WHERE `department`=$dpt AND `status`=3 AND (`tickerr_bugs`.`id` LIKE ? OR `tickerr_bugs`.`date` LIKE ? OR `last_update` LIKE ? OR `subject` LIKE ? OR `content` LIKE ? OR `tickerr_bug_departments`.`name` LIKE ?) ORDER BY `$order_by` $order LIMIT $starting,$rows";
			$query = $this->db->query($sql, array(0, $search, $search, $search, $search, $search, $search));
		}
		return $query;
	}
	
	public function count_search_department_solved_bugs($dpt, $search) {
		$search = "%$search%";
		$sql = "SELECT COUNT(*) as c, tickerr_bugs.*, tickerr_bug_departments.name as department_name FROM tickerr_bugs INNER JOIN tickerr_bug_departments ON tickerr_bugs.department=tickerr_bug_departments.id WHERE `department`=$dpt AND `status`=3 AND (`tickerr_bugs`.`id` LIKE ? OR `tickerr_bugs`.`date` LIKE ? OR `last_update` LIKE ? OR `subject` LIKE ? OR `content` LIKE ? OR `tickerr_bug_departments`.`name` LIKE ?)";
		$query = $this->db->query($sql, array($search, $search, $search, $search, $search, $search));
		$row = $query->row();
		return $row->c;
	}
	
	public function count_department_solved_bugs($dpt) {
		$sql = "SELECT COUNT(*) as c FROM `tickerr_bugs` WHERE `department`=$dpt AND `status`=3";
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
	 * Get list of all bug reports that haven't been solved from a department
	 *
	*/
	public function get_department_other_bugs($dpt, $rows = 20, $starting = 0, $order_by = 'last_update', $order = 'DESC', $search = false) {
		if($search == false) {			
			$sql = "SELECT tickerr_bugs.*, tickerr_bug_departments.name as department_name, CASE WHEN guest_name != '' THEN guest_name ELSE tickerr_users.name END AS client_final_name FROM tickerr_bugs INNER JOIN tickerr_bug_departments ON tickerr_bugs.department=tickerr_bug_departments.id LEFT JOIN tickerr_users ON tickerr_bugs.userid = tickerr_users.id WHERE `department`=$dpt AND (`status`=1 OR `status`=2 OR `status`=4 OR `status`=5 OR `status`=6) AND `agentid`!=0 ORDER BY `$order_by` $order LIMIT $starting,$rows";
			$query = $this->db->query($sql);
		}else{
			$search = "%$search%";
			$sql = "SELECT tickerr_bugs.*, tickerr_bug_departments.name as department_name, CASE WHEN guest_name != '' THEN guest_name ELSE tickerr_users.name END AS client_final_name FROM tickerr_bugs INNER JOIN tickerr_bug_departments ON tickerr_bugs.department=tickerr_bug_departments.id LEFT JOIN tickerr_users ON tickerr_bugs.userid = tickerr_users.id WHERE `department`=$dpt AND (`status`=1 OR `status`=2 OR `status`=4 OR `status`=5 OR `status`=6) AND `agentid`!=0 AND (`tickerr_bugs`.`id` LIKE ? OR `tickerr_bugs`.`date` LIKE ? OR `last_update` LIKE ? OR `subject` LIKE ? OR `content` LIKE ? OR `tickerr_bug_departments`.`name` LIKE ?) ORDER BY `$order_by` $order LIMIT $starting,$rows";
			$query = $this->db->query($sql, array(0, $search, $search, $search, $search, $search, $search));
		}
		return $query;
	}
	
	public function count_search_department_other_bugs($dpt, $search) {
		$search = "%$search%";
		$sql = "SELECT COUNT(*) as c, tickerr_bugs.*, tickerr_bug_departments.name as department_name FROM tickerr_bugs INNER JOIN tickerr_bug_departments ON tickerr_bugs.department=tickerr_bug_departments.id WHERE `department`=$dpt AND (`status`=1 OR `status`=2 OR `status`=4 OR `status`=5 OR `status`=6) AND `agentid`!=0 AND (`tickerr_bugs`.`id` LIKE ? OR `tickerr_bugs`.`date` LIKE ? OR `last_update` LIKE ? OR `subject` LIKE ? OR `content` LIKE ? OR `tickerr_bug_departments`.`name` LIKE ?)";
		$query = $this->db->query($sql, array($search, $search, $search, $search, $search, $search));
		$row = $query->row();
		return $row->c;
	}
	
	public function count_department_other_bugs($dpt) {
		$sql = "SELECT COUNT(*) as c FROM `tickerr_bugs` WHERE `department`=$dpt AND (`status`=1 OR `status`=2 OR `status`=4 OR `status`=5 OR `status`=6) AND `agentid`!=0";
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
	 * Gets list of all users except the current
	 *
	*/
	public function get_all_users_exp($userid, $rows = 20, $starting = 0, $order_by = 'date', $order = 'DESC', $search = false) {
		if($search == false) {			
			$sql = "SELECT * FROM tickerr_users ORDER BY `$order_by` $order LIMIT $starting,$rows";
			$query = $this->db->query($sql);
		}else{
			$search = "%$search%";
			$sql = "SELECT * FROM tickerr_users WHERE (`id` LIKE ? OR `username` LIKE ? OR `name` LIKE ? OR `email` LIKE ? OR `date` LIKE ?) ORDER BY `$order_by` $order LIMIT $starting,$rows";
			$query = $this->db->query($sql, array($search, $search, $search, $search, $search));
		}
		return $query;
	}
	
	public function count_search_all_users_exp($userid, $search) {
		$search = "%$search%";
		$sql = "SELECT COUNT(*) as c FROM tickerr_users WHERE (`id` LIKE ? OR `username` LIKE ? OR `name` LIKE ? OR `email` LIKE ? OR `date` LIKE ?)";
		$query = $this->db->query($sql, array($search, $search, $search, $search, $search));
		$row = $query->row();
		return $row->c;
	}
	
	public function count_all_users_exp($userid) {
		$sql = "SELECT COUNT(*) as c FROM `tickerr_users`";
		$query = $this->db->query($sql);
		$row = $query->row();
		return $row->c;
	}
	/**************** ENDS BLOCK OF 3 FUNCTIONS ****************/
	
	
	/**************** STARTS BLOCK OF 2 FUNCTIONS ****************
	 * These functions help display tables with options to order data
	 * by column
	 *
	 * Purpose of this specific block:
	 * Get list of all tickets submitted by a client
	 *
	*/
	public function get_client_all_tickets($userid, $rows = 20, $starting = 0, $order_by = 'last_update', $order = 'DESC') {
		$sql = "SELECT tickerr_tickets.*, tickerr_ticket_departments.name as department_name FROM tickerr_tickets INNER JOIN tickerr_ticket_departments ON tickerr_tickets.department=tickerr_ticket_departments.id LEFT JOIN tickerr_users ON tickerr_tickets.userid = tickerr_users.id WHERE `userid`=$userid ORDER BY `$order_by` $order LIMIT $starting,$rows";
		$query = $this->db->query($sql);
		return $query;
	}
	
	public function count_client_all_tickets($userid) {
		$sql = "SELECT COUNT(*) as c FROM `tickerr_tickets` WHERE `userid`=$userid";
		$query = $this->db->query($sql);
		$row = $query->row();
		return $row->c;
	}
	/**************** ENDS BLOCK OF 2 FUNCTIONS ****************/
	
	
	/**************** STARTS BLOCK OF 2 FUNCTIONS ****************
	 * These functions help display tables with options to order data
	 * by column
	 *
	 * Purpose of this specific block:
	 * Get list of all bug reports submitted by a client
	 *
	*/
	public function get_client_all_bugs($userid, $rows = 20, $starting = 0, $order_by = 'last_update', $order = 'DESC') {
		$sql = "SELECT tickerr_bugs.*, tickerr_bug_departments.name as department_name, CASE WHEN guest_name != '' THEN guest_name ELSE tickerr_users.name END AS client_final_name FROM tickerr_bugs INNER JOIN tickerr_bug_departments ON tickerr_bugs.department=tickerr_bug_departments.id LEFT JOIN tickerr_users ON tickerr_bugs.userid = tickerr_users.id WHERE `userid`=$userid ORDER BY `$order_by` $order LIMIT $starting,$rows";
		$query = $this->db->query($sql);
		return $query;
	}
	
	public function count_client_all_bugs($userid) {
		$sql = "SELECT COUNT(*) as c FROM `tickerr_bugs` WHERE userid = $userid";
		$query = $this->db->query($sql);
		$row = $query->row();
		return $row->c;
	}
	/**************** ENDS BLOCK OF 2 FUNCTIONS ****************/
	
	
	/**************** STARTS BLOCK OF 2 FUNCTIONS ****************
	 * These functions help display tables with options to order data
	 * by column
	 *
	 * Purpose of this specific block:
	 * Get list of all ratings submitted by a client
	 *
	*/
	public function get_client_all_ratings($userid, $rows = 20, $starting = 0, $order_by = 'last_update', $order = 'DESC') {
		$sql = "SELECT tickerr_tickets.*, tickerr_users.name as agent_name FROM tickerr_tickets LEFT JOIN tickerr_users ON tickerr_users.id = tickerr_tickets.agentid WHERE `userid`=$userid AND rating != 0.0 AND rating_msg != '' ORDER BY `$order_by` $order LIMIT $starting,$rows";
		$query = $this->db->query($sql);
		return $query;
	}
	public function count_client_all_ratings($userid) {
		$sql = "SELECT COUNT(*) as c FROM tickerr_tickets WHERE `userid`=$userid AND rating != 0.0 AND rating_msg != ''";
		$query = $this->db->query($sql);
		$row = $query->row();
		return $row->c;
	}
	/**************** ENDS BLOCK OF 2 FUNCTIONS ****************/
	
	
	/**************** STARTS BLOCK OF 2 FUNCTIONS ****************
	 * These functions help display tables with options to order data
	 * by column
	 *
	 * Purpose of this specific block:
	 * Get list of agent's closed tickets
	 *
	*/
	public function get_agent_closed_tickets($agentid, $rows = 20, $starting = 0, $order_by = 'last_update', $order = 'DESC') {
		$sql = "SELECT tickerr_tickets.*, tickerr_ticket_departments.name as department_name FROM tickerr_tickets INNER JOIN tickerr_ticket_departments ON tickerr_tickets.department=tickerr_ticket_departments.id LEFT JOIN tickerr_users ON tickerr_tickets.userid = tickerr_users.id WHERE `agentid`=$agentid AND `status`=3 ORDER BY `$order_by` $order LIMIT $starting,$rows";
		$query = $this->db->query($sql);
		return $query;
	}
	
	public function count_agent_closed_tickets($agentid) {
		$sql = "SELECT COUNT(*) as c FROM `tickerr_tickets` WHERE `agentid`=$agentid AND `status`=3";
		$query = $this->db->query($sql);
		$row = $query->row();
		return $row->c;
	}
	/**************** ENDS BLOCK OF 2 FUNCTIONS ****************/
	
	
	/**************** STARTS BLOCK OF 2 FUNCTIONS ****************
	 * These functions help display tables with options to order data
	 * by column
	 *
	 * Purpose of this specific block:
	 * Get list of agent's solved bug reports
	 *
	*/
	public function get_agent_solved_bugs($agentid, $rows = 20, $starting = 0, $order_by = 'last_update', $order = 'DESC') {
		$sql = "SELECT tickerr_bugs.*, tickerr_bug_departments.name as department_name, CASE WHEN guest_name != '' THEN guest_name ELSE tickerr_users.name END AS client_final_name FROM tickerr_bugs INNER JOIN tickerr_bug_departments ON tickerr_bugs.department=tickerr_bug_departments.id LEFT JOIN tickerr_users ON tickerr_bugs.userid = tickerr_users.id WHERE `agentid`=$agentid AND `status`=3 ORDER BY `$order_by` $order LIMIT $starting,$rows";
		$query = $this->db->query($sql);
		return $query;
	}
	
	public function count_agent_solved_bugs($agentid) {
		$sql = "SELECT COUNT(*) as c FROM `tickerr_bugs` WHERE agentid = $agentid AND `status`=3";
		$query = $this->db->query($sql);
		$row = $query->row();
		return $row->c;
	}
	/**************** ENDS BLOCK OF 2 FUNCTIONS ****************/
	
	
	/**************** STARTS BLOCK OF 2 FUNCTIONS ****************
	 * These functions help display tables with options to order data
	 * by column
	 *
	 * Purpose of this specific block:
	 * Get list of agent's received ratings
	 *
	*/
	public function get_agent_all_ratings($agentid, $rows = 20, $starting = 0, $order_by = 'last_update', $order = 'DESC') {
		$sql = "SELECT tickerr_tickets.*, CASE WHEN guest_name != '' THEN guest_name ELSE tickerr_users.name END AS client_final_name FROM tickerr_tickets LEFT JOIN tickerr_users ON tickerr_users.id = tickerr_tickets.userid WHERE `agentid`=$agentid AND rating != 0.0 AND rating_msg != '' ORDER BY `$order_by` $order LIMIT $starting,$rows";
		$query = $this->db->query($sql);
		return $query;
	}
	public function count_agent_all_ratings($agentid) {
		$sql = "SELECT COUNT(*) as c FROM tickerr_tickets WHERE `agentid`=$agentid AND rating != 0.0 AND rating_msg != ''";
		$query = $this->db->query($sql);
		$row = $query->row();
		return $row->c;
	}
	/**************** ENDS BLOCK OF 2 FUNCTIONS ****************/
	
	
	// Edit user's details
	public function edit_user($id, $name, $username, $email) {
		$sql = "UPDATE tickerr_users SET name=?, username=?, email=? WHERE id=$id";
		$this->db->query($sql, array($name, $username, $email));
		return true;
	}

	// Remove agent from tickets department
	public function tdepartment_remove_agent($dpt, $agentid) {
		$this->db->query("UPDATE `tickerr_tickets` SET `agentid`=0 WHERE `department`=$dpt AND `agentid`=$agentid");
		
		$query = $this->db->query("SELECT `ticket_departments` FROM `tickerr_users` WHERE `id`=$agentid");
		$row = $query->row();
		$departments = explode('|', $row->ticket_departments);
		$new_dpts = array();
		foreach($departments as $dpt_) {
			if($dpt != $dpt_)
				$new_dpts[] = $dpt_;
		}
		$new_departments = implode('|', $new_dpts);
		
		$this->db->query("UPDATE `tickerr_users` SET `ticket_departments`='$new_departments' WHERE `id`=$agentid");
		
		// -1 to the department agents count
		$this->db->query("UPDATE `tickerr_ticket_departments` SET `agents`=agents-1 WHERE `id`=$dpt AND `agents`>0");
		
		return true;
	}
	
	// Remove agent from bug reports department
	public function bdepartment_remove_agent($dpt, $agentid) {
		$this->db->query("UPDATE `tickerr_bugs` SET `agentid`=0 WHERE `department`=$dpt AND `agentid`=$agentid");
		
		$query = $this->db->query("SELECT `bug_departments` FROM `tickerr_users` WHERE `id`=$agentid");
		$row = $query->row();
		$departments = explode('|', $row->bug_departments);
		$new_dpts = array();
		foreach($departments as $dpt_) {
			if($dpt != $dpt_)
				$new_dpts[] = $dpt_;
		}
		$new_departments = implode('|', $new_dpts);
		
		$this->db->query("UPDATE `tickerr_users` SET `bug_departments`='$new_departments' WHERE `id`=$agentid");
		
		// -1 to the department agents count
		$this->db->query("UPDATE `tickerr_bug_departments` SET `agents`=agents-1 WHERE `id`=$dpt AND `agents`>0");
		
		return true;
	}
	
	// Set new tickets department name 
	public function update_tdepartment_name($dpt, $name) {
		$sql = "UPDATE `tickerr_ticket_departments` SET `name`=? WHERE `id`=$dpt";
		$query = $this->db->query($sql, $name);
		return true;
	}
	
	// Set new bugs department name
	public function update_bdepartment_name($dpt, $name) {
		$sql = "UPDATE `tickerr_bug_departments` SET `name`=? WHERE `id`=$dpt";
		$query = $this->db->query($sql, $name);
		return true;
	}
	
	// Add new agent to tickets department
	public function tdepartment_add_agent($dpt, $agentid) {
		$query = $this->db->query("SELECT `ticket_departments` FROM `tickerr_users` WHERE `id`=$agentid");
		$row = $query->row();
		$departments = explode('|', $row->ticket_departments);
		if($departments[0] == '') array_pop($departments);
		$departments[] = $dpt;
		$new_departments = implode('|', $departments);
		
		$this->db->query("UPDATE `tickerr_users` SET `ticket_departments`='$new_departments' WHERE `id`=$agentid");
		
		// +1 to the department agents count
		$this->db->query("UPDATE `tickerr_ticket_departments` SET `agents`=agents+1 WHERE `id`=$dpt");
		
		return true;
	}
	
	// Add new agent to bugs department
	public function bdepartment_add_agent($dpt, $agentid) {
		$query = $this->db->query("SELECT `bug_departments` FROM `tickerr_users` WHERE `id`=$agentid");
		$row = $query->row();
		$departments = explode('|', $row->bug_departments);
		if($departments[0] == '') array_pop($departments);
		$departments[] = $dpt;
		$new_departments = implode('|', $departments);
		
		$this->db->query("UPDATE `tickerr_users` SET `bug_departments`='$new_departments' WHERE `id`=$agentid");
		
		// +1 to the department agents count
		$this->db->query("UPDATE `tickerr_bug_departments` SET `agents`=agents+1 WHERE `id`=$dpt");
		
		return true;
	}
	
	// Get more information of a tickets department
	public function get_tdepartment_adv($id) {
		$sql = "SELECT tickerr_ticket_departments.*, (SELECT COUNT(*) FROM tickerr_tickets WHERE department = tickerr_ticket_departments.id AND agentid = 0) as no_agent_tickets, (SELECT COUNT(*) FROM tickerr_tickets WHERE department = tickerr_ticket_departments.id AND status = 1 AND agentid != 0) as open_tickets, (SELECT COUNT(*) FROM tickerr_tickets WHERE department = tickerr_ticket_departments.id AND status = 2 AND agentid != 0) as pending_tickets, (SELECT COUNT(*) FROM tickerr_tickets WHERE department = tickerr_ticket_departments.id AND status = 3 AND agentid != 0) as closed_tickets FROM `tickerr_ticket_departments` WHERE `tickerr_ticket_departments`.id=$id";
		$query = $this->db->query($sql);
		if($query->num_rows() == 0) return false;
		return $query->row();
	}
	
	// Get more information of a bgus department
	public function get_bdepartment_adv($id) {
		$sql = "SELECT tickerr_bug_departments.*, (SELECT COUNT(*) FROM tickerr_bugs WHERE department = tickerr_bug_departments.id AND agentid = 0) as free_bugs, (SELECT COUNT(*) FROM tickerr_bugs WHERE department = tickerr_bug_departments.id AND status = 3 AND agentid != 0) as solved_bugs, (SELECT COUNT(*) FROM tickerr_bugs WHERE department = tickerr_bug_departments.id AND (status=1 OR status=2 OR status=4 OR status=5 OR status=6) AND agentid != 0) as other_bugs FROM `tickerr_bug_departments` WHERE `tickerr_bug_departments`.id=$id";
		$query = $this->db->query($sql);
		if($query->num_rows() == 0) return false;
		return $query->row();
	}
	
	// Get tickets department
	public function get_tdepartment($id) {
		$sql = "SELECT * FROM `tickerr_ticket_departments` WHERE `id`=$id";
		$query = $this->db->query($sql);
		if($query->num_rows() == 0) return false;
		return $query->row();
	}
	
	// Get bugs department
	public function get_bdepartment($id) {
		$sql = "SELECT * FROM `tickerr_bug_departments` WHERE `id`=$id";
		$query = $this->db->query($sql);
		if($query->num_rows() == 0) return false;
		return $query->row();
	}
	
	// Delete tickets department
	public function delete_tdepartment($id) {
		// Delete all tickets from this department
		$this->db->query("DELETE FROM `tickerr_tickets` WHERE `department`=$id");
		
		// Delete department
		$this->db->query("DELETE FROM `tickerr_ticket_departments` WHERE `id`=$id");
		return true;
	}
	
	// Delete bugs department
	public function delete_bdepartment($id) {
		// Delete all tickets from this department
		$this->db->query("DELETE FROM `tickerr_bugs` WHERE `department`=$id");
		
		// Delete department
		$this->db->query("DELETE FROM `tickerr_bug_departments` WHERE `id`=$id");
		return true;
	}
	
	// Set tickets department as default
	public function default_tdepartment($id) {
		// Remove current default
		$this->db->query("UPDATE `tickerr_ticket_departments` SET `default`=2 WHERE `default`=1");
		
		// Set new default
		$this->db->query("UPDATE `tickerr_ticket_departments` SET `default`=1 WHERE `id`=$id");
		return true;
	}
	
	// Set bugs department as default
	public function default_bdepartment($id) {
		// Remove current default
		$this->db->query("UPDATE `tickerr_bug_departments` SET `default`=2 WHERE `default`=1");
		
		// Set new default
		$this->db->query("UPDATE `tickerr_bug_departments` SET `default`=1 WHERE `id`=$id");
		return true;
	}
	
	// Create new tickets department
	public function new_tdepartment($dpt) {
		$dt = date('Y-m-d H:i:s');
		$sql = "INSERT INTO `tickerr_ticket_departments`(`name`,`agents`,`tickets`,`date`,`default`) VALUES('$dpt',0,0,'$dt',2)";
		$this->db->query($sql);
		return true;
	}
	
	// Create new bugs department
	public function new_bdepartment($dpt) {
		$dt = date('Y-m-d H:i:s');
		$sql = "INSERT INTO `tickerr_bug_departments`(`name`,`agents`,`reports`,`date`,`default`) VALUES('$dpt',0,0,'$dt',2)";
		$this->db->query($sql);
		return true;
	}
	
	// Check if username already exists in the database
	public function check_existing_username($username) {
		$query = $this->db->query("SELECT COUNT(*) as c FROM tickerr_users WHERE username=?", array($username));
		$row = $query->row();
		if($row->c == '0') return false;
		return true;
	}
	
	// Check is email address already exists in the database
	public function check_existing_email($email) {
		$query = $this->db->query("SELECT COUNT(*) as c FROM tickerr_users WHERE email=?", array($email));
		$row = $query->row();
		if($row->c == '0') return false;
		return true;
	}
	
	// Create new user
	public function create_user($name, $username, $email, $password, $role, $e_confirmation) {
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
			'`role`' => (int)$role,
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
	
	// Link tickets of the email to the userid
	public function link_tickets($userid, $email) {
		$nl = '';
		$sql = "UPDATE `tickerr_tickets` SET `userid`=?, `guest_name`=?, `guest_email`=? WHERE `guest_email`=?";
		$query = $this->db->query($sql, array($userid, $nl, $nl, $email));
		return $query;
	}
	
	// Link bug reports of the email to the userid
	public function link_bug_reports($userid, $email) {
		$nl = '';
		$sql = "UPDATE `tickerr_bugs` SET `userid`=?, `guest_name`=?, `guest_email`=? WHERE `guest_email`=?";
		$query = $this->db->query($sql, array($userid, $nl, $nl, $email));
		return $query;
	}
	
	// Get user information
	public function get_user($id) {
		$sql = "SELECT * FROM `tickerr_users` WHERE `id`=$id";
		$query = $this->db->query($sql);
		if($query->num_rows() == 0) return false;
		$row = $query->row();
		return $row;
	}
	
	// Get list of agents/administrators
	public function get_agents() {
		$sql = "SELECT * FROM `tickerr_users` WHERE `role`=3 OR `role`=2 ";
		$query = $this->db->query($sql);
		if($query->num_rows() == 0) return false;
		return $query;
	}
	
	// Delete client from database
	public function delete_client($id) {
		$query = $this->db->query("DELETE FROM tickerr_users WHERE id = $id");
		if($query == true) {
			$this->db->query("DELETE FROM tickerr_tickets WHERE userid = $id");
			$this->db->query("DELETE FROM tickerr_bugs WHERE userid = $id");
			return true;
		}
		return false;
	}
	
	// Delete agent from database
	public function delete_agent($agent_info, $id) {
		$query = $this->db->query("DELETE FROM tickerr_users WHERE id = $id");
		if($query == true) {
			$this->db->query("UPDATE `tickerr_tickets` SET `agentid`=0 WHERE `agentid`=$id");
			$this->db->query("UPDATE `tickerr_bugs` SET `agentid`=0 WHERE `agentid`=$id");
			
			$tdepartments = explode('|', $agent_info->ticket_departments);
			$bdepartments = explode('|', $agent_info->bug_departments);
			
			if(count($tdepartments) >= 1 && $tdepartments[0] != '') {
				$where = array();
				foreach($tdepartments as $department) {
					$where[] = "`id`=$department";
				}
				$fwhere = implode(' OR ', $where);
				$this->db->query("UPDATE `tickerr_ticket_departments` SET `agents`=agents-1 WHERE $fwhere");
			}
			
			if(count($bdepartments) >= 1 && $bdepartments[0] != '') {
				$where = array();
				foreach($bdepartments as $department) {
					$where[] = "`id`=$department";
				}
				$fwhere = implode(' OR ', $where);
				$this->db->query("UPDATE `tickerr_bug_departments` SET `agents`=agents-1 WHERE $fwhere");
			}
			
			return true;
		}
		return false;
	}
}