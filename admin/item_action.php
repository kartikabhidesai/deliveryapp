<?php

include("../include/config.inc.php");
include("session.php");

$generalFunction = new generalfunction();
$converter = new encryption();
$dbfunction = new dbfunctions();


/* Database connection end */
$data_ids = $_REQUEST['data_ids'];
$data_id_array = explode(",", $data_ids);
if ($_REQUEST['action'] == 'active') {
    $field = 'is_active = 1';
} elseif ($_REQUEST['action'] == 'deactive') {
    $field = 'is_active = 0';
} else {
    $field = 'is_deleted=1';
}
if (!empty($data_id_array)) {
    foreach ($data_id_array as $id) {
        $sql = "UPDATE tbl_item SET $field ";
        $sql .= " WHERE id = '" . $id . "'";
        $query = mysqli_query($dbConn, $sql) or die("tbl_item.php: delete item");
    }
}
?>