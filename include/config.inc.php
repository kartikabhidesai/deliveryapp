<?php
	error_reporting(E_ALL);
	@session_start();
	ob_start();
	date_default_timezone_set('Asia/Riyadh');
	define('SESSION_NAME','_here_');
	define("BASE_URL","http://localhost/deliveryapp/");
	define('SITE_PATH','http://localhost/deliveryapp/admin/');
        define('SITE_NAME','HERE');
	define("APP_TITLE","HERE");
	define("CURRENCY_SIGN", '$');
	define('ADMIN_EMAIL','info@hereapp.com');
	define('FROM_EMAIL','info@hereapp.com');
	
	/*define("DB_HOST", "localhost");
	define("DB_USER", "root");
	define("DB_PASS", "");
	define("DB_NAME", "here_app");*/
	
	define("DB_HOST", "localhost");
        define("DB_USER", "root");
        define("DB_PASS", "");
        define("DB_NAME", "deliveryapp");
	
	$dbConn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	mysqli_query($dbConn,"SET NAMES 'utf8'");
		
	include("settings.php");
    //define('ANDROID_API_KEY',"AAAAtgUwQCk:APA91bELqHlFfyM_rL9wiN5JnmygBE920tW5V-9AzXEFqstYCSM3ULpmyB44sOAKbUNTJKCflpHkgRwjy7hdGl_RbPnJi6FmTatsOhLSNrWOzJhZdroXMf7bPiYcukRlftD6sfDtb1ap");
	function __autoload($class_name)
    {
		include dirname(__FILE__) . "/" . $class_name . '.php';
	}
	include("language/english.php");
	$dbObj = new dbfunctions();
	$myprocess = new inputfilter();
	
	function getLatLong($address)
	{
		//$address = $address.",".$subarea.",".$Surname.",".$region;
		$coordinates = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($address) . '&sensor=true');
		$coordinates = json_decode($coordinates);
		
		$lat1= $coordinates->results[0]->geometry->location->lat;
		$long1= $coordinates->results[0]->geometry->location->lng;
		
		return array($lat1,$long1);
	}
	
	function datFormat($date, $format = false)
	{
		if($date!="" && $date!="0000-00-00" && $date!="0000-00-00 00:00:00")
		{
			$date = date_create($date);
			if ($format == true)
			{
				return date_format($date, "d/m/Y h:i A");
			}
			else
			{
				return date_format($date, "d/m/Y");
			}
		}
		return ;
	}
	function datDefault($date, $format = false)
	{
		if($date!="" && $date!="0000-00-00" && $date!="0000-00-00 00:00:00")
		{
			list($d,$m,$y) = explode("/",$date);
			return $y.'-'.$m.'-'.$d;
		}
		return ;
	}
 ?>