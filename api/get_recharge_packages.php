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
	
	if($_POST['user_id']!='')
	$user_id = $_POST['user_id'];
	else
	$err = $lang["REQ_PARA"].$lang["USER_ID"];	
	
	if($err!='')
	{
		$jsonArray['Success']='0';
		$jsonArray['Message']=$err;
		show_output($jsonArray); 
	}
	
	$jsonArray['Success'] = '1';
	$jsonArray['Message'] = $lang["SUCCESSFUL"];
	$usr = $data->select("tbl_users","wallet_balance" , array("user_id"=>$_POST['user_id']));
	$jsonArray['wallet_balance'] = $usr[0]['wallet_balance'];
	
	$d = array();
	$sql1 = $conn->get_record_set("SELECT package_id,recharge_amount, extra_discount_perc, total_amount FROM tbl_recharge_packages p
	WHERE p.is_active='1' AND p.is_deleted='0' ORDER BY recharge_amount");
	$rows1 = $conn->records_to_array($sql1);
	foreach($rows1 as $row1)
	{
		$d[] = $row1;
	}
	//$jsonArray['currency_sign'] = CURRENCY_SIGN;
	$jsonArray['detail']= $d;
	$d='';	
	show_output($jsonArray);
?>	