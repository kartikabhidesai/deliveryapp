<?php
include("../include/config.inc.php");
include("session.php");
//unset($_SESSION["cust_page"]);
$generalFunction = new generalfunction();
$converter  = new encryption();
$dbfunction = new dbfunctions();


if($_SESSION['pendingRequest'] == "1")
$where = "status='1' AND ";
/* Database connection end */
// storing  request (ie, get/post) global array to a variable  
$requestData= $_REQUEST;


$columns = array( 
// datatable column index  => database column name
	//1 =>'districtCode',
	0 =>'name' ,
	1 =>'recharge_amount',
	2=> 'bank_name',
	3=> 'receipt_photo',
	5=>'created_on'
);
 mysqli_query($dbConn,"SET NAMES 'utf8'");
// getting total number records without any search
$sql = "SELECT rec.*,tec.provider,tec.name,tec.comapny_name,ban.bank_name FROM tbl_recharge_history rec 
		INNER JOIN tbl_user tec ON rec.user_id=tec.user_id
		INNER JOIN tbl_banks ban ON rec.bank_id=ban.bank_id
		WHERE $where recharge_id >0 ";
$query=mysqli_query($dbConn, $sql) or die("cust_grid1.php: get employees");
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

$sql = "SELECT rec.*,tec.provider,tec.name,tec.comapny_name,ban.bank_name FROM tbl_recharge_history rec 
		INNER JOIN tbl_user tec ON rec.user_id=tec.user_id
		INNER JOIN tbl_banks ban ON rec.bank_id=ban.bank_id
		WHERE $where recharge_id >0 ";
if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$sql.=" AND ( rec.recharge_amount LIKE '".$requestData['search']['value']."' "; 
	$sql.=" OR bank_name LIKE '".$requestData['search']['value']."%'";	
	$sql.=" OR (name LIKE '".$requestData['search']['value']."%' AND provider='1')";
	$sql.=" OR (comapny_name LIKE '".$requestData['search']['value']."%' AND provider='0'))";
}
$query=mysqli_query($dbConn, $sql) or die("cust_grid2.php: get employees");
$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */	
$query=mysqli_query($dbConn, $sql) or die("cust_grid3.php: get employees");

$data = array();
$i=1+$requestData['start'];
while( $row=mysqli_fetch_array($query) ) {  // preparing an array
	
	$encodedId = $converter->encode($row["recharge_id"]);
	$editurl = "add_agency.php" . $urltoadd . ($urltoadd != "" ? "&id=" . $encodedId : "?id=" . $encodedId);
	
	if($row['status']=="1")
		$currStatus = 'Pending';
	elseif($row['status']=="2")
		$currStatus = 'Approved';
	elseif($row['status']=="3")
		$currStatus = 'Rejected';

	$nestedData=array(); 
	$nestedData[] = ($row["provider"]=='0')?$row["comapny_name"]:$row["name"];
	$nestedData[] = '<span style="margin-left:30px">'.$row["recharge_amount"].'</span>';
	$nestedData[] = $row["bank_name"];
	$nestedData[] = '
	<a href="'.'../uploads/receipt/'.$row["receipt_photo"].'" target="_blank" class="fancybox" data-fancybox-group="images_gallery">
	<img src="'.'../uploads/receipt/150x150/'.$row["receipt_photo"].'" alt="Thumbnail" width="100" height="100" >
	<div class="overlay-masked">
										<i class="ion ion-eye"></i>
									</div>
	</a>
	';
	if($row['status']=="1" && ($_SESSION[SESSION_NAME . 'status_recharge_request_prvl']=='1' || $_SESSION[SESSION_NAME . 'edit_recharge_request_prvl']=='1'))
	{
	$nestedData[] = "<div class='btn-group'>
											  <button type='button' class='btn btn-primary dropdown-toggle btn-xs' data-toggle='dropdown'>
												<i class='fa1 fa-cog1'></i> $currStatus <span class='caret'></span>
											  </button>
											  <ul class='dropdown-menu primary' role='menu'>
												<li><a onclick=updateStatus('".$encodedId."','approved') >".'Approve'."</a></li>
												<li><a onclick=updateStatus('".$encodedId."','rejected') >".'Reject'."</a></li>
											  </ul>
											</div>";
	}else
	{
		$nestedData[] = "<button type='button' class='btn btn-primary dropdown-toggle btn-xs'> $currStatus </button>";
	}
	/*$nestedData[] = '<a class="ViewDialog btn btn-xs btn-success" data-id="'.$converter->encode($row["id"]).'" href="#viewModel" data-toggle="modal"><i class="icon-trash icon-white"></i> '.$lang["View"].'</a>
	<a href="'.$editurl.'" title="'.$lang["Edit"].'" ><button class="btn btn-xs btn-success">'.$lang["Edit"].'</button></a>
	<a data-id="'.$encodedId.'" class="btnDelete btn btn-xs btn-danger" >'.$lang["Delete"].'</a>
	<a href="'.$addmanagerurl.'" title="'.((!empty($row1))?$lang["Edit"]:$lang["Add"])." ".$lang["manager"].'" ><button class="btn btn-xs btn-info">'.((!empty($row1))?$lang["Edit"]:$lang["Add"])." ".$lang["manager"].'</button></a>
	<a href="'.$planhisturl.'" title="'.$lang["plan"]." ".$lang["history"].'" ><button class="btn btn-xs btn-warning">'.$lang["plan"]." ".$lang["history"].'</button></a>';*/
	$nestedData[] = date("Y-m-d H:i",strtotime($row["created_on"]));
	
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
