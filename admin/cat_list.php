<?php
include("../include/config.inc.php");
include("session.php");

session_regenerate_id(true);
// a5b9bd37ae7343383976a1b5c90ee3fb 
$generalFunction = new generalfunction();

$pagename = "cat_list";
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
$Query = "SELECT * FROM `tbl_users`";
//echo "SELECT * FROM tbl_sale_garage WHERE  sale_startdate<=$cur and sale_enddate>=$cur ORDER BY sale_id DESC LIMIT 5";
$dbfunction1->SimpleSelectQuery($Query);
$totalsaledisplay = $dbfunction1->getNumRows();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!--[if lt IE 7]> <html class="ie lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>    <html class="ie lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>    <html class="ie lt-ie9"> <![endif]-->
<!--[if gt IE 8]> <html class="ie gt-ie8"> <![endif]-->
<!--[if !IE]><!--><html><!-- <![endif]-->
    <head><script language=javascript></script><script language=javascript></script>

        <meta http-equiv="Content-Type" content="text/html; charset=<?php echo $lang["charset"]; ?>" />
        <title><?php echo "Category :: Admin :: " . SITE_NAME; ?></title>
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
                        <span class="pull-right" ><a href="cat_add.php" class="btn btn-icon btn-primary glyphicons" title="Add Category"><i class="icon-plus-sign"></i>Add Category</a></span>
                        <!-- Begin breadcrumb -->
                        <ol class="breadcrumb default square rsaquo sm">
                            <li><a href="dashboard.php"><i class="fa fa-home"></i></a></li>
                            <li><a href="cat_list.php">Categoty List</a></li>
                            <li>view</li>
                        </ol>
                        <?php
                        if (isset($_GET["suc"]) && $_GET["suc"] != "") {
                            $success = $converter->decode($_GET["suc"]);
                            if ($success == 1) {
                                echo '<script> getMessage("success","' . str_replace("{modulename}", "Type", $lang["updatemessage-status"]) . '"); </script>';
                            } elseif ($success == 2) {

                                echo '<script> getMessage("error","' . str_replace("{modulename}", "Type", $lang["deletemodulemessage"]) . '"); </script>';
                            } elseif ($success == 3) {

                                echo '<script> getMessage("error","' . str_replace("{modulename}", "Type", $lang["deletemodulemessage"]) . '"); </script>';
                            } elseif ($success == 4) {

                                echo '<script> getMessage("success","' . str_replace("{modulename}", "Type", $lang["addmodulemessage"]) . '"); </script>';
                            } elseif ($success == 5) {

                                echo '<script> getMessage("success","' . str_replace("{modulename}", "Type", $lang["updatemodulemessage"]) . '"); </script>';
                            }
                        }
                        ?>
                        <!-- End breadcrumb -->
                        <div class="the-box">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="dt-list">
                                    <thead class="the-box dark full">
                                        <tr>
                                            <th><span class="uniformjs"><input type="checkbox" id="selectall" value="0" name="selectall"  /></span> </th>
                                            <th>Category</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>

                                </table>
                                <button id="btnMultiDelete" class="btn btn-xs btn-danger" >Delete</button>
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
                    <!-- BEGIN FOOTER -->
<?php include_once("footer.php"); ?>
                    <!-- END FOOTER -->
                </div><!-- /.page-content -->
            </div><!-- /.wrapper -->
            <!-- END PAGE CONTENT -->
        </div>
        <div class="modal fade" id="viewModel" tabindex="-1" role="dialog" aria-labelledby="viewModel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h3 class="modal-title" id="viewModel">Category</h3>
                    </div>
                    <div class="panel-body" id="txtHint"> 

                    </div>
                    <div class="modal-footer">
                        <!--<button data-bb-handler="success" type="button" class="btn btn-purple">Save</button>-->
                        <button data-bb-handler="danger" data-dismiss="modal" type="button" class="btn btn-black">Close</button>
                    </div>

                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>
    </body>
    <!--Code for POPUP Dialog Box-->
    <script>
        var gridUrl = "cat_grid.php";
        var viewUrl = "cat_view.php";
        var actionUrl = "cat_action.php";
        var targetsCols = [0, 2, 3];
        var orderCols = [[1, "asc"]];
    </script>
<?php include("js-css-footer.php"); ?>
</html>