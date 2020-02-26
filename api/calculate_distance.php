<?php
	require_once('include/config.php');
	require_once('include/init.php');
	//require_once('include/thumb.php');
	
	if($language == 'en')
		require_once('lang/en.php');
	elseif($language == 'ar')
		require_once('lang/ar.php');
	else
		require_once('lang/en.php');

	$conn=new Database;
	$data = new DataManipulator;
	$jsonArray = array();
	$d=array();	
	
	if($_POST['store_lat']!='')
		$store_lat = $_POST['store_lat'];
	else
		$err = $lang["REQ_PARA"] .'store_lat';
	
	if($_POST['store_lng']!='')
		$store_lng = $_POST['store_lng'];
	else
		$err = $lang["REQ_PARA"] .'store_lng';
	
	if($_POST['address_lat']!='')
		$add_lat = $_POST['address_lat'];
	else
		$err = $lang["REQ_PARA"] .'address_lat';
	
	if($_POST['address_lng']!='')
		$add_lng = $_POST['address_lng'];
	else
		$err = $lang["REQ_PARA"] .'address_lng';
		
	if($err!='')
	{
		$jsonArray['Success']='0';
		$jsonArray['Message']=$err;
		show_output($jsonArray);
	}	
	
	$jsonArray['Success'] = '1';
	$jsonArray['Message'] = $lang["SUCCESSFUL"];
	$jsonArray['distance']= calDistance($add_lat,$add_lng,$store_lat,$store_lng, "K");  // OLD PARAMETER
	$jsonArray['store_distance']= calDistance($add_lat,$add_lng,$store_lat,$store_lng, "K"); // NEW PARAMETER
	//$jsonArray['store_address'] = getaddress($store_lat,$store_lng,true);
	$jsonArray['store_address'] = '';
	show_output($jsonArray);
?>