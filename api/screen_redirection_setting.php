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
	$sql1 = $conn->get_record_set("SELECT * FROM `tbl_users` WHERE user_id='$user_id' AND captain_status='Approved'");
	$rows1 = $conn->records_to_array($sql1);
	if($rows1[0]['captain_active']=='1')
	{	
		$sql = $conn->get_record_set("SELECT * FROM `tbl_orders` WHERE captain_id='$user_id' AND order_status='On the way' LIMIT 1");
		$rows = $conn->records_to_array($sql);
		if(!empty($rows))
		{
			$jsonArray['Success']='1';
			$jsonArray['Message']='Show captain accepted order details.';
			$jsonArray['action']='get_order_detail'; // Accepted order details
			$jsonArray['order_id']= $rows[0]['order_id'];
		}
		else
		{
			$jsonArray['Success']='1';
			$jsonArray['Message']='Show captain orders.';
			$jsonArray['action']='get_captain_orders'; // Accepted order details
			$jsonArray['order_id']= '0';
		}
		
	}
	else
	{
		$jsonArray['Success']='1';
		$jsonArray['Message']='You are only customer';
		$jsonArray['action']='do_order'; // New ordr screen\
		$jsonArray['order_id']= '0';
	}
	$jsonArray['currency_sign'] = CURRENCY_SIGN;
	//$jsonArray['detail']= $d;
	show_output($jsonArray);
?>