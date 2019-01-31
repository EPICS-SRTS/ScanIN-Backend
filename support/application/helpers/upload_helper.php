<?php
if(!defined('BASEPATH') ) exit('No direct script access allowed');

function upload_multiple_files($input, $nfiles) {
	$ci =& get_instance();
	
	$files = $_FILES;
	$dbfiles = array();
	
	
	for($i = 0; $i < $nfiles; $i++) {
		// File extension
		$extension = pathinfo($files[$input]['name'][$i], PATHINFO_EXTENSION);
		
		// Get random name with extension
		$random_name = generate_random_filename() . '.' . $extension;
		
		// Check for duplicates
		while(file_exists(FCPATH . 'assets/uploads/' . $random_name))
			$random_name = generate_random_filename() . '.' . $extension;
		
		// Assign vars
		$vars = array('name','type','tmp_name','error','size');
		foreach($vars as $v)
			$_FILES[$input][$v] = $files[$input][$v][$i];
		
		// Configuration
		$config = array(
			'upload_path' => FCPATH . 'uploads/',
			'file_name' => $random_name,
			'allowed_types' => '*',
			'max_size' => 0,
			'overwrite' => false
		);
		
		// Upload
		$ci->upload->initialize($config);
		if($ci->upload->do_upload($input) == false)
			return false;
		else
			$dbfiles[] = $random_name.'*'.$_FILES[$input]['name'];
	}
	
	return $dbfiles;
}


function generate_random_filename($limit = 20) {
	$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
	$random_name = '';
	for($i = 0; $i < $limit; $i++)
		$random_name .= $chars{rand(0, strlen($chars)-1)};
	return $random_name;
}