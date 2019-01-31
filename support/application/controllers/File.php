<?php

/******** Tickerr - Controller ********
 * Controller Name:	File
 * Description: 	This controller is used to securely download files.
**/
class File extends CI_Controller {
	public function index($from, $id, $filecode) {
		// From
		// 1 = Ticket
		// 2 = Ticket reply
		// 3 = Bug report
		if($from != 1 && $from != 2 && $from != 3) die();
		
		if($from == 1) {
			// Source = Ticket
			$this->load->model('Tickets_model', 'tickets_model', true);
			
			// Get ticket info
			$ticket = $this->tickets_model->get_ticket_by_id($id);
			if($ticket == null) die('Invalid file');
			
			$files = explode('|', $ticket->files);
			if(count($files) == '1' && $files[0] == '') die('Invalid file');
		}elseif($from == 2) {
			// Source = Ticket reply
			$this->load->model('Tickets_model','tickets_model', true);
			
			// Get reply info
			$reply = $this->tickets_model->get_ticket_reply_by_id($id);
			if($reply == null) die('Invalid file');
			
			$files = explode('|', $reply->files);
			if(count($files) == '1' && $files[0] == '') die('Invalid file');
		}else{
			// Bug report
			$this->load->model('Bugs_model', 'bugs_model', true);
			
			// Get info
			$bug = $this->bugs_model->guest_bug_info_by_id($id);
			if($bug == null) die ('Invalid file');
			
			$files = explode('|', $bug->files);
			if(count($files) == '1' && $files[0] == '') die('Invalid file');
		}
		
		$o_files = array();
		foreach($files as $file) {
			$files_ = explode('*', $file);
			$o_files[$files_[0]] = $files_[1];
		}
		
		if(!isset($o_files[$filecode])) die('Invalid file');
		
		// File
		$false_file = FCPATH . 'uploads/' . $filecode;
		$original_filename = $o_files[$filecode];
		
		// Chech file existance
		if(@file_exists($false_file) == false) die("File doesn't exist.");
		$filesize = @filesize($false_file);
		
		// File exists, do the rest..
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename='.$original_filename);
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		if($filesize != false)
			header('Content-Length: ' . $filesize);

		readfile($false_file);
	}
}