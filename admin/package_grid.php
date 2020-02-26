<?php
include("../include/config.inc.php");
include("session.php");
$dbConn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME) ;
$generalFunction = new generalfunction();
$converter  = new encryption();
$dbfunction = new dbfunctions();
$requestData= $_REQUEST;
$columns = array( 
	1 =>'package_id' ,
	2 =>'package_name',
	3 =>'recharge_amount',
	4 =>'extra_discount_perc',
	5 =>'total_amount',
);
$sql = "SELECT c.package_id ";
$sql.=" FROM tbl_recharge_packages c WHERE c.is_deleted='0'";
$query=mysqli_query($dbConn, $sql) or die("package_grid.php: get employees1");
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData; 


$sql = "SELECT c.*";
$sql.=" FROM tbl_recharge_packages c WHERE c.is_deleted='0'";
if( !empty($requestData['search']['value']) ) {   
	$sql.=" AND ( package_id LIKE '".$requestData['search']['value']."%' ";	
	$sql.=" OR package_name LIKE '".$requestData['search']['value']."%' ";
	$sql.=" OR recharge_amount LIKE '".$requestData['search']['value']."%' ";
	$sql.=" OR extra_discount_perc LIKE '".$requestData['search']['value']."%' ";
	$sql.=" OR total_amount LIKE '".$requestData['search']['value']."%' )";
}
$query=mysqli_query($dbConn, $sql) or die("package_grid.php: get employees2");
$totalFiltered = mysqli_num_rows($query); 
$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
$query=mysqli_query($dbConn, $sql) or die("package_grid.php: get employees3");

$data = array();
$i=1+$requestData['start'];
while( $row=mysqli_fetch_array($query) ) {  // preparing an array
	
	$encodedId = $converter->encode($row["package_id"]);
	$editurl = "add_package.php" . $urltoadd . ($urltoadd != "" ? "&package_id=" . $encodedId : "?package_id=" . $encodedId);
	$currStatus = ($row['is_active']=="1")?"Active":"Deactive";
	$nestedData=array(); 
	$nestedData[] = "&nbsp;&nbsp;&nbsp;<input type='checkbox'  class='deleteRow center' value='".$encodedId."'  />" ;
	$nestedData[] = '<span style="margin-left:10px">'.$row["package_id"].'</span>';
	$nestedData[] = '<span style="margin-left:10px">'.$row["package_name"].'</span>';
	$nestedData[] = '<span style="margin-left:05px">'.$row["recharge_amount"].'</span>';
	$nestedData[] = '<span style="margin-left:10px">%'.$row["extra_discount_perc"].'</span>';
	$nestedData[] = '<span style="margin-left:10px">'.$row["total_amount"].'</span>';
	$nestedData[] = "<div class='btn-group'>
											  <button type='button' class='btn btn-primary dropdown-toggle btn-xs' data-toggle='dropdown'>
												<i class='fa1 fa-cog1'></i> $currStatus <span class='caret'></span>
											  </button>
											  <ul class='dropdown-menu primary' role='menu'>
												<li><a onclick=updateStatus('".$encodedId."','active') >Active</a></li>
												<li><a onclick=updateStatus('".$encodedId."','deactive') >Deactive</a></li>
											  </ul>
											</div>";
	$nestedData[] = '<a class="ViewDialog btn btn-xs btn-success" data-id="'.$encodedId.'" href="#viewModel" data-toggle="modal">View</a>
	<a href="'.$editurl.'" title="Edit" ><button class="btn btn-xs btn-success">Edit</button></a>';	
	$data[] = $nestedData;
	$i++;
}
$json_data = array(
			"draw"            => intval( $requestData['draw'] ),   
			"recordsTotal"    => intval( $totalData ),  
			"recordsFiltered" => intval( $totalFiltered ),
			"data"            => $data  
			);

echo json_encode($json_data);  // send data as json format

?>
