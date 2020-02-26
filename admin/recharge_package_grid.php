<?php
include_once("../include/config.inc.php");
include_once("session.php");

$generalFunction = new generalfunction();
$converter  = new encryption();
$dbfunction = new dbfunctions();


//mysqli_select_db(DB_NAME);
/*mysqli_query ("set character_set_client='utf8'"); 
mysqli_query ("set character_set_results='utf8'"); 
mysqli_query ("set collation_connection='utf8_general_ci'"); */

/* Database connection end */


// storing  request (ie, get/post) global array to a variable  
$requestData= $_REQUEST;


$columns = array( 
// datatable column index  => database column name
	1 =>'display_order',
	//2 =>'package_name',
	//3 =>'package_name_ar',
	//4 => 'extra_discount_perc',
	2 => 'recharge_amount'
	//3=> 'prdQty',
	//4 =>'prdUnitPrice'
	
);
mysqli_query($dbConn,"SET NAMES 'utf8'"); 
// getting total number records without any search
$sql = "SELECT package_id ";
$sql.=" FROM tbl_recharge_packages WHERE is_deleted='0'";
$query=mysqli_query($dbConn,$sql) or die("recharge_package_grid.php: get products");
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


$sql = "SELECT * ";
$sql.=" FROM tbl_recharge_packages WHERE is_deleted='0'";
if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$sql.=" AND ( recharge_amount LIKE '%".$requestData['search']['value']."%')";
	//$sql.=" OR package_name_ar LIKE '%".$requestData['search']['value']."%'";
	//$sql.=" OR recharge_amount LIKE '%".$requestData['search']['value']."%'";
	//$sql.=" OR extra_discount_perc LIKE '%".$requestData['search']['value']."%' )";    
	
	//$sql.=" OR prdUnitPrice LIKE '".$requestData['search']['value']."%' )";
	
}
$query=mysqli_query($dbConn,$sql) or die("recharge_package_grid.php: get products");
$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */	
$query=mysqli_query($dbConn,$sql) or die("recharge_package_grid.php: get products");

$data = array();
$i=1+$requestData['start'];
while( $row=mysqli_fetch_array($query) ) {  // preparing an array
	
	$currStatus = ($row['is_active']=="1")?"Active":"Deactive";
	$editurl = "add_recharge_package.php" . $urltoadd . ($urltoadd != "" ? "&package_id=" . $converter->encode($row["package_id"]) : "?package_id=" . $converter->encode($row["package_id"]));
	$encodedId = $converter->encode($row["package_id"]);
	
	//$query1 = mysqli_query($dbConn,"select package_name from tbl_recharge_packages where package_id='".$row["extra_discount_perc"]."'");
	//$row1 =mysqli_fetch_array($query1);
	/*$query2 = mysqli_query($dbConn,"select qtyUnit from tbl_qty_units where qtyUnitId='".$row['qtyUnitId']."'");
	$row2 =mysqli_fetch_array($query2);*/
	$nestedData=array(); 
	
	$nestedData[] = "&nbsp;&nbsp;&nbsp;<input type='checkbox'  class='deleteRow center' value='".$converter->encode($row["package_id"])."'  />" ;
	$nestedData[] = "<span style='margin-left:40px;'>".$row["display_order"]."</span>";
	//$nestedData[] = $row["package_name"];  
	//$nestedData[] = $row["package_name_ar"];
	$nestedData[] = $row["extra_discount_perc"];	
	$nestedData[] = $row["recharge_amount"];
	
	
	if($_SESSION[SESSION_NAME . 'edit_recharge_package_prvl']=='1')
	$editButton = '<a href="'.$editurl.'" title="Edit" ><button class="btn btn-xs btn-success">Edit</button></a>&nbsp;';
	else
	$editButton = '';
	
	if($_SESSION[SESSION_NAME . 'delete_recharge_package_prvl']=='1')
	$delButton = '<a data-id="'.$encodedId.'" class="btnDelete btn btn-xs btn-danger" >Delete</a>';
	else
	$delButton = '';
	
	if($_SESSION[SESSION_NAME . 'status_recharge_package_prvl']=='1')
	
	{
	$nestedData[] = "<div class='btn-group'>
											  <button type='button' class='btn btn-primary dropdown-toggle btn-xs' data-toggle='dropdown'>
												<i class='fa1 fa-cog1'></i> $currStatus <span class='caret'></span>
											  </button>
											  <ul class='dropdown-menu primary' role='menu'>
												<li><a onclick=updateStatus('".$encodedId."','active') >Active</a></li>
												<li><a onclick=updateStatus('".$encodedId."','deactive') >Deactive</a></li>
											  </ul>
											</div>";
	}
	$nestedData[] = '<a class="ViewDialog btn btn-xs btn-success" data-id="'.$encodedId.'" href="#viewModel" data-toggle="modal">View</a>&nbsp;'.$editButton.$delButton;
	
	
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
