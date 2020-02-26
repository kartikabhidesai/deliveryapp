<?php
include("../include/config.inc.php");
include("session.php");
$dbConn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME) ;
$generalFunction = new generalfunction();
$converter  = new encryption();
$dbfunction = new dbfunctions();
$requestData= $_REQUEST;
$columns = array( 
	1 =>'commission_id' ,
	2 =>'order_number',
	3 =>'commission_perc',
	4 =>'extra_notes',
	5 =>'display_order',
);
$sql = "SELECT c.commission_id ";
$sql.=" FROM tbl_commission_setting c WHERE c.is_deleted='0'";
$query=mysqli_query($dbConn, $sql) or die("commission_grid.php: get employees1");
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData; 


$sql = "SELECT c.*";
$sql.=" FROM tbl_commission_setting c WHERE c.is_deleted='0'";
if( !empty($requestData['search']['value']) ) {   
	$sql.=" AND ( commission_id LIKE '".$requestData['search']['value']."%' ";	
	$sql.=" OR order_number LIKE '".$requestData['search']['value']."%' ";
	$sql.=" OR commission_perc LIKE '".$requestData['search']['value']."%' ";
	$sql.=" OR extra_notes LIKE '".$requestData['search']['value']."%' ";
	$sql.=" OR display_order LIKE '".$requestData['search']['value']."%' )";
}
$query=mysqli_query($dbConn, $sql) or die("commission_grid.php: get employees2");
$totalFiltered = mysqli_num_rows($query); 
$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
$query=mysqli_query($dbConn, $sql) or die("commission_grid.php: get employees3");

$data = array();
$i=1+$requestData['start'];
while( $row=mysqli_fetch_array($query) ) {  // preparing an array
	
	$encodedId = $converter->encode($row["commission_id"]);
	$editurl = "add_commission.php" . $urltoadd . ($urltoadd != "" ? "&commission_id=" . $encodedId : "?commission_id=" . $encodedId);
	$currStatus = ($row['is_active']=="1")?"Active":"Deactive";
	$nestedData=array(); 
	$nestedData[] = "&nbsp;&nbsp;&nbsp;<input type='checkbox'  class='deleteRow center' value='".$encodedId."'  />" ;
	$nestedData[] = '<span style="margin-left:10px; text-align:center;">'.$row["commission_id"].'</span>';
	$nestedData[] = '<span style="margin-left:50px; text-align:center;">'.$row["order_number"].'</span>';
	$nestedData[] = '<span style="margin-left:50px; text-align:center;">'.$row["commission_perc"].'</span>';
	//$nestedData[] = '<span style="margin-left:10px">%'.$row["extra_notes"].'</span>';
	//$nestedData[] = '<span style="margin-left:10px">'.$row["display_order"].'</span>';
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
