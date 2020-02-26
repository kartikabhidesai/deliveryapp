<?php
include("../include/config.inc.php");
include("session.php");

$generalFunction = new generalfunction();
$converter  = new encryption();
$dbfunction = new dbfunctions();


/* Database connection end */
$data_ids = $_REQUEST['data_ids'];
$data_id_array = explode(",", $data_ids); 
if($_REQUEST['action']=='add_money')
{
	if(!empty($data_id_array)) {
		foreach($data_id_array as $id) {
			
				//$sql = "INSERT INTO tbl_notification_history (not_id,user_id,created_on) VALUES ('$lastid','".$converter->decode($id)."','$currtime')";
				$sql = "UPDATE tbl_users SET `wallet_balance` = `wallet_balance`+'".$_REQUEST['amountText']."' WHERE user_id = '".$converter->decode($id)."'";
				$query=mysqli_query($dbConn, $sql) or die("cust_not_send.php: notification");

		}
	}
}
else if($_REQUEST['action']=='reduse_money')
{
	if(!empty($data_id_array)) {
		foreach($data_id_array as $id) {
			
				//$sql = "INSERT INTO tbl_notification_history (not_id,user_id,created_on) VALUES ('$lastid','".$converter->decode($id)."','$currtime')";
				$sql = "UPDATE tbl_users SET `wallet_balance` = `wallet_balance`-'".$_REQUEST['amountText']."' WHERE user_id = '".$converter->decode($id)."'";
				$query=mysqli_query($dbConn, $sql) or die("cust_not_send.php: notification");

		}
	}
}
	
?>