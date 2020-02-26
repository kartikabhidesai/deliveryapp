<?php
include("../include/config.inc.php");
include("session.php");
//unset($_SESSION["cust_page"]);
$generalFunction = new generalfunction();
$converter  = new encryption();
$dbfunction = new dbfunctions();


/* Database connection end */


// storing  request (ie, get/post) global array to a variable  
$requestData= $_REQUEST;


$columns = array( 
// datatable column index  => database column name
	//1 =>'districtCode',
	0 =>'user_id' ,
	1 =>'full_name',
	//3=> 'email_id',
	//5 =>'phone',
	//6 =>'total_points'
);
if($_SESSION['nid']!="")
{
	$nid = $converter->decode($_SESSION['nid']);
	$where = " AND not_id='".$nid."'";
}
else
{
	$cid = $converter->decode($_SESSION['cid']);
	$where = " AND h.user_id='".$cid."'";
}
// getting total number records without any search
$sql = "SELECT autoId ";
$sql.=" FROM tbl_users c INNER JOIN tbl_notification_history h ON c.user_id=h.user_id WHERE is_deleted='0' $where";
$query=mysqli_query($dbConn, $sql) or die("not_cust_grid.php: get employees");
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


$sql = "SELECT c.*,h.send_time,h.readTime,h.not_id ";
$sql.=" FROM tbl_users c 
INNER JOIN tbl_notification_history h ON c.user_id=h.user_id
WHERE c.is_deleted='0' $where";
if( !empty($requestData['search']['value']) ) {   
	$sql.=" AND ( user_id LIKE '".$requestData['search']['value']."%' "; 
	$sql.=" OR user_id LIKE '".$requestData['search']['value']."%' ";	
	$sql.=" OR full_name LIKE '".$requestData['search']['value']."%' ";
	$sql.=" OR email LIKE '".$requestData['search']['value']."%' ";
	$sql.=" OR phone LIKE '".$requestData['search']['value']."%' ";
	$sql.=" OR total_points LIKE '".$requestData['search']['value']."%' )";
}
$query=mysqli_query($dbConn, $sql) or die("not_cust_grid.php: get employees");
$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */	
$query=mysqli_query($dbConn, $sql) or die("not_cust_grid.php: get employees");

$data = array();
$i=1+$requestData['start'];
while( $row=mysqli_fetch_array($query) ) {  // preparing an array
	
	$encodedId = $converter->encode($row["user_id"]);
	$editurl = "add_cust.php" . $urltoadd . ($urltoadd != "" ? "&user_id=" . $encodedId : "?user_id=" . $encodedId);
	$currStatus = ($row['is_active']=="1")?"Active":"Deactive";

	$query1=mysqli_query($dbConn, "select * from tbl_notification where not_id='".$row["not_id"]."'") ;
	$row1 = mysqli_fetch_assoc($query1);
	
	$nestedData=array(); 
	//$nestedData[] = "&nbsp;&nbsp;&nbsp;<input type='checkbox'  class='deleteRow center' value='".$converter->encode($row["user_id"])."'  />" ;
	
	$nestedData[] = $row["user_id"];
	$nestedData[] = $row["full_name"];
	$nestedData[] = $row1["not_text"];
	$nestedData[] = ($row["send_time"]!="")?date("d/m/Y H:i",$row["send_time"]):"Pending";
	$nestedData[] = ($row["readTime"]!="")?date("d/m/Y H:i",$row["readTime"]):"Unread";
	
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
