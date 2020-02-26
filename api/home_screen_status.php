<?php
	require_once('include/config.php');
	require_once('include/init.php');
	
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
		
	$distance = CAPTAIN_ORDER_DISTANCE ;
	if($err!='')
	{
		$jsonArray['Success']='0';
		$jsonArray['Message']=$err;
		show_output($jsonArray);
	}	
	
	$jsonArray['Success'] = '1';
	$jsonArray['Message'] = $lang["SUCCESSFUL"];
	
	$usr = $data->select("tbl_users" , "*",array("user_id"=>$user_id));
	extract($usr[0]);
	$sql1 = $conn->get_record_set("SELECT n.not_id FROM `tbl_notification` n INNER JOIN `tbl_notification_history` h ON n.not_id=h.not_id
	INNER JOIN `tbl_notification_types` t ON n.not_type_id=t.not_type_id WHERE h.user_id='".$user_id."' AND h.created_on > '".$last_activity_time."' LIMIT 1");
	$rows1 = $conn->records_to_array($sql1);
	if(!empty($rows1))
	$jsonArray['new_notification'] = '1';
	else
	$jsonArray['new_notification'] = '0';	

	$sql = $conn->get_record_set("SELECT *,(cast((((acos(sin((".$captain_lat."*pi()/180)) * 
				sin((u.store_lat*pi()/180))+cos((".$captain_lat."*pi()/180)) * cos((u.store_lat*pi()/180)) * 
				cos(((".$captain_lng."- u.store_lng)* pi()/180))))*180/pi())*60*1.1515*1.609344 ) AS decimal(5,0)))
				AS distance FROM `tbl_orders` u WHERE customer_id!='$user_id' AND captain_id='0' AND u.created_on > '".$last_activity_time."'
				HAVING distance <=$distance LIMIT 1");
	$rows = $conn->records_to_array($sql);
	if(!empty($rows))
	$jsonArray['new_order'] = '1';
	else
	$jsonArray['new_order'] = '0';	
	$jsonArray['api_calling_duration']= '15';
	show_output($jsonArray);
?>