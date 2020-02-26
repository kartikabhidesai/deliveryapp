<?php
include_once("../include/config.inc.php");
include_once("session.php");
include_once("../include/sendNotification.php");

$generalFunction = new generalfunction();
$converter  = new encryption();
$dbfunction = new dbfunctions();
$dbfunction1 = new dbfunctions();


/* Database connection end */
$data_ids = $_REQUEST['data_ids'];
$dbfunction->SimpleSelectQuery("SELECT tec.user_id,tec.current_balance,rec.* FROM tbl_user tec
INNER JOIN tbl_recharge_history rec ON tec.user_id=rec.user_id
WHERE rec.recharge_id='".$converter->decode($data_ids)."' AND status='1'");
$bal = $dbfunction->getFetchArray();
extract($bal);
if($dbfunction->getNumRows()>0)
{	

	if($_REQUEST['action']=='approved')
	{
			/*$dbfunction->SimpleSelectQuery("SELECT tec.user_id,tec.current_balance,rec.* FROM tbl_user tec
			INNER JOIN tbl_recharge_history rec ON tec.user_id=rec.user_id
			WHERE rec.recharge_id='".$converter->decode($data_ids)."'");
			$bal = $dbfunction->getFetchArray();
			extract($bal);*/
			$postbal['type'] = "recharge";
			$postbal['user_id'] = $user_id;
			$postbal['old_balance'] = $current_balance;
			$postbal['amount'] = $recharge_amount;
			$postbal['new_balance'] = ($current_balance+$recharge_amount);
			$postbal['recharge_id'] = $converter->decode($data_ids);
			$postbal['created_on'] = date('Y-m-d H:i:s');
			$dbfunction->InsertQuery("tbl_balance_statements", $postbal);
			$updatebal['current_balance'] = ($current_balance+$recharge_amount);
			$dbfunction->UpdateQuery("tbl_user", $updatebal, "user_id='" .$user_id . "'");
			
					// Notification to add balance
					//$postdata['job_id'] = $row['job_id'];
					$postdata['user_id'] = $user_id;
					//$postdata['user_id2'] = $row['customer_id'];
					$postdata['message'] = "recharge_approved";
					$postdata['created_on'] = time();
					$postdata['updated_on'] = time();
					$dbfunction->InsertQuery("tbl_notification" , $postdata );
					$sql1 = mysqli_query($dbConn,"SELECT notification_enabled,device_token,device_type from tbl_user where `user_id` = '".$user_id."'");
					$row1 = mysqli_fetch_assoc($sql1);
					extract($row1);
					if($notification_enabled == '1')
					{
						$postdata['type'] = "recharge_approved";
						if($device_type == 'ios')
							sendToIphoneAPI($device_token,$postdata);
						else
							sendToAndroidAPI($device_token,$postdata);
					}
					
					
		$field ='status=2';
	}
	elseif($_REQUEST['action']=='rejected')
	{
			/*$dbfunction->SimpleSelectQuery("SELECT tec.user_id,tec.current_balance,rec.* FROM tbl_user tec
			INNER JOIN tbl_recharge_history rec ON tec.user_id=rec.user_id
			WHERE rec.recharge_id='".$converter->decode($data_ids)."'");
			$bal = $dbfunction->getFetchArray();
			extract($bal);*/
			
					// Notification to add balance
					//$postdata['job_id'] = $row['job_id'];
					$postdata['user_id'] = $user_id;
					//$postdata['user_id2'] = $row['customer_id'];
					$postdata['message'] = "recharge_rejected";
					$postdata['created_on'] = time();
					$postdata['updated_on'] = time();
					$dbfunction->InsertQuery("tbl_notification" , $postdata );
					$sql1 = mysqli_query($dbConn,"SELECT notification_enabled,device_token,device_type from tbl_user where `user_id` = '".$user_id."'");
					$row1 = mysqli_fetch_assoc($sql1);
					extract($row1);
					if($notification_enabled == '1')
					{
						$postdata['type'] = "recharge_rejected";
						if($device_type == 'ios')
							sendToIphoneAPI($device_token,$postdata);
						else
							sendToAndroidAPI($device_token,$postdata);
					}
					

		$field = 'status=3';
	}


			$sql = "UPDATE tbl_recharge_history SET $field ";
			$sql.=" WHERE recharge_id = '".$converter->decode($data_ids)."'";
			$query=mysqli_query($dbConn, $sql) or die("cust_action.php: delete employees");
}
?>