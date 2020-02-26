<?php
include_once("../include/config.inc.php");
include_once("session.php");

$generalFunction = new generalfunction();
$converter  = new encryption();
$dbfunction = new dbfunctions();


/* Database connection end */


// storing  request (ie, get/post) global array to a variable  
$requestData= $_REQUEST;


$columns = array( 
// datatable column index  => database column name
	1 =>'field_title' ,
	2 =>'field_value' 
	
);
//mysqli_query($dbConn,"SET NAMES 'utf8'"); 
// getting total number records without any search
$sql = "SELECT id ";
$sql.=" FROM tbl_contents c WHERE is_deleted='0'";
$query=mysqli_query($dbConn,$sql) or die("content_grid.php: get users");
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.
$sql = "SELECT c.*";
$sql.=" FROM tbl_contents c WHERE is_deleted='0'";
if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$sql.=" AND ( field_title LIKE '%".$requestData['search']['value']."%' ";	
	$sql.=" OR field_value LIKE '%".$requestData['search']['value']."%' )";

}
$query=mysqli_query($dbConn,$sql) or die("content_grid.php: get users1");
$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
$query=mysqli_query($dbConn,$sql) or die("content_grid.php: get users2");

$data = array();
$i=1+$requestData['start'];
while( $row=mysqli_fetch_array($query) ) {  // preparing an array
	
	$encodedId = $converter->encode($row["id"]);
	$editurl = "add_content.php" . $urltoadd . ($urltoadd != "" ? "&id=" . $encodedId : "?id=" . $encodedId);
	$currStatus = ($row['is_active']=="1")?"Active":"Deactive";
	$nestedData=array(); 
	$nestedData[] = "&nbsp;&nbsp;&nbsp;<input type='checkbox'  class='deleteRow center' value='".$encodedId."'  />" ;
	$nestedData[] = "<span>".mysqli_real_escape_string($dbConn,$row["field_title"])."</span>";
	$nestedData[] = "<span>".stripcslashes(stripcslashes(mysqli_real_escape_string($dbConn,$row["field_value"])))."</span>";
	
	//if($_SESSION[SESSION_NAME . 'edit_content_prvl']=='1')
	$editButton = '<a href="'.$editurl.'" title="Edit" ><button class="btn btn-xs btn-success">Edit</button></a>&nbsp;';
	//else
	//$editButton = '';
	
	$nestedData[] = '<a class="ViewDialog btn btn-xs btn-success" data-id="'.$encodedId.'" href="#viewModel" data-toggle="modal">View</a>&nbsp;&nbsp;'.$editButton.$delButton;
	
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
