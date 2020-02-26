<?php
require_once("include/config.php");
require_once("include/init.php");

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

	$postdata['updated_on']=date('Y-m-d H:i:s');

	if($_POST['address_id']!='')
		$address_id = $_POST['address_id'];
	else
		$err = $lang["REQ_PARA"]."address_id" ;

	if($_POST['address_title']!='')
		$postdata['address_title'] = $_POST['address_title'];
	else
		$err = $lang["REQ_PARA"].$lang["NAME"];

	if($_POST['address_street']!='')
		$postdata['address_street'] = $_POST['address_street'];
	else
		$err = $lang["REQ_PARA"].$lang["ADDRESS"];
	
	if($_POST['address_city']!='')
		$postdata['address_city'] = $_POST['address_city'];
	else
		$err = $lang["REQ_PARA"].$lang["CITY"];

	if($_POST['address_state']!='')
		$postdata['address_state'] = $_POST['address_state'];
	else
		$err = $lang["REQ_PARA"].$lang["STATE"];
	
	if($_POST['address_zip']!='')
		$postdata['address_zip'] = $_POST['address_zip'];
	else
		$err = $lang["REQ_PARA"].$lang["PINCODE"];

	if($_POST['address_lat']!='')
		$postdata['address_lat'] = $_POST['address_lat'];
	else
		$err = $lang["REQ_PARA"].$lang["LATITUDE"];
	
	if($_POST['address_lng']!='')
		$postdata['address_lng'] = $_POST['address_lng'];
	else
		$err = $lang["REQ_PARA"].$lang["LONGITUDE"];
	
	$data->update( "tbl_user_addresses" , $postdata,array("address_id"=>$address_id) );
	$jsonArray['Success']='1';
	$jsonArray['Message']=$lang["AddressUpdate"];		
	show_output($jsonArray); 
