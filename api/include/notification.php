<?php
require_once('config.php');
//require_once('init.php');

	function sendToIphone_test1($device_token,$data)
{
		$dbConn = mysqli_connect(DB_HOST, DB_NAME, DB_PASS, DB_NAME) or die("Connection failed: " . mysqli_connect_error());
		$noti_array = array("add_job_en"=>"You have a new request from ##UNAME##",
						"add_job_ar"=>"##UNAME## لديك طلب جديد من ",
						"process_en"=>"##UNAME## processed your job request",
						"process_ar"=>"قام بتنفيذ الطلب ##UNAME##",
						"confirm_en"=>"##UNAME## confirmed your job request",
						"confirm_ar"=>"قام بتأكيد الطلب ##UNAME##",
						"complete_en"=>"##UNAME## completed job",
						"complete_ar"=>"قام بإكمال الطلب ##UNAME##",
						"feedback_en"=>"##UNAME## sent feedback on job",
						"feedback_ar"=>"ارسل تقييم ##UNAME##",
						"cancel_en"=>"##UNAME## cancelled job",
						"cancel_ar"=>"قام بإلغاء الطلب ##UNAME##",
						"autocancel_job_sp_en"=>"No other service provider available, Do you want to continue with same service provider?",
						"autocancel_job_sp_ar"=>"لايوجد مقدم خدمة اخر متوفر، هل تريد الاستمرار مع مقدم الخدمة الحالي",
						"autocancel_job_sps_en"	=>"Do you want to change the service provider for your job?",		
						"autocancel_job_sps_ar"	=>"هل تريد تغيير مقدم الخدمة لطلبك؟",
						"admin_cancelled_you_en"	=>"Admin cancelled your job ###REQUESTID##",		
						"admin_cancelled_you_ar"	=>"ألغى المشرف مهمتك ##REQUESTID###",	
						"admin_forwarded_you_en"	=>"Admin forwarded job to you",		
						"admin_forwarded_you_ar"	=>"المشرف تمت إعادة توجيه المهمة إليك",
						"admin_forwarded_en"	=>"Admin forwarded job to ##UNAME##",	
						"admin_forwarded_ar"	=>"##UNAME## تمت إعادة توجيه مهمة المشرف إلى",
						"tech_exp_en"=>"Your account is expired shortly",
						"tech_exp_ar"=>"الفترة المجانية قاربت على الانتهاء",
						"unread_notify_en"=>"You have ##BADGE## unread notifications",
						"unread_notify_ar"=>"إخطارا غير مقروء ##BADGE## لديك",
						"recharge_approved_en"=>"Your recharge request approved and balance is updated",
						"recharge_approved_ar"=>"تمت الموافقة على طلب إعادة شحن رصيدك",
						"recharge_rejected_en"=>"Your recharge request rejected",
						"recharge_rejected_ar"=>"تم رفض طلب إعادة الشحن",
						"add_balance_en"=>"New balance is deposited in your account",
						"add_balance_ar"=>"يتم إيداع رصيد جديد في حسابك",
						"deduct_balance_en"=>"Balance is deducted from your account",
						"deduct_balance_ar"=>"يتم خصم الرصيد من حسابك"
);
	if($data['message']!="unread_notify")
	mysqli_query($dbConn,"UPDATE tbl_users SET badge=badge+1 WHERE user_id='".$data['user_id']."'");
	$sql=mysqli_query($dbConn,"SELECT lang,badge FROM tbl_users  WHERE user_id='".$data['user_id']."'");
	$row=mysqli_fetch_assoc($sql);
	$u_lang = $row['lang'];
	$badge = $row['badge'];
	
	$sql=mysqli_query($dbConn,"SELECT name FROM tbl_users  WHERE user_id='".$data['user_id2']."'");
	$row=mysqli_fetch_assoc($sql);
	$name =  mysqli_real_escape_string($dbConn,$row['name']); 
	
	$sql = mysqli_query($dbConn,"SELECT request_id from tbl_jobs where `job_id` = '".$data['job_id']."'");
	$row = mysqli_fetch_assoc($sql);
	$request_id =  $row['request_id']; 
	
	if($u_lang == 'en')
		$message = $data['message']."_en";
	elseif($u_lang == 'ar')
		$message = $data['message']."_ar";
	else
		$message = $data['message']."_en";
	
	
	if(array_key_exists($message,$noti_array))
	{
		$content = $noti_array[$message];
		$content0=str_replace("##UNAME##",$name, $content);
		$content1=str_replace("##REQUESTID##",$request_id, $content0);
		$content2=str_replace("##BADGE##",$badge, $content1);
		$final_message = $content2;
	}
	else
	{
		$final_message = $data['message'];
	}
	$passphrase = '';
	$ctx = stream_context_create();
	/*stream_context_set_option($ctx, 'ssl', 'local_cert', '../certificates/'.CERTIFICATE_NAME);
	stream_context_set_option($ctx, 'ssl', 'passphrase', PASSPHRASE);
	$fp = stream_socket_client(PUSH_URL, $err, $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);*/
	
	stream_context_set_option($ctx, 'ssl', 'local_cert', 'certificates/apns-dist.pem');
	stream_context_set_option($ctx, 'ssl', 'passphrase', '');
	$fp = stream_socket_client('ssl://gateway.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
	
	$type = ($data['type']!='')?$data['type']:'0';
	
		if($data['message']=='add_job' || $data['message']=='process')
			//$sound = 'newrequest.mp3';
			$sound = 'default';
		else
			$sound = 'default';
			
		$body['aps'] = array(
		'alert' => stripslashes($final_message),
		'title' => APP_TITLE,
		'job_id' => $data['job_id'],
		'user_id' => $data['user_id'],
		'created_on'=> $data['created_on'],
		'type'=> $type,
		'sound' => $sound,
		"mutable-content"=> 1,
        "category"=> "rich-apns",
		"image-url"=> "https://pusher.com/static_logos/320x320.png",
		'badge' => intval($badge)
		
		);
		$body['att'] = array('id' => "https://pusher.com/static_logos/320x320.png");		
	$payload = json_encode($body);
					
	$msg = chr(0) . pack('n', 32) . pack('H*', str_replace(' ', '', $device_token)) . pack('n', strlen($payload)) . $payload;
	$result = fwrite($fp, $msg, strlen($msg));
	if(!$result)
	return "0";
	else
	return "1";
}

function sendToIphone($device_token,$data)
{
		$dbConn = mysqli_connect(DB_HOST, DB_NAME, DB_PASS, DB_NAME) or die("Connection failed: " . mysqli_connect_error());
		mysqli_query($dbConn,"SET NAMES 'utf8'"); 
		$noti_array = array("add_job_en"=>"You have a new request from ##UNAME##",
						"add_job_ar"=>"##UNAME## لديك طلب جديد من ",
						"process_en"=>"##UNAME## processed your job request",
						"process_ar"=>"قام بتنفيذ الطلب ##UNAME##",
						"confirm_en"=>"##UNAME## confirmed your job request",
						"confirm_ar"=>"قام بتأكيد الطلب ##UNAME##",
						"update_cost_en"=>"##UNAME## request to change final cost as ##FINALCOST## for ###REQUESTID##",
						"update_cost_ar"=>"طلب لتغيير التكلفة النهائية ##UNAME##",
						"complete_en"=>"##UNAME## completed job",
						"complete_ar"=>"قام بإكمال الطلب ##UNAME##",
						"feedback_en"=>"##UNAME## sent feedback on job",
						"feedback_ar"=>"ارسل تقييم ##UNAME##",
						"cancel_en"=>"##UNAME## cancelled job",
						"cancel_ar"=>"قام بإلغاء الطلب ##UNAME##",
						"autocancel_job_sp_en"=>"No other service provider available, Do you want to continue with same service provider?",
						"autocancel_job_sp_ar"=>"لايوجد مقدم خدمة اخر متوفر، هل تريد الاستمرار مع مقدم الخدمة الحالي",
						"autocancel_job_sps_en"	=>"Do you want to change the service provider for your job?",		
						"autocancel_job_sps_ar"	=>"هل تريد تغيير مقدم الخدمة لطلبك؟",
						"admin_cancelled_you_en"	=>"Admin cancelled your job ###REQUESTID##",		
						"admin_cancelled_you_ar"	=>"ألغى المشرف مهمتك ##REQUESTID###",	
						"admin_forwarded_you_en"	=>"Admin forwarded job to you",		
						"admin_forwarded_you_ar"	=>"المشرف تمت إعادة توجيه المهمة إليك",
						"admin_forwarded_en"	=>"Admin forwarded job to ##UNAME##",	
						"admin_forwarded_ar"	=>"##UNAME## تمت إعادة توجيه مهمة المشرف إلى",
						"tech_exp_en"=>"Your account is expired shortly",
						"tech_exp_ar"=>"الفترة المجانية قاربت على الانتهاء",
						"unread_notify_en"=>"You have ##BADGE## unread notifications",
						"unread_notify_ar"=>"إخطارا غير مقروء ##BADGE## لديك",
						"recharge_approved_en"=>"Your recharge request approved and balance is updated",
						"recharge_approved_ar"=>"تمت الموافقة على طلب إعادة شحن رصيدك",
						"recharge_rejected_en"=>"Your recharge request rejected",
						"recharge_rejected_ar"=>"تم رفض طلب إعادة الشحن",
						"add_balance_en"=>"New balance is deposited in your account",
						"add_balance_ar"=>"يتم إيداع رصيد جديد في حسابك",
						"deduct_balance_en"=>"Balance is deducted from your account",
						"deduct_balance_ar"=>"يتم خصم الرصيد من حسابك"
);
	if($data['message']!="unread_notify")
	mysqli_query($dbConn,"UPDATE tbl_users SET badge=badge+1 WHERE user_id='".$data['user_id']."'");
	$sql=mysqli_query($dbConn,"SELECT lang,badge FROM tbl_users  WHERE user_id='".$data['user_id']."'");
	$row=mysqli_fetch_assoc($sql);
	$u_lang = $row['lang'];
	$badge = $row['badge'];
	
	$sql=mysqli_query($dbConn,"SELECT name FROM tbl_users  WHERE user_id='".$data['user_id2']."'");
	$row=mysqli_fetch_assoc($sql);
	$name =  mysqli_real_escape_string($dbConn,$row['name']); 
	
	$sql = mysqli_query($dbConn,"SELECT request_id,final_cost from tbl_jobs where `job_id` = '".$data['job_id']."'");
	$row = mysqli_fetch_assoc($sql);
	$request_id =  $row['request_id']; 
	$final_cost =  $row['final_cost']; 
	
	if($u_lang == 'en')
		$message = $data['message']."_en";
	elseif($u_lang == 'ar')
		$message = $data['message']."_ar";
	else
		$message = $data['message']."_en";
	
	
	if(array_key_exists($message,$noti_array))
	{
		$content = $noti_array[$message];
		$content0=str_replace("##UNAME##",$name, $content);
		$content1=str_replace("##REQUESTID##",$request_id, $content0);
		$content2=str_replace("##FINALCOST##",$final_cost, $content1);
		$content3=str_replace("##BADGE##",$badge, $content2);
		$final_message = $content3;
	}
	else
	{
		$final_message = $data['message'];
	}
	$data['push_image'] = (($data['push_image']!='')?PHOTO_URL.'push/'.$data['push_image']:'');
	$passphrase = '';
	$ctx = stream_context_create();
	stream_context_set_option($ctx, 'ssl', 'local_cert', 'certificates/'.CERTIFICATE_NAME);
	stream_context_set_option($ctx, 'ssl', 'passphrase', PASSPHRASE);
	$fp = stream_socket_client(PUSH_URL, $err, $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
	
	
	
	$type = ($data['type']!='')?$data['type']:'0';
	
		if($data['message']=='add_job' || $data['message']=='process')
			//$sound = 'newrequest.mp3';
			$sound = 'default';
		else
			$sound = 'default';
			
		
		$body['aps'] = array(
		'alert' => stripslashes($final_message),
		'title' => APP_TITLE,
		'job_id' => $data['job_id'],
		'user_id' => $data['user_id'],
		'created_on'=> $data['created_on'],
		'type'=> $type,
		'sound' => $sound,
		"mutable-content"=> 1,
                "category"=> "rich-apns",
		"image-url"=> $data['push_image'],
		'badge' => intval($badge)
		
		);
		$body['att'] = array('id' => $data['push_image']);		
		
	$payload = json_encode($body);
					
	$msg = chr(0) . pack('n', 32) . pack('H*', str_replace(' ', '', $device_token)) . pack('n', strlen($payload)) . $payload;
	$result = fwrite($fp, $msg, strlen($msg));
	if(!$result)
	return "0";
	else
	return "1";
}

function sendToAndroid($regid,$data)
{
	$dbConn = mysqli_connect(DB_HOST, DB_NAME, DB_PASS, DB_NAME) or die("Connection failed: " . mysqli_connect_error());
	mysqli_query($dbConn,"SET NAMES 'utf8'"); 
	$noti_array = array("add_job_en"=>"You have a new request from ##UNAME##",
						"add_job_ar"=>"##UNAME## لديك طلب جديد من ",
						"process_en"=>"##UNAME## processed your job request",
						"process_ar"=>"قام بتنفيذ الطلب ##UNAME##",
						"confirm_en"=>"##UNAME## confirmed your job request",
						"confirm_ar"=>"قام بتأكيد الطلب ##UNAME##",
						"update_cost_en"=>"##UNAME## request to change final cost as ##FINALCOST## for ###REQUESTID##",
						"update_cost_ar"=>"طلب لتغيير التكلفة النهائية ##UNAME##",
						"complete_en"=>"##UNAME## completed job",
						"complete_ar"=>"قام بإكمال الطلب ##UNAME##",
						"feedback_en"=>"##UNAME## sent feedback on job",
						"feedback_ar"=>"ارسل تقييم ##UNAME##",
						"cancel_en"=>"##UNAME## cancelled job",
						"cancel_ar"=>"قام بإلغاء الطلب ##UNAME##",
						"autocancel_job_sp_en"=>"No other service provider available, Do you want to continue with same service provider?",
						"autocancel_job_sp_ar"=>"لايوجد مقدم خدمة اخر متوفر، هل تريد الاستمرار مع مقدم الخدمة الحالي",
						"autocancel_job_sps_en"	=>"Do you want to change the service provider for your job?",		
						"autocancel_job_sps_ar"	=>"هل تريد تغيير مقدم الخدمة لطلبك؟",
						"admin_cancelled_you_en"	=>"Admin cancelled your job ###REQUESTID##",		
						"admin_cancelled_you_ar"	=>"ألغى المشرف مهمتك ##REQUESTID###",	
						"admin_forwarded_you_en"	=>"Admin forwarded job to you",		
						"admin_forwarded_you_ar"	=>"المشرف تمت إعادة توجيه المهمة إليك",
						"admin_forwarded_en"	=>"Admin forwarded job to ##UNAME##",	
						"admin_forwarded_ar"	=>"##UNAME## تمت إعادة توجيه مهمة المشرف إلى",
						"tech_exp_en"=>"Your account is expired shortly",
						"tech_exp_ar"=>"الفترة المجانية قاربت على الانتهاء",
						"unread_notify_en"=>"You have ##BADGE## unread notifications",
						"unread_notify_ar"=>"إخطارا غير مقروء ##BADGE## لديك",
						"recharge_approved_en"=>"Your recharge request approved and balance is updated",
						"recharge_approved_ar"=>"تمت الموافقة على طلب إعادة شحن رصيدك",
						"recharge_rejected_en"=>"Your recharge request rejected",
						"recharge_rejected_ar"=>"تم رفض طلب إعادة الشحن",
						"add_balance_en"=>"New balance is deposited in your account",
						"add_balance_ar"=>"يتم إيداع رصيد جديد في حسابك",
						"deduct_balance_en"=>"Balance is deducted from your account",
						"deduct_balance_ar"=>"يتم خصم الرصيد من حسابك"
);
	if($data['message']!="unread_notify")
	mysqli_query($dbConn,"UPDATE tbl_users SET badge=badge+1 WHERE user_id='".$data['user_id']."'");
	$sql=mysqli_query($dbConn,"SELECT lang,badge,current_balance FROM tbl_users  WHERE user_id='".$data['user_id']."'");
	$row=mysqli_fetch_assoc($sql);
	$u_lang = $row['lang'];
	$badge = $row['badge'];
	$current_balance = $row['current_balance'];
	
	$sql=mysqli_query($dbConn,"SELECT name FROM tbl_users  WHERE user_id='".$data['user_id2']."'");
	$row=mysqli_fetch_assoc($sql);
	$name =  mysqli_real_escape_string($dbConn,$row['name']); 
	
	$sql = mysqli_query($dbConn,"SELECT request_id,final_cost from tbl_jobs where `job_id` = '".$data['job_id']."'");
	$row = mysqli_fetch_assoc($sql);
	$request_id =  $row['request_id'];
	$final_cost =  $row['final_cost']; 
	
	if($u_lang == 'en')
		$message = $data['message']."_en";
	elseif($u_lang == 'ar')
		$message = $data['message']."_ar";
	else
		$message = $data['message']."_en";

	if(array_key_exists($message,$noti_array))
	{
		$content = $noti_array[$message];
		$content0=str_replace("##UNAME##",$name, $content);
		$content1=str_replace("##REQUESTID##",$request_id, $content0);
		$content2=str_replace("##FINALCOST##",$final_cost, $content1);
		$content3=str_replace("##BADGE##",$badge, $content2);
		$final_message = $content3;
	}
	else
	{
		$final_message = $data['message'];
	}
	$data['push_image'] = (($data['push_image']!='')?PHOTO_URL.'push/'.$data['push_image']:'');
	$reg_id=$regid;
	$apiKey= ANDROID_API_KEY ;		
	$registrationIDs = array($reg_id);

	// Message to be sent
	//$message = "Push notification testing by hemal";
		
	// Set POST variables
	//$url = 'https://android.googleapis.com/gcm/send';
	$url = 'https://fcm.googleapis.com/fcm/send';
		
	$type = ($data['type']!='')?$data['type']:'0';	
	
	if($data['message']=='add_job' || $data['message']=='process')
		//$sound = 'newrequest.mp3';
		$sound = 'default';
	else
		$sound = 'default';
	
	$fields = array(
		'registration_ids'  => $registrationIDs,
		'data'              => array( 'message' => $final_message,
										'job_id' => $data['job_id'],
										'user_id' => $data['user_id'],
										'created_on'=> $data['created_on'],
										'type'=> $type,
										'image-url' => $data['push_image'],
										//"image-url"=> "https://pusher.com/static_logos/320x320.png",
										'sound' => $sound,
										'current_balance'=>$current_balance
									),
	);
		
	$headers = array( 
	'Authorization: key=' . $apiKey,
	'Content-Type: application/json'
	);
	// Open connection
	$ch = curl_init();
		
	// Set the url, number of POST vars, POST data
	curl_setopt( $ch, CURLOPT_URL, $url );
		
	curl_setopt( $ch, CURLOPT_REQUEST, true );
	curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $ch, CURLOPT_REQUESTFIELDS, json_encode( $fields ) );
		
									// Execute post
	$result = curl_exec($ch);
		
	// Close connection
	curl_close($ch);	
	return $result;
	$data = json_decode($result);
	return $data->success;
}
?>