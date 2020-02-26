<?php
	include("../include/config.inc.php");
	include("session.php");

	$generalFunction = new generalfunction();
	$converter  = new encryption();
	$dbfunction = new dbfunctions();
	$pagename   = "join_captain_list";
	$pageurl    = "join_captain_list.php";
	if($converter->decode($_POST['action'])=="redeemPoints")
	{
		extract($_POST);
		$points = $total_points-$redeemPoints;
		$points = ($points >=0)?$points:0;
		$updatedata = array("total_points" => $points);
		$dbfunction->UpdateQuery("tbl_users",$updatedata , "user_id='" . $converter->decode($_POST['id']) . "'");
		$geturldriver = "?suc=" . $converter->encode("6");
		$urltoredirect = $pageurl . $geturldriver;
		$generalFunction->redirect($urltoredirect);
	}
	
	if($converter->decode($_REQUEST['action'])=="approveCaptain")
	{
		$id = $converter->decode($_REQUEST['id']);
		$updatedata = array("captain_status"=>"Approved", "captain_approved_on" => date("Y-m-d"));
		$dbfunction->UpdateQuery("tbl_users",$updatedata , "user_id='$id'");
		
		/*$dbfunction-> SimpleSelectQuery("select c.* from `tbl_customers` c inner join tbl_orders o on c.custId=o.custId where o.invoiceNo='$id'");
		$result = $dbfunction->getFetchArray();
		$device_token = $result['device_token'];
		$custId = $result['custId'];
		$badge = $result['badge'];
		//$text = "Your order #".$id." has been confirmed. We will inform you before delivery.";
		$text = "تم تأكيد طلبك رقم ".$id." سوف نبلغكم عندما يكون طلبك بطريقة لك.";
		
		if($result['device_type']=='iphone')
		$result=sendToIphone($device_token,$text,$type,$badge);
		else
		$result=sendToAndroid($device_token,$text,$type,$badge);	
		
		$postdata_noti['notText'] = $text;
		$postdata_noti['notTypeId'] = '15';
		$postdata_noti['createdTimestamp'] = $currtime;
		$autoId = $dbfunction->InsertQuery( "tbl_notification" , $postdata_noti );	
		$lastid = $dbfunction->getLastInsertedId();	
		
		$postdata_noti_his['notId'] = $lastid;
		$postdata_noti_his['custId'] = $custId;
		$postdata_noti_his['send_time'] = $currtime;
		$postdata_noti_his['createdTimestamp'] = $currtime;
		$autoId = $dbfunction->InsertQuery( "tbl_notification_history" , $postdata_noti_his );	
		$lastid = $dbfunction->getLastInsertedId();	*/
		
		$geturldriver = "?suc=" . $converter->encode("5");
		$urltoredirect = $pageurl . $geturldriver;
		$generalFunction->redirect($urltoredirect);
	}
	if($converter->decode($_REQUEST['action'])=="rejectCaptain")
	{
		$id = $converter->decode($_REQUEST['id']);
		//$updatedata = array("orderStatus"=>"6","cancel_reason"=>$_POST['cancel_reason']);
		$updatedata = array("captain_status"=>"Rejected", "captain_approved_on" => date("Y-m-d"));
		$dbfunction->UpdateQuery("tbl_users",$updatedata , "user_id='$id'");
		/*$dbfunction-> SimpleSelectQuery("select c.*,o.remainBalance from `tbl_customers` c inner join tbl_orders o on c.custId=o.custId where o.invoiceNo='$id'");
		$result = $dbfunction->getFetchArray();
		mysqli_query($dbConn, "update tbl_customers set remainBalance=remainBalance+".$result["remainBalance"]." where custId='".$result['custId']."'");
		$device_token = $result['device_token'];
		$badge = $result['badge'];
		$custId = $result['custId'];
		$text = $_POST['cancel_reason']." :سبب .".$id." تم إلغاء طلبك رقم";
		//$text = "Your order #".$id." is cancelled. Reason: ".$_POST['cancel_reason'];
		
		if($result['device_type']=='iphone')
		$result=sendToIphone($device_token,$text,$type,$badge);
		else
		$result=sendToAndroid($device_token,$text,$type,$badge);	
		
		$postdata_noti['notText'] = $text;
		$postdata_noti['notTypeId'] = '15';
		$postdata_noti['createdTimestamp'] = $currtime;
		$autoId = $dbfunction->InsertQuery( "tbl_notification" , $postdata_noti );	
		$lastid = $dbfunction->getLastInsertedId();	
		
		$postdata_noti_his['notId'] = $lastid;
		$postdata_noti_his['custId'] = $custId;
		$postdata_noti_his['send_time'] = $currtime;
		$postdata_noti_his['createdTimestamp'] = $currtime;
		$autoId = $dbfunction->InsertQuery( "tbl_notification_history" , $postdata_noti_his );	*/
		
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
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <title><?php  echo "Join Request(s) :: Admin :: ".SITE_NAME; ?></title>
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
					<h1 class="page-heading">Join Request(s) <small></small></h1>
					
					<!--<span class="pull-right" ><a href="add_cust.php" class="btn btn-icon btn-primary glyphicons" title="Add Captain">Add Captain</a></span>-->
					<!-- End page heading -->
					<!-- Begin breadcrumb -->
					<ol class="breadcrumb default square rsaquo sm">
						<li><a href="dashboard.php"><i class="fa fa-home"></i></a></li>
						<li><a href="captain_list.php">Join Captain</a></li>
						<li class="active">View</li>
						
					</ol>
					
					<!-- End breadcrumb -->
					<?php
							if (isset($_GET["suc"]) && $_GET["suc"] != "")
                            {
					
								$success = $converter->decode($_GET["suc"]);
									if ($success == 1)
                                {
									echo '<script> getMessage("success","'.str_replace("{modulename}", "Captain", $lang["updatemessage-status"]).'"); </script>';
								}
								elseif ($success == 2)
                                {
									
									echo '<script> getMessage("error","'.str_replace("{modulename}", "Captain", $lang["deletemodulemessage"]).'"); </script>';
								}
								elseif ($success == 3)
                                {
									
									echo '<script> getMessage("error","'.str_replace("{modulename}", "Captain", $lang["deletemodulemessage"]).'"); </script>';
								}
								elseif ($success == 4)
                                {
									
									echo '<script> getMessage("success","'.str_replace("{modulename}", "Captain", $lang["addmodulemessage"]).'"); </script>';
								}
								elseif ($success == 5)
                                {
									
									echo '<script> getMessage("success","'.str_replace("{modulename}", "Captain", $lang["updatemodulemessage"]).'"); </script>';
								}
								elseif ($success == 6)
                                {
									
									echo '<script> getMessage("success","Points Redeemed Successfully"); </script>';
								}
							}
						?>
						
					<!-- BEGIN DATA TABLE -->
					<div class="the-box">
						<div class="table-responsive">
					
						<table class="table table-striped table-hover display" id="dt-list" >
							<thead class="the-box dark full">
								<tr>
									<!--<th><span class="uniformjs"><input type="checkbox" id="selectall" value="0" name="selectall"  /></span> </th>-->
									<th>ID</th>
									<th>Full Name</th>
									<th>City</th>
									<th>Phone</th>
									<th>Requested on</th>
									<th>Status</th>
									<th>Action</th>
								</tr>
							</thead>
							
						</table>
						
						<!--<button id="btnMultiDelete" class="btn btn-xs btn-danger" >Delete</button>
						<a class="notDialog" href="#notModel" data-toggle="modal" style="text-decoration:underline">
						<button id="btnMultiNot" class="btn btn-xs btn-success" >Send Notification</button>
						</a>>-->
						<!--<a class="addMoneyModelDialog" href="#addMoneyModel" data-toggle="modal">
						<button id="btnMultiNot" class="btn btn-xs btn-success" >Add Balance</button>
						</a>-->
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
				<h3 class="modal-title" id="viewModel">Captain Info</h3>
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
		<div class="modal fade" id="pointsModel" tabindex="-1" role="dialog" aria-labelledby="pointsModel" aria-hidden="true">
		  <div class="modal-dialog">
			<div class="modal-content">
			
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h3 class="modal-title" id="pointsModel">Redeem Points</h3>
			  </div>
			  <div class="panel-body" id="divRedeemPoints"> 
			  
			  </div>
			  <div class="modal-footer">
			  <!--<button data-bb-handler="success" type="button" class="btn btn-purple">Save</button>-->
			  <button data-bb-handler="danger" data-dismiss="modal" type="button" class="btn btn-black">Close</button>
			  </div>
			  
			</div><!-- /.modal-content -->
		  </div><!-- /.modal-dialog -->
		</div><!-- /.modal -->

		<!-- Modal End -->
		<!-- Reduce money Modal -->
		<div class="modal fade" id="reduceMoneyModel" tabindex="-1" role="dialog" aria-labelledby="reduceMoneyModel" aria-hidden="true">
		  <div class="modal-dialog">
			<div class="modal-content">
			
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h3 class="modal-title" id="reduceMoneyModel">Deduct Balance</h3>
			  </div>
			  <div class="panel-body" id="divReduseMOney"> 
			  
			  </div>
			  <div class="modal-footer">
			  <!--<button data-bb-handler="success" type="button" class="btn btn-purple">Save</button>-->
			  <button data-bb-handler="danger" data-dismiss="modal" type="button" class="btn btn-black">Close</button>
			  </div>
			  
			</div><!-- /.modal-content -->
		  </div><!-- /.modal-dialog -->
		</div><!-- /.modal -->

		<!--  Reduce money Modal End -->
		
		<!-- Notification Modal -->
		<div class="modal fade" id="notModel" tabindex="-1" role="dialog" aria-labelledby="notModel" aria-hidden="true">
		  <div class="modal-dialog">
			<div class="modal-content">
			
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h3 class="modal-title" id="notModel">Notification</h3>
			  </div>
			  <div class="panel-body" id="divNotification"> 
			  
			  </div>
			  <div class="modal-footer">
			  <!--<button data-bb-handler="success" type="button" class="btn btn-purple">Save</button>-->
			  <button data-bb-handler="danger" data-dismiss="modal" type="button" class="btn btn-black">Close</button>
			  </div>
			  
			</div><!-- /.modal-content -->
		  </div><!-- /.modal-dialog -->
		</div><!-- /.modal -->

		<!-- Notification Modal End-->
		<!-- Add Balance Modal -->
		<div class="modal fade" id="addMoneyModel" tabindex="-1" role="dialog" aria-labelledby="addMoneyModel" aria-hidden="true">
		  <div class="modal-dialog">
			<div class="modal-content">
			
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h3 class="modal-title" id="addMoneyModel">Add Balance</h3>
			  </div>
			  <div class="panel-body" id="divAddMoney"> 
			  
			  </div>
			  <div class="modal-footer">
			  <!--<button data-bb-handler="success" type="button" class="btn btn-purple">Save</button>-->
			  <button data-bb-handler="danger" data-dismiss="modal" type="button" class="btn btn-black">Close</button>
			  </div>
			  
			</div><!-- /.modal-content -->
		  </div><!-- /.modal-dialog -->
		</div><!-- /.modal -->

		<!-- Add Balance Modal End-->
		
		<script>
		var gridUrl = "join_captain_grid.php";
		var viewUrl = "captain_view.php";
		var actionUrl = "captain_action.php";
		var targetsCols = [3,4,5,6];
		var orderCols = [[0,"desc"]];
		var buttonValue = ['excel', 'pdf'];
			$(document).ready(function() {
			
				$(document).on("click", ".pointsDialog", function () {
					var id = $(this).data('id');
					 var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("divRedeemPoints").innerHTML = xmlhttp.responseText;
					}
					};
					
					xmlhttp.open("GET", "cust_redeem_points.php?id=" + id, true);
					xmlhttp.send();
				});	
				
				<!------reduce money------------->
				$(document).on("click", ".reduceMoneyDialog", function () {
					var id = $(this).data('id');
					 var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("divReduseMOney").innerHTML = xmlhttp.responseText;
					}
					};
					
					xmlhttp.open("GET", "remove_money_dialog.php?id=" + id, true);
					xmlhttp.send();
				});	
				
				<!-- Notification Modal End-->
				$(document).on("click", ".addMoneyModelDialog", function () {
					
					var id = $(this).data('id');
					 var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("divAddMoney").innerHTML = xmlhttp.responseText;
					}
					};
					
					xmlhttp.open("GET", "add_money_dialog.php", true);
					xmlhttp.send();
					
				});
					<!-- Notification Modal End-->
					$(document).on("click", "#btnAddAmountSend", function () {
					
					var amountText = $("#amountText").val().trim();
					
					if(amountText=="")
					{
						$("#amountText").css('border-color', 'red');
						return false;
					}	
					
				if( $('.deleteRow:checked').length > 0 ){  // at-least one checkbox checked
						
									var ids = [];
									$('.deleteRow').each(function(){
										if($(this).is(':checked')) { 
											ids.push($(this).val());
										}
									});
									var ids_string = ids.toString();  // array to string conversion 
									$.ajax({
										type: "POST",
										url: "cust_money.php",
										data: {data_ids:ids_string,amountText:amountText,"action":"add_money"},
										success: function(result) {
											dataTable.draw(false); // redrawing datatable
											$('#selectall').attr('checked', false); // Unchecks it
											getMessage("success","Balance Added Successfully");
										},
										async:false
									});
								
						}
						else
						{
							bootbox.alert("Please check at least one checkbox");	
							return false;
						}
				
				});
				
					<!-- Notification Modal End-->
				$(document).on("click", ".notDialog", function () {
					
					var id = $(this).data('id');
					 var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("divNotification").innerHTML = xmlhttp.responseText;
					}
					};
					
					xmlhttp.open("GET", "cust_not_dialog.php", true);
					xmlhttp.send();
					
				});
					<!-- Notification Modal End-->
					
				$(document).on("click", "#btnSend", function () {
					
					var not_type_id = $("#not_type_id").val().trim();
					var not_text = $("#not_text").val().trim();
					if(not_type_id=="")
					{
						$("#not_type_id").css('border-color', 'red');
						return false;
					}
					if(not_text=="")
					{
						$("#not_text").css('border-color', 'red');
						$("#not_type_id").css('border-color', '');
						return false;
					}	
					
				if( $('.deleteRow:checked').length > 0 ){  // at-least one checkbox checked
						
									var ids = [];
									$('.deleteRow').each(function(){
										if($(this).is(':checked')) { 
											ids.push($(this).val());
										}
									});
									var ids_string = ids.toString();  // array to string conversion 
									$.ajax({
										type: "POST",
										url: "cust_not_send.php",
										data: {data_ids:ids_string,not_type_id:not_type_id,not_text:not_text,"action":"notification"},
										success: function(result) {
											dataTable.draw(false); // redrawing datatable
											$('#selectall').attr('checked', false); // Unchecks it
											getMessage("success","Notification Send Successfully");
										},
										async:false
									});
								
						}
						else
						{
							bootbox.alert("Please check at least one checkbox");	
							return false;
						}
				
				});
				$(document).on("click", "#btnReduseAmountSend", function () {
					var amountText1 = $("#amountText").val().trim();
					
					if(amountText1=="")
					{
						$("#amountText").css('border-color', 'red');
						return false;
					}	
					var id = document.getElementById("customer_id").value;
					$.ajax({
					   type: "POST",
					   url: "cust_money.php",
					   data: {data_ids:id,amountText:amountText1,"action":"reduse_money"},
						success: function(result) {
							dataTable.draw(false); // redrawing datatable
							getMessage("success","Balance Deducted Successfully");
						},
						async:false
					 });
				});
				$(document).on("click", "#btnSave", function () {
					var purchase = $("#total_points").val();
					var redeem = $("#redeemPoints").val();
					var diff = redeem-purchase;
					if(diff > 0 || isNaN(redeem))
					{
						bootbox.alert("Maximum redeem points: "+purchase);
						$("#redeemPoints").css('border-color', 'red');
						return false;
					}
					});
				});
				
		</script>
		<?php include("js-css-footer.php");?> 