<?php
	function rand_string($typeString, $intLength = 6) 
	{

		if($typeString==1){ $validCharacters = "abcdefghijklmnopqrstuxyvwz0123456789ABCDEFGHIJKLMNOPQRSTUXYVWZ";}
		if($typeString==2){ $validCharacters = "1234567890";}
		if($typeString==3){ $validCharacters = "abcdefghijklmnopqrstuxyvwz";}
		if($typeString==4){ $validCharacters = "ABCDEFGHIJKLMNOPQRSTUXYVWZ";}

		$validCharNumber = strlen($validCharacters);
		$result = "";
		for ($i = 0; $i < $intLength; $i++) {
			$index = mt_rand(0, $validCharNumber - 1);
			$result .= $validCharacters[$index];
		}
		return $result;
	}
	require_once("include/config.php");
	require_once("include/init.php");
	//include_once("include/notification.php");
	include_once("../include/sendNotification.php");
	include_once("../smtpmail/mail.php");
	
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
	
	if($_POST['store_id']!='')
		$postdata['store_id'] = $_POST['store_id']; // id from get_store.php
	else
		$err = $lang["REQ_PARA"] .'store_id';

	if($_POST['store_name']!='')
		$postdata['store_name'] = mysqli_real_escape_string($dbConn,$_POST['store_name']);
	else
		$err = $lang["REQ_PARA"] .'store_name';
	
	if($_POST['store_lat']!='')
		$postdata['store_lat'] = $_POST['store_lat'];
	else
		$err = $lang["REQ_PARA"] .'store_lat';
	
	if($_POST['store_lng']!='')
		$postdata['store_lng'] = $_POST['store_lng'];
	else
		$err = $lang["REQ_PARA"] .'store_lng';
	
	if($_POST['address_id']!='')
		$postdata['address_id'] = $_POST['address_id'];
	else
		$err = $lang["REQ_PARA"] .'address_id';
	
	if($_POST['order_size']!='')
		$postdata['order_size'] = $_POST['order_size'];    // 'small', 'medium', 'large'
	else
		$err = $lang["REQ_PARA"] .'order_size';
	
	if($_POST['order_details']!='')
		$postdata['order_details'] = $_POST['order_details'];
	else
		$err = $lang["REQ_PARA"] .'order_details';
	
	if($_POST['coupon_code']!='')
		$postdata['coupon_code'] = $_POST['coupon_code'];
	
	if($_POST['delivery_fee'] >0)
		$postdata['delivery_fee'] = $_POST['delivery_fee'];
	else
		$err = "You are not allow to order with zero delivery fee.";
	
	if($_POST['captain_tip']!='')
		$postdata['captain_tip'] = $_POST['captain_tip'];
	else
		$postdata['captain_tip'] = '0';
	
	//if($_POST['sub_total']!='')
	//	$postdata['sub_total'] = $_POST['sub_total'];
	//else
		//$postdata['sub_total'] = ($postdata['delivery_fee'] + $postdata['captain_tip']);
		$postdata['sub_total'] = '0';
	
	if(is_numeric($_POST['discount'])) 
		$postdata['discount'] = $_POST['discount'];
	else
		$postdata['discount'] = 0;
	
	//if($_POST['grand_total']!='')
	//	$postdata['grand_total'] = $_POST['grand_total'];
	//else
		$postdata['grand_total'] = ($postdata['delivery_fee'] - $postdata['discount']);
	
	if($_POST['payment_type']!='')
		$postdata['payment_type'] = $_POST['payment_type']; // 'Cash on Delivery', 'In System Transfer'
	else
		$err = $lang["REQ_PARA"] .'payment_type';
	
	if($_POST['customer_id']!='')
		$postdata['customer_id'] = $_POST['customer_id'];
	else
		$err = $lang["REQ_PARA"].$lang["CUSTOMER_ID"];
	
	//$postdata['order_status'] = 'Received';
	$custPoint = $data->select("tbl_settings","item_value",array("item_key"=>'customer_point'));
	$captPoint = $data->select("tbl_settings","item_value",array("item_key"=>'captain_point'));

	$postdata['customer_points'] = ($postdata['grand_total'] / $custPoint[0]['item_value']);	
	$postdata['earning_points'] = ($postdata['grand_total'] / $captPoint[0]['item_value']);	 
	 
	$postdata['created_on'] = date('Y-m-d H:i:s');
	//$postdata['city'] = getaddress($postdata['latitude'],$postdata['longitude']);
	
	/*if($_POST['sub_total']!=$postdata['sub_total'])
	{
		$jsonArray['Success']='0';
		$jsonArray['Message']='Incorrect sub total amount.';
		show_output($jsonArray);
	}*/
	
	if($_POST['grand_total']!=$postdata['grand_total'])
	{
		$jsonArray['Success']='0';
		$jsonArray['Message']=$lang["INCORRECT_GRAND_TOTAL"];
		show_output($jsonArray);
	}
	
	if($err!='')
	{
		$jsonArray['Success']='0';
		$jsonArray['Message']=$err;
		show_output($jsonArray); 
	}
	
	$add = $data->select("tbl_user_addresses" , "*", array('address_id' => $postdata['address_id']));
	$postdata['address_lat'] = $add[0]['address_lat'];
	$postdata['address_lng'] = $add[0]['address_lng'];
	$postdata['delivery_address'] = $add[0]['address_street'];

	$city = getaddress($postdata['store_lat'],$postdata['store_lng']);
	if($city != 'Riyadh' && RIYADH_ONLY)
	{
		$jsonArray['Success']='0';
		$jsonArray['Message']=$lang["SERVING_ONLY_FOR_RIYADH"];
		show_output($jsonArray);
	}

	$city = getaddress($postdata['address_lat'],$postdata['address_lng']);
	if($city != 'Riyadh' && RIYADH_ONLY)
	{
		$jsonArray['Success']='0';
		$jsonArray['Message']=$lang["SERVING_ONLY_FOR_RIYADH"];
		show_output($jsonArray);
	}

	// START COUPON CODE LOGIC
	if($postdata['coupon_code']!='')
	{	
		$sql = $conn->get_record_set("SELECT * FROM `tbl_coupons` WHERE coupon_code='".$postdata['coupon_code']."' AND is_deleted='0'");
		$row = $conn->records_to_array($sql);	
		extract($row[0]);
		if(!empty($row[0]))
		{
			if($discount_type=='percent')
			{
				$discount = round(($postdata['delivery_fee']*$discount_value)/100,2);
			}
			else
			{
				$discount = $discount_value;
			}		
			$sql1 = $conn->get_record_set("SELECT *	FROM `tbl_coupons_history` WHERE coupon_code='".$postdata['coupon_code']."' AND customer_id='".$postdata['customer_id']."'");
			$row1 = $conn->records_to_array($sql1);	
			if($is_active=='0')
			{
				$jsonArray['Success']='0';
				$jsonArray['Message']=$lang["INVALIDE_COUPON_CODE"];
				show_output($jsonArray);
			}	
			elseif($start_time > date('Y-m-d H:i:s'))
			{
				$jsonArray['Success']='0';
				$jsonArray['Message']=$lang["INVALIDE_COUPON_CODE"];
				show_output($jsonArray);
			}
			elseif($expiry_time < date('Y-m-d H:i:s'))
			{
				$jsonArray['Success']='0';
				$jsonArray['Message']=$lang["COUPON_CODE_EXPIRED"];
				show_output($jsonArray);
			}
			elseif($min_order_amt > $postdata['delivery_fee'])
			{
				$jsonArray['Success']='0';
				$jsonArray['Message']=$lang["MINIMUM_ORDER_AMOUNT_MUST_BE"].$min_order_amt;
				show_output($jsonArray);
			}
			elseif($is_multi_use=='0' && !empty($row1))
			{
				$jsonArray['Success']='0';
				$jsonArray['Message']=$lang["ALREADY_USED_COUPON"];
				show_output($jsonArray);
			}
			elseif($discount!=$postdata['discount'])
			{
				$jsonArray['Success']='0';
				$jsonArray['Message']=$lang["INCORRECT_DISCOUNT_AMOUNT"];
				show_output($jsonArray);
			}
		}
		else
		{
			$jsonArray['Success']='0';
			$jsonArray['Message']=$lang["INVALIDE_COUPON_CODE"];
			show_output($jsonArray);
		}
	}	
	// END COUPON CODE LOGIC
	
	$sql = $conn->get_record_set("SELECT user_id,full_name,wallet_balance,firebase_userid FROM tbl_users WHERE is_active='1' AND user_id='".$postdata['customer_id']."'");
	$rows = $conn->records_to_array($sql);
	if(!empty($rows))
	{
			if($postdata['payment_type']=='In System Transfer')
			{
				if($rows[0]['wallet_balance'] < $postdata['grand_total'])
				{
					$jsonArray['Success']='0';
					$jsonArray['Message']=$lang["INSUFFICIENT_WALLET_BALANCE"];
					show_output($jsonArray);
				}
				
				$sql1 = $conn->get_record_set("SELECT SUM(grand_total) AS occupied_balance FROM tbl_orders WHERE customer_id='".$postdata['customer_id']."'
				AND payment_type='In System Transfer' AND (order_status='Received' OR order_status='On the way')");
				$rows1 = $conn->records_to_array($sql1);
				$remain_balance = $rows[0]['wallet_balance'] - $rows1[0]['occupied_balance'];
				if($remain_balance < $postdata['grand_total'])
				{
					$jsonArray['Success']='0';
					$jsonArray['Message']=$lang["INSUFFICIENT_WALLET_BALANCE1"];
					show_output($jsonArray);
				}
			}

			$postdata['customer_name'] = $rows[0]['full_name'];
			//$postdata['customer_firebase_userid'] = $rows[0]['firebase_userid'];
			if(!empty($_FILES['order_photo']))
			{
				$path= '../uploads/order/';
				$userf = $_FILES['order_photo']['tmp_name'];
				$fname = $_FILES['order_photo']['name'];
				$ext = pathinfo($fname, PATHINFO_EXTENSION);
				$img =  time()."_"."order.".$ext;
				if(move_uploaded_file($userf,$path.$img))
				{
					$postdata['order_photo'] =$img;
					createthumb($path . $img, $path.'150x150/'.$img,150,150);		
				}
			}
			
			while(true)
			{
				$postdata["invoice_no"] =  rand_string(2,6);
				$sql = mysqli_query($dbConn,"select * from tbl_orders where `invoice_no`='".$postdata['invoice_no']."'");
				if(mysqli_num_rows($sql)==0)
				break;
			}
			
			$order_id = $data->insert( "tbl_orders" , $postdata );
			
			// PUSH NOTIFICATION
			$url = BASE_URL."api/notify_new_order.php?store_lat=".$postdata['store_lat']."&store_lng=".$postdata['store_lng']."&customer_id=".$postdata['customer_id']."&order_id".$order_id;
			 $ch = curl_init();
	        curl_setopt($ch, CURLOPT_URL, $url);
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
	        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	        $coordinates = curl_exec($ch);
	        curl_close($ch);
			
			$currtime = date('Y-m-d H:i:s');
			$text = "New order placed. Order #".$postdata["invoice_no"];
			$text_ar = " تم ارسال طلبك. طلبك #".$postdata["invoice_no"];
			$postdata_noti['not_text'] = $text;
			$postdata_noti['not_text_ar'] = $text_ar;
			$postdata_noti['not_type_id'] = '15';
			$postdata_noti['invoice_no'] = $postdata["invoice_no"];
			$postdata_noti['created_on'] = $currtime;
			$lastid = $data->insert( "tbl_notification" , $postdata_noti );	
		
			$postdata_noti_his['not_id'] = $lastid;
			$postdata_noti_his['user_id'] = $postdata['customer_id'];
			$postdata_noti_his['send_time'] = $currtime;
			$postdata_noti_his['created_on'] = $currtime;
			$autoId = $data->insert( "tbl_notification_history" , $postdata_noti_his );	
			
			$jsonArray['Success']='1';
			$jsonArray['Message']=$lang["SUCCESSFUL"];
			$sql = $conn->get_record_set("SELECT * FROM `tbl_orders` WHERE `order_id` = '".$order_id."'");
			$row = $conn->records_to_array($sql);
			$jsonArray['detail']= $row[0]; 
	}
	else
	{
		$jsonArray['Success']= '0';
		$jsonArray['Message']= $lang["NOT_FOUND"];
	}
	
	show_output($jsonArray);