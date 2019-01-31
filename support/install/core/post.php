<?php

class Post {	
	// Return post value if it's set
	public function post_value($val, $other = '') {
		return (isset($_POST[$val])) ? $_POST[$val] : $other;
	}
}