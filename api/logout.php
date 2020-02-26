<?php
require_once("include/config.php");
require_once("include/init.php");

if($language == 'en')
	require_once('lang/en.php');
elseif($language == 'ar')
	require_once('lang/ar.php');
else
	require_once('lang/en.php');

$conn=new Database;
$data = new DataManipulator;
$jsonArray = array();

if($_POST['user_id']!='')
	$user_id = $_POST['user_id'];
else
	$err=$lang["REQ_PARA"].$lang["USER_ID"];
	
if($err!='')
{
	$jsonArray['Success']='0';
	$jsonArray['Message']=$err;
	show_output($jsonArray); 
}

$sql = $conn->get_record_set("select * from tbl_users where user_id='$user_id'");
$rows = $conn->records_to_array($sql);
if(!empty($rows))
{
	$postdata['device_id'] = '0';
	$postdata['device_token'] = '0';
	$postdata['device_type'] = '0';
	$postdata['badge_count'] = '0';
	$postdata['is_online'] = '0';
	$data->update( "tbl_users" , $postdata , array("user_id"=>$user_id) );
	$jsonArray['Success']='1';
	$jsonArray['Message']=$lang["SUC_LOGOUT"];
}
else
{
	$jsonArray['Success']='0';
	$jsonArray['Message']=$lang["NOT_FOUND"];
}

show_output($jsonArray);
