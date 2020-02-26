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
	$d=array();	

	if($_POST['user_id']!='')
		$user_id = $_POST['user_id'];
	else
		$err = $lang["REQ_PARA"].$lang["USER_ID"];

	if($_POST['address_id']!='')
		$address_id = $_POST['address_id'];
	else
		$err = $lang["REQ_PARA"]."address_id";
	
	if($err!='')
	{
		$jsonArray['Success']='0';
		$jsonArray['Message']=$err;
		show_output($jsonArray); 
	}

	$sql = $conn->get_record_set("SELECT * FROM tbl_user_addresses WHERE address_id=".$address_id." AND user_id=".$user_id." AND is_deleted='0'");
	$rows = $conn->records_to_array($sql);
	if(!empty($rows) && !empty($address_id))
	{	
		if($rows[0]['is_favorite']=='0')
		{
			$postdata['is_favorite'] = '1';	
			$jsonArray['Success'] = '1';
			$jsonArray['Message'] = $lang["FAVORITE_SUCCESSFULLY"];
			$jsonArray['is_favorite'] = '1';
		}
		else
		{
			$postdata['is_favorite'] = '0';	
			$jsonArray['Success'] = '1';
			$jsonArray['Message'] = $lang["UNFAVORITE_SUCCESSFULLY"];
			$jsonArray['is_favorite'] = '0';
		}
		$data->update("tbl_user_addresses" , $postdata,array("address_id"=>$address_id));
	
	}
	else
	{
		$jsonArray['Success'] = '0';
		$jsonArray['Message'] = $lang["NO_RECORD"];
	}	
	show_output($jsonArray);
