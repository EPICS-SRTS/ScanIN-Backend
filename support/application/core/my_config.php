<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class MY_Config extends CI_Config
{
	/**
	 * Load a config file - Overrides built-in CodeIgniter config file loader
	 * 
	 * @param string $file
	 * @param boolean $use_sections
	 * @param boolean $fail_gracefully 
	 */
	function load($file = '', $use_sections = FALSE, $fail_gracefully = FALSE)
	{
		parent::load($file, $use_sections, $fail_gracefully);
 
		//Local settings override permanent settings always.
		if (is_readable(FCPATH . 'system/config.local.php'))
			parent::load('system/config.local.php', $use_sections, $fail_gracefully);
	}
}