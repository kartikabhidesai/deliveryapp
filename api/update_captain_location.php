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
	
	if($_POST['captain_id']!='')
		$captain_id = $_POST['captain_id'];
	else
		$err = $lang["REQ_PARA"].$lang["CAPTAIN_ID"];

	if($_POST['captain_lat']!='')
		$postdata['captain_lat'] = trim($_POST['captain_lat']);
	else
		$err = $lang["REQ_PARA"]."captain_lat";
	
	if($_POST['captain_lng']!='')
		$postdata['captain_lng'] = trim($_POST['captain_lng']);
	else
		$err = $lang["REQ_PARA"]."captain_lng";
	
	if($err!='')
	{
		$jsonArray['Success']='0';
		$jsonArray['Message']=$err;
		show_output($jsonArray);
	}	

	$postdata['updated_on']=date('Y-m-d H:i:s');
	$sql = $conn->get_record_set("SELECT * FROM `tbl_users` WHERE `user_id`='$captain_id' AND is_deleted='0'");
	$row = $conn->records_to_array($sql);
	if(!empty($row))
	{
		$data->update( "tbl_users" , $postdata,array("user_id"=>$captain_id) );
		$jsonArray['Success']='1';
		$jsonArray['Message']=$lang["SUCCESSFUL"];		
	}
	else
	{
		$jsonArray['Success'] = '0';
		$jsonArray['Message'] = $lang["NOT_FOUND"];	
	}
	$jsonArray['api_calling_duration'] = LOCATION_UPDATE_DURATION; // In Second
	show_output($jsonArray);
?>