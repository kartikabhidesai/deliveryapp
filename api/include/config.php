<?php

date_default_timezone_set('Asia/Riyadh');
error_reporting(0);
$dev = false;
// $_SERVER['HTTP_X_REQUESTED_WITH'];
// $dev = isset($_SERVER['HTTP_DEV'])?$_SERVER['HTTP_DEV']:false;

define("BASE_URL_API", "http://localhost/deliveryapp/api/");
define("BASE_URL", "http://localhost/deliveryapp/");
define("PHOTO_URL", BASE_URL."uploads/");
define("NOTIFICATION_ICON", PHOTO_URL."icons/");
/** DATABASE CONFIGURATION **/
define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PASS", "");
define("DB_NAME", "deliveryapp");


define("SMTP_HOST", "smtp.gmail.com");
define("SMTP_PORT", "465");
define("SMTP_UNAME", "officetestg106@gmail.com");
define("SMTP_PASS", "officetest@1234");
define("APP_TITLE", "Test");
define("FROM_EMAIL", "officetestg106@gmail.com");

/*define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PASS", "");
define("DB_NAME", "here_app");*/
$dbConn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME) or die("Connection failed: " . mysqli_connect_error());
mysqli_query($dbConn,"SET NAMES 'utf8'"); 		

define("APP_TITLE","HERE");
define("RIYADH_ONLY",false);
define("CONTACT_EMAIL","info@hereapp.com");
define("FROM_EMAIL","info@hereapp.com");
define("ADMIN_EMAIL","anas.kadival@gmail.com");
define("CURRENCY_SIGN","$");
define("STRIPE_CURRENCY","usd");
define("STRIPE_SECRET_KEY","sk_test_OmHns3GqAL2D0ERZcy481lpY00jD1V80yB"); // Keyur Account
define("STRIPE_PUBLISH_KEY","pk_test_qAatcJFi3oeP1pX2Lq5JjLfh00wzReJyMb"); // Keyur Account
define("APP_KEY", "5772fce1b91e966206b81bfda5f3e944"); // here app developed by anas
define("GOOGLE_API_KEY","AIzaSyDP05tJPZ0sgMIMmqJyC4SKUThpdUi3-ZM");
define("STORE_API_RADIUS","5000");
define("CAPTAIN_ORDER_DISTANCE","30"); // KM - captain will see this distance orders
define("LOCATION_UPDATE_DURATION","30"); // In Sec
define("PHONE_CODE","+966");


define("ANDROID_API_KEY","");
define('PASSPHRASE', ''); 
define('PATH_CERTIFICATES','../certificates/'); //Relative path to the folder for the push notifications's certificate
if(false)
{	
	define('CERTIFICATE_NAME','apns-dev.pem');	//Name of the push notifications's certificate
	define('PUSH_URL','ssl://gateway.sandbox.push.apple.com:2195');	//Name of the push notifications's certificate
}
else
{
	define('CERTIFICATE_NAME','apns-dist.pem');	//Name of the push notifications's certificate
	define('PUSH_URL','ssl://gateway.push.apple.com:2195');	//Name of the push notifications's certificate
}	 
 
set_time_limit(0);
ini_set('max_execution_time',0);


