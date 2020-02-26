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
		$err = $lang["REQ_PARA"].$lang["USER_ID"];
	
	if($err!='')
	{
		$jsonArray['Success']='0';
		$jsonArray['Message']=$err;
		show_output($jsonArray);
	}	
	$sql = $conn->get_record_set("SELECT * FROM `tbl_user_addresses` WHERE `user_id` = '".$user_id."' AND is_deleted='0' ORDER BY address_id DESC ");
	$rows = $conn->records_to_array($sql);

	foreach($rows as $row1)
	{
		$d[]= $row1;
	}

	$sql = $conn->get_record_set("SELECT * FROM `tbl_user_addresses` WHERE `user_id` = '".$user_id."' AND is_deleted='0' AND is_favorite='1' ORDER BY address_id DESC ");
	$rows = $conn->records_to_array($sql);
	$fav=array();	
	foreach($rows as $row1)
	{
		$fav[]= $row1;
	}
	
	$sql = $conn->get_record_set("SELECT * FROM `tbl_user_addresses` WHERE `user_id` = '".$user_id."' AND is_deleted='0' AND is_favorite='0' ORDER BY address_id DESC ");
	$rows = $conn->records_to_array($sql);
	$nonfav=array();	
	foreach($rows as $row1)
	{
		$nonfav[]= $row1;
	}

	$jsonArray['Success'] = '1';
	$jsonArray['Message'] = $lang["SUCCESSFUL"];
	$jsonArray['detail']= $d;
	$jsonArray['Favorite']= $fav;
	$jsonArray['NonFavorite']= $nonfav;
	show_output($jsonArray);
?>