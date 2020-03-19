<?php

include_once("../include/config.inc.php");
include_once("session.php");

$generalFunction = new generalfunction();
$converter = new encryption();
$dbfunction = new dbfunctions();
$prod_id = $converter->decode($_GET['id']);
$requestData = $_REQUEST;
$columns = array(
// datatable column index  => database column name
    1 => 'item_image',
    2 => 'item_name',
    3 => 'rating',
    4 => 'item_size',
    5 => 'item_price',
);

// getting total number records without any search
$sql = "SELECT *";
$sql .= " FROM tbl_item";
$query = mysqli_query($dbConn, $sql) or die("tbl_item.php: get item");
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


$sql = "SELECT tbl_item.*, tbl_item_size_price.item_size, tbl_item_size_price.item_price ,tbl_variant.variant_name";
$sql .= " FROM tbl_item INNER JOIN tbl_item_size_price ON tbl_item_size_price.item_id=tbl_item.id INNER JOIN tbl_variant ON tbl_variant.id=tbl_item_size_price.item_size  WHERE tbl_item.is_deleted='0' AND tbl_item.prod_id=$prod_id GROUP BY tbl_item_size_price.item_id";
if (!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
    $sql .= " AND ( item_name LIKE '" . $requestData['search']['value'] . "%' ";
    $sql .= " OR ( item_name LIKE '" . $requestData['search']['value'] . "%' )";
    //$sql.=" OR prdUnitPrice LIKE '".$requestData['search']['value']."%' )";
}
$query = mysqli_query($dbConn, $sql) or die("tbl_item.php: search item");

$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
$sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "  LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */
$query = mysqli_query($dbConn, $sql) or die("tbl_item.php: get item");
$data = array();
$i = 1 + $requestData['start'];
while ($row = mysqli_fetch_array($query)) {  // preparing an array

    $editurl = "item_edit.php" . $urltoadd . ($urltoadd != "" ? "&id=" . $converter->encode($row["id"]) ."&prod_id=".$converter->encode($prod_id) : "?id=" . $converter->encode($row["id"])."&prod_id=".$converter->encode($prod_id));
    $encodedId = $row["id"];
    $nestedData = array();

    $nestedData[] = "&nbsp;&nbsp;&nbsp;<input type='checkbox'  class='deleteRow center' value='" . $row["id"] . "'  />";
    $nestedData[] = '<span style="margin-left:10px"><img src="../uploads/item/' . $row['item_image'] . '" width="100" height="100" /></span>';
    $nestedData[] = '<span style="margin-left:10px">' . $row["item_name"] . '</span>';
    $nestedData[] = '<span style="margin-left:10px">' . $row["variant_name"] . '</span>';
    $nestedData[] = '<span style="margin-left:10px">' . $row["item_price"] . '</span>';

    $nestedData[] = '<a href="' . $editurl . '" title="Edit" ><button class="btn btn-xs btn-success">Edit</button></a>
	<a data-id="' . $encodedId . '" class="btnDelete btn btn-xs btn-danger" >Delete</a>';


    $data[] = $nestedData;
    $i++;
}



$json_data = array(
    "draw" => intval($requestData['draw']), // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
    "recordsTotal" => intval($totalData), // total number of records
    "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
    "data" => $data   // total data array
);

echo json_encode($json_data);  // send data as json format
?>
