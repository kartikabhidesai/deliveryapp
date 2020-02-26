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

	if($_POST['customer_id']!='')
		$user_id = $_POST['customer_id']; //it can be customer or captain
	else
		$err = $lang["REQ_PARA"]."customer_id";

	if($_POST['order_id']!='')
		$order_id = $_POST['order_id'];
	else
		$err = $lang["REQ_PARA"]."order_id";

	if($err!='')
	{
		$jsonArray['Success']='0';
		$jsonArray['Message']=$err;
		show_output($jsonArray);
	}	

	$sql = $conn->get_record_set("SELECT * FROM `tbl_orders` WHERE (customer_id='$user_id' OR captain_id='$user_id') AND order_id='$order_id'");
	$rows = $conn->records_to_array($sql);
	if(!empty($rows))
	{	
		foreach($rows as $row)
		{
			
				if($row['order_status']=='Received')
				{
					$row1['status'] = 'Order Received';
					$row1['active'] = '0';
					$d[] = $row1;
					$row1['status'] = 'On the way';
					$row1['active'] = '0';
					$d[] = $row1;
					$row1['status'] = 'Delivered';
					$row1['active'] = '0';
					$d[] = $row1;
				}
				elseif($row['order_status']=='On the way' && $row['invoice_photo']=='')
				{
					$row1['status'] = 'Order Received';
					$row1['active'] = '1';
					$d[] = $row1;
					$row1['status'] = 'On the way';
					$row1['active'] = '0';
					$d[] = $row1;
					$row1['status'] = 'Delivered';
					$row1['active'] = '0';
					$d[] = $row1;
				}
				elseif($row['order_status']=='On the way')
				{
					$row1['status'] = 'Order Received';
					$row1['active'] = '1';
					$d[] = $row1;
					$row1['status'] = 'On the way';
					$row1['active'] = '1';
					$d[] = $row1;
					$row1['status'] = 'Delivered';
					$row1['active'] = '0';
					$d[] = $row1;
				}
				elseif($row['order_status']=='Delivered')
				{
					$row1['status'] = 'Order Received';
					$row1['active'] = '1';
					$d[] = $row1;
					$row1['status'] = 'On the way';
					$row1['active'] = '1';
					$d[] = $row1;
					$row1['status'] = 'Delivered';
					$row1['active'] = '1';
					$d[] = $row1;
				}
				elseif($row['order_status']=='Cancelled')
				{
					$row1['status'] = 'Order Received';
					$row1['active'] = '1';
					$d[] = $row1;
					$row1['status'] = 'Cancelled';
					$row1['active'] = '1';
					$d[] = $row1;
				}
				//$ord['orderStatusDetails']=$d;
				//$d=array();
		}

		$jsonArray['Success']='1';
		$jsonArray['Message']=$lang["SUCCESSFUL"];
		$jsonArray['api_calling_duration']= LOCATION_UPDATE_DURATION;
		$d1['invoice_no']= $row['invoice_no'];
		$d1['order_status']= $row['order_status'];
		$d1['address_lat']= $row['address_lat'];
		$d1['address_lng']= $row['address_lng'];
		$d1['store_lat']= $row['store_lat']; 
		$d1['store_lng']= $row['store_lng']; 
		if($row['captain_id']!='0')
		{
			$cap = $data->select("tbl_users" , "*", array('user_id' => $row['captain_id']));
			$d1['captain_lat']= $cap[0]['captain_lat'];
			$d1['captain_lng']= $cap[0]['captain_lng'];
		}
		else
		{
			$d1['captain_lat']= '';
			$d1['captain_lng']= '';
		}

			// Receiver ID of last user (customer/captain) who chat with customer/captain
			$sqlMsg = $conn->get_record_set("SELECT * FROM `tbl_messages` WHERE (sender_id='".$row['customer_id']."' OR receiver_id='".$row['customer_id']."') AND order_id='".$order_id."' AND is_deleted='0' ORDER BY message_id DESC LIMIT 1");
			$msg = $conn->records_to_array($sqlMsg);
			if($user_id == $msg[0]['sender_id'])
			$d1['receiver_id']= $msg[0]['receiver_id'];	
			elseif($user_id == $msg[0]['receiver_id'])
			$d1['receiver_id']= $msg[0]['sender_id'];	
			else
			$d1['receiver_id']= '0';	
			// End Receiver ID
		
		if($row['customer_id']== $user_id)
		{
			$d1['can_confirm_delivery']= ($row['order_status']=='Delivered' && $row['customer_confirmed_delivery']=='0')?'1':'0';
			$cap = $data->select("tbl_users" , "*", array('user_id' => $row['captain_id']));
			$d1['firebase_userid']= ($cap[0]['firebase_userid']!=NULL)?$cap[0]['firebase_userid']:'';
			$d1['receiver_id']= ($row['captain_id']>0)?$row['captain_id']:$d1['receiver_id'];
			$d1['is_customer']= '1';
		}
		else
		{
			$d1['can_confirm_delivery']= '0';
			$cus = $data->select("tbl_users" , "*", array('user_id' => $row['customer_id']));
			$d1['firebase_userid']= ($cus[0]['firebase_userid']!=NULL)?$cus[0]['firebase_userid']:'';
			$d1['receiver_id']= $row['customer_id'];
			$d1['is_customer']= '0';
		}
		$d1['captain_phone']= '';
		$d1['customer_phone']= '';

		$d1['tracking_status'] = $d;
		$jsonArray['detail']=$d1;	
	}
	else
	{
		$jsonArray['Success']='0';
		$jsonArray['Message']=$lang["NO_ORDER_FOUND"];
	}
show_output($jsonArray);
?>