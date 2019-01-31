<?php

class Checker {
	private $proceed = true;
	private $warning = false;
	
	/** Var to store the result in a number. Used to avoid installation without
		meeting the requirements.
		0 = Proceed without warning
		1 = Proceed with warning
		2 = Don't proceed
	**/
	private $n_result = 0;
	
	public function check_system() {
		$result = array();
		
		// First, check the PHP version.
		if(version_compare(PHP_VERSION, '5.4.0', '>=')) {
			$result[] = array('success', 'Minimum PHP version required is 5.4.0. Yours is '. PHP_VERSION . '. Great!');
		}elseif(version_compare(PHP_VERSION, '5.2.4', '>=')) {
			$result[] = array('warning', 'Minimum PHP version required is 5.4.0. Yours is '. PHP_VERSION . '. Tickerr should work anyways, but it is highly recommended to install it on a greater version.');
			$this->warning = true;
		}else{
			$result[] = array('danger', 'Minimum PHP version required is 5.4.0 or 5.2.4 at least. Yours is '. PHP_VERSION);
			$this->warning = true;
			$this->proceed = false;
		}
		
		// Check if this is already installed
		if(file_exists('../config.local.php') && is_writable('../config.local.php')) {
			$result[] = array('warning', 'A config.local.php file has been detected, which means that Tickerr might be already installed. If you proceed, information from any previous installation will be deleted.');
			$this->warning = true;
		}
		
		// If we cannot write the file (to delete it)..
		if(file_exists('../config.local.php') && !is_writable('../config.local.php')) {
			$result[] = array('danger', 'A config.local.php file has been detected, which means that Tickerr might be already installed. However, to proceed with the installation, you need to delete the file manually. Please keep in mind that information from any previous installation will be deleted.');
			$this->warning = true;
		}
		
		// Check if config sample exists
		if(!file_exists('../config.sample.php')) {
			$result[] = array('danger', 'The config.sample.php file doesn\'t exist, and it\'s needed to continue with the installation. Please locate it inside your .zip file and place it in the root directory of Tickerr.');
			$this->warning = true;
			$this->proceed = false;
		}
		
		// Check if we can read and write it...
		if(file_exists('../config.sample.php') && (!is_readable('../config.sample.php') || !is_writable('../config.sample.php'))) {
			$result[] = array('danger', 'The config.sample.php must have read/write permissions.');
			$this->warning = true;
			$this->proceed = false;
		}
		
		// File exists and we have permissions
		if(file_exists('../config.sample.php') && is_readable('../config.sample.php') && is_writable('../config.sample.php')) {
			$result[] = array('success', 'Sample configuration file exists and it has the needed permissions.');
		}
		
		// Check if we have PDO driver...
		if(!extension_loaded('pdo') || !class_exists('PDO', false)) {
			$result[] = array('danger', 'The PDO class is needed to continue with the installation, and it seems that it isn\'t installed nor enabled.');
			$this->warning = true;
			$this->proceed = false;
		}
		
		// Can we check if mod_rewrite is enabled?
		if(function_exists('apache_get_modules')) {
			if(in_array('mod_rewrite', apache_get_modules()) == true) {
				$result[] = array('success', 'mod_rewrite is enabled.');
			}else{
				$result[] = array('danger', 'It seems that mod_rewrite is not enabled. Tickerr cannot be installed.');
				$this->warning = true;
				$this->proceed = false;
			}
		}else{
			$result[] = array('warning', 'Tickerr couldn\'t check if mod_rewrite is enabled or not. If it isn\'t, Tickerr will not work.');
			$this->warning = true;
		}
		
		// Upload directory exists and has the right permissions?
		if(is_dir('../uploads') && is_writable('../uploads')) {
			$result[] = array('success', 'uploads/ directory exists and it has write permissions.');
		}elseif(!is_dir('../uploads')) {
			// Try to create it..
			if(mkdir('../uploads') == false) {
				$result[] = array('danger', 'uploads/ directory doesn\'t exist and it couldn\'t be created. Please create it manually and refresh this page.');
				$this->warning = true;
				$this->proceed = false;
			}else{
				if(is_writable('../uploads'))
					$result[] = array('success', 'uploads/ directory has been created and it hass write permissions.');
				else {
					$result[] = array('danger', 'uploads/ directory has been created but it doesn\'t have write permissions. Please set them manually.');
					$this->warning = true;
					$this->proceed = false;
				}
			}
		}elseif(is_dir('../uploads') && !is_writable('../uploads')) {
			$result[] = array('danger', 'uploads/ directory exists but it doesn\'t have write permissions. Please set them manually.');
			$this->warning = true;
			$this->proceed = false;
		}
		
		// Upload directory for profile images
		if(!is_dir('../assets/img/profile_img')) {
			$result[] = array('danger', 'assets/img/profile_img/ directory doesn\'t exist. Please extract it from the Tickerr .zip file.');
			$this->warning = true;
			$this->proceed = false;
		}elseif(is_dir('../assets/img/profile_img') && !is_writable('../assets/img/profile_img')) {
			$result[] = array('danger', 'assets/img/profile_img/ directory exist but it doesn\'t have write permissions. Please set them manually.');
			$this->warning = true;
			$this->proceed = false;
		}else{
			$result[] = array('success', 'assets/img/profile_img/ directory exists and it has write permissions.');
		}
		
		// Check GD library
		if(extension_loaded('gd') && function_exists('gd_info')) {
			$result[] = array('success', 'GD library exists and is available');
		}else{
			$result[] = array('danger', 'It seems that the GD library doesn\'t exist or isn\'t available.');
			$this->warning = true;
			$this->proceed = false;
		}

		// Check cURL library
		if(extension_loaded('curl') && function_exists('curl_init')) {
			$result[] = array('success', 'cURL library exists and is available');
		}else{
			$result[] = array('danger', 'It seems that the cURL library doesn\'t exist or isn\'t available.');
			$this->warning = true;
			$this->proceed = false;
		}
		
		// Store result in a numeric value..
		if($this->warning == true && $this->proceed == true)
			$this->n_result = 1;
		if($this->proceed == false)
			$this->n_result = 2;
		
		return $result;
	}
	
	public function get_proceed_var() {
		return $this->proceed;
	}
	
	public function get_warning_var() {
		return $this->warning;
	}
	
	public function get_n_result() {
		return $this->n_result;
	}
}