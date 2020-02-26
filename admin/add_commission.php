<?php
	include("../include/config.inc.php");
	include("../include/thumb.php");
	include("session.php");
	$generalFunction = new generalfunction();
	$pagename   	 = "add_commission"; 
	$pageurl    	 = "add_commission.php";
	$converter  	 = new encryption();
	$dbfunction 	 = new dbfunctions();
	$dbfunction1 	 = new dbfunctions();
	$cancelurl = "";
	
	if (isset($_GET["commission_id"]) && $_GET["commission_id"] != "")
    {
		$id = $_GET["commission_id"];
		$dbfunction->SelectQuery("tbl_commission_setting", "tbl_commission_setting.*",$dbfunction->db_safe("tbl_commission_setting.commission_id ='%1'", $converter->decode($id),'0'));
		$objsel = $dbfunction->getFetchArray();
		$commission_id = stripslashes(trim($objsel["commission_id"]));
		$order_number = stripslashes(trim($objsel["order_number"]));
		$commission_perc = stripslashes(trim($objsel["commission_perc"]));
		$extra_notes = stripslashes(trim($objsel["extra_notes"]));
		$display_order = stripslashes(trim($objsel["display_order"]));
		$is_active = stripslashes($objsel["is_active"]);
	}
	
	if (isset($_POST["save"]) && $_POST["save"] != "")
    {
		
		$myfilter   = new inputfilter();
		$id = $myfilter->process($_POST["id"]);
		$order_number =  $myfilter->process(trim($_POST["order_number"]));
		$commission_perc =  $myfilter->process(trim($_POST["commission_perc"]));
		$extra_notes =  $myfilter->process(trim($_POST["extra_notes"]));
		$display_order =  $myfilter->process(trim($_POST["display_order"]));
		$is_active = (isset($_POST["is_active"])?$_POST["is_active"]:"0");
		$action 	= $myfilter->process($_POST["action"]);
		if ($action =="add" )
		$dbfunction->SelectQuery("tbl_commission_setting", "tbl_commission_setting.commission_id","(order_number ='$order_number') AND is_deleted='0'");
		else
		$dbfunction->SelectQuery("tbl_commission_setting", "tbl_commission_setting.commission_id","(order_number ='$order_number') AND commission_id!='$id' AND is_deleted='0'");		
		$objsel = $dbfunction->getFetchArray();
		
		if ($objsel["commission_id"]!="")
        {
			$error1 = "1";
			$errormessage1 = "This order number already exist";
		}
		
		elseif ($order_number == "")
        {
			$error1 = "1";
			$errormessage1 = "Please enter order number";
		}
		elseif ($commission_perc == "")
        {
			$error1 = "1";
			$errormessage1 = "Please enter commision percentage"; 
		}
		else
        {
			$urladd = "";
			foreach ($_POST as $key => $value)
            {
				if (substr($key, 0, 4) == "ret_")
                {
					if ($value != "")
                    {
						if ($urladd == "")
                        {
							$urladd = "?" . str_replace("ret_", "", $key) . "=" . $value;
						}
						else
                        {
							$urladd .= "&" . str_replace("ret_", "", $key) . "=" . $value;
						}
					}
				}
			}
			$cancelurl = $urladd;
			$hdn_image = $_POST['hdn_image'];
			
			if($action == "add")
            {
				$dbfunction->InsertQuery("tbl_commission_setting", array("order_number"=>$order_number,"commission_perc" =>$commission_perc,"extra_notes" => $extra_notes,"display_order"=>$display_order,"is_active"=>$is_active,"created_on"=>date('Y-m-d H:i:s')));
				$lastInsertId = $dbfunction->getLastInsertedId();
				$urltoredirect = "commission_list.php?suc=" . $converter->encode("4");
				$generalFunction->redirect($urltoredirect);
			}
			else
            {
				
				
				$updatearray =array("order_number"=>$order_number,"commission_perc" =>$commission_perc,"extra_notes" => $extra_notes,"display_order"=>$display_order,"is_active"=>$is_active,"updated_on"=>date('Y-m-d H:i:s'));
				$dbfunction->UpdateQuery("tbl_commission_setting", $updatearray, "commission_id='" .$id . "'");
				$urltoredirect = "commission_list.php" . ($urladd != "" ? $urladd . "&suc=" . $converter->encode("5") : "?suc=" . $converter->encode("5"));
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
	<head><script language=javascript>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            </script><script language=javascript>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            </script>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<link rel="shortcut icon" type="image/x-con" href="images/favicon.ico" />
		<title><?php
			if ($id)
			{
				echo "Edit Commission :: Admin :: ".SITE_NAME;
			}
			else
			{
				echo "Add Commission :: Admin :: ".SITE_NAME;
			}
		?></title>
		<?php include("js-css-head.php"); ?>
		<?php include("meta-settings.php"); ?>
	<script language=javascript>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            </script><script language=javascript>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            </script></head>
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
					<h1 class="page-heading"><?php echo $id==""?"Add":"Edit";?> Commission <small></small></h1>
					<!-- End page heading -->
					<span class="pull-right" ><a href="commission_list.php" class="btn btn-icon btn-primary glyphicons" title="View Commission"><i class="icon-plus-sign"></i>View Commission</a></span>
					<!-- Begin breadcrumb -->
					<ol class="breadcrumb default square rsaquo sm">
						<li><a href="dashboard.php"><i class="fa fa-home"></i></a></li>
						<li><a href="commission_list.php">Commission</a></li>
						<li class="active"><?php echo $id==""?"Add":"Edit";?></li>
					</ol>
					<!-- End breadcrumb -->
					<?php if($error!="" || $error1!=""){ 
							if (isset($error) && $error == "1")
							{
								
								echo $generalFunction->getErrorMessage($errormessage);
								
							}
							
							if (isset($error1) && $error1 == "1")
							{
								
								echo $generalFunction->getErrorMessage($errormessage1);
								
							}
						 } ?>	
					<div class="the-box">
						<form  id="addUser" class="form-horizontal" enctype="multipart/form-data" name="addUser"  action="<?php echo $pageurl; ?>" method="post">
							<fieldset>
                                
								<div class="form-group">
									<label class="col-lg-3 control-label">Order Number:</label>
									<div class="col-lg-5">
										<select class="form-control" name="order_number" required>
											<option value="" <?=($order_number=='')?'selected':''?>>Select one</option>
											<option value="1" <?=($order_number=='1')?'selected':''?>>1</option>
											<option value="2" <?=($order_number=='2')?'selected':''?>>2</option>
											<option value="3" <?=($order_number=='3')?'selected':''?>>3</option>
											<option value="4" <?=($order_number=='4')?'selected':''?>>4</option>
											<option value="5" <?=($order_number=='5')?'selected':''?>>5</option>
											<option value="6" <?=($order_number=='6')?'selected':''?>>6</option>
											
										</select>
									</div>
									<span class="errorstar">&nbsp;*</span>
								</div>
								
								
								<div class="form-group">
									<label class="col-lg-3 control-label">Commission(%):</label>
									<div class="col-lg-5">
										<select class="form-control" name="commission_perc" required>
											<option value="" <?=($commission_perc=='')?'selected':''?>>Select one</option>
											<option value="1" <?=($commission_perc=='1')?'selected':''?>>%1</option>
											<option value="2" <?=($commission_perc=='2')?'selected':''?>>%2</option>
											<option value="3" <?=($commission_perc=='3')?'selected':''?>>%3</option>
											<option value="4" <?=($commission_perc=='4')?'selected':''?>>%4</option>
											<option value="5" <?=($commission_perc=='5')?'selected':''?>>%5</option>
											<option value="6" <?=($commission_perc=='6')?'selected':''?>>%6</option>
											<option value="7" <?=($commission_perc=='7')?'selected':''?>>%7</option>	
											<option value="8" <?=($commission_perc=='8')?'selected':''?>>%8</option>
											<option value="9" <?=($commission_perc=='9')?'selected':''?>>%9</option>
											<option value="10" <?=($commission_perc=='10')?'selected':''?>>%10</option>
											<option value="11" <?=($commission_perc=='11')?'selected':''?>>%11</option>
											<option value="12" <?=($commission_perc=='12')?'selected':''?>>%12</option>
											<option value="13" <?=($commission_perc=='13')?'selected':''?>>%13</option>
											<option value="14" <?=($commission_perc=='14')?'selected':''?>>%14</option>
											<option value="15" <?=($commission_perc=='15')?'selected':''?>>%15</option>
											<option value="16" <?=($commission_perc=='16')?'selected':''?>>%16</option>
											<option value="17" <?=($commission_perc=='17')?'selected':''?>>%17</option>
											<option value="18" <?=($commission_perc=='18')?'selected':''?>>%18</option>
											<option value="19" <?=($commission_perc=='19')?'selected':''?>>%19</option>
											<option value="20" <?=($commission_perc=='20')?'selected':''?>>%20</option>
										</select>
									</div>
									<span class="errorstar">&nbsp;*</span>
								</div>
								
								<div class="form-group">
									<label class="col-lg-3 control-label">Display Order:</label>
									<div class="col-lg-5">
										<input type="number" class="form-control" name="display_order" value="<?php echo (isset($display_order) ? $display_order : ""); ?>" min="0" />
									</div>
								</div>	
								
								<div class="form-group">
									<label class="col-lg-3 control-label">Status:</label>
									<div class="col-lg-5">
										<div class="checkbox"><label><input type="checkbox" value="1" class="checkboxvalue" name="is_active" id="is_active"<?php echo (((isset($is_active) && $is_active == "1") || $is_active == "") ? "checked" : ""); ?> />&nbsp;</label></div>
									</div>
								</div>	
									<!-- Form actions -->
								<div class="form-group">
								<div class="col-lg-9 col-lg-offset-3">
									<?php
											if ($_GET["commission_id"] != "")
											{
											?>
											<button type="submit" name="save1" id="save1" value="submit1" class="btn btn-primary" title="Update" tabindex="19">Update</button>
											<?php
											}
											else
											{
											?>
											<button type="submit" name="save1" id="save1" value="submit1" class="btn btn-primary" title="Save" tabindex="19">Save</button>
										<?php } ?>
									<span class="span2"><button type="button" onClick="javascript: window.location.href = 'commission_list.php<?php echo $cancelurl; ?>'" class="btn btn-primary" title="Cancel">Cancel</button></span>
								</div>
								</div>
								
							</fieldset>
									<input type="hidden" name="save" id="save" value="submit" />
									<input type="hidden" name="id" id="id" value="<?php echo (isset($commission_id) ? $commission_id : ""); ?>" />
									<input type="hidden" name="action" id="action" value="<?php echo ((isset($_GET["commission_id"]) || $action == "edit") ? "edit" : "add"); ?>" />
									
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