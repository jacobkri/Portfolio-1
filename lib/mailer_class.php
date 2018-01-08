<?php
   // https://github.com/beamtic/mailer_class
   //   Class to handle e-mail and mailing lists
   //   Database stuff is likely going to be handled via dependency injection, alternatively we may use files when no db class is available...
   
   // Licensed under the Apache License, Version 2.0 (the "License");
   // you may not use this file except in compliance with the License.
   // You may obtain a copy of the License at

   //   http://www.apache.org/licenses/LICENSE-2.0


class mailer_class {
  private $db = false; // Database object
  private $fh = false; // File_handler object
  public $from_email = 'mailer_class@example.com';
  public $from_name = 'John';
  public $content_type = 'text/html';
  public $charset = 'UTF-8';
  
  public function __construct($db=false, $fh=false) {
	if ($db != false) {
	  // If a database object was injected, rely on database for list subscribtions
	  $this->db = $db;
	}
	if ($fh != false) {
	  // Use external file handler object if available, otherwise use basic file handling
	  $this->fh = $fh;
	}
  }
  public function send_email($msg_subject='N/A', $msg_body='', $recipients) {
      
    $from = $this->from_name.'<'.$this->from_email.'>';
    $to = $this->comma_this_shit($recipients);

    $subject = $msg_subject;
	$message = $msg_body; // text/html source

    $headers = array();	
	$headers[] = 'MIME-Version: 1.0';
	$headers[] = 'Content-type: '.$this->content_type.'; charset=' . $this->charset;
	$headers[] = 'To: ' . $to; // Comma seperated list of names and emails I.e. John <john@example.com>, smith <smith@example.com>
	$headers[] = 'From: ' . $from;
	// $headers[] = 'Cc: ';
	// $headers[] = 'Bcc: ';
	
	$parms = '-r '.$this->from_email;
	mail($to, $subject, $message, implode("\r\n", $headers), $parms);
  }
  public function list_subscribe($list_id) {

  }
  public function list_unsubscribe($list_id) {
	  
  }
  private function comma_this_shit($input_array) {
      $comma_seperated = '';
      foreach ($input_array as $key => $value) {
          $comma_seperated .= $key . ' <'. $value.'>, ';
      }
      return rtrim($comma_seperated, ", ");
  }

}