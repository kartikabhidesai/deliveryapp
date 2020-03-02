<?php
require_once('include/database.php');
require_once('include/manipulate.php');
require_once('include/table_vars.php');
require_once('include/thumb.php');
//header('Content-type:application/json');
$conn=new Database;
$data = new DataManipulator;

function show_output1($str){
	$outputjson['data'] = $str;
	echo json_encode($outputjson);
	exit;
}

function show_output($str){
	
	if(isset($_REQUEST['user_id']) && $_REQUEST['user_id']!="")
	$user_id = 	$_REQUEST['user_id'];
	elseif(isset($_REQUEST['customer_id']) && $_REQUEST['customer_id']!="")
	$user_id = 	$_REQUEST['customer_id'];	
	elseif(isset($_REQUEST['captain_id']) && $_REQUEST['captain_id']!="")	
	$user_id = 	$_REQUEST['captain_id'];
	else
	$user_id = '0';	
	if($user_id >0)	
	{
		$dbConn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME) or die("Connection failed: " . mysqli_connect_error());
		$sql = mysqli_query($dbConn,"SELECT captain_status FROM tbl_users WHERE user_id='".$user_id."'");
		$cpt = mysqli_fetch_array($sql);
		$str['captain_status'] = ($cpt['captain_status']=='Rejected')?'None':$cpt['captain_status'];
	}
	else
	{
		$str['captain_status'] = 'None';
	}
	$str['currency_sign'] = CURRENCY_SIGN;
	$str['stripe_currency'] = STRIPE_CURRENCY;
	$str['stripe_secret_key'] = STRIPE_SECRET_KEY;
	$str['stripe_publish_key'] = STRIPE_PUBLISH_KEY;
	$str['google_api_key'] = GOOGLE_API_KEY;
	$outputjson['data'] = $str;
	echo json_encode($outputjson);
	exit;
}

/** Authorize Application **/
$_SERVER['HTTP_X_REQUESTED_WITH'];
if( !isset($_SERVER['HTTP_APP_KEY']) || $_SERVER['HTTP_APP_KEY'] != APP_KEY){
//	$data1['Error'] = 'Unauthorized Access';
//	show_output($data1);
}
if($_SERVER['HTTP_DEVICE_ID']!='')
$device_id = $_SERVER['HTTP_DEVICE_ID'];
else
$device_id = "simulator";/// temp solution
//$err='Required parameter in header - device_id';

if($_SERVER['HTTP_DEVICE_TYPE']=='iphone' || $_SERVER['HTTP_DEVICE_TYPE']=='android')
$device_type = $_SERVER['HTTP_DEVICE_TYPE'];
else
$device_type = 'iphone';	
//$err='Required parameter in header - device_type';

if($_SERVER['HTTP_DEVICE_TOKEN']!='')
$device_token = $_SERVER['HTTP_DEVICE_TOKEN'];

if($_SERVER['HTTP_APP_VERSION']!='')
$app_version = $_SERVER['HTTP_APP_VERSION'];

if($_SERVER['HTTP_API_VERSION']!='')
$api_version = $_SERVER['HTTP_API_VERSION'];

if($_SERVER['HTTP_LANGUAGE']!='')
$language = $_SERVER['HTTP_LANGUAGE'];

if($_SERVER['HTTP_CURRENT_LAT']!='')
$current_lat = $_SERVER['HTTP_CURRENT_LAT'];

if($_SERVER['HTTP_CURRENT_LNG']!='')
$current_lng = $_SERVER['HTTP_CURRENT_LNG'];

/*if($api_version == '' && $device_type == 'ios')
{
	$jsonArray['Success']='-1';
	if($language == 'en')
		$jsonArray['Message']="Please update new version of app from AppStore";	
	elseif($language == 'ar')
		$jsonArray['Message']="???? ??????? ?????? ??????? ?? ??????";
	show_output($jsonArray);
}*/
if($api_version != '2' && $device_type == 'android')
{
	$jsonArray['Success']='-1';
	if($language == 'en')
		$jsonArray['Message']="Please update new version of app from PlayStore";	
	elseif($language == 'ar')
		$jsonArray['Message']="???? ??????? ?????? ??????? ?? ??????";
	//show_output($jsonArray);
}
	if(isset($_REQUEST['user_id']) && $_REQUEST['user_id']!="")	
	$user_id = 	$_REQUEST['user_id'];	
	elseif(isset($_REQUEST['customer_id']) && $_REQUEST['customer_id']!="")
	$user_id = 	$_REQUEST['customer_id'];		
	elseif(isset($_REQUEST['captain_id']) && $_REQUEST['captain_id']!="")		
	$user_id = 	$_REQUEST['captain_id'];	
	else	
	$user_id = '0';		
if($user_id!="0")
//if(false)
{
	//$user_id = $_REQUEST['user_id'];
	$sql = mysqli_query($dbConn,"SELECT * FROM tbl_users WHERE user_id='$user_id'");
	$chk = mysqli_fetch_array($sql);
	if($lat!='' && $lng!='')
	{
		mysqli_query($dbConn,"UPDATE tbl_users SET language='".$language."',captain_lat='".$current_lat."',captain_lng='".$current_lng."' WHERE user_id='$user_id'");
	}
	if($chk['is_deleted']=='1')
	{
		$jsonArray['Success']='-1';
		if($language == 'en')
			$jsonArray['Message']="Your account is removed";	
		elseif($language == 'ar')
			$jsonArray['Message']="??? ????? ?????";
		show_output($jsonArray);
	}
	else if($chk['is_active']=='0')
	{
		$jsonArray['Success']='-1';
		if($language == 'en')
			$jsonArray['Message']="Your account is deactivated. Please try after some times.";	
		elseif($language == 'ar')
			$jsonArray['Message']="????? ?? ??????. ?????? ??????? ?????";	
		show_output($jsonArray);
	}
	else if($chk['is_verified']=='1')
	{
		/*if($chk['type']=='2' && $chk['is_approved']=='0')
		{
			$jsonArray['Success']='-1';
			if($language == 'en')
				$jsonArray['Message']="Your account is not approved. Please contact admin.";	
			elseif($language == 'ar')
				$jsonArray['Message']="?? ??? ???????? ??? ?????. ?????? ??????? ????? ?? ???????? ??? ???? ?? ??? ????";
			show_output($jsonArray);
		}
		else*/ if(strlen($chk['device_token']) <50)
		{
			mysqli_query($dbConn,"UPADTE tbl_users SET device_id='".$device_id."',device_token='".$device_token."',device_type='".$device_type."' WHERE user_id='$user_id'");
		}
		/*else if($chk['device_token']!=$device_token)
		{
			$jsonArray['Success']='-1';
			$jsonArray['Message']="You have signed in on some other device and now automatically signed out from this device";	
			show_output($jsonArray);
		}*/
		else if($chk['device_id']!=$device_id)
		{
			$jsonArray['Success']='-1';
			if($language == 'ar')
				$jsonArray['Message']="?? ????? ?? ???? ???? ??? ??? ????? ?????? ??????? ??? ?????? ?? ??? ??????";
				else
				$jsonArray['Message']="You have signed in on some other device and now relogin from this device";	
			//show_output($jsonArray);
			mysqli_query($dbConn,"UPADTE tbl_users SET device_id='".$device_id."',device_token='".$device_token."',device_type='".$device_type."' WHERE user_id='$user_id'");
		}
	}
}