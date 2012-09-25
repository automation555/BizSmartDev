<?php
/* 	File:	write.php
	Title: 	json write api
	Author:	Justin Maier
	Url:	http://justinmaier.com
	Date:	2008-05-21
	Version:	1*/
	
class edit{
	protected $response,$dom,$xpath,$target,$default;
	public $content;
	public function __construct($xpath,$content,$target,$default,$tags){
		require_once("jsonResponse.php");
		$this->target = $target;
		$this->response = new jsonResponse();
		$this->xpath = $xpath;
		$this->default = $default;
		$this->content = strip_tags($content,$tags);
		$this->dom = new DOMDocument();
		if(!$this->dom->loadHTMLFile($target))$this->response->error("File Doesn't Exist");
		if($this->content=="")$this->getDefault();
		$this->makeEdit();
	}
	private function makeEdit(){
		$xpath=new DOMXPath($this->dom);
		$elements = $xpath->query("/".$this->xpath);
		foreach($elements as $el){
			$el->nodeValue="";
			$el->appendChild($this->dom->createCDATASection($this->content));
			break;
		}
		$this->dom->saveHTMLFile($this->target);
		$this->response->success("Changes have been made");
	}
	private function getDefault(){
		$defaultDom = new DOMDocument();
		$defaultDom->loadHTMLFile($this->default);
		$xpath=new DOMXPath($defaultDom);
		$elements = $xpath->query("/".$this->xpath);
		foreach($elements as $el){
			$this->content=$el->nodeValue;
			break;
		}
	}
	public function __toString(){
		return(json_encode($this->response));
	}
}
?>