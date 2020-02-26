<?php
//include_once('config.inc.php');
//include_once('include/init.php'); 
define("ANDROID_API_KEY1","AAAA2n9tKSY:APA91bENiuVats_mpgYFnZjg3mtfGTwurVIRVe7E2MpUi_2wIiEVIFOXHrqqWuS2G9gIyhHPDYxLWd06DoZPhSgjw3h32fpy_7p0C7ROy85fmpFUdLm0YIbDtzJ8T9STR7k89xGBgdls");
//define("ANDROID_API_KEY","AIzaSyBUHgGsPB8EttmuClLbc1AIv6we1lleVXk"); // Sender ID : 938440730918
define("ANDROID_API_KEY","AAAA78xptOU:APA91bHnemMm1Ol4T5fMY9sHQx1sN-E9XAWU7KJcEPAO8DzOwka6Aa4dYSpuXRRaNucReCRMLLbXGPddk9CZ9p-PRKgyBOEIlAHxkUrqOS7UFJa0MUZaekhb-9IKDzk9jALP9qVtpXNy");
define("APP_TITLE","HERE");


function sendToIphone($deviceToken,$message,$type,$badge,$enableNotification=1,$arr=[])
{	
	$final_message = $message;
	$arr['sender_id'] = isset($arr['sender_id'])?$arr['sender_id']:'';
	$arr['receiver_id'] = isset($arr['receiver_id'])?$arr['receiver_id']:'';
	$arr['order_id'] = isset($arr['order_id'])?$arr['order_id']:'';
	$passphrase = '';
	$ctx = stream_context_create();
	
	stream_context_set_option($ctx, 'ssl', 'local_cert', '../include/apns-cert-here.pem');
	stream_context_set_option($ctx, 'ssl', 'passphrase', '');
	$fp = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
	
	if (!$fp){
		//exit("Failed to connect: $err $errstr" . PHP_EOL);
	}
   // var_dump($fp); 
	//$type = ($data['type']!='')?$data['type']:'0';
		$sound = 'default';
		if($data['image']) // image notification
		{	
			$body['aps'] = array(
			'alert' => stripslashes($final_message),
			'title' => APP_TITLE,
			'type'=> $type,
			'sender_id'=> $arr['sender_id'],
			'receiver_id'=> $arr['receiver_id'],
			'order_id'=> $arr['order_id'],
			'sound' => $sound,
			"mutable-content"=> 1,
	        "category"=> "rich-apns",
			"image-url"=> $data['image'],
			'badge' => intval($badge)
			);
			$body['att'] = array('id' => $data['image']);	
		}
		else
		{
			$body['aps'] = array(
			'alert' => stripslashes($final_message),
			'title' => APP_TITLE,
			'type'=> $type,
			'sender_id'=> $arr['sender_id'],
			'receiver_id'=> $arr['receiver_id'],
			'order_id'=> $arr['order_id'],
			'sound' => $sound,
			"mutable-content"=> 1,
	        "category"=> "rich-apns",
			"image-url"=> "",
			'badge' => intval($badge)
			);
			$body['att'] = array('id' => "");	
		}
 	//	$deviceToken;
	$payload = json_encode($body);
			
	$msg = chr(0) . pack('n', 32) . pack('H*', str_replace(' ', '', $deviceToken)) . pack('n', strlen($payload)) . $payload;
	$result = fwrite($fp, $msg, strlen($msg));
	fclose($fp);
	if(!$result)
	return "0";
	else
	return $result;
}

function sendToAndroid($deviceToken,$message,$type,$badge,$enableNotification=1,$arr=[])
{
	$arr['sender_id'] = isset($arr['sender_id'])?$arr['sender_id']:'';
	$arr['receiver_id'] = isset($arr['receiver_id'])?$arr['receiver_id']:'';
	$arr['order_id'] = isset($arr['order_id'])?$arr['order_id']:'';
	/*$apiKey= ANDROID_API_KEY1 ;		
	$registrationIDs = array($deviceToken);
	// Message to be sent
	//$message = "Push notification testing by hemal";
	// Set POST variables
	$url = 'https://android.googleapis.com/gcm/send';
	$fields = array(
		'registration_ids'  => $registrationIDs,
		'data'              => array( "title" => APP_TITLE, "message" => $message,"type" => $type),
	);
		
	$headers = array( 
	'Authorization: key=' . $apiKey,
	'Content-Type: application/json'
	);
	// Open connection
	$ch = curl_init();
	// Set the url, number of POST vars, POST data
	curl_setopt( $ch, CURLOPT_URL, $url );
	curl_setopt( $ch, CURLOPT_POST, true );
	curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $fields ) );
	$result = curl_exec($ch);
	// Close connection
	curl_close($ch);	
	$data = json_decode($result);*/
	$apiKey= ANDROID_API_KEY ;		
	$registrationIDs = array($deviceToken);
	// Message to be sent
	//$message = "Push notification testing by hemal";
	// Set POST variables
	$url = 'https://android.googleapis.com/gcm/send';
	$fields = array(
		'registration_ids'  => $registrationIDs,
		'data'              => array( "title" => APP_TITLE, "message" => $message,"type" => $type, "sender_id" => $arr['sender_id'], 
		"receiver_id" => $arr['receiver_id'], "order_id" => $arr['order_id']),
	);
		
	$headers = array( 
	'Authorization: key=' . $apiKey,
	'Content-Type: application/json'
	);
	// Open connection
	$ch = curl_init();
	// Set the url, number of POST vars, POST data
	curl_setopt( $ch, CURLOPT_URL, $url );
	curl_setopt( $ch, CURLOPT_POST, true );
	curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $fields ) );
	return $result = curl_exec($ch);
	// Close connection
	curl_close($ch);	
	$data = json_decode($result);
	
	return $data->success;
}
?>