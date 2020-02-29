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

    $item_name = $_POST["item_name"];
    print_r($_REQUEST);die();
    
    $dbfunction->SelectQuery("tbl_variant", "tbl_variant.variant_name", "variant_name ='$name' AND is_deleted='0'");
    $objsel = $dbfunction->getFetchArray();

    if ($objsel["variant_name"] != "") {
        $error1 = "1";
        $errormessage1 = "Variant Name Already Exist";
    } else {
//        $dbfunction->InsertQuery("tbl_variant", array("variant_name" => $name));
//        $urltoredirect = "variant_list.php?suc=" . $converter->encode("4");
//        $generalFunction->redirect($urltoredirect);
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<head>
    <script language=javascript></script><script language=javascript></script>

    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo $lang["charset"]; ?>" />
    <title><?php echo "Variant :: Admin :: " . SITE_NAME; ?></title>
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
                                                    <input type='checkbox'  class='item_seleteded' id="item_<?php echo $row['id']; ?>" name="item_<?php echo $row['id']; ?>" value="<?php echo $row['id']; ?>" onclick="item_selected(<?php echo $row['id']; ?>)"/>
                                                    <span><?php echo $row['variant_name']; ?></span>
                                                </div>
                                                <div class="col-lg-9">
                                                    <input type="text" class="form-control textbox" id="item_price_<?php echo $row['id']; ?>" name="item_price_<?php echo $row['id']; ?>" value="" placeholder="Enter Price" required  disabled="disabled"/>
                                                </div>
                                            </div>
                                            <br>
                                            <?php } ?>
                                    </div>
                                    <span class="errorstar">&nbsp;*</span>
                                </div>

                                <div class="form-group">
                                    <label class="col-lg-3 control-label" for="image">Item Image:</label>

                                    <div class="col-lg-5">
                                        <div class="checkbox">
                                            <input class="inputwidth" style="cursor:pointer;" id="image" name="image" type="file"  />
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