<?php
include("../include/config.inc.php");
include("session.php");

$generalFunction = new generalfunction();
$converter  = new encryption();
$dbfunction = new dbfunctions();


/* Database connection end */


// storing  request (ie, get/post) global array to a variable  
$requestData= $_REQUEST;


$columns = array( 
// datatable column index  => database column name
	
	1=> 'not_type_title',
	2 => 'not_text',
	3 =>'created_on'
	
);

// getting total number records without any search
$sql = "SELECT not_id ";
$sql.=" FROM tbl_notification n INNER JOIN tbl_notification_types t ON n.not_type_id=t.not_type_id";
$query=mysqli_query($dbConn, $sql) or die("not_master_grid.php: get employees");
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


$sql = "SELECT n.*,t.not_type_title ";
$sql.=" FROM tbl_notification n INNER JOIN tbl_notification_types t ON n.not_type_id=t.not_type_id WHERE 1=1 ";
if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$sql.=" AND ( not_text LIKE '".$requestData['search']['value']."%' ";    
	$sql.=" OR not_type_title LIKE '".$requestData['search']['value']."%' )";
	//$sql.=" OR created_on LIKE '".$requestData['search']['value']."%' )";
	//$sql.=" OR phone LIKE '".$requestData['search']['value']."%' )";
}
$query=mysqli_query($dbConn, $sql) or die("not_master_grid.php: get employees");
$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */	
$query=mysqli_query($dbConn, $sql) or die("not_master_grid.php: get employees");

$data = array();
$i=1+$requestData['start'];
while( $row=mysqli_fetch_array($query) ) {  // preparing an array
	$message = "Are you sure to delete record ?";
	//$currStatus = ($row['is_active']=="1")?"Active":"Deactive";
	$editurl = "add_not_master.php" . $urltoadd . ($urltoadd != "" ? "&not_id=" . $converter->encode($row["not_id"]) : "?not_id=" . $converter->encode($row["not_id"]));
	$encodedId = $converter->encode($row["not_id"]);
	$nestedData=array(); 
	$sql1 = "SELECT count(*) As totalUsers FROM tbl_notification_history WHERE not_id=".$row["not_id"];
	$query1=mysqli_query($dbConn, $sql1) or die("not_master_grid.php: get employees");
	$row1=mysqli_fetch_array($query1);
	$nestedData[] = "&nbsp;&nbsp;&nbsp;<input type='checkbox'  class='deleteRow center' value='".$converter->encode($row["not_id"])."'  />" ;
	$nestedData[] = $row["not_type_title"];
	$nestedData[] = $row["not_text"];
	$nestedData[] = datFormat($row["created_on"]);
	$nestedData[] = '<span style="margin-left:5px">'.$row1["totalUsers"]." | <a href='not_cust_list.php?nid=".$encodedId."'>Click Here</a></span>";
	
	$data[] = $nestedData;
	$i++;
}



$json_data = array(
			"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
			"recordsTotal"    => intval( $totalData ),  // total number of records
			"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
			"data"            => $data   // total data array
			);

echo json_encode($json_data);  // send data as json format

?>
