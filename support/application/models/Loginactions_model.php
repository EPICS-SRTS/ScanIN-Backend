<?php

class Loginactions_model extends CI_Model {
	// Validate current session
	public function validate_session($user, $pass) {
		$user = $this->db->escape($user);
		$pass = $this->db->escape($pass);
		
		$query = $this->db->query("SELECT `id` FROM `tickerr_users` WHERE `username`=$user && `password`=$pass");
		if($query->num_rows() == 0)
			return false;
		return true;
	}
	
	// Generate password-recovery code
	public function generate_recovery($email) {
		// Generate code
		$recovery_code = '';
		$random_chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';
		for($i = 0; $i < 34; $i++)
			$recovery_code .= $random_chars{rand(0, strlen($random_chars)-1)};
		
		// Check if a code already exists
		$sql = "SELECT `recover_password_str` FROM `tickerr_users` WHERE `email`=?";
		$query = $this->db->query($sql, $email);
		$row = $query->row();
		if($row->recover_password_str == '') {
			// Code is empty, insert new one
			$sql = "UPDATE `tickerr_users` SET `recover_password_str`=? WHERE `email`=?";
			$query = $this->db->query($sql, array($recovery_code, $email));
			return $recovery_code;
		}
		
		// Return already existing code
		return $row->recover_password_str;
	}
	
	// Validate password recovery code
	public function validate_recovery($email, $code) {
		$sql = "SELECT `id` FROM `tickerr_users` WHERE `email`=? AND `recover_password_str`=?";
		$query = $this->db->query($sql, array($email, $code));
		if($query->num_rows() == 0)
			return false;
		return true;
	}
	
	// Recover password
	public function recover($email, $password) {
		$recovery_code = '';
		$random_chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';
		for($i = 0; $i < 34; $i++)
			$recovery_code .= $random_chars{rand(0, strlen($random_chars)-1)};
		
		$sql = "UPDATE `tickerr_users` SET `password`=?, `recover_password_str`=? WHERE `email`=?";
		$query = $this->db->query($sql, array(md5($password), $recovery_code, $email));
		return true;
	}
	
	// Check activation code to activate account
	public function check_activation_code($code) {
		$sql = "SELECT `id` FROM `tickerr_users` WHERE `confirmation_str`=?";
		$query = $this->db->query($sql, $code);
		if($query->num_rows() == 0)
			return false;
		return true;
	}
	
	// Activate account
	public function activate_account($code) {
		$cf = 2;
		$sql = "UPDATE `tickerr_users` SET `email_confirmation`=? WHERE `confirmation_str`=?";
		$query = $this->db->query($sql, array($cf, $code));
		return true;
	}
	
	// Get email address with the activation code
	public function get_email_by_activation($code) {
		$sql = "SELECT `email` FROM `tickerr_users` WHERE `confirmation_str`=?";
		$query = $this->db->query($sql, $code);
		$row = $query->row();
		return $row->email;
	}
	
	// Check if the account is activated
	public function check_activated_account($code) {
		$sql = "SELECT `email_confirmation` FROM `tickerr_users` WHERE `confirmation_str`=?";
		$query = $this->db->query($sql, $code);
		$row = $query->row();
		if($row->email_confirmation == '2')
			return true;
		return false;
	}
}