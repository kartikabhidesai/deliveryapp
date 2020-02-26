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
	
	if($_POST['activation_code']!='')
		$activation_code = $_POST['activation_code'];
	else
		$err = $lang["REQ_PARA"].$lang["ACTIVATION_CODE"];
	
	if($_POST['user_id']!='')
		$user_id = $_POST['user_id'];
	else
		$user_id = '0';
	//else
		///$err = $lang["REQ_PARA"].$lang["USER_ID"];
	
	if($err!='')
	{
		$jsonArray['Success']='0';
		$jsonArray['Message']=$err;
		show_output($jsonArray);
	}	
 	
	$activation_code_converted = convert_en($activation_code);
	if($user_id >0)
		$sql = $conn->get_record_set("Select u.* from tbl_users u where u.activation_code='".$activation_code_converted ."' and user_id='$user_id'");
	else
		$sql = $conn->get_record_set("Select u.* from tbl_users u where u.activation_code='".$activation_code_converted ."' order by 
			user_id desc limit 1");

	$row = $conn->records_to_array($sql);
	if(!empty($row[0]))
	{
		$user_id = $row[0]['user_id'];
		$sql1 = $conn->get_record_set("Select u.* from tbl_users u where user_id!='$user_id' and phone='".$row[0]['temp_phone']."'
		and is_deleted='0' and is_verified='1'");
		$row1 = $conn->records_to_array($sql1);
		if(empty($row1[0]))
		{
			$postdata['phone'] = $row[0]['temp_phone'];
			$postdata['temp_phone'] = "";
			$postdata['activation_code'] = '';
			$postdata['activation_date'] = date("Y-m-d H:i:s");
			$postdata['is_verified'] = '1';
			$data->update( "tbl_users" , $postdata , array("user_id"=>$user_id ));
			$jsonArray['Success']='1';
			$jsonArray['Message']=$lang["CODE_VER"];	
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
					$row1['total_points']= $row1['total_customer_points'] + $row1['total_captain_points']; 
					$jsonArray['is_verified']= $row1['is_verified'];
					if($row1['full_name']=='')
					$jsonArray['completed_profile']= '0';
					else
					$jsonArray['completed_profile']= '1';
					$jsonArray['detail']= $row1;
			}			
		}
		else
		{
			$jsonArray['Success']='0';
			$jsonArray['Message']=$lang["PHONE_EXIST_ALREADY"];	
		}
	}
	else
	{
		$jsonArray['Success']='0';
		$jsonArray['Message']=$lang["CODE_INVALID"];	
	}
	show_output($jsonArray);	
?>