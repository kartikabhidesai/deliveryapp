<?php
	require_once('include/config.php');
	require_once('include/init.php');
	require_once('include/thumb.php');
	
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
		$err = $lang["REQ_PARA"]."user_id";
		
	if($err!='')
	{
		$jsonArray['Success']='0';
		$jsonArray['Message']=$err;
		show_output($jsonArray);
	}	
	
	$sm = $data->select("tbl_fee_setting","item_value",array("item_key"=>'small'));
	$md = $data->select("tbl_fee_setting","item_value",array("item_key"=>'medium'));
	$lg = $data->select("tbl_fee_setting","item_value",array("item_key"=>'medium'));

	$d['small'] = $sm[0]['item_value'];
	$d['medium'] = $md[0]['item_value'];
	$d['large'] = $lg[0]['item_value'];
	$usr = $data->select("tbl_users","wallet_balance",array("user_id"=>$_POST['user_id']));
	$jsonArray['Success'] = '1';
	$jsonArray['Message'] = $lang["SUCCESSFUL"];
	$jsonArray['wallet_balance'] = $usr[0]['wallet_balance'];
	$jsonArray['deliveryFee']= $d;
	show_output($jsonArray);
?>