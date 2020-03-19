<?php
include("../include/config.inc.php");
include("session.php");

session_regenerate_id(true);
$converter = new encryption();
// a5b9bd37ae7343383976a1b5c90ee3fb 
$generalFunction = new generalfunction();
$prod_id = $converter->decode($_GET['id']);
$additem = "item_add.php" . $urltoadd . ($urltoadd != "" ? "&id=" . $converter->encode($prod_id) : "?id=" . $converter->encode($prod_id));
$pagename = "item_list";
$dbfunction = new dbfunctions();
?>

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
            <!-- Content -->
            <!-- BEGIN PAGE CONTENT -->
            <div class="page-content">


                <div class="container-fluid">
                    <!-- Begin page heading -->
                    <h1 class="page-heading">Item List <small></small></h1>
                    <!-- End page heading -->
                    <span class="pull-right" ><a href="<?php echo $additem; ?>" class="btn btn-icon btn-primary glyphicons" title="Add Item"><i class="icon-plus-sign"></i>Add Item</a></span>
                    <!-- Begin breadcrumb -->
                    <ol class="breadcrumb default square rsaquo sm">
                        <li><a href="dashboard.php"><i class="fa fa-home"></i></a></li>
                        <li><a href="item_list.php">Item List</a></li>
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
                                        <th>Item Image</th>
                                        <th>Item Name</th>
                                        <th>Item size</th>
                                        <th>Item price</th>
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
                    <h3 class="modal-title" id="viewModel">Item</h3>
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
    var gridUrl = "item_grid.php?id=<?php echo $_GET['id'] ?>";
    var viewUrl = "item_view.php";
    var actionUrl = "item_action.php";
    var targetsCols = [0, 4, 5];
    var orderCols = [[1, "asc"]];
</script>
<?php include("js-css-footer.php"); ?>
</html>