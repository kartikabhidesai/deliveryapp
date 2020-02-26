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
	
	if($_POST['phone']!='')
		$phone = trim($_POST['phone']);
	else
		$err = $lang["REQ_PARA"].$lang["PHONE"];
	
	if($_POST['activation_code']!='')
		$activation_code = $_POST['activation_code'];
	else
		$err = $lang["REQ_PARA"].$lang["ACTIVATION_CODE"];
	
	if($_POST['password']!='')
		$password = md5(convert_en($_POST['password']));
	else
		$err = $lang["REQ_PARA"].$lang["PASSWORD"];
	
	if($_POST['confirm_password']!='')
		$confirm_password = md5(convert_en($_POST['confirm_password']));
	else
		$err = $lang["REQ_PARA"].$lang["CONFIRM_PASSWORD"];
	
	
	if($err!='')
	{
		$jsonArray['Success']='0';
		$jsonArray['Message']=$err;
		show_output($jsonArray);
	}	

	$postdata['updated_on']=date('Y-m-d H:i:s');

	$sql = $conn->get_record_set("SELECT * FROM `tbl_users` WHERE `activation_code`='".$activation_code."' AND phone='".$phone."' AND is_deleted='0'");
	$row = $conn->records_to_array($sql);
	if(!empty($row))
	{
                $postdata['email_id'] =$row[0]['email_id'];
                $postdata['full_name'] =$row[0]['full_name'];
		$postdata['password'] = $password;
		$postdata['activation_code'] = '';
		$data->update( "tbl_users" , $postdata,array("phone"=>$phone,"activation_code"=>$activation_code));
		$jsonArray['Success']='1';
		$jsonArray['Message']=$lang["RESET_PASSWORD_SUCCESSFULLY"];
                reset_password_mail($postdata);
	}
	else
	{
		$jsonArray['Success'] = '0';
		$jsonArray['Message'] = $lang["INCORRECT_CODE"];	
	}
	show_output($jsonArray);
        function reset_password_mail($postdata) {
            include('smtpmail/sendmail.php');
            $userData['{{firstname}}'] = $postdata['full_name'];
            $userData['{{email_id}}'] = $postdata['email_id'];
            $email = $postdata['email_id'];
            $subject = 'Here Delivery - Your password has been successfully reset';
            $attach = '';
            $userData = json_encode($userData);
            $message = createtemplate('emailtemplate/reset_password.php', $userData);
            send_mail($email, $subject, $message, $attach);

        }
?>