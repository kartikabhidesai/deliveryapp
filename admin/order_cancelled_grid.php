<?php
include("../include/config.inc.php");
include("session.php");

$generalFunction = new generalfunction();
$converter  = new encryption();
$dbfunction = new dbfunctions();

if($_SESSION['user_id_orderlist']!="")
{
	$customer_id = $converter->decode($_SESSION['user_id_orderlist']);
	$where = " customer_id='".$customer_id."'";
}
else
{
	$where = " orderType='regular'";
}

$requestData= $_REQUEST;

$columns = array( 
// datatable column index  => database column name
	0 =>'invoice_no', 
	1 =>'customer_name',
	3 =>'store_name',
	4 =>'delivery_fee',
	5 =>'discount',
	6 =>'grand_total'
);
$sql = "SELECT * ";
$sql.=" FROM tbl_orders WHERE order_status='Cancelled'";
$query=mysqli_query($dbConn, $sql) or die("order_grid.php: get employees");
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData; 
$sql = "SELECT * ";
$sql.=" FROM tbl_orders WHERE order_status='Cancelled'";
if( !empty($requestData['search']['value']) ) {
	$sql.=" AND ( invoice_no LIKE '".$requestData['search']['value']."%' ";    
	$sql.=" OR customer_name LIKE '".$requestData['search']['value']."%' ";
	$sql.=" OR store_name LIKE '".$requestData['search']['value']."%' )";
	
}
$query=mysqli_query($dbConn, $sql) or die("order_grid.php: get employees2");
$totalFiltered = mysqli_num_rows($query); 
$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
$query=mysqli_query($dbConn, $sql) or die("order_grid.php: get employees3");
$data = array();
$i=1+$requestData['start'];
while( $row=mysqli_fetch_array($query) ) {  // preparing an array
	
	if($row["order_status"] == '6')
	{
		$style = 'background-color: Green;color: white';//for completed
	}
	else if($row["order_status"] == '7' || $row["order_status"] == '8')
	{
		$style = 'background-color: red;color: white';//for Cancle
	}
	else if($row["order_status"] == '2')
	{
		$style = 'background-color: orange;color: black';//for Approve
	}
	else
	{
		$style = 'background-color: none';//for Others
	}
	
	/*$query1 = mysqli_query($dbConn,"select driverName from tbl_drivers where driverId='".$row["driverId"]."'");
	$row1 =mysqli_fetch_array($query1);
	if(!empty($row1))
	$nestedData1 = $row1["driverName"].' ('.$row["deliverySequence"].') <a class="manageDialog" data-id="'.$converter->encode($row["invoice_no"]).'" href="#manageModel" data-toggle="modal"><img src="../assets/img/pencil-icon.gif"></a>';
	else
	$nestedData1 = '<a class="manageDialog btn btn-xs btn-success" data-id="'.$converter->encode($row["invoice_no"]).'" href="#manageModel" data-toggle="modal">Assign</a>';	*/
	//$query1 = mysqli_query($dbConn,"select order_status from tbl_order_status where orderStatusId='".$row["order_status"]."'");
	//$row1 =mysqli_fetch_array($query1);
	$encodedId = $converter->encode($row["invoice_no"]);
	$encodedStatus = $converter->encode("status");
	$currStatus = ($row['is_active']=="1")?"Active":"Deactive";
	$editurl = "add_order.php" . $urltoadd . ($urltoadd != "" ? "&invoice_no=" . $encodedId : "?invoice_no=" . $encodedId);
	//$cancelOrder = '&nbsp;|&nbsp;<a class="cancelDialog" data-id="'.$encodedId.'" href="#cancelModel" data-toggle="modal" style="text-decoration:underline">Cancel</a>';
	if($row["order_status"]!='Delivered' && $row["order_status"]!='Cancelled')
	$statusAction =	'&nbsp;|&nbsp;<a class="cancelDialog" data-id="'.$encodedId.'" href="#cancelModel" data-toggle="modal" style="text-decoration:underline">Cancel</a>';
	else
	$statusAction ='';

	$query1 = mysqli_query($dbConn,"select count(*) as 'total_orders' from tbl_orders where orderId <= '".$row['orderId']."' and customer_id = '".$row['customer_id']."'");
	$row2 =mysqli_fetch_array($query1);
	if(!empty($row2))
		$total = $row2['total_orders'];
	
	//$order_type=($row['orderType'] == '1')?"Subscription ":"Regular ";
	switch($row['orderType'])
	{
		case "0": 
			$order_type = "Regular "; 
		break;
		case "1": 
			$order_type = "Subscription "; 
		break;
	}
	
	$nestedData=array(); 
	$nestedData[] = '<span style="'.$style.'">'.$row["invoice_no"].'</span>';
	$nestedData[] = '<span style="margin-left:10px">'.$row["customer_name"].'</span>';
	$nestedData[] =	'<span style="margin-left:20px">'.$row["store_name"].'</span>';
	$nestedData[] = '<span style="margin-left:10px">'.$row["delivery_fee"].'</span>';
	$nestedData[] = '<span style="margin-left:10px">'.$row["grand_total"].'</span>';
	$nestedData[] = '<span style="margin-left:10px">'.$row["payment_type"].'</span>';
	$nestedData[] = '<span style="margin-left:10px">'.datFormat($row["created_on"]).'</span>';
	$nestedData[] = '<span style="margin-left:0">'.$row['order_status'].$statusAction.'</span>';
	$nestedData[] = '<span style="margin-left:10px"><a class="ViewDialog btn btn-xs btn-success" data-id="'.$encodedId.'" href="#viewModel" data-toggle="modal">View</a></span>';
	$data[] = $nestedData;
	$i++;
}
$json_data = array(
			"draw"            => intval( $requestData['draw'] ),  
			"recordsTotal"    => intval( $totalData ), 
			"recordsFiltered" => intval( $totalFiltered ),
			"data"            => $data  
			);

echo json_encode($json_data);  

?>
