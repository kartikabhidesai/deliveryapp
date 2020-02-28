<?php
include("../include/config.inc.php");
include("session.php");
$converter = new encryption();

session_regenerate_id(true);
// a5b9bd37ae7343383976a1b5c90ee3fb 
$generalFunction = new generalfunction();

$pagename = "product_add";
$dbfunction = new dbfunctions();

$sql = "SELECT id, name";
$sql .= " FROM tbl_category WHERE is_deleted=0";
$query = mysqli_query($dbConn, $sql) or die("tbl_category.php: get category");

$dbfunction3 = new dbfunctions();
$dbfunction1 = new dbfunctions();
$Query = "SELECT * FROM `tbl_users`";
$dbfunction1->SimpleSelectQuery($Query);
$totalsaledisplay = $dbfunction1->getNumRows();

if (isset($_POST["save"]) && $_POST["save"] != "") {

    $category = $_POST["category"];
    $prod_name = $_POST["prod_name"];
    $address = $_POST["address"];
    $mobile = $_POST["mobile"];

//    function ProcessedImage($image, $fieldname) {
//        global $generalFunction;
//        if ($generalFunction->validAttachment($image)) {
//            ini_set('max_execution_time', '999999');
//            $orgfile_name = $image;
//            $image = $_FILES[$fieldname]['size'];
//            $ext1 = $generalFunction->getExtention($orgfile_name);
//            $file_name = $generalFunction->getFileName($orgfile_name);
//            $new_filename = $generalFunction->validFileName($file_name);
//            $tmp_file = $_FILES[$fieldname]['tmp_name'];
//            $image = $new_filename . date('Y-m-d H:i:s') . "." . $ext1;
//            if ($fieldname == "image") {
//                $original = "../uploads/product/" . $image;
//                $path = "../uploads/product/";
//            }
//            $size = 112097152;
//            if ($image > $size) {
//                echo $Messages = "File Size should not be more than 2 mb";
//                exit;
//            }
//            if (!move_uploaded_file($tmp_file, $original)) {
//                echo $Messages = "File not uploaded";
//                exit;
//            } else {
//                createthumb($original, $path . '30/' . $image, 30, 30);
//                //createthumb($original, $path.'80/'.$not_type_icon,80,80);
//            }
//        }
//        return $image;
//    }
//
//    $profileimage = $_FILES['image']['name'];
////    $hdn_image = $_POST['hdn_image'];
//    $image = ProcessedImage($profileimage, "image");
      $image = 'download.jpg';

    $dbfunction->SelectQuery("tbl_product", "product_name", "product_name ='$prod_name' AND is_deleted='0'");
    $objsel = $dbfunction->getFetchArray();

    if ($objsel["product_name"] != "") {
        $error1 = "1";
        $errormessage1 = "Product Name Already Exist";
    } else {
        $dbfunction->InsertQuery("tbl_product", array("cat_id" => $category, "product_name" => $prod_name, "address" => $address, "mobile" => $mobile, "image" => $image));
        $urltoredirect = "product_list.php?suc=" . $converter->encode("4");
        $generalFunction->redirect($urltoredirect);
    }
}
?>
<head><script language=javascript></script><script language=javascript></script>

    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo $lang["charset"]; ?>" />
    <title><?php echo "Product :: Admin :: " . SITE_NAME; ?></title>
    <link rel="shortcut icon" type="image/x-con" href="images/Logo1.ico" />
    <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>

    <?php include("js-css-head.php"); ?>
    <?php include("meta-settings.php"); ?>

    <script language=javascript></script><script language=javascript></script>
</head>
<body onLoad="document.getElementById('st').focus();">
    <div class="container-fluid fluid menu-left">
        <?php include("header.php"); ?>
        <div id="wrapper">     
            <?php include("leftside.php"); ?>
            <div class="page-content">
                <div class="container-fluid">
                    <h1 class="page-heading">Product List <small></small></h1>
                    <span class="pull-right" ><a href="product_list.php" class="btn btn-icon btn-primary glyphicons" title="View Product"><i class="icon-plus-sign"></i>View Product</a></span>
                    <ol class="breadcrumb default square rsaquo sm">
                        <li><a href="dashboard.php"><i class="fa fa-home"></i></a></li>
                        <li><a href="cat_list.php">Product List</a></li>
                        <li>Add</li>
                    </ol>
                    <?php
                    if (isset($error1) && $error1 == "1") {
                        echo $generalFunction->getErrorMessage($errormessage1);
                    }
                    ?>
                    <div class="the-box">
                        <form  id="addCat" class="form-horizontal" enctype="multipart/form-data" name="addProduct"  action="<?php echo $pageurl; ?>" method="post">
                            <fieldset>
                                <div class="form-group">
                                    <label class="col-lg-3 control-label">Category:</label>
                                    <div class="col-lg-5">
                                        <select class='form-control' name="category" id="category">
                                            <option value=''>Select Category</option>
                                            <?php while($row=mysqli_fetch_array($query)){ ?>
                                                <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <span class="errorstar">&nbsp;*</span>
                                </div>

                                <div class="form-group">
                                    <label class="col-lg-3 control-label">Name:</label>
                                    <div class="col-lg-5">
                                        <input type="text" class="form-control" name="prod_name" value="" placeholder="Enter Product Name" required />
                                    </div>
                                    <span class="errorstar">&nbsp;*</span>
                                </div>

                                <div class="form-group">
                                    <label class="col-lg-3 control-label">Address:</label>
                                    <div class="col-lg-5">
                                        <input type="text" class="form-control" name="address" value="" placeholder="Enter Address" required />
                                    </div>
                                    <span class="errorstar">&nbsp;*</span>
                                </div>

                                <div class="form-group">
                                    <label class="col-lg-3 control-label">Contact Number:</label>
                                    <div class="col-lg-5">
                                        <input type="text" class="form-control" name="mobile" id='mobile' value="" placeholder="Enter Mobile Number" required />
                                    </div>
                                    <span class="errorstar">&nbsp;*</span>
                                </div>

                                <div class="form-group">
                                    <label class="col-lg-3 control-label" for="not_type_icon">Image:</label>

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
                                        <span class="span2"><button type="button" onClick="javascript: window.location.href = 'product_list.php'" class="btn btn-primary" title="Cancel">Cancel</button></span>
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
<!--Code for POPUP Dialog Box-->
<!--    <div id="dialog" title="Contact"></div>
    <script language="javascript">
        $(function () {
            $('#err').fadeOut(5000);
            $('.dialog_link').click(function () {
                $('#dialog').dialog('open');
                var val = $(this).attr("id");
                $('#dialog').load("contact_view.php?id=" + val);

                return false;
            });

        });
    </script>-->
<!--Code for POPUP Dialog Box-->
<!--Code for POPUP Dialog Box-->


<!--Code for POPUP Dialog Box-->
</html>