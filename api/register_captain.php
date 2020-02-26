<?php
require_once("include/config.php");
require_once("include/init.php");
require_once("include/thumb.php");

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
	
	if($_POST['captain_name']!='')
		$postdata['captain_name'] = $_POST['captain_name'];
	else
		$err = $lang["REQ_PARA"].$lang["NAME"];
	
	if($_POST['captain_city']!='')
		$postdata['captain_city'] = $_POST['captain_city'];
	else
		$err = $lang["REQ_PARA"]."captain_city";

	if(strtolower($postdata['captain_city']) != 'Riyadh' && RIYADH_ONLY)
	{
		$jsonArray['Success']= '0';
		$jsonArray['Message']= $lang["SERVING_ONLY_FOR_RIYADH"]; 
		show_output($jsonArray);
	}

	$postdata['captain_status'] = 'Pending'; 
	//$postdata['captain_status'] = 'Approved'; // Temporary
	
	if(!empty($_FILES['captain_pic']['name']))
	{
		$path= '../uploads/captain/';
		$userf = $_FILES['captain_pic']['tmp_name'];
		$str = explode(".",$_FILES['captain_pic']['name']);
		$l=count($str);
		$img =  time()."_"."captain_pic.".$str[$l-1];
		if(move_uploaded_file($userf,$path.$img))
		{
			$postdata['captain_pic'] =$img;
			createthumb($path . $img, $path.'150x150/'.$img,150,150);	
		}
	}
	else
	{
		$err = $lang["REQ_PARA"]."captain_pic";
	}
	
	if(!empty($_FILES['captain_id_pic']['name']))
	{
		$path= '../uploads/captain/';
		$userf = $_FILES['captain_id_pic']['tmp_name'];
		$str = explode(".",$_FILES['captain_id_pic']['name']);
		$l=count($str);
		$img =  time()."_"."captain_id_pic.".$str[$l-1];
		if(move_uploaded_file($userf,$path.$img))
		{
			$postdata['captain_id_pic'] =$img;
			createthumb($path . $img, $path.'150x150/'.$img,150,150);	
		}
	}
	else
	{
		$err = $lang["REQ_PARA"]."captain_id_pic";
	}
	
	if(!empty($_FILES['captain_license']['name']))
	{
		$path= '../uploads/captain/';
		$userf = $_FILES['captain_license']['tmp_name'];
		$str = explode(".",$_FILES['captain_license']['name']);
		$l=count($str);
		$img =  time()."_"."captain_license.".$str[$l-1];
		if(move_uploaded_file($userf,$path.$img))
		{
			$postdata['captain_license'] =$img;
			createthumb($path . $img, $path.'150x150/'.$img,150,150);	
		}
	}
	else
	{
		$err = $lang["REQ_PARA"]."captain_license";
	}
	
	if($err!='')
	{
		$jsonArray['Success']='0';
		$jsonArray['Message']=$err;
		show_output($jsonArray); 
	}
	
		$sql = $conn->get_record_set("SELECT * FROM `tbl_users` WHERE user_id='$user_id' AND is_deleted='0'");
		$rows = $conn->records_to_array($sql);
		if(!empty($rows))
		{
			$postdata['captain_requested_on'] = date('Y-m-d');
			$user_id = $data->update( "tbl_users" , $postdata, array("user_id" => $user_id) );
			$jsonArray['Success']='1';
			$jsonArray['Message']=$lang["SUCCESSFUL_CAPTAIN_REGISTRATION"];
		}
		else
		{
			$jsonArray['Success']='0';
			$jsonArray['Message']=$lang["UNAUTHORIZED_ACCESSS"];
		}
		show_output($jsonArray); 
