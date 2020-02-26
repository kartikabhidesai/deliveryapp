<?php
	include("../include/config.inc.php");
	include("../include/thumb.php");
	include("session.php");
	$generalFunction = new generalfunction();
	$pagename   	 = "add_recharge_package"; 
	$pageurl    	 = "add_recharge_package.php";
	$converter  	 = new encryption();
	$dbfunction 	 = new dbfunctions();
	$dbfunction1 	 = new dbfunctions();
	
	
	
	
	if (isset($_GET["package_id"]) && $_GET["package_id"] != "")
    {
		//$_SESSION["recharge_package_paging"] = '"draw" : false,	"bProcessing": true,"bServerSide": true,"bStateSave": true,' ;
		$id = $_GET["package_id"];
		$dbfunction->SelectQuery("tbl_recharge_packages", "tbl_recharge_packages.*",$dbfunction->db_safe("tbl_recharge_packages.package_id ='%1'", $converter->decode($id),'0'));
		$objsel = $dbfunction->getFetchArray();
		$package_id = stripslashes(trim($objsel["package_id"]));
		$package_name = stripslashes(trim($objsel["package_name"]));
		$package_name_ar = stripslashes(trim($objsel["package_name_ar"]));
		$recharge_amount = stripslashes(trim($objsel["recharge_amount"]));
		$extra_discount_perc = stripslashes(trim($objsel["extra_discount_perc"]));
		$total_amount = stripslashes(trim($objsel["total_amount"]));
		$display_order = stripslashes(trim($objsel["display_order"]));
		$is_active = stripslashes($objsel["is_active"]);
		//$action = stripslashes($objsel["action"]);
	}
	else
	{
		$sql = mysqli_query($dbConn,"SELECT MAX(`display_order`) AS max_order FROM `tbl_recharge_packages` WHERE `is_deleted`='0'");
		$row = mysqli_fetch_assoc($sql);
		$display_order = $row['max_order']+1;
	}
	
	if (isset($_POST["save"]) && $_POST["save"] != "")
    {
		$myfilter   = new inputfilter();
		$id = $myfilter->process($_POST["id"]);
		$package_name =  $myfilter->process(trim($_POST["package_name"]));
		$package_name_ar =  $myfilter->process(trim($_POST["package_name_ar"]));
		$recharge_amount =  $myfilter->process(trim($_POST["recharge_amount"]));
		$extra_discount_perc =  $myfilter->process(trim($_POST["extra_discount_perc"]));
		$total_amount =  $myfilter->process(trim($_POST["total_amount"]));
		$display_order =  $myfilter->process(trim($_POST["display_order"]));
		
		$is_active = $myfilter->process($_POST["is_active"]);
		$action 	= $myfilter->process($_POST["action"]);
	
		
		$dbfunction->SelectQuery("tbl_recharge_packages", "tbl_recharge_packages.package_id","recharge_amount ='$recharge_amount' AND extra_discount_perc='$extra_discount_perc' AND is_deleted='0'");
		$objsel = $dbfunction->getFetchArray();
		//print_r($action);exit;
		if ($action =="add" && $objsel["package_id"]!="")
        {
			$error1 = "1";
			$errormessage1 = "Package already exist";
		}
		elseif ($action =="edit" && $objsel["package_id"]!="" && $objsel["package_id"]!=$id)
        {
			$error1 = "1";
			$errormessage1 = "Package already exist";
		}
		/*elseif ($package_name == "")
        {
			$error1 = "1";
			$errormessage1 = "Please enter bank name";
		}
		elseif ($package_name_ar == "")
        {
			$error1 = "1";
			$errormessage1 = "Please enter bank name in arabic";
		}*/
		elseif ($extra_discount_perc == "")
        {
			$error1 = "1";
			$errormessage1 = "Please select type";
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
			
			function ProcessedImage($recharge_package_image,$fieldname)
			{
				global $generalFunction;
				if ($recharge_package_image != "")
				{
					if ($generalFunction->validAttachment($recharge_package_image))
					{
						ini_set('max_execution_time', '999999');
						$orgfile_name = $recharge_package_image;
						$image = $_FILES[$fieldname]['size'];
						$ext1 = $generalFunction->getExtention($orgfile_name);
						$file_name = $generalFunction->getFileName($orgfile_name);
						$new_filename = $generalFunction->validFileName($file_name);
						$tmp_file = $_FILES[$fieldname]['tmp_name'];
						$recharge_package_image = $new_filename . time() . "." . $ext1;
					    if($fieldname == "recharge_package_image"){
						$original = "../uploads/Recharge Packages/" . $recharge_package_image;
						$path = "../uploads/Recharge Packages/";
						}
						$size = 112097152;
						if ($image > $size)
						{
							echo $Messages = "File Size should not be more than 2 mb";
							exit;
						}
						if (!move_uploaded_file($tmp_file, $original))
						{
							echo $Messages = "File not uploaded";
							exit;
						}
						else
						{
							createthumb($original, $path.'200/'.$recharge_package_image,200,200);
							createthumb($original, $path.'150/'.$recharge_package_image,150,150);
							createthumb($original, $path.'100/'.$recharge_package_image,100,100);
							createthumb($original, $path.'60/'.$recharge_package_image,60,60);
						}
					}
				}
				else
				{
					$recharge_package_image = $_POST['hdn_image'];
				}
				return $recharge_package_image;
			}
			
			$profileimage = $_FILES['recharge_package_image']['name'];
			$hdn_image = $_POST['hdn_image'];
			//$recharge_package_image = ProcessedImage($profileimage,"recharge_package_image");
			
			if($action == "add")
            {
				
				$dbfunction1->SelectQuery("tbl_recharge_packages", "tbl_recharge_packages.package_id","`display_order` ='$display_order' AND is_deleted='0'");
				$objsel1 = $dbfunction1->getFetchArray();
				if(!empty($objsel1))
				{
					mysqli_query($dbConn,"SET @`display_order`:='$display_order';");
					mysqli_query($dbConn,"update tbl_recharge_packages set `display_order`=@`display_order`:=@`display_order`+1 where `display_order` >='$display_order' AND is_deleted='0'");
				}
				
				$dbfunction->InsertQuery("tbl_recharge_packages", array("display_order"=>$display_order,"package_name"=>$package_name,"package_name_ar"=>$package_name_ar,"recharge_amount"=>$recharge_amount,"extra_discount_perc"=>$extra_discount_perc,"total_amount"=>$total_amount,"is_active"=>'1',"created_on"=>date('Y-m-d H:i:s')));
				// $lastInsertId = $dbfunction->getLastInsertedId();
				// $dbfunction->InsertQuery("tbl_user_garage", array("id" => $lastInsertId, "OfferDesc" => $OfferDesc,"OfferImage"=>$SiteLogo2,"IsActivate" => $Status));
				$urltoredirect = "recharge_package_list.php?suc=" . $converter->encode("4");
				$generalFunction->redirect($urltoredirect);
			}
			else
            {
				$dbfunction1->SelectQuery("tbl_recharge_packages", "tbl_recharge_packages.package_id","`display_order` ='$display_order' AND is_deleted='0' AND package_id!='" .$id . "'");
				$objsel1 = $dbfunction1->getFetchArray();
				if(!empty($objsel1))
				{
					mysqli_query($dbConn,"SET @`display_order`:='$display_order';");
					mysqli_query($dbConn,"update tbl_recharge_packages set `display_order`=@`display_order`:=@`display_order`+1 where `display_order` >='$display_order' AND is_deleted='0'");
				}
				
				$updatearray =array("display_order"=>$display_order,"package_name"=>$package_name,"package_name_ar"=>$package_name_ar,"recharge_amount"=>$recharge_amount,"extra_discount_perc"=>$extra_discount_perc,"total_amount"=>$total_amount,"is_active"=>$is_active,"updated_on"=>date('Y-m-d H:i:s'));
				$dbfunction->UpdateQuery("tbl_recharge_packages", $updatearray, "package_id='" .$id . "'");
				$urltoredirect = "recharge_package_list.php" . ($urladd != "" ? $urladd . "&suc=" . $converter->encode("5") : "?suc=" . $converter->encode("5"));
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
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<link rel="shortcut icon" type="image/x-con" href="images/favicon.ico" />
		<title><?php
			if ($id)
			{
				echo "Edit Recharge Package :: Admin :: ".SITE_NAME;
			}
			else
			{
				echo "Add Recharge Package :: Admin :: ".SITE_NAME;
			}
		?></title>
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
					<h1 class="page-heading"><?php echo $id==""?"Add":"Edit";?> Recharge Package <small></small></h1>
					<!-- End page heading -->
					<span class="pull-right" ><a href="recharge_package_list.php" class="btn btn-icon btn-primary glyphicons" title="View Recharge Packages"><i class="icon-plus-sign"></i>View Recharge Packages</a></span>
					<!-- Begin breadcrumb -->
					<ol class="breadcrumb default square rsaquo sm">
						<li><a href="dashboard.php"><i class="fa fa-home"></i></a></li>
						<li><a href="recharge_package_list.php">Recharge Package</a></li>
						<li class="active"><?php echo $id==""?"Add":"Edit";?></li>
					</ol>
					<!-- End breadcrumb -->
					
					<?php
							if (isset($error) && $error == "1")
							{
								echo $generalFunction->getErrorMessage($errormessage);
							}
							
							if (isset($error1) && $error1 == "1")
							{
								echo $generalFunction->getErrorMessage($errormessage1);
							}
						?>
						
					<div class="the-box">
						<form  id="addcategory" class="form-horizontal" enctype="multipart/form-data" name="addcategory"  action="<?php echo $pageurl; ?>" method="post">
							<fieldset>
								
								
								
							<!--	<div class="form-group">
									<label class="col-lg-3 control-label"></label>
									<div class="col-lg-3">
										<label class="col-lg-3 control-label">en</label>
									</div>
									<div class="col-lg-3">
										 <label class="col-lg-3 control-label">ar</label>
									</div>
								</div>	
                               <div class="form-group">
									<label class="col-lg-3 control-label">Recharge Package Name:</label>
									<div class="col-lg-3">
										<input type="text" class="form-control" name="package_name" value="<?php echo (isset($package_name) ? $package_name : ""); ?>" required />
									</div>
									<div class="col-lg-3">
										<input type="text" class="form-control" name="package_name_ar" value="<?php echo (isset($package_name_ar) ? $package_name_ar : ""); ?>" required />
									</div>
									<span class="errorstar">&nbsp;*</span>
								</div>-->
								
								<div class="form-group">
									<label class="col-lg-3 control-label">Package Amount:</label>
									<div class="col-lg-6">
										<input type="number" class="form-control" name="recharge_amount" value="<?php echo (isset($recharge_amount) ? $recharge_amount : ""); ?>" min="1" required />
									</div>
									<span class="errorstar">&nbsp;*</span>
								</div>
										
								<div class="form-group">
									<label class="col-lg-3 control-label">Extra Discount Percent:</label>
									<div class="col-lg-6">
										<input type="number" class="form-control" name="extra_discount_perc" value="<?php echo (isset($extra_discount_perc) ? $extra_discount_perc : ""); ?>" min="1" required />
									</div>
									<span class="errorstar">&nbsp;*</span>
								</div>
								
								<div class="form-group">
									<label class="col-lg-3 control-label">Total Amount:</label>
									<div class="col-lg-6">
										<input type="number" class="form-control" name="total_amount" value="<?php echo (isset($total_amount) ? $total_amount : ""); ?>" min="1" required />
									</div>
									<span class="errorstar">&nbsp;*</span>
								</div>
								
								
								<div class="form-group">
									<label class="col-lg-3 control-label">Sequence No.:</label>
									<div class="col-lg-6">
										<input type="number" class="form-control" name="display_order" value="<?php echo (isset($display_order) ? $display_order : ""); ?>" min="0" required />
									</div>
									<span class="errorstar">&nbsp;*</span>
								</div>
								<div class="form-group">
									<label class="col-lg-3 control-label">Status:</label>
									<div class="col-lg-6">
										<div class="checkbox"><label><input type="checkbox" value="1" class="checkboxvalue" name="is_active" id="is_active" <?php echo (((isset($is_active) && $is_active == "1") || $is_active == "") ? "checked" : ""); ?> />&nbsp;</label></div>
									</div>
								</div>
								
								<!-- Form actions -->
								<div class="form-group">
								<div class="col-lg-9 col-lg-offset-3">
									<?php
											if ($_GET["package_id"] != "")
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
									<span class="span2"><button type="button" onClick="javascript: window.location.href = 'recharge_package_list.php<?php echo $cancelurl; ?>'" class="btn btn-primary" title="Cancel">Cancel</button></span>
								</div>
								</div>
								
							</fieldset>
									<input type="hidden" name="save" id="save" value="submit" />
									<input type="hidden" name="id" id="id" value="<?php echo (isset($package_id) ? $package_id : ""); ?>" />
									<input type="hidden" name="action" id="action" value="<?php echo ((isset($_GET["package_id"]) || $action == "edit") ? "edit" : "add"); ?>" />
									
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