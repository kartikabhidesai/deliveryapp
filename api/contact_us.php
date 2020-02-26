<?php
require_once('include/config.php');
require_once('include/init.php');

if($language == 'en')
	require_once('lang/en.php');
elseif($language == 'ar')
	require_once('lang/ar.php');
else
	require_once('lang/en.php');
	
$obj=new Database;
$data = new DataManipulator;
$jsonArray = array();

if($err!='')
{
	$jsonArray['Success']='0';
	$jsonArray['Message']=$err;
	show_output($jsonArray);
}	
$sql = $conn->get_record_set("SELECT * FROM `tbl_settings`");
$rows1 = $conn->records_to_array($sql);
foreach($rows1 as $row1)
{
	$d[$row1['item_key']] = $row1['item_value'];
}
$d['contact_firebase_userid'] = 'Z23F58RnkGSfw7826v5J4S0XLKx1';
/*$d['address'] = '2345 Yellow St. Riyadh, KSA';
$d['address_lat'] = '24.8772492';
$d['address_lng'] = '46.8684931';
$d['email_id'] = 'info@hereapp.com';
$d['twitter_link'] = 'https://twitter.com';
$d['instagram_link'] = 'https://instagram.com';
$d['contact_number'] = PHONE_CODE . '530816725';*/
$jsonArray['Success'] = '1';
$jsonArray['Message'] = $lang["SUCCESSFUL"];
$jsonArray['detail']=$d;
show_output($jsonArray);
?>