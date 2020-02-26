<?php

	require_once("include/config.php");
	
	require_once("include/init.php");
	include_once("include/notification.php");
	
	if($language == 'en')
		require_once('lang/en.php');
	elseif($language == 'ar')
		require_once('lang/ar.php');
	else
		require_once('lang/en.php');
	
	$conn=new Database;
	$data = new DataManipulator;
	$jsonArray = array();

	if($_POST['full_name']!='')
		$postdata['full_name'] = $_POST['full_name'];
	//else
		 //$err = $lang["REQ_PARA"]."full_name";
	 
	if($_POST['phone']!='')
		$postdata['phone'] = $_POST['phone'];
	else
		 $err = $lang["REQ_PARA"].$lang["PHONE"];
	
	$phone = $postdata['phone'];
	
	if($_POST['password']!='')
		$postdata['password'] = md5(convert_en($_POST['password']));
	//else
		//$err = $lang["REQ_PARA"].$lang["PASSWORD"];
	
	if($_POST['email_id']!='')
		$postdata['email_id'] = $_POST['email_id'];
	//else
		// $err = $lang["REQ_PARA"]."email_id";
	 
	$postdata['device_id'] = $device_id; 
	$postdata['device_token'] = $device_token; 
	$postdata['device_type'] = $device_type;
	$postdata['is_online'] = "1";
	
	if($err!='')
	{
		$jsonArray['Success']='0';
		$jsonArray['Message']=$err;
		show_output($jsonArray); 
	}
	
	$digits = 4;
	if (strpos($phone, '9999') !== false)
	$postdata['activation_code'] = "1234";
	else
	$postdata['activation_code'] = rand(pow(10, $digits-1), pow(10, $digits)-1);	
	//$postdata['activation_code'] = "1234"; // temp
	$sms_text = $lang["SIGNUP_SMS_TEXT"].$postdata['activation_code'];
	
	$sql = $conn->get_record_set("SELECT * FROM `tbl_users` WHERE phone LIKE '%".substr($phone, -9)."' AND is_deleted ='0'");
	$rows = $conn->records_to_array($sql);
	if(empty($rows))
	{
		$sql1 = $conn->get_record_set("SELECT firebase_userid FROM `tbl_users` WHERE phone LIKE '%".substr($phone, -9)."' AND firebase_userid!=''");
		$rows1 = $conn->records_to_array($sql1);
		if(!empty($rows1))
		{
			$postdata['firebase_userid'] = $rows1[0]['firebase_userid'];
		}
		$postdata['created_on'] = date('Y-m-d H:i:s');
		$postdata['temp_phone'] = $_POST['phone'];
		$postdata['profile_pic'] = 'default_profile.png';
		// SMS VERIFICATION CODE
		
		$user_id = $data->insert("tbl_users" , $postdata );
		if (strpos($phone, '9999') !== false)		{
			//$msgId = send_sms($phone,$sms_text);
		}
		else {
			$msgId = send_sms($phone,$sms_text);
		}

		$jsonArray['Success']='1';
		$jsonArray['Message']=$lang["VERIFICATION_CODE_SENT"];	
	}
	elseif($rows[0]['is_verified']=='0')
	{
			if (strpos($phone, '9999') !== false)		{
			//$msgId = send_sms($phone,$sms_text);
			}
			else {
				$msgId = send_sms($phone,$sms_text);
			}
			$jsonArray['Success']='1';
			$jsonArray['Message']=$lang["VERIFICATION_CODE_SENT"];
			$data->update( "tbl_users" , $postdata,array("user_id"=>$rows[0]['user_id']) );
	}
	else
	{
		$jsonArray['Success']='0';
		$jsonArray['Message']=$lang["PHONE_EXIST_ALREADY"];
		show_output($jsonArray);
	}
	
		$sql = $conn->get_record_set("SELECT * FROM `tbl_users` WHERE user_id='$user_id' AND is_deleted ='0'");
		$rows = $conn->records_to_array($sql);
		foreach($rows as $row1)
		{
			$row1['password']='';
			if((PHOTO_URL ."profile/".$row1['profile_pic']) && $row1['profile_pic']!='')
			$row1['profile_pic_150'] = ($row1['profile_pic']!='')?PHOTO_URL ."profile/".$row1['profile_pic']:'';
			else
			$row1['profile_pic_150'] = '';
			$row1['profile_pic'] = ($row1['profile_pic']!='')?PHOTO_URL ."profile/".$row1['profile_pic']:'';
			$row1['total_points']= strval($row1['total_customer_points'] + $row1['total_captain_points']); 
			$jsonArray['is_verified']= $row1['is_verified'];
			if($row1['full_name']=='')
			$jsonArray['completed_profile']= '0';
			else
			$jsonArray['completed_profile']= '1';
			$jsonArray['detail']= $row1;
		}
	show_output($jsonArray);
?>