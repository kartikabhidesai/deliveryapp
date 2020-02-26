<?php
	require_once('include/config.php');
	require_once('include/init.php');
	
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
	
	if($_POST['order_id']!='')
		$order_id = $_POST['order_id'];
	else
		$err = $lang["REQ_PARA"]."order_id";
	
	if($_POST['message_id'] >0)
		$where = "message_id >'". $_POST['message_id']."' AND ";
		
	if($err!='')
	{
		$jsonArray['Success']='0';
		$jsonArray['Message']=$err;
		show_output($jsonArray);
	}	
	$sql = $conn->get_record_set("SELECT * FROM `tbl_messages` WHERE $where (sender_id='$user_id' OR receiver_id='$user_id') AND order_id='$order_id' AND is_deleted='0' ORDER BY message_id DESC");
	$rows = $conn->records_to_array($sql);
	if(!empty($rows))
	{
		foreach($rows as $row1)
		{
			if($user_id == $row1['receiver_id'])
			$data->update("tbl_messages", array('read_time' => date('Y-m-d H:i:s')), array('message_id' => $row1['message_id']));
		
			if($row1['type']=='image')
			$row1['image']	= PHOTO_URL ."chat/".  $row1['image'];
			elseif($row1['type']=='audio')
			$row1['audio']	= PHOTO_URL ."chat/".  $row1['audio'];
			elseif($row1['type']=='video')
			$row1['video']	= PHOTO_URL ."chat/".  $row1['video'];
			$row1['display_time']= getTime($row1['send_time']);
			$d[]= $row1;
		}	
		$jsonArray['Success'] = '1';
		$jsonArray['Message'] = $lang["SUCCESSFUL"];
	}
	else
	{
		$jsonArray['Success']='0';
		$jsonArray['Message'] = $lang["NO_MESSAGE_FOUND"];
	}
	$jsonArray['api_calling_duration'] = "5"; // second
	$jsonArray['detail']= $d;
	show_output($jsonArray);
?>