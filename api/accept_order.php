<?php
	require_once("include/config.php");
	require_once("include/init.php");
	include_once("../include/sendNotification.php");

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
		$postdata['captain_id'] = $_POST['captain_id'];
	else
		$err = $lang["REQ_PARA"]."captain_id";

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
	
	$cap = $data->select("tbl_users" , "*", array('user_id' => $postdata['captain_id']));
	$postdata['order_status'] = 'On the way';
	$postdata['captain_name'] = $cap[0]['captain_name'];
	//$postdata['captain_firebase_userid'] = $cap[0]['firebase_userid'];
	$sql = $conn->get_record_set("SELECT o.* ,u.device_type, u.device_token,u.badge_count FROM `tbl_orders` o INNER JOIN `tbl_users` u ON o.customer_id=u.user_id WHERE order_id='$order_id' AND captain_id='0'");
	$rows = $conn->records_to_array($sql);
	if(!empty($rows))
	{
			$data->update("tbl_orders" , $postdata,array("order_id"=>$order_id) );
	
			$currtime = date('Y-m-d H:i:s');
			$text = "New order has been Accepted. Order #".$rows[0]["invoice_no"];
			$text_ar = " تم قبول طلب جديد. طلب #".$rows[0]["invoice_no"];
			$postdata_noti['not_text'] = $text;
			$postdata_noti['not_text_ar'] = $text_ar;
			$postdata_noti['not_type_id'] = '15';
			$postdata_noti['invoice_no'] = $rows[0]["invoice_no"];
			$postdata_noti['created_on'] = $currtime;
			$lastid = $data->insert( "tbl_notification" , $postdata_noti );	
		
			$postdata_noti_his['not_id'] = $lastid;
			$postdata_noti_his['user_id'] = $postdata['captain_id'];
			$postdata_noti_his['send_time'] = $currtime;
			$postdata_noti_his['created_on'] = $currtime;
			$autoId = $data->insert( "tbl_notification_history" , $postdata_noti_his );
			
			//===============================================
			$currtime = date('Y-m-d H:i:s');
			$text = "Your order has been accepted and captain is on the way. Order #".$rows[0]["invoice_no"];
			$text_ar = " تم قبول طلبك والكابتن بالطريق. طلبك #".$rows[0]["invoice_no"];
			$postdata_noti['not_text'] = $text;
			$postdata_noti['not_text_ar'] = $text_ar;
			$postdata_noti['not_type_id'] = '15';
			$postdata_noti['invoice_no'] = $rows[0]["invoice_no"];
			$postdata_noti['created_on'] = $currtime;
			$lastid = $data->insert( "tbl_notification" , $postdata_noti );	
		
			$postdata_noti_his['not_id'] = $lastid;
			$postdata_noti_his['user_id'] = $rows[0]['customer_id'];
			$postdata_noti_his['send_time'] = $currtime;
			$postdata_noti_his['created_on'] = $currtime;
			$autoId = $data->insert( "tbl_notification_history" , $postdata_noti_his );
			
			$text = ($language=='ar')?$text_ar:$text;
			$type ='accept_order';
			$arr['order_id'] = $order_id;
			if($rows[0]['device_type']=='iphone')
			$result=sendToIphone($rows[0]['device_token'],$text,$type,$rows[0]['badge_count'],'1',$arr);
			else
			$result=sendToAndroid($rows[0]['device_token'],$text,$type,$rows[0]['badge_count'],'1',$arr);
	
			$jsonArray['Success']='1';
			$jsonArray['Message']=$lang["SUCCESSFUL"];	
			$jsonArray['location_update_duration'] = LOCATION_UPDATE_DURATION; // In Second
	}
	else
	{
		$jsonArray['Success']='0';
		$jsonArray['Message']=$lang["ALREADY_ACCEPTED_BY_ANOTHER"];
	}
	show_output($jsonArray); 
