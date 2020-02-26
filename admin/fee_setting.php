<?php
	include("../include/config.inc.php");
	include("session.php");
	$generalFunction = new generalfunction();
	$pagename = "fee_setting";
	$pageurl = "fee_setting.php";
	$converter = new encryption();
	$dbfunction = new dbfunctions();
	//print_r($_POST);exit;
	if (isset($_POST["save"]) && $_POST["save"] != "")
    {
		for($i=0; $i <count($_POST['item_id']); $i++)
		{
			$dbfunction->UpdateQuery("tbl_fee_setting", array("from_distance" => $_POST['from_distance'][$i], "to_distance" => $_POST['to_distance'][$i], 
			"item_value" => $_POST['item_value'][$i]), "item_id=".$_POST['item_id'][$i]);
		}
		
		$generalFunction->redirect($pageurl . "?suc=" . $converter->encode("1"));
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
		<link rel="shortcut icon" type="image/x-con" href="images/favicon.ico" />
		<title><?php echo "Delivery Fee :: Admin :: ".SITE_NAME;	?></title>
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
					<h1 class="page-heading">Delivery Fee <small></small></h1>
					<!-- End page heading -->
					
					<!-- Begin breadcrumb -->
					<ol class="breadcrumb default square rsaquo sm">
						<li><a href="dashboard.php"><i class="fa fa-home"></i></a></li>
						
						<li class="active">Delivery Fee</a></li>
					</ol>
					<!-- End breadcrumb -->
					
					<?php
							if (isset($_GET["suc"]) && $_GET["suc"] != "")
                            {
								$success = $converter->decode($_GET["suc"]);
								if ($success == 1)
                                {
									echo $generalFunction->getSuccessMessage("Delivery fee updated.");
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
						<form  id="changepassword" class="form-horizontal" enctype="multipart/form-data" name="changepassword"  action="<?php echo $pageurl; ?>" method="post">
							<fieldset>
								<div class="form-group">
									<label class="col-lg-3 control-label"></label>
									<div class="col-lg-2">
										<label class="control-label">From Distance:</label>
									</div>
									<div class="col-lg-2">
										<label class="control-label">To Distance:</label>
									</div>
									<div class="col-lg-2">
										<label class="control-label">Delivery Fee:</label>
									</div>
									
								</div>
								
								<?php
								$dbfunction->SelectQuery("tbl_fee_setting", "*", "is_active='1' AND is_deleted='0' ORDER BY display_order");
								while($obj = $dbfunction->getFetchArray())
								{
									
								?>
                                <div class="form-group">
									<label class="col-lg-3 control-label"><?=$obj['item_title']?>:</label>
									<div class="col-lg-2">
										<input class="form-control" id="" name="from_distance[]" value="<?=$obj['from_distance']?>" type="text" maxlength="16" required />
									</div>
									<div class="col-lg-2">
										<input class="form-control" id="" name="to_distance[]" value="<?=$obj['to_distance']?>" type="text" maxlength="16" required />
									</div>
									<div class="col-lg-2">
										<input class="form-control" id="" name="item_value[]" value="<?=$obj['item_value']?>" type="text" maxlength="16" required />
									</div>
									<span class="errorstar">&nbsp;*</span>
								</div>
								<input name="item_id[]" value="<?=$obj['item_id']?>" type="hidden" required />
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