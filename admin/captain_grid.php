<?php
include("../include/config.inc.php");
include("session.php");

$generalFunction = new generalfunction();
$converter  = new encryption();
$dbfunction = new dbfunctions();
$requestData= $_REQUEST;
$columns = array( 
	0 =>'user_id' ,
	1 =>'captain_name',
	2 =>'captain_city',
	3 =>'phone',
	4 =>'captain_approved_on',
	5 =>'total_orders',
);

// getting total number records without any search
$sql = "SELECT c.user_id ";
$sql.=" FROM tbl_users c WHERE user_id!='-1' AND captain_status='Approved' AND c.is_deleted='0'";
$query=mysqli_query($dbConn, $sql) or die("captain_grid.php: get employees1");
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData; 

$sql = "SELECT c.*";
$sql.=" FROM tbl_users c WHERE user_id!='-1' AND captain_status='Approved' AND c.is_deleted='0'";
if( !empty($requestData['search']['value']) ) {   
	$sql.=" AND ( user_id LIKE '".$requestData['search']['value']."%' ";	
	$sql.=" OR captain_name LIKE '".$requestData['search']['value']."%' ";
	$sql.=" OR email_id LIKE '".$requestData['search']['value']."%' ";
	$sql.=" OR phone LIKE '".$requestData['search']['value']."%' ";
	//$sql.=" OR captain_approved_on LIKE '".$requestData['search']['value']."%' ";
	$sql.=" OR total_captain_points LIKE '".$requestData['search']['value']."%' )";
}
$query=mysqli_query($dbConn, $sql) or die("captain_grid.php: get employees2");
$totalFiltered = mysqli_num_rows($query);
$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
$query=mysqli_query($dbConn, $sql) or die("captain_grid.php: get employees3");

$data = array();
$i=1+$requestData['start'];
while( $row=mysqli_fetch_array($query) ) {  // preparing an array
	
	$encodedId = $converter->encode($row["user_id"]);
	$editurl = "add_cust.php" . $urltoadd . ($urltoadd != "" ? "&user_id=" . $encodedId : "?user_id=" . $encodedId);
	$currStatus = ($row['captain_active']=="1")?"Active":"Deactive";
	if($row["total_captain_points"]>0)
	$btnRedeem =' | <a class="pointsDialog" data-id="'.$encodedId.'" href="#pointsModel" data-toggle="modal" style="text-decoration:underline">Redeem</a>';
	else
	$btnRedeem ='';
	if($row["wallet_balance"]>0)
	$reduceBalance =' | <a class="reduceMoneyDialog" data-id="'.$encodedId.'" href="#reduceMoneyModel" data-toggle="modal" style="text-decoration:underline">Deduct</a>';
	else
	$reduceBalance ='';
	$query1=mysqli_query($dbConn, "select count(*) as total_orders from tbl_orders where captain_id='".$row["user_id"]."' and order_status='Delivered'") ;
	$row1 = mysqli_fetch_assoc($query1);
	$notif = ($row["enableNotification"]=="1")?"Enabled<br/>":"Disabled<br/>";
	$notif .= "<a href='not_captain_list.php?cid=".$encodedId."'>View Notification</a>";
	$nestedData=array(); 
	//$nestedData[] = "&nbsp;&nbsp;&nbsp;<input type='checkbox'  class='deleteRow center' value='".$encodedId."'  />" ;
	$nestedData[] = '<span style="margin-left:10px"><a target="_blank" href="captain_order_list.php?user_id='.$encodedId.'">'.$row["user_id"].'</span>';
	$nestedData[] = '<span style="margin-left:10px">'.$row["captain_name"].'</span>';
	$nestedData[] = '<span style="margin-left:5px">'.$row["captain_city"].'</span>';
	$nestedData[] = '<span style="margin-left:5px">'.$row["phone"].'</span>';
	$nestedData[] = '<span style="margin-left:5px">'.datFormat($row["captain_approved_on"]).'</span>';
	$nestedData[] = '<span style="margin-left:35px">'.$row1["total_orders"].'</span>';
	$nestedData[] = '<span style="margin-left:20px">'.$row["total_captain_points"].'</span>';
	$nestedData[] = "<div class='btn-group'>
											  <button type='button' class='btn btn-primary dropdown-toggle btn-xs' data-toggle='dropdown'>
												<i class='fa1 fa-cog1'></i> $currStatus <span class='caret'></span>
											  </button>
											  <ul class='dropdown-menu primary' role='menu'>
												<li><a onclick=updateStatus('".$encodedId."','active') >Active</a></li>
												<li><a onclick=updateStatus('".$encodedId."','deactive') >Deactive</a></li>
											  </ul>
											</div>";
	if($row["captain_status"]=='Pending')
	$statusAction =	'<br><a  href="captain_list.php?id='.$encodedId.'&action='.$converter->encode("approveCaptain").'" style="text-decoration:underline" >Approve</a>&nbsp;|
	&nbsp;<a  href="captain_list.php?id='.$encodedId.'&action='.$converter->encode("rejectCaptain").'" style="text-decoration:underline" >Reject</a>';
	else if($row["captain_status"]=='Approved')
	$statusAction =	'<br><a  href="captain_list.php?id='.$encodedId.'&action='.$converter->encode("rejectCaptain").'" style="text-decoration:underline" >Reject</a>';	
	else
	$statusAction ='';
	//$statusAction =''; // TEMP
	//$nestedData[] = $row['captain_status'].$statusAction;
	$nestedData[] = '<a class="ViewDialog btn btn-xs btn-success" data-id="'.$encodedId.'" href="#viewModel" data-toggle="modal">View</a>';	
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
