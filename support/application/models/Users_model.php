<?php

class Users_model extends CI_Model {
	// Get user information
	public function get_user_info($id) {
		$id = (int)$id;
		$sql = "SELECT * FROM `tickerr_users` WHERE `id`=?";
		$query = $this->db->query($sql, array($id));
		return $query->row();
	}
	
	// Get user role
	public function get_user_role($username) {
		$sql = "SELECT `role` FROM `tickerr_users` WHERE `username`=?";
		$query = $this->db->query($sql, array($username));
		$row = $query->row();
		return $row->role;
	}
	
	// Get user ID (by the username)
	public function get_user_id($username) {
		$sql = "SELECT `id` FROM `tickerr_users` WHERE `username`=?";
		$query = $this->db->query($sql, array($username));
		$row = $query->row();
		return $row->id;
	}
	
	// Update user data
	public function change_user_data($user, $data, $val) {
		$sql = "UPDATE `tickerr_users` SET `$data`=? WHERE `id`=?";
		$query = $this->db->query($sql, array($val, (int)$user));
		return true;
	}
	
	// Get agents list
	public function get_agents() {
		$sql = "SELECT * FROM `tickerr_users` WHERE `role` = 2 OR `role` = 3";
		
		$query = $this->db->query($sql);
		return $query;
	}
}