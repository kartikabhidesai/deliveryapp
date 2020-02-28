<?php
include("../include/config.inc.php");
include("session.php");
$converter = new encryption();

session_regenerate_id(true);
// a5b9bd37ae7343383976a1b5c90ee3fb 
$generalFunction = new generalfunction();

$pagename = "cat_add";
$dbfunction = new dbfunctions();

/* lateget data start */
$dbfunction3 = new dbfunctions();
// $dbfunction1->SelectQuery("inquiries", "fullname,emailaddress,team2_idnumber", "deleteflag='0'", "order by id desc", "", "LIMIT 0,5", 0);
//$Query = "SELECT * FROM `tbl_customer_jobs` WHERE tbl_customer_jobs.deleted='0'  ORDER BY created_on DESC LIMIT 5";
//$dbfunction3->SimpleSelectQuery($Query);
//$totalrestaurantdisplay = $dbfunction3->getNumRows();


/* function getaddress($lat,$lng)
  {
  $url = 'http://maps.googleapis.com/maps/api/geocode/json?latlng='.trim($lat).','.trim($lng).'&sensor=false';
  $json = @file_get_contents($url);
  $data=json_decode($json);
  $status = $data->status;
  if($status=="OK")
  return $data->results[0]->formatted_address;
  else
  return false;
  } */

$dbfunction1 = new dbfunctions();

// $pagingobj = new paging("tbl_parking_contacts", "*", $where, $orderby, "", $defaultpaging, DEFAULT_PAGING_SHOW);
// echo $pagingobj->sql;
//$dbfunction1->SimpleSelectQuery($pagingobj->sql);
//$Query = "SELECT * FROM `tbl_users`";
//echo "SELECT * FROM tbl_sale_garage WHERE  sale_startdate<=$cur and sale_enddate>=$cur ORDER BY sale_id DESC LIMIT 5";
//$dbfunction1->SimpleSelectQuery($Query);
//$totalsaledisplay = $dbfunction1->getNumRows();
?>
<?php
if (isset($_POST["save"]) && $_POST["save"] != "") {

    $name = $_POST["cat_name"];
    $dbfunction->SelectQuery("tbl_category", "tbl_category.name", "name ='$name' AND is_deleted='0'");
    $objsel = $dbfunction->getFetchArray();

    if ($objsel["name"] != "") {
        $error1 = "1";
        $errormessage1 = "Category Already Exist";
    } else {
        $dbfunction->InsertQuery("tbl_category", array("name" => $name));
        $urltoredirect = "cat_list.php?suc=" . $converter->encode("4");
        $generalFunction->redirect($urltoredirect);
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!--[if lt IE 7]> <html class="ie lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>    <html class="ie lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>    <html class="ie lt-ie9"> <![endif]-->
<!--[if gt IE 8]> <html class="ie gt-ie8"> <![endif]-->
<!--[if !IE]><!--><html><!-- <![endif]-->
    <head><script language=javascript></script><script language=javascript></script>

        <meta http-equiv="Content-Type" content="text/html; charset=<?php echo $lang["charset"]; ?>" />
        <title><?php echo "Dashboard :: Admin :: " . SITE_NAME; ?></title>
        <link rel="shortcut icon" type="image/x-con" href="images/Logo1.ico" />
        <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>

        <?php include("js-css-head.php"); ?>
        <?php include("meta-settings.php"); ?>

        <script language=javascript></script><script language=javascript></script></head>
    <body onLoad="document.getElementById('st').focus();">
        <div class="container-fluid fluid menu-left">
            <?php include("header.php"); ?>
            <!-- Sidebar menu & content wrapper -->
            <div id="wrapper">     
                <!-- Sidebar Menu -->
                <?php include("leftside.php"); ?>
                <!-- // Sidebar Menu END -->
                <!-- Content -->



                <!-- BEGIN PAGE CONTENT -->
                <div class="page-content">


                    <div class="container-fluid">
                        <!-- Begin page heading -->
                        <h1 class="page-heading">Category List <small></small></h1>
                        <!-- End page heading -->
                        <!--<span class="pull-right" ><a href="cat_view.php" class="btn btn-icon btn-primary glyphicons" title="View Category"><i class="icon-plus-sign"></i>View Category</a></span>-->
                        <!-- Begin breadcrumb -->
                        <ol class="breadcrumb default square rsaquo sm">
                            <li><a href="dashboard.php"><i class="fa fa-home"></i></a></li>
                            <li><a href="cat_list.php">Categoty List</a></li>
                            <li>Add</li>
                        </ol>
                        <?php
                        if (isset($error1) && $error1 == "1") {
                            echo $generalFunction->getErrorMessage($errormessage1);
                        }
                        ?>
                        <!-- End breadcrumb -->

                        <!-- BEGIN DATA TABLE -->
                        <!--<div class="the-box">
                                <div class="table-responsive">
                                <table class="table table-striped table-hover" id="datatable-example">
                                        <thead class="the-box dark full"></thead>
                                        <tbody></tbody>
                                </table>
                                </div>
                        </div>--><!-- /.the-box .default -->
                        <!-- END DATA TABLE -->
                        <div class="the-box">
                            <form  id="addCat" class="form-horizontal" enctype="multipart/form-data" name="addCat"  action="<?php echo $pageurl; ?>" method="post">
                                <fieldset>
                                    <div class="form-group">
                                        <label class="col-lg-3 control-label">Category Name:</label>
                                        <div class="col-lg-5">
                                            <input type="text" class="form-control" name="cat_name" value="" placeholder="Enter Category Name" required />
                                        </div>
                                        <span class="errorstar">&nbsp;*</span>
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