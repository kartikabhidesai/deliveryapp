<?php
	include("../include/config.inc.php");
	include("../include/thumb.php");
	include("session.php");
	$generalFunction = new generalfunction();
	$pagename   	 = "add_coupon"; 
	$pageurl    	 = "add_coupon.php";
	$converter  	 = new encryption();
	$dbfunction 	 = new dbfunctions();
	$dbfunction1 	 = new dbfunctions();
	$cancelurl = "";
	
	if (isset($_GET["coupon_id"]) && $_GET["coupon_id"] != "")
    {
		$id = $_GET["coupon_id"];
		$dbfunction->SelectQuery("tbl_coupons", "tbl_coupons.*",$dbfunction->db_safe("tbl_coupons.coupon_id ='%1'", $converter->decode($id),'0'));
		$objsel = $dbfunction->getFetchArray();
		$coupon_id = stripslashes(trim($objsel["coupon_id"]));
		$coupon_code = stripslashes(trim($objsel["coupon_code"]));
		$coupon_descr = stripslashes(trim($objsel["coupon_descr"]));
		$discount_value = stripslashes(trim($objsel["discount_value"]));
		$discount_type = stripslashes(trim($objsel["discount_type"]));
		$min_order_amt = stripslashes(trim($objsel["min_order_amt"]));
		//list($from_date,$from_time) = explode(" ",datFormat($objsel["start_time"],true));
		//list($to_date,$to_time) = explode(" ",datFormat($objsel["expiry_time"],true));
		list($from_date,$from_time) = explode(" ",$objsel["start_time"]);
		list($to_date,$to_time) = explode(" ",$objsel["expiry_time"]);
		$is_multi_use = stripslashes(trim($objsel["is_multi_use"]));
		$is_active = stripslashes($objsel["is_active"]);
	}
	
	if (isset($_POST["save"]) && $_POST["save"] != "")
    {
		
		$myfilter   = new inputfilter();
		$id = $myfilter->process($_POST["id"]);
		$coupon_code =  $myfilter->process(trim($_POST["coupon_code"]));
		$coupon_descr =  $myfilter->process(trim($_POST["coupon_descr"]));
		$discount_value =  $myfilter->process(trim($_POST["discount_value"]));
		$discount_type =  $myfilter->process(trim($_POST["discount_type"]));
		$min_order_amt =  $myfilter->process(trim($_POST["min_order_amt"]));
		//$start_time =  date('Y-m-d H:i:s',strtotime(datDefault($_POST["from_date"]).' '.$_POST["from_time"]));
		//$expiry_time =  date('Y-m-d H:i:s',strtotime(datDefault($_POST["to_date"]).' '.$_POST["to_time"]));
		$start_time =  date('Y-m-d H:i:s',strtotime(($_POST["from_date"]).' '.$_POST["from_time"]));
		$expiry_time =  date('Y-m-d H:i:s',strtotime(($_POST["to_date"]).' '.$_POST["to_time"]));
		$is_multi_use =  $myfilter->process(trim($_POST["is_multi_use"]));
		$is_active = (isset($_POST["is_active"])?$_POST["is_active"]:"0");
		$action 	= $myfilter->process($_POST["action"]);
		if ($action =="add" )
		$dbfunction->SelectQuery("tbl_coupons", "tbl_coupons.coupon_id","(coupon_code ='$coupon_code') AND is_deleted='0'");
		else
		$dbfunction->SelectQuery("tbl_coupons", "tbl_coupons.coupon_id","(coupon_code ='$coupon_code') AND coupon_id!='$id' AND is_deleted='0'");		
		$objsel = $dbfunction->getFetchArray();
		
		if ($objsel["coupon_id"]!="")
        {
			$error1 = "1";
			$errormessage1 = "Expiry Time or Discount Already Exist";
		}
		
		elseif ($coupon_code == "")
        {
			$error1 = "1";
			$errormessage1 = "Please enter coupon code";
		}
		/*elseif ($coupon_descr == "")
        {
			$error1 = "1";
			$errormessage1 = "Please enter coupon description";
		}*/
		elseif ($expiry_time == "")
        {
			$error1 = "1";
			$errormessage1 = "Please select start time";
		}
		elseif ($expiry_time == "")
        {
			$error1 = "1";
			$errormessage1 = "Please enter expiry time";
		}
		elseif ($expiry_time == "")
        {
			$error1 = "1";
			$errormessage1 = "Please select type";
		}
		elseif ($discount_value == "")
        {
			$error1 = "1";
			$errormessage1 = "Please enter discount_value"; 
		}
		elseif ($min_order_amt == "")
        {
			$error1 = "1";
			$errormessage1 = "Please enter min. order AMT";
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
				$dbfunction->InsertQuery("tbl_coupons", array("coupon_code"=>$coupon_code,"coupon_descr" =>$coupon_descr,"start_time" => $start_time,"expiry_time" => $expiry_time,"discount_type"=>$discount_type,"discount_value" => $discount_value,"min_order_amt" => $min_order_amt,"is_multi_use"=>$is_multi_use,"is_active"=>$is_active,"created_on"=>date('Y-m-d H:i:s')));
				$lastInsertId = $dbfunction->getLastInsertedId();
				$urltoredirect = "coupon_list.php?suc=" . $converter->encode("4");
				$generalFunction->redirect($urltoredirect);
			}
			else
            {
				
				
				$updatearray =array("coupon_code"=>$coupon_code,"coupon_descr" =>$coupon_descr,"start_time" => $start_time,"expiry_time" => $expiry_time,"discount_type"=>$discount_type,"discount_value" => $discount_value,"min_order_amt" => $min_order_amt,"is_multi_use"=>$is_multi_use,"is_active"=>$is_active,"updated_on"=>date('Y-m-d H:i:s'));
				$dbfunction->UpdateQuery("tbl_coupons", $updatearray, "coupon_id='" .$id . "'");
				$urltoredirect = "coupon_list.php" . ($urladd != "" ? $urladd . "&suc=" . $converter->encode("5") : "?suc=" . $converter->encode("5"));
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
				echo "Edit Coupon :: Admin :: ".SITE_NAME;
			}
			else
			{
				echo "Add Coupon :: Admin :: ".SITE_NAME;
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
					<h1 class="page-heading"><?php echo $id==""?"Add":"Edit";?> Coupon <small></small></h1>
					<!-- End page heading -->
					<span class="pull-right" ><a href="coupon_list.php" class="btn btn-icon btn-primary glyphicons" title="View Coupon"><i class="icon-plus-sign"></i>View Coupon</a></span>
					<!-- Begin breadcrumb -->
					<ol class="breadcrumb default square rsaquo sm">
						<li><a href="dashboard.php"><i class="fa fa-home"></i></a></li>
						<li><a href="coupon_list.php">Coupon</a></li>
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
									<label class="col-lg-3 control-label">Coupon Code:</label>
									<div class="col-lg-5">
										<input type="text" class="form-control" name="coupon_code" value="<?php echo (isset($coupon_code) ? $coupon_code : ""); ?>" placeholder="e.g. ABC001" required />
									</div>
									<span class="errorstar">&nbsp;*</span>
								</div>
								<div class="form-group">
									<label class="col-lg-3 control-label">Description:</label>
									<div class="col-lg-5">
										<textarea class="form-control" name="coupon_descr"><?php echo (isset($coupon_descr) ? $coupon_descr : ""); ?></textarea>
									</div>
								</div>
								
								<div class="form-group">
									<label class="col-lg-3 control-label">Start Time:</label>
									<div class="col-lg-3">
										<input type="date" class="form-control" name="from_date" value="<?php echo (isset($from_date) ? $from_date : ""); ?>" min="<?=date("Y-m-d")?>" required/>
									</div>
									<div class="col-lg-2">
										<input type="text" class="form-control timepicker" name="from_time" value="<?php echo (isset($from_time) ? $from_time : ""); ?>" required/>
									</div>
									<span class="errorstar">&nbsp;*</span>
								</div>
								
								<div class="form-group">
									<label class="col-lg-3 control-label">Expiry Time:</label>
									<div class="col-lg-3">
										<input type="date" class="form-control" name="to_date" value="<?php echo (isset($to_date) ? $to_date : ""); ?>" min="<?=date("Y-m-d")?>" required />
									</div>
									<div class="col-lg-2">
										<input type="text" class="form-control timepicker" name="to_time" value="<?php echo (isset($to_time) ? $to_time : ""); ?>" required />
									</div>
									<span class="errorstar">&nbsp;*</span>
								</div>
								
								<div class="form-group">
									<label class="col-lg-3 control-label">Discount:</label>
									<div class="col-lg-3">
										<input type="number" class="form-control" name="discount_value" value="<?php echo (isset($discount_value) ? $discount_value : ""); ?>" min="1" required />
									</div>
									<div class="col-lg-2">
										<select class="form-control" name="discount_type">
										<option value="flat" <?=($discount_type=='flat')?'selected':''?>>Flat</option>
										<option value="percent" <?=($discount_type=='percent')?'selected':''?>>Percentage</option>
										</select>
									</div>
									<span class="errorstar">&nbsp;*</span>
								</div>
								<div class="form-group">
									<label class="col-lg-3 control-label">Min. Order AMT:</label>
									<div class="col-lg-5">
										<input type="number" class="form-control" name="min_order_amt" value="<?php echo (isset($min_order_amt) ? $min_order_amt : ""); ?>" min="0" />
									</div>
								</div>

								<div class="form-group">
									<label class="col-lg-3 control-label">Multiple Use:</label>
									<div class="col-lg-5">
										<div class="checkbox"><label><input type="checkbox" value="1" class="checkboxvalue" name="is_multi_use" id="is_multi_use"<?php echo (((isset($is_multi_use) && $is_multi_use == "1") || $is_multi_use == "") ? "checked" : ""); ?> />&nbsp;</label></div>
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
											if ($_GET["coupon_id"] != "")
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
									<span class="span2"><button type="button" onClick="javascript: window.location.href = 'coupon_list.php<?php echo $cancelurl; ?>'" class="btn btn-primary" title="Cancel">Cancel</button></span>
								</div>
								</div>
								
							</fieldset>
									<input type="hidden" name="save" id="save" value="submit" />
									<input type="hidden" name="id" id="id" value="<?php echo (isset($coupon_id) ? $coupon_id : ""); ?>" />
									<input type="hidden" name="action" id="action" value="<?php echo ((isset($_GET["coupon_id"]) || $action == "edit") ? "edit" : "add"); ?>" />
									
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