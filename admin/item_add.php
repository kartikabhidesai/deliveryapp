<?php
include("../include/config.inc.php");
include("session.php");
$converter = new encryption();

session_regenerate_id(true);
// a5b9bd37ae7343383976a1b5c90ee3fb 
$generalFunction = new generalfunction();

$pagename = "item_add";
$dbfunction = new dbfunctions();

$sql = "SELECT id, variant_name";
$sql .= " FROM tbl_variant WHERE is_deleted=0";
$query = mysqli_query($dbConn, $sql) or die("tbl_variant.php: get variant");
?>
<?php
if (isset($_POST["save"]) && $_POST["save"] != "") {

    $prod_id = $converter->decode($_GET['id']);
    $count = count($_POST['item']);
    $item_name = $_POST['item_name'];
    $dbfunction->SelectQuery("tbl_item", "tbl_item.item_name", "item_name ='$item_name' AND is_deleted='0' AND prod_id=$prod_id");
    $objsel = $dbfunction->getFetchArray();

    function ProcessedImage($image, $fieldname) {
       
        global $generalFunction;
        if ($generalFunction->validAttachment($image)) {
            ini_set('max_execution_time', '999999');
            $orgfile_name = $image;
            $image = $_FILES[$fieldname]['size'];
            $ext1 = $generalFunction->getExtention($orgfile_name);
            $file_name = $generalFunction->getFileName($orgfile_name);
            $new_filename = $generalFunction->validFileName($file_name);
            $tmp_file = $_FILES[$fieldname]['tmp_name'];
            $image = $new_filename . time() . "." . $ext1;
            if ($fieldname == "item") {
                $original = "../uploads/item/" . $image;
                $path = "../uploads/item/";
            }
            $size = 112097152;
            if ($image > $size) {
                echo $Messages = "File Size should not be more than 2 mb";
                exit;
            }
       //     echo $tmp_file.'=='.$original; exit();
            if (!move_uploaded_file($tmp_file, $original)) {
                echo $Messages = "File not uploaded";
                exit;
            } 
//            else {
//                createthumb($original, $path . '30/' . $image, 30, 30);
//                //createthumb($original, $path.'80/'.$not_type_icon,80,80);
//            }
        }
        return $image;
    }
//
    $profileimage = $_FILES['item']['name'];
    $hdn_image = $_POST['hdn_image'];
    $image = ProcessedImage($profileimage, "item");

    if ($objsel["item_name"] != "") {
        $error1 = "1";
        $errormessage1 = "Item Name Already Exist";
    } else {
        $dbfunction->InsertQuery("tbl_item", array("item_name" => $_POST['item_name'], "prod_id" => $prod_id, "item_image" => $image, "create_date" => date('Y-m-d H:i:s')));
        $sql = "SELECT id";
        $sql .= " FROM tbl_item ORDER BY id DESC LIMIT 1";
        $query = mysqli_query($dbConn, $sql) or die("tbl_item.php: get item");
        while ($row = mysqli_fetch_array($query)) {
            $last_id = $row['id'];
        }
        $i = 0;
        while ($i < $count) {
            $dbfunction->InsertQuery("tbl_item_size_price", array("item_id" => $last_id, "item_size" => $_REQUEST['item'][$i], "item_price" => $_REQUEST['item_price'][$i], "create_date" => date('Y-m-d H:i:s')));
            $i++;
        }
        $product_id = $converter->encode($prod_id);
        $urltoredirect = "item_list.php?id=".$product_id."&suc=" . $converter->encode("4");
        $generalFunction->redirect($urltoredirect);
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<head>
    <script language=javascript></script><script language=javascript></script>

    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo $lang["charset"]; ?>" />
    <title><?php echo "Item :: Admin :: " . SITE_NAME; ?></title>
    <link rel="shortcut icon" type="image/x-con" href="images/Logo1.ico" />
    <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>

    <?php include("js-css-head.php"); ?>
    <?php include("meta-settings.php"); ?>

    <script language=javascript></script><script language=javascript></script>
</head>
<body onLoad="document.getElementById('st').focus();">
    <div class="container-fluid fluid menu-left">
        <?php include("header.php"); ?>
        <!-- Sidebar menu & content wrapper -->
        <div id="wrapper">     
            <!-- Sidebar Menu -->
            <?php include("leftside.php"); ?>
            <!-- // Sidebar Menu END -->
            <!-- BEGIN PAGE CONTENT -->
            <div class="page-content">
                <div class="container-fluid">
                    <!-- Begin page heading -->
                    <h1 class="page-heading">Item Add<small></small></h1>
                    <!-- End page heading -->
                    <span class="pull-right" ><a href="item_list.php" class="btn btn-icon btn-primary glyphicons" title="View Item"><i class="icon-plus-sign"></i>View Item</a></span>
                    <!-- Begin breadcrumb -->
                    <ol class="breadcrumb default square rsaquo sm">
                        <li><a href="dashboard.php"><i class="fa fa-home"></i></a></li>
                        <li><a href="item_list.php">Item List</a></li>
                        <li>Add</li>
                    </ol>
                    <?php
                    if (isset($error1) && $error1 == "1") {
                        echo $generalFunction->getErrorMessage($errormessage1);
                    }
                    ?>

                    <div class="the-box">
                        <form  id="addCat" class="form-horizontal" enctype="multipart/form-data" name="addCat"  action="<?php echo $pageurl; ?>" method="post">
                            <fieldset>
                                <div class="form-group">
                                    <label class="col-lg-3 control-label">Item Name:</label>
                                    <div class="col-lg-5">
                                        <input type="text" class="form-control" name="item_name" value="" placeholder="Enter Item Name" required />
                                    </div>
                                    <span class="errorstar">&nbsp;*</span>
                                </div>

                                <div class="form-group">
                                    <label class="col-lg-3 control-label">Item Size:</label>
                                    <div class="col-lg-5">
                                        <?php while ($row = mysqli_fetch_array($query)) { ?>
                                            <div class="row">
                                                <div class="col-lg-3">
                                                    <input type='checkbox'  class='item_seleteded' id="item_<?php echo $row['id']; ?>" name="item[]" value="<?php echo $row['id']; ?>" onclick="item_selected(<?php echo $row['id']; ?>)"/>
                                                    <span><?php echo $row['variant_name']; ?></span>
                                                </div>
                                                <div class="col-lg-9">
                                                    <input type="text" class="form-control textbox" id="item_price_<?php echo $row['id']; ?>" name="item_price[]" value="" placeholder="Enter Price" required  disabled="disabled"/>
                                                </div>
                                            </div>
                                            <br>
                                            <?php } ?>
                                    </div>
                                    <span class="errorstar">&nbsp;*</span>
                                </div>

                                <div class="form-group">
                                    <label class="col-lg-3 control-label" for="not_type_icon">Image:</label>

                                    <div class="col-lg-5">
                                        <div class="checkbox">
                                            <input class="inputwidth" style="cursor:pointer;" id="image" name="item" type="file"  />
                                        </div>
                                    </div>
                                </div>	

                                <!-- Form actions -->
                                <div class="form-group">
                                    <div class="col-lg-9 col-lg-offset-3">

                                        <button type="submit" name="save" id="save" value="submit" class="btn btn-primary" title="Save">Save</button>
                                        <span class="span2"><button type="button" onClick="javascript: window.location.href = 'cat_list.php<?php echo $cancelurl; ?>'" class="btn btn-primary" title="Cancel">Cancel</button></span>
                                    </div>
                                </div>

                            </fieldset>
                        </form>
                        <!-- // Table END -->
                    </div>
                </div><!-- /.container-fluid -->
                <!-- BEGIN FOOTER -->
                <?php include_once("footer.php"); ?>
                <!-- END FOOTER -->
            </div><!-- /.page-content -->
        </div><!-- /.wrapper -->
        <!-- END PAGE CONTENT -->
    </div>
</body>
<script src="../assets/js/item_add.js" type="text/javascript"></script>
</html>