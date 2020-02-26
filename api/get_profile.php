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

	$obj=new Database;
	$data = new DataManipulator;
	$jsonArray = array();
	
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
			$jsonArray['Success']='1';
			$jsonArray['Message']=$lang["SUCCESSFUL"];
			$sql = $conn->get_record_set("SELECT * FROM `tbl_users` WHERE user_id='$user_id' AND is_deleted ='0'");
			$rows = $conn->records_to_array($sql);
			foreach($rows as $row1)
			{
					$row1['password']='';
					if((PHOTO_URL ."profile/".$row1['profile_pic']) && $row1['profile_pic']!='')
					$row1['profile_pic_150'] = ($row1['profile_pic']!='')?PHOTO_URL ."profile/".$row1['profile_pic']:'';
					else
					$row1['profile_pic_150'] = '';
					$row1['profile_pic'] = ($row1['profile_pic']!='')?PHOTO_URL ."profile/".$row1['profile_pic']:'';
					$row1['total_points']= strval($row1['total_customer_points'] + $row1['total_captain_points']); 
					$jsonArray['is_verified']= $row1['is_verified'];
					if($row1['full_name']=='')
					$jsonArray['completed_profile']= '0';
					else
					$jsonArray['completed_profile']= '1';
					$jsonArray['detail']= $row1;
					show_output($jsonArray);
			}			
		$jsonArray['Success'] = '0';
		$jsonArray['Message'] = $lang["INVALID_USER"];	
		show_output($jsonArray);
?>