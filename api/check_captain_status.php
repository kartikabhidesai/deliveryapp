<?php
require_once('include/config.php');
require_once('include/init.php');
require_once('include/thumb.php');
	
	if($language == 'en')
		require_once('lang/en.php');
	elseif($language == 'ar')
		require_once('lang/ar.php');
	else
		require_once('lang/en.php');

	$conn=new Database;
	$data = new DataManipulator;
	$jsonArray = array();
	$d=array();	
	
	if($_POST['user_id']!='')
		$user_id = $_POST['user_id'];
	else
		$err = $lang["REQ_PARA"]."user_id";
		
	if($err!='')
	{
		$jsonArray['Success']='0';
		$jsonArray['Message']=$err;
		show_output($jsonArray);
	}	
	
	$sql = $conn->get_record_set("SELECT captain_status FROM `tbl_users` WHERE user_id='$user_id' AND is_deleted='0'");
	$row = $conn->records_to_array($sql);
	if(!empty($row))
	{
		$jsonArray['Success'] = '1';
		$jsonArray['Message'] = $lang["SUCCESSFUL"];
		$jsonArray['captain_status'] = $row[0]['captain_status'];
	}
	else
	{
		$jsonArray['Success'] = '0';
		$jsonArray['Message'] = $lang["SUCCESSFUL"];
		$jsonArray['captain_status'] = '';
	}		
	show_output($jsonArray);
?>