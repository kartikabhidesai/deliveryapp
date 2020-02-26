<?php
	include_once("../include/config.inc.php");
	include_once("session.php");
	include_once("../include/sendNotification.php");
	$generalFunction = new generalfunction();
	$converter  = new encryption();
	$dbfunction = new dbfunctions();
	
	$pagename   = "recharge_request_list";
	$pageurl    = "recharge_request_list.php";
	if($converter->decode($_GET['action'])=="pendingRequest")
	$_SESSION['pendingRequest'] = "1";
	else
	unset($_SESSION['pendingRequest']);
	
	if($converter->decode($_POST['action'])=="addBalance")
	{
		if($_POST['amount']>0) 
		{
			$id = $converter->decode($_POST['id']);
			$dbfunction->SimpleSelectQuery("SELECT current_balance FROM tbl_user WHERE user_id='".$id."'");
			$bal = $dbfunction->getFetchArray();
			extract($bal);
			$postbal['type'] = "addition";
			$postbal['user_id'] = $id;
			$postbal['old_balance'] = $current_balance;
			$postbal['amount'] = $_POST['amount'];
			$postbal['new_balance'] = ($current_balance+$_POST['amount']);
			$postbal['comment'] = $_POST['comment'];
			$postbal['created_on'] = date('Y-m-d H:i:s');
			$dbfunction->InsertQuery("tbl_balance_statements", $postbal);
			$updatebal['current_balance'] = ($current_balance+$_POST['amount']);
			$dbfunction->UpdateQuery("tbl_user", $updatebal, "user_id='" .$id . "'");
			
					// Notification to add balance
					//$postdata['job_id'] = $row['job_id'];
					$postdata['user_id'] = $id;
					//$postdata['user_id2'] = $row['customer_id'];
					$postdata['message'] = "add_balance";
					$postdata['created_on'] = time();
					$postdata['updated_on'] = time();
					$sql1 = mysqli_query($dbConn,"SELECT * from tbl_user where `user_id` = '".$id."'");
					$row1 = mysqli_fetch_assoc($sql1);
					extract($row1);
					if($notification_enabled == '1')
					{
						if($device_type == 'ios')
							sendToIphoneAPI($device_token,$postdata);
						else
							sendToAndroidAPI($device_token,$postdata);
					}
					$dbfunction->InsertQuery("tbl_notification" , $postdata );
			
			$geturldriver = "?suc=" . $converter->encode("6");
			$urltoredirect = $pageurl . $geturldriver;
			$generalFunction->redirect($urltoredirect);
		}
		
	}
	
	if($converter->decode($_POST['action'])=="deductBalance")
	{
		if($_POST['amount']>0)
		{
			$id = $converter->decode($_POST['id']);
			$dbfunction->SimpleSelectQuery("SELECT current_balance FROM tbl_user WHERE user_id='".$id."'");
			$bal = $dbfunction->getFetchArray();
			extract($bal);
			$postbal['type'] = "deduction";
			$postbal['user_id'] = $id;
			$postbal['old_balance'] = $current_balance;
			$postbal['amount'] = $_POST['amount'];
			$postbal['new_balance'] = ($current_balance-$_POST['amount']);
			$postbal['comment'] = $_POST['comment'];
			$postbal['job_id'] = $_POST['job_id'];
			$postbal['created_on'] = date('Y-m-d H:i:s');
			$dbfunction->InsertQuery("tbl_balance_statements", $postbal);
			$updatebal['current_balance'] = ($current_balance-$_POST['amount']);
			$dbfunction->UpdateQuery("tbl_user", $updatebal, "user_id='" .$id . "'");
			
					// Notification to deduct balance
					$postdata['job_id'] = $_POST['job_id'];
					$postdata['user_id'] = $id;
					//$postdata['user_id2'] = $row['customer_id'];
					$postdata['message'] = "deduct_balance";
					$postdata['created_on'] = time();
					$postdata['updated_on'] = time();
					$sql1 = mysqli_query($dbConn,"SELECT * from tbl_user where `user_id` = '".$id."'");
					$row1 = mysqli_fetch_assoc($sql1);
					extract($row1);
					if($notification_enabled == '1')
					{
						if($device_type == 'ios')
							sendToIphoneAPI($device_token,$postdata);
						else
							sendToAndroidAPI($device_token,$postdata);
					}
					$dbfunction->InsertQuery("tbl_notification" , $postdata );
			
			$geturldriver = "?suc=" . $converter->encode("6");
			$urltoredirect = $pageurl . $geturldriver;
			$generalFunction->redirect($urltoredirect);
		}
		
	}
	
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
        <title><?php  echo "Recharge Request(s) :: Admin :: ".SITE_NAME; ?></title>
		<link rel="shortcut icon" type="image/x-con" href="images/favicon.ico" />

		<!--<script type="text/javascript" language="javascript" src="http://192.185.48.215/~lakadia7/projects/demo/matien/js/jquery.js"></script>
		<script type="text/javascript" language="javascript" src="http://192.185.48.215/~lakadia7/projects/demo/matien/js/jquery.dataTables.js"></script>-->
        <?php include("js-css-head.php"); ?>
        <?php include("meta-settings.php"); ?>
	</head>
    <body >
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
					<h1 class="page-heading">Recharge Request(s)</h1>
					<span class="pull-right" >
					
					</span>
					<!-- Begin breadcrumb -->
					<ol class="breadcrumb default square rsaquo sm">
						<li><a href="dashboard.php"><i class="fa fa-home"></i></a></li>
						<li><a href="recharge_request_list.php">Service Provider</a></li>
						<li class="active">View</li>
						
					</ol>
					
					<!-- End breadcrumb -->
					<?php
							if (isset($_GET["suc"]) && $_GET["suc"] != "")
                            {
					
								$success = $converter->decode($_GET["suc"]);
								if ($success == 1)
                                {
									echo '<script> getMessage("success","'.str_replace("{modulename}", "Service Provider", $lang["updatemessage-status"]).'"); </script>';
								}
								elseif ($success == 2)
                                {
									
									echo '<script> getMessage("error","'.str_replace("{modulename}", "Service Provider", $lang["deletemodulemessage"]).'"); </script>';
								}
								elseif ($success == 3)
                                {
									
									echo '<script> getMessage("error","'.str_replace("{modulename}", "Service Provider", $lang["deletemodulemessage"]).'"); </script>';
								}
								elseif ($success == 4)
                                {
									
									echo '<script> getMessage("success","'.str_replace("{modulename}", "Service Provider", $lang["addmodulemessage"]).'"); </script>';
								}
								elseif ($success == 5)
                                {
									
									echo '<script> getMessage("success","'.str_replace("{modulename}", "Service Provider", $lang["updatemodulemessage"]).'"); </script>';
								}
								elseif ($success == 6)
                                {
									
									echo '<script> getMessage("success","Balance updated successfully"); </script>';
								}
							}
						?>
						
					
					<!-- BEGIN DATA TABLE -->
					<div class="the-box">
						<div class="table-responsive">
					
						<table class="table table-striped table-hover display" id="dt-list" >
							<thead class="the-box dark full">
								<tr>
									<th>Freelancer/Company</th>
									<th>Amount</th>
									<th>Bank</th>
									<th>Receipt</th>
									
									<?php //if($_SESSION[SESSION_NAME . 'status_recharge_request_prvl']=='1') { 
									if(true) {
									?>
									<th>Status</th>
									<?php } ?>
									<th>Created</th>
								</tr>
							</thead>
							
						</table>
						
						
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
				<h3 class="modal-title" id="viewModel">Service Provider Info</h3>
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
		
		<!-- Modal -->
		<div class="modal fade" id="addBalModel" tabindex="-1" role="dialog" aria-labelledby="addBalModel" aria-hidden="true">
		  <div class="modal-dialog">
			<div class="modal-content">
			
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h3 class="modal-title" id="addBalModel">Add Balance</h3>
			  </div>
			  <div class="panel-body" id="modelBody"> 
			  
			  </div>
			  <div class="modal-footer">
			  <!--<button data-bb-handler="success" type="button" class="btn btn-purple">Save</button>-->
			  <button data-bb-handler="danger" data-dismiss="modal" type="button" class="btn btn-black">Close</button>
			  </div>
			  
			</div><!-- /.modal-content -->
		  </div><!-- /.modal-dialog -->
		</div><!-- /.modal -->
		<!-- Modal End -->
		
		<!-- Modal -->
		<div class="modal fade" id="deductBalModel" tabindex="-1" role="dialog" aria-labelledby="deductBalModel" aria-hidden="true">
		  <div class="modal-dialog">
			<div class="modal-content">
			
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h3 class="modal-title" id="deductBalModel">Deduct Balance</h3>
			  </div>
			  <div class="panel-body" id="modelBody1"> 
			  
			  </div>
			  <div class="modal-footer">
			  <!--<button data-bb-handler="success" type="button" class="btn btn-purple">Save</button>-->
			  <button data-bb-handler="danger" data-dismiss="modal" type="button" class="btn btn-black">Close</button>
			  </div>
			  
			</div><!-- /.modal-content -->
		  </div><!-- /.modal-dialog -->
		</div><!-- /.modal -->
		<!-- Modal End -->
		
		<!-- Notification Modal End-->
		
		<script>
		
		
		var action = "<?=($_GET['action'] !='')?$_GET['action']:'';?>";
		var gridUrl = "recharge_request_grid.php";
		var viewUrl = "recharge_request_view.php";
		var actionUrl = "recharge_request_action.php";
		
		<?php if($_SESSION[SESSION_NAME . 'status_recharge_request_prvl']=='1') { ?>
		var targetsCols = [4];
		var orderCols = [[5,"desc"]];
		var widthCols = [null,null,null,null,null,null];
		<?php }else{ ?>
		var targetsCols = [4];
		var orderCols = [[5,"desc"]];
		var widthCols = [null,null,null,null,null,null];
		<?php } ?>
		var demoUrl="recharge_request_add_bal.php";
		var demoUrl1="recharge_request_deduct_bal.php";
		</script>
		<?php include("js-css-footer.php");?>