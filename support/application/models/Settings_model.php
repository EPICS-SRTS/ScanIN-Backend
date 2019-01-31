<?php

class Settings_model extends CI_Model {
	// Get setting by its name
	public function get_setting($name) {
		$sql = "SELECT `value` FROM `tickerr_settings` WHERE `name`=?";
		$query = $this->db->query($sql, $name);
		$row = $query->row();
		return $row->value;
	}
	
	// Get multiple settings
	public function get_multiple_settings($names) {
		$where = array();
		foreach($names as $n)
			$where[] = '`name`=?';
		$where = implode(' OR ', $where);
		$sql = "SELECT `name`,`value` FROM `tickerr_settings` WHERE $where";
		$query = $this->db->query($sql, $names);
		
		$result = new stdClass();
		foreach($query->result() as $row)
			$result->{$row->name} = $row->value;
		return $result;
	}
	
	// Change setting
	public function set_setting($name, $value) {
		$sql = "INSERT INTO `tickerr_settings`(`name`,`value`) VALUES(?,?)";
		if($this->db->query($sql, array($name, $value)) == true)
			return true;
		return false;
	}
	
	// Change setting
	public function change_setting($name, $value) {
		$sql = "UPDATE `tickerr_settings` SET `value`=? WHERE `name`=?";
		$this->db->query($sql, array($value, $name));
		return true;
	}
	
	// Get email settings (method, host, port, user, password)
	public function get_email_settings() {
		// Get method. 1=Smtp, 2=Sendmail
		$method = $this->get_setting('mailer_method');
		
		// Get settings according to the method
		if($method == 1) {
			$query = $this->db->query("SELECT * FROM `tickerr_settings` WHERE `name`='smtp_host' OR `name`='smtp_port'
			OR `name`='smtp_user' OR `name`='smtp_pass' OR `name`='smtp_timeout'");
		}else{
			$query = $this->db->query("SELECT * FROM `tickerr_settings` WHERE `name`='mailer_method'");
		}
		
		$cfg = array();
		foreach($query->result() as $row)
			$cfg[$row->name] = $row->value;
		
		return $cfg;
	}
	
	// Get email information (from address, name...)
	public function get_email_info() {
		$query = $this->db->query("SELECT * FROM `tickerr_settings` WHERE `name`='email_from_address' OR
		`name`='email_from_name' OR `name`='email_cc'");
		
		$final = array();
		foreach($query->result() as $row)
			$final[$row->name] = $row->value;
		return $final;
	}
	
	// Get specific email information (subject, content..)
	public function get_email_specific($t) {
		$query = $this->db->query("SELECT * FROM `tickerr_settings` WHERE `name`='{$t}_type' OR `name`='{$t}_title'
		OR `name`='{$t}_content'");
		$final = array();
		foreach($query->result() as $row)
			$final[str_replace("{$t}_", '', $row->name)] = $row->value;
		return $final;
	}
}