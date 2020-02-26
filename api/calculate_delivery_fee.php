<?php
	require_once('include/config.php');
	require_once('include/init.php');
	//require_once('include/thumb.php');
	
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
	
	if($_POST['customer_id']!='')
		$customer_id = $_POST['customer_id'];
	else
		$err = $lang["REQ_PARA"] .'customer_id';
	
	if($_POST['store_lat']!='')
		$store_lat = $_POST['store_lat'];
	else
		$err = $lang["REQ_PARA"] .'store_lat';
	
	if($_POST['store_lng']!='')
		$store_lng = $_POST['store_lng'];
	else
		$err = $lang["REQ_PARA"] .'store_lng';
	
	if($_POST['address_lat']!='')
		$add_lat = $_POST['address_lat'];
	else
		$err = $lang["REQ_PARA"] .'address_lat';
	
	if($_POST['address_lng']!='')
		$add_lng = $_POST['address_lng'];
	else
		$err = $lang["REQ_PARA"] .'address_lng';
	
	if($_POST['order_size']!='')
		$order_size = $_POST['order_size']; //small,medium,large
	else
		$err = $lang["REQ_PARA"] .'order_size';
		
	if($err!='')
	{
		$jsonArray['Success']='0';
		$jsonArray['Message']=$err;
		show_output($jsonArray);
	}	
	
	$jsonArray['Success'] = '1';
	$jsonArray['Message'] = $lang["SUCCESSFUL"];
	$distance = calDistance($add_lat,$add_lng,$store_lat,$store_lng, "K"); // NEW PARAMETER
	//echo "SELECT * FROM `tbl_fee_setting` WHERE item_key='$order_size' AND $distance BETWEEN from_distance AND to_distance"; 
	$sql = $conn->get_record_set("SELECT * FROM `tbl_fee_setting` WHERE item_key='$order_size' AND $distance BETWEEN from_distance AND to_distance");
	$row = $conn->records_to_array($sql);
	$jsonArray['delivery_fee'] =  ($row[0]['item_value']>0)?$row[0]['item_value']:'0';
	$jsonArray['sub_total'] =  '0';  // invoice item(s) value
	$jsonArray['discount'] =  '0';
	$jsonArray['grand_total'] =  strval($jsonArray['delivery_fee'] - $jsonArray['discount']);
	show_output($jsonArray);
?>