<?php
if(!defined('BASEPATH') ) exit('No direct script access allowed');

// Separate uploaded files string
function separate_files($files) {
	return explode('|', $files);
}

// Check if file exists and return its size
function check_file($file) {
	if(is_array($file)) $file = $file[0];
	
	if(@file_exists(FCPATH . 'uploads/' . $file) == false)
		return false;
		
	if(@filesize(FCPATH . 'uploads/' . $file) != false)
		return number_format(filesize(FCPATH . 'uploads/' . $file) / 1048576, 2) . 'MB';
	else
		return 'N/A';
}