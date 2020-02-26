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
	
	if($_POST['captain_id']!='')
		$captain_id = $_POST['captain_id'];
	else
		$err = $lang["REQ_PARA"]."captain_id";
	
	if($_POST['current_lat']!='')
		$current_lat = $_POST['current_lat'];
	else
		$err = $lang["REQ_PARA"].$lang["LATITUDE"];	
	
	if($_POST['current_lng']!='')
		$current_lng = $_POST['current_lng'];
	else
		$err = $lang["REQ_PARA"].$lang["LONGITUDE"];	
		
	$distance = CAPTAIN_ORDER_DISTANCE ;
	if($err!='')
	{
		$jsonArray['Success']='0';
		$jsonArray['Message']=$err;
		show_output($jsonArray);
	}	
	
	$sql1 = $conn->get_record_set("SELECT * FROM `tbl_users` WHERE user_id='$captain_id'");
	$rows1 = $conn->records_to_array($sql1);
	if($rows1[0]['captain_status']=='Pending')
	{
		$jsonArray['Success']='0';
		$jsonArray['Message']=$lang["REQUEST_PENDING_FOR_APPROVAL"];
	}
	elseif($rows1[0]['captain_active']=='0')
	{
		$jsonArray['Success']='0';
		$jsonArray['Message']=$lang["YOUR_ACCOUNT_DEACTIVATED"];
	}
	elseif($rows1[0]['wallet_balance'] <='0')
	{
		$jsonArray['Success']='0';
		$jsonArray['Message']=$lang["RECHARGE_YOUR_WALLET"];
	}
	else
	{	
		$sql = $conn->get_record_set("SELECT * FROM `tbl_orders` WHERE captain_id='$captain_id' AND (order_status='Received' OR order_status='On the way') LIMIT 1");
		$rows = $conn->records_to_array($sql);
		if(empty($rows))
		{
			$sql = $conn->get_record_set("SELECT *,(cast((((acos(sin((".$current_lat."*pi()/180)) * 
				sin((u.store_lat*pi()/180))+cos((".$current_lat."*pi()/180)) * cos((u.store_lat*pi()/180)) * 
				cos(((".$current_lng."- u.store_lng)* pi()/180))))*180/pi())*60*1.1515*1.609344 ) AS decimal(5,0)))
				AS distance FROM `tbl_orders` u WHERE customer_id!='$captain_id' AND captain_id='0' HAVING distance <=$distance  ORDER BY order_id DESC ");
			$rows = $conn->records_to_array($sql);
		}

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
			    $store_distance = calDistance($current_lat,$current_lng,$store_lat,$store_lng, "K");
				$customer_distance = calDistance($store_lat,$store_lng,$address_lat,$address_lng, "K");
				$row1['store_distance'] = $store_distance.' KM'; 
				$row1['customer_distance'] = $customer_distance.' KM'; 
				$row1['total_distance'] = ($store_distance + $customer_distance).' KM'; 
				$row1['customer_distance'] = $row1['total_distance']; // IOS use this tag as total_distance
				$delivery_min = ($store_distance + $customer_distance) * 4 ;
				$delivery_hrs = intval($delivery_min / 60); 
				$delivery_hrs = ($delivery_hrs >9)?$delivery_hrs:'0'.$delivery_hrs; 
				$delivery_min = intval($delivery_min % 60); 
				$delivery_min = ($delivery_min >9)?$delivery_min:'0'.$delivery_min;
				$row1['expected_delivery_time'] = $delivery_hrs.':'.$delivery_min.' MIN'; 
				$row1['order_time'] = strtoupper(getTime($row1['created_on']));  
				$row1['can_accept_order'] = ($row1['order_status']=='Received')?'1':'0';
				$row1['can_deliver_order'] = ($row1['order_status']=='On the way')?'1':'0';
				$usr = $data->select("tbl_users" , "*", array('user_id' => $row1['customer_id']));
				$row1['firebase_userid']= ($usr[0]['firebase_userid']!=NULL)?$usr[0]['firebase_userid']:'';
				$row1['customer_phone']= PHONE_CODE . $usr[0]['phone'];
				$row1['receiver_id']= $row1['customer_id'];
				$d[]= $row1;
			}
			$jsonArray['Success'] = '1';
			$jsonArray['Message'] = $lang["SUCCESSFUL"];
		}
		else
		{
			$jsonArray['Success']='0';
			$jsonArray['Message']=$lang["NO_NEW_ORDER_AVAILABLE"];
		}
	}
	//$jsonArray['currency_sign'] = CURRENCY_SIGN;
	$jsonArray['detail']= $d;
	$time = date('Y-m-d H:i:s');
	mysqli_query($dbConn,"UPDATE tbl_users SET last_activity_time='".$time."' WHERE user_id='$captain_id'");
	show_output($jsonArray);
?>