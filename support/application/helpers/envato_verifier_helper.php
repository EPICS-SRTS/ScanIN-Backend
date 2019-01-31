<?php
if(!defined('BASEPATH') ) exit('No direct script access allowed');

function verify_envato_purchase_code($username, $api, $code) {
	$ch = @curl_init();
	if($ch == false) return false;
	
	curl_setopt($ch, CURLOPT_URL, "http://marketplace.envato.com/api/edge/$username/$api/verify-purchase:$code.json");
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	
	$result = json_decode(curl_exec($ch), true);
	curl_close($ch);
	
	if(isset($result['verify-purchase']['item_name']))
		return true;
	return false;
}

function verify_envato_userapi($username, $api) {
	$ch = @curl_init();
	if($ch == false) return false;
	
	curl_setopt($ch, CURLOPT_URL, "http://marketplace.envato.com/api/edge/$username/$api/vitals.json");
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows: U: Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	
	$result = json_decode(curl_exec($ch), true);
	curl_close($ch);
	
	if(isset($result['vitals']['username']))
		return true;
	return false;
}