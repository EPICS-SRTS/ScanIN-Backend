<?php
if(!defined('BASEPATH') ) exit('No direct script access allowed');

// Get PHP.ini "upload_max_filesize" var and return it converted to MB.
function get_upload_max_filesize() {
	$upload_max_filesize = trim(ini_get('upload_max_filesize'));
	
	$upload_max_filesize_val = $upload_max_filesize * 1;
	$upload_max_filesize_last = strtolower($upload_max_filesize[strlen($upload_max_filesize)-1]);
	if($upload_max_filesize_last == 'g')
		$upload_max_filesize_val *= 1024;
	elseif($upload_max_filesize_last == 'k')
		$upload_max_filesize_val /= 1024;
	elseif($upload_max_filesize_last != 'm')
		$upload_max_filesize_val /= 1048576;
		
	return $upload_max_filesize_val;
}

function get_post_max_size() {
	$post_max_size = trim(ini_get('post_max_size'));
	
	$post_max_size_val = $post_max_size * 1;
	$post_max_size_last = strtolower($post_max_size[strlen($post_max_size)-1]);
	if($post_max_size_last == 'g')
		$post_max_size_val *= 1024;
	elseif($post_max_size_last == 'k')
		$post_max_size_val /= 1024;
	elseif($post_max_size_last != 'm')
		$post_max_size_val /= 1048576;
	
	return $post_max_size_val;
}