function send_mail_old($to,$subject,$message,$attachment)
{
		$url = BASE_URL."api/smtpmail/smtpgmail.php";
		$data = array("to"=>$to,"subject"=>$subject,"message"=>$message,"attachment"=>$attachment);
		$timeout=3600;
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $url );
		curl_setopt( $ch, CURLOPT_REQUEST, true);
		curl_setopt( $ch, CURLOPT_REQUESTFIELDS, $data);
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );    # required for https urls
		$content = curl_exec( $ch );
		//return $content;
		return $url;
}
function test_sms($phone_number,$message_data)
{
	$result = sms_bulksms($phone_number,$message_data);
	return $result;
}
function send_sms($phone_number,$message_data)
{
	$result = sms_shamelsms($phone_number,$message_data);
	//return $result;
}
function resend_sms($phone_number,$message_data)
{
	$result = sms_shamelsms($phone_number,$message_data);
}
function send_sms_admin($phone_number,$message_data)
{
	return $result = sms_twilio($phone_number,$message_data);
}
function sms_bulksms($phone_number,$message_data)
{
		$url = BASE_URL."api/include/send_sms.php";
		$data = array("phone_number"=>$phone_number,"message_data"=>$message_data);
		$timeout=3600;
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $url );
		curl_setopt( $ch, CURLOPT_POST, true);
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );    # required for https urls
		return $content = curl_exec( $ch );
}
function sms_shamelsms($mobile,$message)
{
	$mobile = ((strpos($mobile,"0",0)===false)?$mobile:ltrim($mobile, '0'));
	$mobile = ((strlen($mobile)>=12)? $mobile : "966".$mobile);
	//$url = "http://www.shamelsms.net/api/httpSms.aspx?username=$username&password=$password&mobile=$mobile&message=$message&sender=$senderName&unicodetype=U";
	$url = 'http://www.shamelsms.net/api/httpSms.aspx?' . http_build_query(array(
	    'username' => 'hoopoe',
	    'password' => '123456789',
	 	'mobile' => $mobile,
	 	'message' => $message,
	 	'sender' => 'here-app',
	 	'unicodetype' => 'U'
	));
        
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL,$url);
	$content = curl_exec($ch);
	return $content;

}
function sms_sendpk($phone_number,$message_data)
{
	$phone_number = ((strpos($phone_number,"0",0)===false)?$phone_number:ltrim($phone_number, '0'));
	$phone = ((strlen($phone_number)>10)? $phone_number : "996".$phone_number);
	$uname = "966559422292";
	$pass   = "123789";
	$sender = "TSTALA";
	$uri = 'https://sendpk.com/api/sms.php?username='.$uname.'&password='.$pass.'&sender='.$sender.'&mobile='.$phone.'&message='.$message_data;
    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_URL, $uri );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, false ); // don't echo
	return $result = json_decode(curl_exec($ch)); 
}
function getaddress($latitude,$longitude,$fullAddress=false)
{
	$count = 1;
	$latlng = $latitude.",".$longitude;
	
	while($count<=3)
	{
		// if($count==1)
		// $baseetahAPIkey="";
	 //    elseif($count==2)
		$baseetahAPIkey="&key=".GOOGLE_API_KEY;
		// elseif($count==3)
		// $baseetahAPIkey="&key=AIzaSyCN53Bo22fnKQZUDRuR9h3Zbkx9KO0TVhY";
		// $count++;
		
//		$url = "http://maps.googleapis.com/maps/api/geocode/json?latlng='.$latlng.$baseetahAPIkey1;
		$url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=".$latlng.$baseetahAPIkey;
		//$coordinates = file_get_contents($url);
	        $ch = curl_init();
	        curl_setopt($ch, CURLOPT_URL, $url);
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
	        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	        $coordinates = curl_exec($ch);
	        curl_close($ch);
		$coordinates = json_decode($coordinates,true);
		if($coordinates["status"] == 'OK')
		{
			foreach($coordinates['results'] as $coordinate)
			{
				foreach($coordinate['address_components'] as $coordinater)
				{
					foreach($coordinater['types'] as $types)
					{
						if($types == "locality")
						{
							$city = $coordinater['long_name'];
						}
						
					}
					if($city=='')
					{
						foreach($coordinater['types'] as $types)
						{
							if($types == "administrative_area_level_2")
							{
								$city = $coordinater['long_name'];
							}
							
						}
					}
					if($city=='')
					{
						foreach($coordinater['types'] as $types)
						{
							if($types == "administrative_area_level_1")
							{
								$city = $coordinater['long_name'];
							}
							
						}
					}
					if($fullAddress)
					return $address = $coordinate['formatted_address'];
				}
			}
			return $city;
		}
		
	}
	return $city;
}

function getlatlong($address)
{
	$apiKey = GOOGLE_API_KEY;
	$url = "https://maps.google.com/maps/api/geocode/json?address=".urlencode($address)."&key=$apiKey";

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);    
	$responseJson = curl_exec($ch);
	curl_close($ch);

	$response = json_decode($responseJson);
	$returnArray = array();
	if ($response->status == 'OK') {
	    $returnArray['latitude'] = $response->results[0]->geometry->location->lat;
	    $returnArray['longitude'] = $response->results[0]->geometry->location->lng;
	} 
	else {
	    $returnArray['latitude'] = '';
	    $returnArray['longitude'] = '';
	}   
	return $returnArray;
}

function convert_en($string) {
	$arabic_eastern = array('٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩');
	$arabic_western = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
	return str_replace($arabic_eastern, $arabic_western, $string);
}

function getTime($ptime)
{
			$etime = time() - strtotime($ptime);

			if ($etime < 1)
			{
				return '0 sec';
			}

			$a = array( 365 * 24 * 60 * 60  =>  'yr',
						 30 * 24 * 60 * 60  =>  'mon',
							  24 * 60 * 60  =>  'day',
								   60 * 60  =>  'hr',
										60  =>  'min',
										 1  =>  'sec'
						);
			$a_plural = array( 'yr'   => 'yrs',
							   'mon'  => 'mons',
							   'day'    => 'days',
							   'hr'   => 'hrs',
							   'min' => 'mins',
							   'sec' => 'secs'
						);

			foreach ($a as $secs => $str)
			{
				$d = $etime / $secs;
				if ($d >= 1)
				{
					$r = round($d);
					return $r .' '. ($r > 1 ? $a_plural[$str] : $str).' ago';
				}
			}
}
//echo calDistance(32.9697, -96.80322, 29.46786, -98.53506, "M") . " Miles<br>";
//echo calDistance(32.9697, -96.80322, 29.46786, -98.53506, "K") . " Kilometers<br>";
//echo calDistance(32.9697, -96.80322, 29.46786, -98.53506, "N") . " Nautical Miles<br>";
function calDistance($lat1, $lon1, $lat2, $lon2, $unit) {
  if (($lat1 == $lat2) && ($lon1 == $lon2)) {
    return 0;
  }
  else {
    $theta = $lon1 - $lon2;
    $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
    $dist = acos($dist);
    $dist = rad2deg($dist);
    $miles = $dist * 60 * 1.1515;
    $unit = strtoupper($unit);

    if ($unit == "K") {
      return round(($miles * 1.609344));
    } else if ($unit == "N") {
      return ($miles * 0.8684);
    } else {
      return $miles;
    }
  }
}
?>