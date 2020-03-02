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
	
	$sql = $conn->get_record_set("SELECT tbl_item.*, tbl_item_size_price.item_size, tbl_item_size_price.item_price ,tbl_variant.variant_name FROM tbl_item INNER JOIN tbl_item_size_price ON tbl_item_size_price.item_id=tbl_item.id INNER JOIN tbl_variant ON tbl_variant.id=tbl_item_size_price.item_size  WHERE tbl_item.is_deleted='0' GROUP BY tbl_item_size_price.item_id");
	$rows = $conn->records_to_array($sql);

	$jsonArray['Success'] = '1';
	$jsonArray['Message'] = $lang["SUCCESSFUL"];
	$jsonArray['item']= $rows;
	show_output1($jsonArray);
?>