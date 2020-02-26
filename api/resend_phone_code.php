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

	if($_POST['user_id']!='')
		$user_id = $_POST['user_id'];
	else
		$err = $lang["REQ_PARA"].$lang["USER_ID"];
		
	if($err!='')
	{
		$jsonArray['Success']='0';
		$jsonArray['Message']=$err;
		show_output($jsonArray);
	}	
	
	$digits = 4;
	$postdata['activation_code'] = ($dev==true)?"1234":rand(pow(10, $digits-1), pow(10, $digits)-1);
	$sms_text = $lang["SIGNUP_SMS_TEXT"].$postdata['activation_code'];
	
	$sql = $conn->get_record_set("Select u.* from tbl_users u where u.user_id='".$user_id."' AND is_active='1' AND is_deleted='0'");
	$row = $conn->records_to_array($sql);
	if(!empty($row[0]))
	{
		if(!$dev)
		{
			$res = resend_sms($row[0]['temp_phone'],$sms_text);
		}
		$data->update( "tbl_users" , $postdata , array("user_id"=>$user_id ));
		$jsonArray['Success']='1';
		$jsonArray['Message']=$lang["CONF_LINK"];	
	}
	else
	{
		$jsonArray['Success']='0';
		$jsonArray['Message']= $lang["INVALID_USER"] ;	
	}
	show_output($jsonArray);	
?>