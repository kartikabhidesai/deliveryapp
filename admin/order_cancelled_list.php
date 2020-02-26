<?php
	include("../include/config.inc.php");
	include('../include/sendNotification.php');
	include("session.php");
	
	$generalFunction = new generalfunction();
	$converter  = new encryption();
	$dbfunction = new dbfunctions();
	$pagename   = "order_cancelled_list";
	$pageurl    = "order_cancelled_list.php";
	$currtime = date('Y-m-d H:i:s');
	
	if($_GET['user_id']!="")
	{
		$_SESSION['user_id_orderlist']=$_GET['user_id'];
	}
	else
	{
		unset($_SESSION['user_id_orderlist']);
	}

	if($converter->decode($_REQUEST['action'])=="manageOrder")
	{
		$updatedata = array("driverId" => $_POST['driverId'],"deliverySequence"=>$_POST["deliverySequence"],"order_status"=>"3");
		$dbfunction->UpdateQuery("tbl_orders",$updatedata , "invoice_no='" . $converter->decode($_POST['id']) . "'");
		$geturldriver = "?suc=" . $converter->encode("5");
		$urltoredirect = $pageurl . $geturldriver;
		$generalFunction->redirect($urltoredirect);
	}
	if($converter->decode($_REQUEST['action'])=="status")
	{
		$id = $converter->decode($_REQUEST['id']);
		$code = $converter->decode($_REQUEST['code']);
		$updatedata = array("order_status"=>$code);	
		$dbfunction->UpdateQuery("tbl_orders",$updatedata , "invoice_no='$id'");
		
		$dbfunction-> SimpleSelectQuery("select c.* from `tbl_users` c inner join tbl_orders o on c.user_id=o.user_id where o.invoice_no='$id'");
		$result = $dbfunction->getFetchArray();
		$device_token = $result['device_token'];
		$user_id = $result['user_id'];
		$badge = $result['badge'];
		//$text = "Your order #".$id." has been confirmed. We will inform you before delivery.";
		if($code=='2')
		$text = "تم تأكيد طلبك رقم ".$id;
		elseif($code=='3')
		$text = "تم إعداد طلبك ".$id; 
		elseif($code=='4')
		$text = "طلبك على الطريق ".$id; 
		elseif($code=='5')
		$text = "طلبك من الباب ".$id; 
		elseif($code=='6')
		$text = "تم تسليم طلبك ".$id; 
		if($result['device_type']=='iphone')
		$result=sendToIphone($device_token,$text,$type,$badge);
		else
		$result=sendToAndroid($device_token,$text,$type,$badge);	
		
		$postdata_noti['not_text'] = $text;
		$postdata_noti['not_type_id'] = '15';
		$postdata_noti['created_on'] = $currtime;
		if($code=='2' || $code=='6')
		{
		$autoId = $dbfunction->InsertQuery( "tbl_notification" , $postdata_noti );	
		$lastid = $dbfunction->getLastInsertedId();	
		$postdata_noti_his['not_id'] = $lastid;
		$postdata_noti_his['user_id'] = $user_id;
		$postdata_noti_his['send_time'] = $currtime;
		$postdata_noti_his['created_on'] = $currtime;
		$autoId = $dbfunction->InsertQuery( "tbl_notification_history" , $postdata_noti_his );	
		$lastid = $dbfunction->getLastInsertedId();	
		}
		$geturldriver = "?suc=" . $converter->encode("5");
		$urltoredirect = $pageurl . $geturldriver;
		$generalFunction->redirect($urltoredirect);
	}
	if($converter->decode($_REQUEST['action'])=="cancelOrder")
	{
		$id = $converter->decode($_REQUEST['id']);
		$updatedata = array("order_status"=>"Cancelled","cancel_reason"=>$_POST['cancel_reason']);
		$dbfunction->UpdateQuery("tbl_orders",$updatedata , "invoice_no='$id'");
		$dbfunction-> SimpleSelectQuery("select c.* from `tbl_users` c inner join tbl_orders o on c.user_id=o.customer_id where o.invoice_no='$id'");
		$result = $dbfunction->getFetchArray();
		//mysqli_query($dbConn, "update tbl_users set wallet_balance=wallet_balance+".$result["wallet_balance"]." where user_id='".$result['user_id']."'");
		$device_token = $result['device_token'];
		$badge = $result['badge_count'];
		$user_id = $result['user_id'];
		//$text = $_POST['cancel_reason']." :سبب .".$id." تم إلغاء طلبك رقم";
		$text = "Your order #".$id." is cancelled. Reason: ".$_POST['cancel_reason'];
		
		if($result['device_type']=='iphone')
		$result=sendToIphone($device_token,$text,$type,$badge);
		else
		$result=sendToAndroid($device_token,$text,$type,$badge);	
		
		$postdata_noti['not_text'] = $text;
		$postdata_noti['not_type_id'] = '15';
		$postdata_noti['created_on'] = $currtime;
		$autoId = $dbfunction->InsertQuery( "tbl_notification" , $postdata_noti );	
		$lastid = $dbfunction->getLastInsertedId();	
		
		$postdata_noti_his['not_id'] = $lastid;
		$postdata_noti_his['user_id'] = $user_id;
		$postdata_noti_his['send_time'] = $currtime;
		$postdata_noti_his['created_on'] = $currtime;
		$autoId = $dbfunction->InsertQuery( "tbl_notification_history" , $postdata_noti_his );	
		
		$geturldriver = "?suc=" . $converter->encode("5");
		$urltoredirect = $pageurl . $geturldriver;
		$generalFunction->redirect($urltoredirect);
	}

