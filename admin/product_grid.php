<?php

include_once("../include/config.inc.php");
include_once("session.php");

$generalFunction = new generalfunction();
$converter = new encryption();
$dbfunction = new dbfunctions();

$requestData = $_REQUEST;
$columns = array(
// datatable column index  => database column name
    1 => 'name',
);

// getting total number records without any search
$sql = "SELECT *";
$sql .= " FROM tbl_product";
$query = mysqli_query($dbConn, $sql) or die("product_grid.php: get products");
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


$sql = "SELECT tbl_product.*, tbl_category.name";
$sql.=" FROM tbl_product INNER JOIN tbl_category ON tbl_category.id=tbl_product.cat_id WHERE tbl_product.is_deleted='0'";
if (!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
    $sql .= " AND ( name LIKE '" . $requestData['search']['value'] . "%' ";
    $sql .= " OR ( name LIKE '" . $requestData['search']['value'] . "%' )";
    //$sql.=" OR prdUnitPrice LIKE '".$requestData['search']['value']."%' )";
}
$query = mysqli_query($dbConn, $sql) or die("product_grid.php: search products");

$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
$sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "  LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */
$query = mysqli_query($dbConn, $sql) or die("product_grid.php: get products");
$data = array();
$i = 1 + $requestData['start'];
while ($row = mysqli_fetch_array($query)) {  // preparing an array
    
        $currStatus = ($row['is_active']=="1")?"Active":"Deactive";
	$editurl = "product_edit.php" . $urltoadd . ($urltoadd != "" ? "&id=" . $converter->encode($row["id"]) : "?id=" . $converter->encode($row["id"]));
	$additem = "item_list.php" . $urltoadd . ($urltoadd != "" ? "&id=" . $converter->encode($row["id"]) : "?id=" . $converter->encode($row["id"]));
	$encodedId = $row["id"];
        $nestedData = array();

        $nestedData[] = "&nbsp;&nbsp;&nbsp;<input type='checkbox'  class='deleteRow center' value='". $row["id"] ."'  />" ;
        $nestedData[] = '<span style="margin-left:10px"><img src="../uploads/product/' . $row['image'] . '" width="100" height="100" /></span>';
        $nestedData[] = '<span style="margin-left:10px">'.$row["product_name"].'</span>';
        $nestedData[] = '<span style="margin-left:10px">'.$row["name"].'</span>';
        $nestedData[] = '<span style="margin-left:10px">'.$row["mobile"].'</span>';
        $nestedData[] = '<span style="margin-left:10px">'.$row["address"].'</span>';
        $nestedData[] = "<div class='btn-group'>
                        <button type='button' class='btn btn-primary dropdown-toggle btn-xs' data-toggle='dropdown'>
                              <i class='fa1 fa-cog1'></i> $currStatus <span class='caret'></span>
                        </button>
                        <ul class='dropdown-menu primary' role='menu'>
                              <li><a onclick=updateStatus('".$encodedId."','active') >Active</a></li>
                              <li><a onclick=updateStatus('".$encodedId."','deactive') >Deactive</a></li>
                        </ul>
                      </div>";
	$nestedData[] = '<a href="'.$additem.'" class="btn btn-xs btn-success" data-id="'.$row["id"].'">Item</a>
	<a href="'.$editurl.'" title="Edit" ><button class="btn btn-xs btn-success">Edit</button></a>
	<a data-id="'.$encodedId.'" class="btnDelete btn btn-xs btn-danger" >Delete</a>';

    
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
