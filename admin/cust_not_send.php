<?php
include("../include/config.inc.php");
include('../include/sendNotification.php');
include("session.php");

$generalFunction = new generalfunction();
$converter  = new encryption();
$dbfunction = new dbfunctions();


/* Database connection end */
$data_ids = $_REQUEST['data_ids'];
$data_id_array = explode(",", $data_ids); 
if($_REQUEST['action']=='notification')
{
	$currtime = date('Y-m-d H:i:s');
	$not_type_id = $_REQUEST['not_type_id'];
	$not_text = mysqli_real_escape_string($dbConn, $_REQUEST['not_text']);
	$sql = "INSERT INTO tbl_notification (not_text,not_type_id,created_on) VALUES ('$not_text','$not_type_id','$currtime')";
	$query=mysqli_query($dbConn, $sql) or die("cust_not_send.php: notification");
	$lastid =  mysqli_insert_id($dbConn); 
 
	$sql2 = "select * from tbl_notification_types where not_type_id='$not_type_id'";
	$query2=mysqli_query($dbConn, $sql2) or die("cust_not_send.php: notification");
	$row2 =mysqli_fetch_array($query2);
	$not_type_title = $row2['not_type_title'];
	
	if(!empty($data_id_array)) {
		foreach($data_id_array as $id) {
			//$sql1 = "select * from tbl_users where enableNotification='1' and user_id='".$converter->decode($id)."'";
			$sql1 = "select * from tbl_users where user_id='".$converter->decode($id)."'";
			$query1=mysqli_query($dbConn, $sql1) or die("cust_not_send.php: notification");
			if(mysqli_num_rows($query1)>0)
			{
				$row1 =mysqli_fetch_array($query1);
				$enableNotification = $row1['enableNotification'];
				$device_type = $row1['device_type'];
				$badge = $row1['badge']+ 1;
				$message = $not_type_title.":\n ".$not_text;
				//$type = '3';
				$type = '';
				if($device_type == 'iphone' || $device_type == 'ipad')
					$value = sendToIphone($row1['device_token'],$message,$type,$badge,$enableNotification);
				else if($device_type == 'androidphone' || $device_type == 'android')
					$value = sendToAndroid($row1['device_token'],$message,$type,$badge,$enableNotification);
				$sql = "INSERT INTO tbl_notification_history (not_id,user_id,send_time,created_on) VALUES ('$lastid','".$converter->decode($id)."','$currtime','$currtime')";
				$query=mysqli_query($dbConn, $sql) or die("cust_not_send.php: notification");
				//return $value ;	
			}
		}
	}
} 
	
?>