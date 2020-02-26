<?php
	include("../include/config.inc.php");
	include("session.php");
	$generalFunction = new generalfunction();
	$pagename = "app_settings";
	$pageurl = "app_settings.php";
	$converter = new encryption();
	$dbfunction = new dbfunctions();
	if (isset($_POST["save"]) && $_POST["save"] != "")
    {
		
		$error = "0";
		$dbfunction->UpdateQuery("tbl_districts", array("deliveryFee" => $_POST["delivery_fee_per_sr"]), "deliveryFee!='0'");
		$dbfunction->UpdateQuery("tbl_settings", array("value" => $_POST["points_per_sr"]), "type='points_per_sr'");
		$dbfunction->UpdateQuery("tbl_settings", array("value" => $_POST["delivery_fee_per_sr"]), "type='delivery_fee_per_sr'");
		$dbfunction->UpdateQuery("tbl_settings", array("value" => $_POST["subscribers_discount"],"description" => $_POST["subscribers_discount_type"]), "type='subscribers_discount'");
		$dbfunction->UpdateQuery("tbl_settings", array("value" => $_POST["unsubscribers_discount"],"description" => $_POST["unsubscribers_discount_type"]), "type='unsubscribers_discount'");
		$generalFunction->redirect($pageurl . "?suc=" . $converter->encode("1"));
		
	}
	//$dbfunction->SelectQuery("tbl_settings", "*", "status=1");
	$dbfunction = mysqli_query($dbConn, "SELECT * FROM tbl_settings WHERE status='1'");
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
		<link rel="shortcut icon" type="image/x-con" href="images/favicon.ico" />
		<title><?php echo "Setting :: Admin :: ".SITE_NAME;	?></title>
		<?php include("js-css-head.php"); ?>
		<?php include("meta-settings.php"); ?>
	</head>
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
					<h1 class="page-heading">App Setting<small></small></h1>
					<!-- End page heading -->
					
					<!-- Begin breadcrumb -->
					<ol class="breadcrumb default square rsaquo sm">
						<li><a href="dashboard.php"><i class="fa fa-home"></i></a></li>
						
						<li class="active">App Setting</a></li>
					</ol>
					<!-- End breadcrumb -->
					
					<?php
							if (isset($_GET["suc"]) && $_GET["suc"] != "")
                            {
								$success = $converter->decode($_GET["suc"]);
								if ($success == 1)
                                {
									echo $generalFunction->getSuccessMessage("Updated successfully.");
								}
							}
						?>
                        <?php
							if (isset($error) && $error == "1")
                            {
								echo $generalFunction->getErrorMessage("Please enter correct value.");
							}
						?>
						
					<div class="the-box">
						<form  id="addFrm" class="form-horizontal" enctype="multipart/form-data" name="addFrm"  action="<?php echo $pageurl; ?>" method="post">
							<fieldset>
								<?php
								// while ($objsel = $dbfunction->getFetchArray()) 
								while ($objsel = mysqli_fetch_assoc($dbfunction))
								{ ?>
                                <div class="form-group">
									<label class="col-lg-3 control-label"><?php echo $objsel["title"]; ?>:</label>
									<?php if($objsel["type"]=='subscribers_discount' || $objsel["type"]=='unsubscribers_discount') { ?>
									<div class="col-lg-5" style="width:25%">
										<input class="form-control" id="<?php echo $objsel["type"]; ?>" name="<?php echo $objsel["type"]; ?>" type="number" value="<?php echo $objsel["value"]; ?>" maxlength="16" min="0" required />
									</div>
									<div class="col-lg-5" style="width:16.5%">
									<select class="form-control" id="<?php echo $objsel["type"]; ?>_type" name="<?php echo $objsel["type"]; ?>_type" required>
									<option value="flat" <?php if($objsel["description"]=='flat') { ?> selected <?php } ?>>Flat</option>
									<option value="percent" <?php if($objsel["description"]=='percent') { ?> selected <?php } ?> >Percent</option>
									</select>
									
									</div>
									<?php }else{ ?>
									<div class="col-lg-5">
										<input class="form-control" id="<?php echo $objsel["type"]; ?>" name="<?php echo $objsel["type"]; ?>" type="number" value="<?php echo $objsel["value"]; ?>" maxlength="16" min="1" required />
									</div>
									<?php } ?>
									<span class="errorstar">&nbsp;*</span>
								</div>
								
								<?php } ?>
								<!-- Form actions -->
								<div class="form-group">
								<div class="col-lg-9 col-lg-offset-3">
									
									
									<button type="submit" name="save1" id="save1" value="submit1" class="btn btn-primary" title="Save" tabindex="19">Update</button>
								
								</div>
								</div>
							</fieldset>
									<input type="hidden" name="save" id="save" value="submit" />
									
									
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