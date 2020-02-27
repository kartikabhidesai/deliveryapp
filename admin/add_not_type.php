<?php
include("../include/config.inc.php");
include("../include/thumb.php");
include("session.php");
$generalFunction = new generalfunction();
$pagename = "add_not_type";
$pageurl = "add_not_type.php";
$converter = new encryption();
$dbfunction = new dbfunctions();

function dateFormat($date, $format = false) {
    $date = date_create($date);
    if ($format == true) {
        return date_format($date, "y/m/d H:i:s");
    } else {
        return date_format($date, "d/m/Y");
    }
}

$cancelurl = "";

if ($_GET["st"] != "") {
    $cancelurl .= ($cancelurl == "" ? "?st=" . $_GET["st"] : "&st=" . $_GET["st"]);
}
if ($_GET["page_no"] != "") {
    $cancelurl .= ($cancelurl == "" ? "?page_no=" . $_GET["page_no"] : "&page_no=" . $_GET["page_no"]);
}
if ($_GET["sort"] != "") {
    $cancelurl .= ($cancelurl == "" ? "?sort=" . $_GET["sort"] : "&sort=" . $_GET["sort"]);
}
if ($_GET["perpage"] != "") {
    $cancelurl .= ($cancelurl == "" ? "?perpage=" . $_GET["perpage"] : "&perpage=" . $_GET["perpage"]);
}
if ($_GET["order"] != "") {
    $cancelurl .= ($cancelurl == "" ? "?order=" . $_GET["order"] : "&order=" . $_GET["order"]);
}
if ($_GET["type"] != "") {
    $cancelurl .= ($cancelurl == "" ? "?type=" . $_GET["type"] : "&type=" . $_GET["type"]);
}

if (isset($_GET["not_type_id"]) && $_GET["not_type_id"] != "") {
    $_SESSION["nottype_paging"] = '"draw" : false,	"bProcessing": true,"bServerSide": true,"bStateSave": true,';
    $id = $_GET["not_type_id"];
    $dbfunction->SelectQuery("tbl_notification_types", "tbl_notification_types.*", $dbfunction->db_safe("tbl_notification_types.not_type_id ='%1'", $converter->decode($id), '0'));
    $objsel = $dbfunction->getFetchArray();
    $not_type_id = stripslashes(trim($objsel["not_type_id"]));
    $not_type_title = stripslashes(trim($objsel["not_type_title"]));
    $not_type_icon = stripslashes(trim($objsel["not_type_icon"]));
    $not_type_app = stripslashes(trim($objsel["not_type_app"]));
    //$prdUnitPrice = stripslashes(trim($objsel["prdUnitPrice"]));
    $is_active = stripslashes($objsel["is_active"]);
    //$action = stripslashes($objsel["action"]);
}

