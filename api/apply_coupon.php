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

	if($_POST['user_id']!='')
	$user_id = $_POST['user_id'];
	else
	$err = $lang["REQ_PARA"] . "user_id";

	if($_POST['coupon_code']!='')
	$coupon_code = trim($_POST['coupon_code']);
	else
	$err = $lang["REQ_PARA"] . "coupon_code";

	if(is_numeric($_POST['order_amt']))
	$order_amt = trim($_POST['order_amt']);
	else
	$err = $lang["REQ_PARA"] . "order_amt";

	if($err!='')
	{
		$jsonArray['Success']='0';
		$jsonArray['Message']=$err;
		show_output($jsonArray);
	}	

	$jsonArray['delivery_fee'] =  $order_amt;
	$jsonArray['sub_total'] =  '0'; // invoice item(s) value
	$jsonArray['discount'] =  '0';
	$jsonArray['grand_total'] =  strval($jsonArray['delivery_fee'] - $jsonArray['discount']);

	$sql = $conn->get_record_set("SELECT *	FROM `tbl_coupons` WHERE coupon_code='".$coupon_code."' AND is_deleted='0'");
	$row = $conn->records_to_array($sql);	
	extract($row[0]);
	if(!empty($row[0]))
	{
		
		$sql1 = $conn->get_record_set("SELECT *	FROM `tbl_coupons_history` WHERE coupon_code='".$coupon_code."' AND user_id='".$user_id."'");
		$row1 = $conn->records_to_array($sql1);	
		if($is_active=='0')
		{
			$jsonArray['Success']='0';
			$jsonArray['Message']=$lang["INVALIDE_COUPON_CODE"];
		}	
		elseif($start_time > date('Y-m-d H:i:s'))
		{
			$jsonArray['Success']='0';
			$jsonArray['Message']=$lang["INVALIDE_COUPON_CODE"];
		}
		elseif($expiry_time < date('Y-m-d H:i:s'))
		{
			$jsonArray['Success']='0';
			$jsonArray['Message']=$lang["COUPON_CODE_EXPIRED"];
		}
		elseif($min_order_amt > $order_amt)
		{
			$jsonArray['Success']='0';
			$jsonArray['Message']=$lang["MINIMUM_ORDER_AMOUNT_MUST_BE"].$min_order_amt;
		}
		elseif($is_multi_use=='0' && !empty($row1))
		{
			$jsonArray['Success']='0';
			$jsonArray['Message']=$lang["ALREADY_USED_COUPON"];
		}
		else
		{
			if($discount_type=='flat')
			$jsonArray['discount'] =  $discount_value;
			else
			$jsonArray['discount'] =  strval(($jsonArray['delivery_fee'] * $discount_value)/100);
			$jsonArray['grand_total'] =  strval($jsonArray['delivery_fee'] - $jsonArray['discount']);
			
			$jsonArray['Success']='1';
			if($discount_type=='flat')
			$jsonArray['Message']= $lang["COUPON_APPLIED"].CURRENCY_SIGN. round($discount_value) .$lang["COUPON_DISCOUNT_OFF"];
			else
			$jsonArray['Message']= $lang["COUPON_APPLIED"]. round($discount_value) .'%'.$lang["COUPON_DISCOUNT_OFF"];	
			$jsonArray['discount_value']=$discount_value; // NO LONGER USED
			$jsonArray['discount_type']=$discount_type; // flat/percent // NO LONGER USED
		}	
	}
	else
	{
		$jsonArray['Success']='0';
		$jsonArray['Message']=$lang["INVALIDE_COUPON_CODE"];
	}
	show_output($jsonArray);
?>