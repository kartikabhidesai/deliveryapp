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
	$jsonArray = array();
	
	$sql = $conn->get_record_set("SELECT variant_name FROM tbl_variant WHERE is_deleted='0' ");
	$rows = $conn->records_to_array($sql);

	$jsonArray['Success'] = '1';
	$jsonArray['Message'] = $lang["SUCCESSFUL"];
	$jsonArray['variant']= $rows;
	show_output1($jsonArray);
?>