<?php
	include("../include/config.inc.php");
	include("session.php");
	$generalFunction = new generalfunction();
	$pagename = "changepassword";
	$pageurl = "changepassword.php";
	$converter = new encryption();
	$dbfunction = new dbfunctions();
	//print_r($_POST);exit;
	if (isset($_POST["save"]) && $_POST["save"] != "")
    {
		$myfilter = new inputfilter();
		$currentpassword = $converter->encode($myfilter->process($_POST["currentpassword"]));
		$password = $converter->encode($myfilter->process($_POST["password"]));
		$cnfpassword = $myfilter->process($_POST["cnfpassword"]);
		// $currentpassword = "";
		$dbfunction->SelectQuery("tbl_admin", "admin_password", "admin_id='" . $_SESSION[SESSION_NAME . "userid"] . "'");
		$objcheck = $dbfunction->getFetchArray();
		$error = "0";
		
		if ($currentpassword != stripslashes($objcheck["admin_password"]))
        {
			$error = "1";
			$errormessage = "Please enter correct Current Password.";
		}
		elseif ($_POST["password"] != $_POST["cnfpassword"])
        {
			$error = "1";
			$errormessage = "New Password and Confirm New  Password should be same.";
		}
		else
        {
			$dbfunction->UpdateQuery("tbl_admin", array("admin_password" => $password), "admin_id='" . $_SESSION[SESSION_NAME . "userid"] . "'");
			$generalFunction->redirect($pageurl . "?suc=" . $converter->encode("1"));
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
		<title><?php echo "Change Password :: Admin :: ".SITE_NAME;	?></title>
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
					<h1 class="page-heading">Change Password <small></small></h1>
					<!-- End page heading -->
					
					<!-- Begin breadcrumb -->
					<ol class="breadcrumb default square rsaquo sm">
						<li><a href="dashboard.php"><i class="fa fa-home"></i></a></li>
						
						<li class="active">Change Password</a></li>
					</ol>
					<!-- End breadcrumb -->
					
					<?php
							if (isset($_GET["suc"]) && $_GET["suc"] != "")
                            {
								$success = $converter->decode($_GET["suc"]);
								if ($success == 1)
                                {
									echo $generalFunction->getSuccessMessage($lang["changepassword"]);
								}
							}
						?>
                        <?php
							if (isset($error) && $error == "1")
                            {
								echo $generalFunction->getErrorMessage("Please enter correct Current Password.");
							}
						?>
						
					<div class="the-box">
						<form  id="changepassword" class="form-horizontal" enctype="multipart/form-data" name="changepassword"  action="<?php echo $pageurl; ?>" method="post">
							<fieldset>
                                <div class="form-group">
									<label class="col-lg-3 control-label">Current Password:</label>
									<div class="col-lg-5">
										<input class="form-control" id="currentpassword" name="currentpassword" type="password" maxlength="16" required />
									</div>
									<span class="errorstar">&nbsp;*</span>
								</div>
								
								<div class="form-group">
									<label class="col-lg-3 control-label">New Password:</label>
									<div class="col-lg-5">
										<input class="form-control" id="password" name="password" type="password" value="" maxlength="16" required />
									</div>
									<span class="errorstar">&nbsp;*</span>
								</div>
								
								<div class="form-group">
									<label class="col-lg-3 control-label">Confirm New Password:</label>
									<div class="col-lg-5">
										<input class="form-control" id="cnfpassword" name="cnfpassword" type="password" maxlength="16" required />
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
		
		<?php
			/*if (isset($error) && $error == "1")
			{
				echo '<script language="javascript">HideErrorMessage();</script>';
			}*/
		?>  
		<script>
			// fancy_box();
			/*$("body").on("click", ".checkboxvalue",function() {
				if ($(this).val() == 1)
				{
					$(this).val("0");
					var vals = $(this).val();
					$("#hidden_status").val(vals);
				}
				else
				{
					$(this).val("1");
					var vals = $(this).val();
					$("#hidden_status").val(vals);
				}
			})
			$("#teamid").change(function(){
				var vals = $(this).val();
				$("#RestaurantIcon1").val(vals);
			})
			$("#profileImage").change(function(){
				var vals = $(this).val();
				$("#profileImage1").val(vals);
			})*/
		</script>
		<!--<style type="text/css">
			.fancybox-custom .fancybox-skin {
			box-shadow: 0 0 50px #222;
			}
		</style>-->
	</body>
</html>																																	