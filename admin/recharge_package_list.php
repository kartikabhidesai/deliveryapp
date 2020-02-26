<?php

	include_once("../include/config.inc.php");
	include_once("session.php");
	$generalFunction = new generalfunction();
	$converter  = new encryption();
	$dbfunction = new dbfunctions();
	$pagename   = "recharge_package_list";
	$pageurl    = "recharge_package_list.php";
	
?>
<!DOCTYPE html>
<!--[if lt IE 7]> <html class="ie lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>    <html class="ie lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>    <html class="ie lt-ie9"> <![endif]-->
<!--[if gt IE 8]> <html class="ie gt-ie8"> <![endif]-->
<!--[if !IE]><!--><html><!-- <![endif]-->
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <title><?php  echo "View Recharge Package(s) :: Admin :: ".SITE_NAME; ?></title>
		<link rel="shortcut icon" type="image/x-con" href="images/favicon.ico" />
		<?php include("js-css-head.php"); ?>
        <?php include("meta-settings.php"); ?>
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
					<h1 class="page-heading">View Recharge Package(s) <small></small></h1>
					<!-- End page heading -->
					<span class="pull-right" >
					<!--<a href="recharge_package_export.php" class="btn btn-icon btn-primary glyphicons" title="Export Data">Export</a>-->
					<?php if($_SESSION[SESSION_NAME . 'add_recharge_package_prvl']=='1') {
					
					?>
					<a href="add_recharge_package.php" class="btn btn-icon btn-primary glyphicons" title="Add Recharge Package"><i class="icon-plus-sign"></i>Add Recharge Package</a>
					<?php } ?>
					</span>
					<!-- Begin breadcrumb -->
					<ol class="breadcrumb default square rsaquo sm">
						<li><a href="dashboard.php"><i class="fa fa-home"></i></a></li>
						<li><a href="recharge_package_list.php">Recharge Package</a></li>
						<li class="active">View</li>
					</ol>
					<!-- End breadcrumb -->
					<?php
							if (isset($_GET["suc"]) && $_GET["suc"] != "")
                            {
								$success = $converter->decode($_GET["suc"]);
									if ($success == 1)
                                {
									echo '<script> getMessage("success","'.str_replace("{modulename}", "bank", $lang["updatemessage-status"]).'"); </script>';
								}
								elseif ($success == 2)
                                {
									
									echo '<script> getMessage("error","'.str_replace("{modulename}", "bank", $lang["deletemodulemessage"]).'"); </script>';
								}
								elseif ($success == 3)
                                {
									
									echo '<script> getMessage("error","'.str_replace("{modulename}", "bank", $lang["deletemodulemessage"]).'"); </script>';
								}
								elseif ($success == 4)
                                {
									
									echo '<script> getMessage("success","'.str_replace("{modulename}", "bank", $lang["addmodulemessage"]).'"); </script>';
								}
								elseif ($success == 5)
                                {
									
									echo '<script> getMessage("success","'.str_replace("{modulename}", "bank", $lang["updatemodulemessage"]).'"); </script>';
								}
							}
						?>
					
					<!-- BEGIN DATA TABLE -->
					<div class="the-box">
						<div class="table-responsive">
						<table class="table table-striped table-hover" id="dt-list">
							<thead class="the-box dark full">
								<tr>
									<th><span class="uniformjs"><input type="checkbox" id="selectall" value="0" name="selectall"  /></span> </th>
									<th>Sequence No.</th>
									<!--<th>Recharge Package Name</th>
									<th>Recharge Package Name(ar)</th>-->
									<th>Extra Discount Percent</th>
									<th>Package Amount</th>
									<?php if($_SESSION[SESSION_NAME . 'status_recharge_package_prvl']=='1') { 
									
									?>
									<th>Status</th>
									<?php } ?>
									<th>Action</th>
								</tr>
							</thead>
							
						</table>
						<?php if($_SESSION[SESSION_NAME . 'delete_recharge_package_prvl']=='1') { ?>
						<button id="btnMultiDelete" class="btn btn-xs btn-danger" >Delete</button>
						<?php } ?>
						</div>
					</div>
					<!-- END DATA TABLE -->
				
				
				</div><!-- /.container-fluid -->
				
				
				
				<!-- BEGIN FOOTER -->
				<?php include_once("footer.php"); ?>
				<!-- END FOOTER -->
				
				
			</div><!-- /.page-content -->
		</div><!-- /.wrapper -->
		<!-- END PAGE CONTENT -->
		
		
		<!-- Modal -->
		<div class="modal fade" id="viewModel" tabindex="-1" role="dialog" aria-labelledby="viewModel" aria-hidden="true">
		  <div class="modal-dialog">
			<div class="modal-content">
			
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h3 class="modal-title" id="viewModel">Recharge Package Info</h3>
			  </div>
			  <div class="panel-body" id="txtHint"> 
			  
			  </div>
			  <div class="modal-footer">
			  <!--<button data-bb-handler="success" type="button" class="btn btn-purple">Save</button>-->
			  <button data-bb-handler="danger" data-dismiss="modal" type="button" class="btn btn-black">Close</button>
			  </div>
			  
			</div><!-- /.modal-content -->
		  </div><!-- /.modal-dialog -->
		</div><!-- /.modal -->

		<!-- Modal End -->
		
		<script>
			var gridUrl = "recharge_package_grid.php";
			var viewUrl = "recharge_package_view.php";
			var actionUrl = "recharge_package_action.php";
			
			<?php if($_SESSION[SESSION_NAME . 'status_recharge_package_prvl']=='1') {
			
			?>
			var targetsCols = [0,3,4,5];
			var orderCols = [[1,"asc"]];
			var widthCols = [ null,null,null,null,null,{ "width": "20%"}];
			<?php }else{ ?>
			var targetsCols = [0,3,4];
			var orderCols = [[1,"asc"]];
			var widthCols = [ null,null,null,null,{ "width": "20%"}];
			<?php } ?>
		</script>
		
		<?php include("js-css-footer.php");?>
		
	