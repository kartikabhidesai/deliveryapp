<?php
require_once('include/database.php');
require_once ('include/manipulate.php');
header('content-type:application/json');
$conn=new Database;


function show_output($str){
	$outputjson['data'] = $str;
	echo json_encode($outputjson);
	exit;
}

