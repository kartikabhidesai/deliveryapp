<?php
	include("../include/config.inc.php");
	include("session.php");
	$generalFunction = new generalfunction();
	$pagename = "general_settings";
	$pageurl = "general_settings.php";
	$converter = new encryption();
	$dbfunction = new dbfunctions();
	//print_r($_POST);exit;
	if (isset($_POST["save"]) && $_POST["save"] != "")
    {
		$dbfunction->UpdateQuery("tbl_settings", array("item_value" => mysqli_real_escape_string($dbConn, $_POST['item_value_1'])), "item_key='customer_point'");
		$dbfunction->UpdateQuery("tbl_settings", array("item_value" => mysqli_real_escape_string($dbConn, $_POST['item_value_2'])), "item_key='captain_point'");
		$dbfunction->UpdateQuery("tbl_settings", array("item_value" => mysqli_real_escape_string($dbConn, $_POST['item_value_3'])), "item_key='address'");
		$dbfunction->UpdateQuery("tbl_settings", array("item_value" => mysqli_real_escape_string($dbConn, $_POST['item_value_4'])), "item_key='address_lat'");
		$dbfunction->UpdateQuery("tbl_settings", array("item_value" => mysqli_real_escape_string($dbConn, $_POST['item_value_5'])), "item_key='address_lng'");
		$dbfunction->UpdateQuery("tbl_settings", array("item_value" => mysqli_real_escape_string($dbConn, $_POST['item_value_6'])), "item_key='email_id'");
		$dbfunction->UpdateQuery("tbl_settings", array("item_value" => mysqli_real_escape_string($dbConn, $_POST['item_value_7'])), "item_key='twitter_link'");
		$dbfunction->UpdateQuery("tbl_settings", array("item_value" => mysqli_real_escape_string($dbConn, $_POST['item_value_8'])), "item_key='instagram_link'");
		$dbfunction->UpdateQuery("tbl_settings", array("item_value" => mysqli_real_escape_string($dbConn, $_POST['item_value_9'])), "item_key='fb_messenger_link'");
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
		<title><?php echo "Settings :: Admin :: ".SITE_NAME;	?></title>
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
					<h1 class="page-heading">Settings <small></small></h1>
					<!-- End page heading -->
					
					<!-- Begin breadcrumb -->
					<ol class="breadcrumb default square rsaquo sm">
						<li><a href="dashboard.php"><i class="fa fa-home"></i></a></li>
						
						<li class="active">Settings</a></li>
					</ol>
					<!-- End breadcrumb -->
					
					<?php
							if (isset($_GET["suc"]) && $_GET["suc"] != "")
                            {
								$success = $converter->decode($_GET["suc"]);
								if ($success == 1)
                                {
									echo $generalFunction->getSuccessMessage("Settings value updated.");
								}
							}
						?>
                        <?php
							if (isset($error) && $error == "1")
                            {
								echo $generalFunction->getErrorMessage("Please enter correct value.");
							}
						?>
						<?php
						$dbfunction->SelectQuery("tbl_settings", "item_value,item_key,item_id", "is_active='1' AND is_deleted='0'");
						while($objcheck = $dbfunction->getFetchArray())
						{
							if($objcheck['item_key'] == 'customer_point')
								$item_value_1 = $objcheck['item_value'];
							elseif($objcheck['item_key'] == 'captain_point')
								$item_value_2 = $objcheck['item_value'];
							elseif($objcheck['item_key'] == 'address')
								$item_value_3 = $objcheck['item_value'];
							elseif($objcheck['item_key'] == 'address_lat')
								$item_value_4 = $objcheck['item_value'];
							elseif($objcheck['item_key'] == 'address_lng')
								$item_value_5 = $objcheck['item_value'];
							elseif($objcheck['item_key'] == 'email_id')
								$item_value_6 = $objcheck['item_value'];
							elseif($objcheck['item_key'] == 'twitter_link')
								$item_value_7 = $objcheck['item_value'];
							elseif($objcheck['item_key'] == 'instagram_link')
								$item_value_8 = $objcheck['item_value'];
							elseif($objcheck['item_key'] == 'fb_messenger_link')
								$item_value_9 = $objcheck['item_value'];
						}
						?>
					<div class="the-box">
						<form  id="changepassword" class="form-horizontal" enctype="multipart/form-data" name="changepassword"  action="<?php echo $pageurl; ?>" method="post">
							<fieldset>
                                <div class="form-group">
									<label class="col-lg-3 control-label">Customer get 1 point per <?=CURRENCY_SIGN?></label>
									<div class="col-lg-5">
										<input class="form-control" id="" name="item_value_1" value="<?=$item_value_1?>" type="number" maxlength="16" required /> 
									</div>
									
									<span class="errorstar">&nbsp;*</span>
								</div>
								
								<div class="form-group">
									<label class="col-lg-3 control-label">Captain get 1 point per <?=CURRENCY_SIGN?></label>
									<div class="col-lg-5">
										<input class="form-control" id="" name="item_value_2" value="<?=$item_value_2?>" type="number" maxlength="16" required /> 
									</div>
									
									<span class="errorstar">&nbsp;*</span>
								</div>
								<div class="form-group">
									<label class="col-lg-3 control-label">Address</label>
									<div class="col-lg-5">
										<input class="form-control" id="" name="item_value_3" value="<?=$item_value_3?>" type="text"  required /> 
									</div>
									<span class="errorstar">&nbsp;*</span>
								</div>
								<div class="form-group">
									<label class="col-lg-3 control-label">Address Latitude</label>
									<div class="col-lg-5">
										<input class="form-control" id="" name="item_value_4" value="<?=$item_value_4?>" type="text"  required /> 
									</div>
									<span class="errorstar">&nbsp;*</span>
								</div>
								<div class="form-group">
									<label class="col-lg-3 control-label">Address Longitude</label>
									<div class="col-lg-5">
										<input class="form-control" id="" name="item_value_5" value="<?=$item_value_5?>" type="text"  required /> 
									</div>
									<span class="errorstar">&nbsp;*</span>
								</div>
								<div class="form-group">
									<label class="col-lg-3 control-label">Email Id</label>
									<div class="col-lg-5">
										<input class="form-control" id="" name="item_value_6" value="<?=$item_value_6?>" type="text"  required /> 
									</div>
									<span class="errorstar">&nbsp;*</span>
								</div>
								<div class="form-group">
									<label class="col-lg-3 control-label">Twitter Link</label>
									<div class="col-lg-5">
										<input class="form-control" id="" name="item_value_7" value="<?=$item_value_7?>" type="text"  required /> 
									</div>
									<span class="errorstar">&nbsp;*</span>
								</div>
								<div class="form-group">
									<label class="col-lg-3 control-label">Instagram Link</label>
									<div class="col-lg-5">
										<input class="form-control" id="" name="item_value_8" value="<?=$item_value_8?>" type="text"  required /> 
									</div>
									<span class="errorstar">&nbsp;*</span>
								</div>
								<div class="form-group">
									<label class="col-lg-3 control-label">Facebook Link</label>
									<div class="col-lg-5">
										<input class="form-control" id="" name="item_value_9" value="<?=$item_value_9?>" type="text"  required /> 
									</div>
									<span class="errorstar">&nbsp;*</span>
								</div>
								
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