if (isset($_POST["save"]) && $_POST["save"] != "") {

    $myfilter = new inputfilter();
    $id = $myfilter->process($_POST["id"]);
    $not_type_title = $myfilter->process(trim($_POST["not_type_title"]));
    $not_type_app = $myfilter->process(trim($_POST["not_type_app"]));
    $is_active = $myfilter->process($_POST["is_active"]);
    $action = $myfilter->process($_POST["action"]);
    $dbfunction->SelectQuery("tbl_notification_types", "tbl_notification_types.not_type_id", "not_type_title ='$not_type_title' AND is_deleted='0'");
    $objsel = $dbfunction->getFetchArray();
    //print_r($action);exit;
    if ($action == "add" && $objsel["not_type_id"] != "") {
        $error1 = "1";
        $errormessage1 = "Type Already Exist";
    } elseif ($action == "edit" && $objsel["not_type_id"] != "" && $objsel["not_type_id"] != $id) {
        $error1 = "1";
        $errormessage1 = "Type Already Exist";
    } elseif ($not_type_title == "") {
        $error1 = "1";
        $errormessage1 = "Please enter Display Title";
    } elseif ($not_type_app == "") {
        $error1 = "1";
        $errormessage1 = "Please enter Type Title";
    } else {
        $urladd = "";
        foreach ($_POST as $key => $value) {
            if (substr($key, 0, 4) == "ret_") {
                if ($value != "") {
                    if ($urladd == "") {
                        $urladd = "?" . str_replace("ret_", "", $key) . "=" . $value;
                    } else {
                        $urladd .= "&" . str_replace("ret_", "", $key) . "=" . $value;
                    }
                }
            }
        }
        $cancelurl = $urladd;

        //$Sitelogo = $_FILES['teamid']['name'];
        //$Sitelogo2 = $_FILES['car_image']['name'];
        // $Sitelogo2 = $_FILES['OfferImage']['name'];
        // $Sitelogo3 = $_FILES['MovieImage3']['name'];
        //$hdn_image = $_POST['hdn_image'];
        function ProcessedImage($not_type_icon, $fieldname) {
            global $generalFunction;
            if ($not_type_icon != "") {
                if ($generalFunction->validAttachment($not_type_icon)) {
                    ini_set('max_execution_time', '999999');
                    $orgfile_name = $not_type_icon;
                    $image = $_FILES[$fieldname]['size'];
                    $ext1 = $generalFunction->getExtention($orgfile_name);
                    $file_name = $generalFunction->getFileName($orgfile_name);
                    $new_filename = $generalFunction->validFileName($file_name);
                    $tmp_file = $_FILES[$fieldname]['tmp_name'];
                    $not_type_icon = $new_filename . date('Y-m-d H:i:s') . "." . $ext1;
                    if ($fieldname == "not_type_icon") {
                        $original = "../uploads/icons/" . $not_type_icon;
                        $path = "../uploads/icons/";
                    }
                    $size = 112097152;
                    if ($image > $size) {
                        echo $Messages = "File Size should not be more than 2 mb";
                        exit;
                    }
                    if (!move_uploaded_file($tmp_file, $original)) {
                        echo $Messages = "File not uploaded";
                        exit;
                    } else {
                        createthumb($original, $path . '30/' . $not_type_icon, 30, 30);
                        //createthumb($original, $path.'80/'.$not_type_icon,80,80);
                    }
                }
            } else {
                $not_type_icon = $_POST['hdn_image'];
            }
            return $not_type_icon;
        }

        $profileimage = $_FILES['not_type_icon']['name'];
        $hdn_image = $_POST['hdn_image'];
        $not_type_icon = ProcessedImage($profileimage, "not_type_icon");


        //$SiteLogo = ProcessedImage($Sitelogo,"teamid");
        //$car_image = ProcessedImage($Sitelogo2,"car_image");
        // $SiteLogo3 = ProcessedImage($Sitelogo3,"MovieImage3");

        /* $GoogleAPI = "http://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($AdvertiseDesc)."&sensor=false";
          $GoogleDatasInString = file_get_contents($GoogleAPI);
          $GoogleResult = json_decode($GoogleDatasInString,true);
          $Latitude = $GoogleResult['results'][0]['geometry']['location']['lat'];
          $Longitude = $GoogleResult['results'][0]['geometry']['location']['lng']; */

        if ($action == "add") {
            $dbfunction->InsertQuery("tbl_notification_types", array("not_type_title" => $not_type_title, "not_type_app" => $not_type_app, "not_type_icon" => $not_type_icon, "is_active" => '1', "created_on" => date('Y-m-d H:i:s')));
            // $lastInsertId = $dbfunction->getLastInsertedId();
            // $dbfunction->InsertQuery("tbl_user_garage", array("id" => $lastInsertId, "OfferDesc" => $OfferDesc,"OfferImage"=>$SiteLogo2,"IsActivate" => $Status));
            $urltoredirect = "not_type_list.php?suc=" . $converter->encode("4");
            $generalFunction->redirect($urltoredirect);
        } else {
            $updatearray = array("not_type_title" => $not_type_title, "not_type_app" => $not_type_app, "not_type_icon" => $not_type_icon, "is_active" => $is_active, "updated_on" => date('Y-m-d H:i:s'));
            $dbfunction->UpdateQuery("tbl_notification_types", $updatearray, "not_type_id='" . $id . "'");
            $urltoredirect = "not_type_list.php" . ($urladd != "" ? $urladd . "&suc=" . $converter->encode("5") : "?suc=" . $converter->encode("5"));
            $generalFunction->redirect($urltoredirect);
        }
    }
}
?>
<!DOCTYPE html>
<!--[if lt IE 7]> <html class="ie lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>    <html class="ie lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>    <html class="ie lt-ie9"> <![endif]-->
<!--[if gt IE 8]> <html class="ie gt-ie8"> <![endif]-->
<!--[if !IE]><!--><html><!-- <![endif]-->
    <head><script language=javascript></script><script language=javascript></script>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <link rel="shortcut icon" type="image/x-con" href="images/favicon.ico" />
        <title><?php
            if ($id) {
                echo "Edit Type :: Admin :: " . SITE_NAME;
            } else {
                echo "Add Type :: Admin :: " . SITE_NAME;
            }
            ?></title>
        <?php include("js-css-head.php"); ?>
        <?php include("meta-settings.php"); ?>
        <script language=javascript></script><script language=javascript></script></head>
    <body class="tooltips">

        <div class="wrapper page-content">
            <?php include("header.php"); ?>
            <!-- Sidebar menu & content wrapper -->

            <!-- Sidebar Menu -->
            <?php include("leftside.php"); ?>
            <!-- // Sidebar Menu END -->
            <div id="page-content"> 
                <!-- Content -->
                <div id="content" class="container-fluid">
                    <h1 class="page-heading"><?php echo $id == "" ? "Add" : "Edit"; ?> Notification Type <small></small></h1>
                    <!-- End page heading -->
                    <span class="pull-right" ><a href="not_type_list.php" class="btn btn-icon btn-primary glyphicons" title="View Types"><i class="icon-plus-sign"></i>View Types</a></span>
                    <!-- Begin breadcrumb -->
                    <ol class="breadcrumb default square rsaquo sm">
                        <li><a href="dashboard.php"><i class="fa fa-home"></i></a></li>
                        <li><a href="not_type_list.php">Notification Type</a></li>
                        <li class="active"><?php echo $id == "" ? "Add" : "Edit"; ?></li>
                    </ol>
                    <!-- End breadcrumb -->

                    <?php
                    if (isset($error) && $error == "1") {
                        echo $generalFunction->getErrorMessage($errormessage);
                    }

                    if (isset($error1) && $error1 == "1") {
                        echo $generalFunction->getErrorMessage($errormessage1);
                    }
                    ?>

                    <div class="the-box">
                        <form  id="addType" class="form-horizontal" enctype="multipart/form-data" name="addType"  action="<?php echo $pageurl; ?>" method="post">
                            <fieldset>
                                <div class="form-group">
                                    <label class="col-lg-3 control-label">Display Title:</label>
                                    <div class="col-lg-5">
                                        <input type="text" class="form-control" name="not_type_title" value="<?php echo (isset($not_type_title) ? $not_type_title : ""); ?>" placeholder="Discount,Free delivery,Reduced,Issue etc" required />
                                    </div>
                                    <span class="errorstar">&nbsp;*</span>
                                </div>

                                <div class="form-group">
                                    <label class="col-lg-3 control-label">Short Desc:</label>
                                    <div class="col-lg-5">
                                        <input type="text" class="form-control" name="not_type_app" value="<?php echo (isset($not_type_app) ? $not_type_app : ""); ?>" placeholder="DISCOUNT,REDUCED,TECHNICAL ISSUE etc" required />
                                    </div>
                                    <span class="errorstar">&nbsp;*</span>
                                </div>




                                <div class="form-group">
                                    <label class="col-lg-3 control-label" for="not_type_icon">Icon:</label>

                                    <div class="col-lg-5">
                                        <div class="checkbox">
                                            <input class="inputwidth" style="cursor:pointer;" id="not_type_icon" name="not_type_icon" type="file"  />
                                        </div>
                                    </div>
                                </div>
                                <?php
                                if ($not_type_icon != "") {
                                    ?>
                                    <div class="form-group">
                                        <label class="col-lg-3 control-label"></label>
                                        <div class="col-lg-5">
                                            <input type="hidden" name="hdn_image" id="hdn_image" value="<?php echo $not_type_icon; ?>" />
                                            <img src="../uploads/icons/<?php echo $not_type_icon; ?>" alt="Preview Image" height="30px" width="30px" style="border-radius:5px" />

                                        </div>

                                    </div>		
                                    <?php
                                }
                                ?>
                                <div class="form-group">
                                    <label class="col-lg-3 control-label">Status:</label>
                                    <div class="col-lg-5">
                                        <div class="checkbox"><label><input type="checkbox" value="1" class="checkboxvalue" name="is_active" id="is_active" <?php echo (((isset($is_active) && $is_active == "1") || $is_active == "") ? "checked" : ""); ?> />&nbsp;</label></div>
                                    </div>
                                </div>

                                <!-- Form actions -->
                                <div class="form-group">
                                    <div class="col-lg-9 col-lg-offset-3">
                                        <?php
                                        if ($_GET["not_type_id"] != "") {
                                            ?>
                                            <button type="submit" name="save1" id="save1" value="submit1" class="btn btn-primary" title="Update" tabindex="19">Update</button>
                                            <?php
                                        } else {
                                            ?>
                                            <button type="submit" name="save1" id="save1" value="submit1" class="btn btn-primary" title="Save" tabindex="19">Save</button>
                                        <?php } ?>
                                        <span class="span2"><button type="button" onClick="javascript: window.location.href = 'not_type_list.php<?php echo $cancelurl; ?>'" class="btn btn-primary" title="Cancel">Cancel</button></span>
                                    </div>
                                </div>

                            </fieldset>
                            <input type="hidden" name="save" id="save" value="submit" />
                            <input type="hidden" name="id" id="id" value="<?php echo (isset($not_type_id) ? $not_type_id : ""); ?>" />
                            <input type="hidden" name="action" id="action" value="<?php echo ((isset($_GET["not_type_id"]) || $action == "edit") ? "edit" : "add"); ?>" />

                        </form>
                        <!-- // Table END -->
                    </div><!-- /.the-box -->
                </div>
            </div>
            <!-- // Content END --> 

            <div class="clearfix"></div>
            <!-- // Sidebar menu & content wrapper END -->

            <?php include("footer.php"); ?>

        </div><!-- /.wrapper -->
        <?php include("js-css-footer.php"); ?>
    </body>
</html>																																	