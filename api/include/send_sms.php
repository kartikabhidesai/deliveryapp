<?php
	//extract($_POST);
	$phone_number = $_POST['phone_number'];
	$message_data = urlencode($_POST['message_data']);
	// BULKSMS SERVICE
	$phone_number = ((strpos($phone_number,"0",0)===false)?$phone_number:ltrim($phone_number, '0'));
	$phone = ((strlen($phone_number)>10)? $phone_number : "966".$phone_number);
	$api_id = "API215243846882";
	//$sender_id = "TSTALA";
	//$sender_id = "MAX-AD";
	//$sender_id = "INFO";
	$sender_id = "HEREAPP";
	$pass   = "heresms@531";
	$uri = 'http://api.bulksmsplans.com/api/SendSMS?api_id='.$api_id.'&api_password='.$pass.'&sms_type=T&encoding=U&sender_id='.$sender_id.'&phonenumber='.$phone.'&textmessage='.$message_data ;
    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_URL, $uri );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, false ); // don't echo
	return $result = json_decode(curl_exec($ch)); 
    //return ($result->status=='S')?TRUE:FALSE;
	
	// SMSCOUNTRY SERVICE
	/*$phone_number = ((strpos($_POST['phone_number'],"0",0)===false)?$_POST['phone_number']:ltrim($_POST['phone_number'], '0'));
	$phone_number_updated = ((strlen($phone_number)>10)? $phone_number : "966".$phone_number);
	//$uname = "adhmn.sa";
	//$pass = "adhmn0388";
	//$sender = "Delivery";
	$uri = 'http://api.smscountry.com/SMSCwebservice_bulk.aspx?User='.$uname.'&passwd='.$pass.'&mobilenumber='.$phone_number_updated.'&message='. urlencode($_POST['message_data']).'&sid='.$sender.'&mtype=LNG&DR=Y';
	$res = curl_init();
    curl_setopt( $res, CURLOPT_URL, $uri );
    curl_setopt( $res, CURLOPT_RETURNTRANSFER, false ); // don't echo
	curl_exec( $res );*/
	
?>