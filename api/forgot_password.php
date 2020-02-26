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

	if($_POST['phone']!='')
		$phone = $_POST['phone'];
	else
		$err = $lang["REQ_PARA"].$lang["PHONE"];
		
	if($err!='')
	{
		$jsonArray['Success']='0';
		$jsonArray['Message']=$err;
		show_output($jsonArray);
	}	
	
	$digits = 4;
	$postdata['activation_code'] = ($dev==true)?"1234":rand(pow(10, $digits-1), pow(10, $digits)-1);
	$sms_text = $lang["SIGNUP_SMS_TEXT"].$postdata['activation_code'];
	
	$sql = $conn->get_record_set("Select u.* from tbl_users u where u.phone='".$phone."' AND is_active='1' AND is_deleted='0'");
	$row = $conn->records_to_array($sql);
	//print_r($row);exit;
	if(!empty($row))
	{
		if(!$dev)
		{
			$res = resend_sms($row[0]['phone'],$sms_text);
		}
		$data->update( "tbl_users" , $postdata , array("user_id"=>$row[0]['user_id'] ));
		$jsonArray['Success']='1';
		$jsonArray['Message']=$lang["FORGOT_PASSWORD_OTP"];	
	}
	else
	{
		$jsonArray['Success']='0';
		$jsonArray['Message']= $lang["INVALID_USER"] ;	
	}
	show_output($jsonArray);	
?>