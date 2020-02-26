<?php
	require_once("include/config.php");
	require_once("include/init.php");
	include_once("../include/sendNotification.php");
	
	if($lang == 'en')
		require_once('lang/en.php');
	elseif($lang == 'ar')
		require_once('lang/ar.php');
	else
		require_once('lang/en.php');

	$conn=new Database;
	$data = new DataManipulator;
	$jsonArray = array();
	$d=array();	
	
	if($_POST['sender_id']!='')
		$postdata['sender_id'] = $_POST['sender_id']; 
	else
		$err = $lang["REQ_PARA"] .'sender_id';
	
	if($_POST['receiver_id']!='')
		$postdata['receiver_id'] = $_POST['receiver_id']; 
	else
		$err = $lang["REQ_PARA"] .'receiver_id';
	
	if($_POST['order_id']!='')
		$postdata['order_id'] = $_POST['order_id']; 
	else
		$err = $lang["REQ_PARA"] .'order_id';
	
	if($_POST['type']!='')
		$postdata['type'] = $_POST['type']; // text,image,audio
	//else
		//$err = $lang["REQ_PARA"] .'type';
 
	if($_POST['text']!='')
	{
	    $postdata['type'] = 'text';
		$postdata['text'] = mysqli_real_escape_string($dbConn,$_POST['text']);
	}
	elseif(!empty($_FILES['image']['name']))
	{
		$postdata['type'] = 'image';
		$path= '../uploads/chat/';
		$userf = $_FILES['image']['tmp_name'];
		$fname = $_FILES['image']['name'];
		$ext = pathinfo($fname, PATHINFO_EXTENSION);
		$img =  time()."_"."chat.".$ext;
		if(move_uploaded_file($userf,$path.$img))
		{
			$postdata['image'] =$img;
		}
		else
		{
			$err = $lang['IMAGE_SENDING_FAILED'];
		}
	}
	elseif(!empty($_FILES['audio']['name']))
	{
		$postdata['type'] = 'audio';
		$path= '../uploads/chat/';
		$userf = $_FILES['audio']['tmp_name'];
		$fname = $_FILES['audio']['name'];
		$ext = pathinfo($fname, PATHINFO_EXTENSION);
		$img =  time()."_"."chat.".$ext;
		if(move_uploaded_file($userf,$path.$img))
		{
			$postdata['audio'] =$img;
		}
		else
		{
			$err = $lang['AUDIO_SENDING_FAILED'];
		}
	}
	/*elseif(!empty($_FILES['video']['name']))
	{
		$postdata['type'] = 'video';
		$path= '../uploads/chat/';
		$userf = $_FILES['video']['tmp_name'];
		$fname = $_FILES['video']['name'];
		$ext = pathinfo($fname, PATHINFO_EXTENSION);
		$img =  time()."_"."chat.".$ext;
		if(move_uploaded_file($userf,$path.$img))
		{
			$postdata['video'] =$img;
		}
		else
		{
			$err = 'Video sending failed. Please try again';
		}
	}*/
	else
	{
		$err = $lang['INVALID_MESSAGE'];
	}	
	
	$postdata['send_time'] = date('Y-m-d H:i:s');
	if($err!='')
	{
		$jsonArray['Success']='0';
		$jsonArray['Message']=$err;
		show_output($jsonArray); 
	}
	
	$message_id = $data->insert( "tbl_messages" , $postdata );
	
	$sql = $conn->get_record_set("SELECT * FROM `tbl_messages` WHERE message_id='$message_id'");
	$rows = $conn->records_to_array($sql);
	if(!empty($rows))
	{
		foreach($rows as $row1)
		{
			//$data->update("tbl_messages", array('read_time' => date('Y-m-d H:i:s')), array('message_id' => $row1['message_id']));
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
	
	$usr = $data->select("tbl_users", "*", array('user_id' => $postdata['receiver_id']));
	$text = "You have new chat message.";
	$type ='chat';
	$arr['sender_id'] = $postdata['receiver_id'];
	$arr['receiver_id'] = $postdata['sender_id'];
	$arr['order_id'] = $postdata['order_id'];
	if($usr[0]['device_type']=='iphone')
	$result=sendToIphone($usr[0]['device_token'],$text,$type,$usr[0]['badge_count'],'1',$arr);
	else
	$result=sendToAndroid($usr[0]['device_token'],$text,$type,$usr[0]['badge_count'],'1',$arr);
	//print_r($result);exit;
	$jsonArray['Success']='1';
	$jsonArray['Message']=$lang["SUCCESSFUL"];
	$jsonArray['detail']= $d;
	show_output($jsonArray);
