<?php
include("../include/config.inc.php");
include("session.php");

$generalFunction = new generalfunction();
$converter  = new encryption();
$dbfunction = new dbfunctions();
$requestData= $_REQUEST;
$columns = array( 
	1 =>'user_id' ,
	2 =>'full_name',
	3 =>'phone',
	4 =>'created_on',
	5 =>'total_customer_points'
);

// getting total number records without any search
$sql = "SELECT user_id ";
$sql.=" FROM tbl_users WHERE user_id!='-1' AND is_deleted='0'";
$query=mysqli_query($dbConn, $sql) or die("cust_grid.php: get employees1");
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData; 


$sql = "SELECT * ";
$sql.=" FROM tbl_users WHERE user_id!='-1' AND is_deleted='0'";
if( !empty($requestData['search']['value']) ) {   
	$sql.=" AND ( user_id LIKE '".$requestData['search']['value']."%' ";	
	$sql.=" OR full_name LIKE '".$requestData['search']['value']."%' ";
	$sql.=" OR email_id LIKE '".$requestData['search']['value']."%' ";
	$sql.=" OR phone LIKE '".$requestData['search']['value']."%' ";
	//$sql.=" OR created_on LIKE '".$requestData['search']['value']."%' ";
	$sql.=" OR total_customer_points LIKE '".$requestData['search']['value']."%' )";
}
$query=mysqli_query($dbConn, $sql) or die("cust_grid.php: get employees2");
$totalFiltered = mysqli_num_rows($query); 
$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
$query=mysqli_query($dbConn, $sql) or die("cust_grid.php: get employees3");

$data = array();
$i=1+$requestData['start'];
while( $row=mysqli_fetch_array($query) ) {  // preparing an array
	
	$encodedId = $converter->encode($row["user_id"]);
	$editurl = "add_cust.php" . $urltoadd . ($urltoadd != "" ? "&user_id=" . $encodedId : "?user_id=" . $encodedId);
	$currStatus = ($row['is_active']=="1")?"Active":"Deactive";
	if($row["total_customer_points"]>0)
	$btnRedeem =' | <a class="pointsDialog" data-id="'.$encodedId.'" href="#pointsModel" data-toggle="modal" style="text-decoration:underline">Redeem</a>';
	else
	$btnRedeem ='';
	if($row["wallet_balance"]>0)
	$reduceBalance =' | <a class="reduceMoneyDialog" data-id="'.$encodedId.'" href="#reduceMoneyModel" data-toggle="modal" style="text-decoration:underline">Deduct</a>';
	else
	$reduceBalance ='';
	$notif = ($row["enableNotification"]=="1")?"Enabled<br/>":"Disabled<br/>";
	$notif .= "<a href='not_cust_list.php?cid=".$encodedId."'>View Notification</a>";
	$nestedData=array(); 
	$nestedData[] = "&nbsp;&nbsp;&nbsp;<input type='checkbox'  class='deleteRow center' value='".$encodedId."'  />" ;
	$nestedData[] = '<span style="margin-left:10px"><a target="_blank" href="customer_order_list.php?user_id='.$encodedId.'">'.$row["user_id"].'</a></span>';
	$nestedData[] = '<span style="margin-left:10px">'.$row["full_name"].'</span>';
	$nestedData[] = '<span style="margin-left:05px">'.$row["phone"].'</span>';
	$nestedData[] = '<span style="margin-left:10px">'.datFormat($row["created_on"]).'</span>';
	//$nestedData[] = '<span style="margin-left:5px">'.$row["wallet_balance"].$reduceBalance.'</span>';
	$nestedData[] = '<span style="margin-left:10px">'.$row["total_customer_points"].$btnRedeem.'</span>';
	$nestedData[] = $notif;
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
	<a href="'.$editurl.'" title="Edit" ><button class="btn btn-xs btn-success">Edit</button></a>
	<a data-id="'.$encodedId.'" class="btnDelete btn btn-xs btn-danger" >Delete</a>';	
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
