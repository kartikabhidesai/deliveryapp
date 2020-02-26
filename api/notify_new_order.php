<?php
		require_once('include/config.php');
		include_once("../include/sendNotification.php");
		
		if($language == 'en')
			require_once('lang/en.php');
		elseif($language == 'ar')
			require_once('lang/ar.php');
		else
			require_once('lang/en.php');

		$distance = CAPTAIN_ORDER_DISTANCE ;
		extract($_REQUEST);
		$sql = $conn->get_record_set("SELECT *,(cast((((acos(sin((".$store_lat."*pi()/180)) * 
				sin((u.captain_lat*pi()/180))+cos((".$store_lat."*pi()/180)) * cos((u.captain_lat*pi()/180)) * 
				cos(((".$store_lng."- u.captain_lng)* pi()/180))))*180/pi())*60*1.1515*1.609344 ) AS decimal(5,0)))
				AS distance FROM `tbl_users` u WHERE user_id!='$customer_id' AND captain_status='Approved' AND captain_active='1' AND is_deleted='0' 
					AND wallet_balance >0 HAVING distance <='$distance' ORDER BY distance");
		$rows = $conn->records_to_array($sql);
		if(!empty($rows))
		{
			$text = "New order around you. Hurry up to accept it.";
			$text_ar = "طلب جديد من حولك. عجلوا لقبوله.";
			foreach($rows as $row1)
			{
				$text = ($row1['language']=='ar')?$text_ar:$text;
				$type ='new_order';
				$arr['order_id'] = $order_id;
				if($row1['device_type']=='iphone')
				$result=sendToIphone($row1['device_token'],$text,$type,$row1['badge_count'],'1',$arr);
				else
				$result=sendToAndroid($row1['device_token'],$text,$type,$row1['badge_count'],'1',$arr);
			}
			$jsonArray['Success'] = '1';
			$jsonArray['Message'] = $lang["SUCCESSFUL"];
		}
		else
		{
			$jsonArray['Success']='0';
			$jsonArray['Message']=$lang["NO_NEW_ORDER_AVAILABLE"];
		}

	//$jsonArray['detail']= $d;
	//echo json_encode($jsonArray);
?>