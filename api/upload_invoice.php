<?php
require_once("include/config.php");
require_once("include/init.php");
require_once('include/thumb.php');
include_once("../include/sendNotification.php");

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

	if($_POST['captain_id']!='')
		$captain_id = $_POST['captain_id'];
	else
		$err = $lang["REQ_PARA"]."captain_id";

	if($_POST['order_id']!='')
		$order_id = $_POST['order_id'];
	else
		$err = $lang["REQ_PARA"]."order_id";
	
	if($_POST['sub_total']>0)
		$postdata['sub_total'] = $_POST['sub_total'];
	else
		$err = "Please enter subtotal.";
	
	if(!empty($_FILES['invoice_photo']))
	{
		$path= '../uploads/invoice/';
		$userf = $_FILES['invoice_photo']['tmp_name'];
		$fname = $_FILES['invoice_photo']['name'];
		$ext = pathinfo($fname, PATHINFO_EXTENSION);
		$img =  time()."_"."invoice.".$ext;
		if(move_uploaded_file($userf,$path.$img))
		{
			$postdata['invoice_photo'] =$img;
			createthumb($path . $img, $path.'150x150/'.$img,150,150);		
		}
		else
		{
			$err = $lang["TRY_WITH_SMALL_PHOTO"];
		}
	}
	else
	{	
		$err = $lang["REQ_PARA"]."invoice_photo";
	}
		
	//$postdata['order_status'] = 'On the way';
	if($err!='')
	{
		$jsonArray['Success']='0';
		$jsonArray['Message']=$err;
		show_output($jsonArray); 
	}
	
		$sql = $conn->get_record_set("SELECT o.* ,u.device_type, u.device_token,u.badge_count FROM `tbl_orders` o INNER JOIN `tbl_users` u ON o.customer_id=u.user_id WHERE order_id='$order_id' AND captain_id='$captain_id'");
		$rows = $conn->records_to_array($sql);
		if(!empty($rows))
		{
			if(is_numeric($_POST['sub_total']))
			$postdata['grand_total'] =  strval(($postdata['sub_total']+$rows[0]['delivery_fee']) - $rows[0]['discount']);
			$data->update("tbl_orders" , $postdata,array("order_id"=>$order_id) );
	
			$text = "Order invoice uploaded. You can check it. Order #".$rows[0]["invoice_no"];
			$text_ar = " تم تحميل فاتورة الطلب. يمكنك التحقق من ذلك. طلب #".$rows[0]["invoice_no"];
			$text = ($language=='ar')?$text_ar:$text;
			$type ='accept_order';
			$arr['order_id'] = $order_id;
			if($rows[0]['device_type']=='iphone')
			$result=sendToIphone($rows[0]['device_token'],$text,$type,$rows[0]['badge_count'],'1',$arr);
			else
			$result=sendToAndroid($rows[0]['device_token'],$text,$type,$rows[0]['badge_count'],'1',$arr);
		
			$d['delivery_fee'] = $rows[0]['delivery_fee'];
			$d['sub_total'] = $postdata['sub_total'];
			$d['discount'] = $rows[0]['discount'];
			$d['grand_total'] = $postdata['grand_total'];
			$jsonArray['Success']='1';
			$jsonArray['Message']=$lang["SUCCESSFUL"];	
				$jsonArray['detail']=$d;	
		}
		else
		{
			$jsonArray['Success']='0';
			$jsonArray['Message']=$lang["SOMETHING_GOING_WRONG"];
		}
		show_output($jsonArray); 