?>
<!DOCTYPE html>
<!--[if lt IE 7]> <html class="ie lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>    <html class="ie lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>    <html class="ie lt-ie9"> <![endif]-->
<!--[if gt IE 8]> <html class="ie gt-ie8"> <![endif]-->
<!--[if !IE]><!--><html><!-- <![endif]-->
    <head><script language=javascript>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            </script><script language=javascript>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            </script>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <title><?php  echo "View Cancelled Order(s) :: Admin :: ".SITE_NAME; ?></title>
		<link rel="shortcut icon" type="image/x-con" href="images/favicon.ico" />
		<script>
		var gridUrl = "order_cancelled_grid.php";
		var viewUrl = "order_view.php";
		var actionUrl = "order_action.php";
		var manageUrl = "order_manage.php";
		var cancelUrl = "order_cancel.php";
		</script>
        <?php include("js-css-head.php"); ?>
        <?php include("meta-settings.php"); ?>
	<script language=javascript>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            </script><script language=javascript>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            </script></head>
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
					<h1 class="page-heading">View Cancelled Order(s) <small></small></h1>
					
					<!--<span class="pull-right" ><a href="add_cust.php" class="btn btn-icon btn-primary glyphicons" title="Add Order"><i class="icon-plus-sign"></i>Add Order</a></span>-->
					<!-- End page heading -->
				
					<!-- Begin breadcrumb -->
					<ol class="breadcrumb default square rsaquo sm">
						<li><a href="dashboard.php"><i class="fa fa-home"></i></a></li>
						<li><a href="order_list.php">Order</a></li>
						<li class="active">View</li>
						
					</ol>
					
					<!-- End breadcrumb -->
					<?php
							if (isset($_GET["suc"]) && $_GET["suc"] != "")
                            {
					
								$success = $converter->decode($_GET["suc"]);
									if ($success == 1)
                                {
									echo '<script> getMessage("success","'.str_replace("{modulename}", "Order", $lang["updatemessage-status"]).'"); </script>';
								}
								elseif ($success == 2)
                                {
									
									echo '<script> getMessage("error","'.str_replace("{modulename}", "Order", $lang["deletemodulemessage"]).'"); </script>';
								}
								elseif ($success == 3)
                                {
									
									echo '<script> getMessage("error","'.str_replace("{modulename}", "Order", $lang["deletemodulemessage"]).'"); </script>';
								}
								elseif ($success == 4)
                                {
									
									echo '<script> getMessage("success","'.str_replace("{modulename}", "Order", $lang["addmodulemessage"]).'"); </script>';
								}
								elseif ($success == 5)
                                {
									
									echo '<script> getMessage("success","'.str_replace("{modulename}", "Order", $lang["updatemodulemessage"]).'"); </script>';
								}
							}
						?>
						
					<!-- BEGIN DATA TABLE -->
					<div class="the-box">
						<div class="table-responsive">
					
						<table class="table table-striped table-hover" id="dt-list" >
							<thead class="the-box dark full">
								<tr>
									<th>Order#</th>
									<th>Customer</th>
									<th>Store Name</th>
									<th>Delivery Fee</th>
									<th>Grand Total</th>
									<th>Payment Type</th>
									<th>Order Time</th>
									<th>Order Status</th>
									<th>Action</th>
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
				<h3 class="modal-title" id="viewModel">Order Details</h3>
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
		
		<?php //include_once("order_manage.php"); ?>
		<!-- Modal -->
		<div class="modal fade" id="manageModel" tabindex="-1" role="dialog" aria-labelledby="manageModel" aria-hidden="true">
		  <div class="modal-dialog">
			<div class="modal-content">
			
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h3 class="modal-title" id="manageModel">Manage Order</h3>
			  </div>
			 <div class="panel-body" id="txtManage">  </div>
			  <div class="modal-footer">
			  <!--<button data-bb-handler="success" type="button" class="btn btn-purple">Save</button>-->
			  <button data-bb-handler="danger" data-dismiss="modal" type="button" class="btn btn-black">Close</button>
			  </div>
			  
			</div><!-- /.modal-content -->
		  </div><!-- /.modal-dialog -->
		</div><!-- /.modal -->

		<!-- Modal -->
		<div class="modal fade" id="cancelModel" tabindex="-1" role="dialog" aria-labelledby="cancelModel" aria-hidden="true">
		  <div class="modal-dialog">
			<div class="modal-content">
			
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h3 class="modal-title" id="cancelModel">Cancel Order</h3>
			  </div>
			 <div class="panel-body" id="divCancelOrder">  </div>
			  <div class="modal-footer">
			  <!--<button data-bb-handler="success" type="button" class="btn btn-purple">Save</button>-->
			  <button data-bb-handler="danger" data-dismiss="modal" type="button" class="btn btn-black">Close</button>
			  </div>
			  
			</div><!-- /.modal-content -->
		  </div><!-- /.modal-dialog -->
		</div><!-- /.modal -->
	
		<script>
			var targetsCols = [0,1,2,3,4,5,6,7];
			var orderCols = [[6,"desc"]];
			var buttonValue = ['excel', 'pdf'];			
		</script>
		<?php include("js-css-footer.php");?>