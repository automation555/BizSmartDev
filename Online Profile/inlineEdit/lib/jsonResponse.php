<?php
/* 	File:	jsonResponse.php
	Title: 	Json Response Class
	Author:	Justin Maier
	Url:	http://justinmaier.com
	Date:	2008-05-20
	Version:	1*/

class jsonResponse{
	public $success, $body;
	public function error($message='Undefined Error'){
		$this->success=false;
		$this->body=$message;
		die($this);
	}
	public function success($content=''){
		$this->success=true;
		$this->body=$content;
	}
	public function __toString(){
		return json_encode($this);
	}
}
?>