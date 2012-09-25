<?php
/* 	File:	save.php
	Title:	php save class for inlineEdit 3
	Author:	Justin Maier
	Url:	http://justinmaier.com
	Date:	2008-06-06
	Version:	1*/
header('Content-type: application/json');
require_once("jsonResponse.php");
$response = new jsonResponse();
!isset($_POST["json"])?$response->error("No Query"):false;
$request = json_decode(stripslashes($_POST["json"]));

if($request->type != 'edit')$response->error("Query Error. Request Type: "+$request->type+" does not exist");
require_once("edit.php");
$target="../demo/save.html";
$default="save.html";
$allowedTags="<b><br><em>";
$edit = new edit($request->body->xpath,$request->body->content,$target,$default,$allowedTags);
?>
<?=$edit?>