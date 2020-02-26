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
	
	if($_POST['customer_id']!='')
		$customer_id = $_POST['customer_id'];
	else
		$err = $lang["REQ_PARA"]."customer_id";
		
	if($err!='')
	{
		$jsonArray['Success']='0';
		$jsonArray['Message']=$err;
		show_output($jsonArray);
	}	
	$sql = $conn->get_record_set("SELECT * FROM `tbl_orders` WHERE customer_id='$customer_id' OR captain_id='$customer_id' ORDER BY order_id DESC");
	$rows = $conn->records_to_array($sql);

	if(!empty($rows))
	{
		foreach($rows as $row1)
		{
			extract($row1);
			if($row1['order_photo']!='')
			$row1['order_photo'] = PHOTO_URL ."order/".$row1['order_photo'];
			else
			$row1['order_photo'] = '';
			if($row1['invoice_photo']!='')
			$row1['invoice_photo'] = PHOTO_URL ."invoice/".$row1['invoice_photo'];
			else
			$row1['invoice_photo'] = '';
			$store_distance = calDistance($address_lat,$address_lng,$store_lat,$store_lng, "K");
			$customer_distance = '0';
			$row1['store_distance'] = $store_distance.' KM'; 
			$row1['customer_distance'] = $customer_distance.' KM'; 
			$delivery_min = ($store_distance + $customer_distance) * 4 ;
			$delivery_hrs = intval($delivery_min / 60); 
			$delivery_hrs = ($delivery_hrs >9)?$delivery_hrs:'0'.$delivery_hrs; 
			$delivery_min = intval($delivery_min % 60); 
			$delivery_min = ($delivery_min >9)?$delivery_min:'0'.$delivery_min;
			$row1['expected_delivery_time'] = $delivery_hrs.':'.$delivery_min.' MIN'; 
			$row1['order_time'] = strtoupper(getTime($row1['created_on']));  
			$usr = $data->select("tbl_users" , "*", array('user_id' => $row1['captain_id']));
			$row1['firebase_userid']= ($usr[0]['firebase_userid']!=NULL)?$usr[0]['firebase_userid']:'';
			$row1['captain_phone']= PHONE_CODE . $usr[0]['phone'];
			$d[]= $row1;
		}	
		$jsonArray['Success'] = '1';
		$jsonArray['Message'] = $lang["SUCCESSFUL"];
	}
	else
	{
		$jsonArray['Success']='0';		$jsonArray['Message'] = $lang["NO_ORDER_FOUND"];
	}
	//$jsonArray['currency_sign'] = CURRENCY_SIGN;
	$jsonArray['detail']= $d;
	show_output($jsonArray);
?>