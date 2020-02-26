<?php
	include("../include/config.inc.php");
	include("../include/thumb.php");
	include("session.php");
	$generalFunction = new generalfunction();
	$pagename   	 = "add_cust"; 
	$pageurl    	 = "add_cust.php";
	$converter  	 = new encryption();
	$dbfunction 	 = new dbfunctions();
	$dbfunction1 	 = new dbfunctions();
	$cancelurl = "";
	if (isset($_GET["user_id"]) && $_GET["user_id"] != "")
    {
		$id = $_GET["user_id"];
		$dbfunction->SelectQuery("tbl_users", "tbl_users.*",$dbfunction->db_safe("tbl_users.user_id ='%1'", $converter->decode($id),'0'));
		$objsel = $dbfunction->getFetchArray();
		$user_id = stripslashes(trim($objsel["user_id"]));
		$full_name = stripslashes(trim($objsel["full_name"]));
		$password = stripslashes(trim(base64_decode($objsel["password"])));
		$email_id = stripslashes(trim($objsel["email_id"]));
		$phone = stripslashes(trim($objsel["phone"]));
		//$note = stripslashes(trim($objsel["note"]));
		$wallet_balance = stripslashes(trim($objsel["wallet_balance"]));
		$is_active = stripslashes($objsel["is_active"]);
	}
	
	if (isset($_POST["save"]) && $_POST["save"] != "")
    {
		
		$myfilter   = new inputfilter();
		$id = $myfilter->process($_POST["id"]);
		$full_name =  $myfilter->process(trim($_POST["full_name"]));
		$password =  $myfilter->process(trim($_POST["password"]));
		$email_id =  $myfilter->process(trim($_POST["email_id"]));
		$phone =  $myfilter->process(trim($_POST["phone"]));
		$wallet_balance =  $myfilter->process(trim($_POST["wallet_balance"]));
		$is_active = (isset($_POST["is_active"])?$_POST["is_active"]:"0");
		$action 	= $myfilter->process($_POST["action"]);
		if ($action =="add" )
		$dbfunction->SelectQuery("tbl_users", "tbl_users.user_id","(email_id ='$email_id' OR phone='$phone') AND is_deleted='0'");
		else
		$dbfunction->SelectQuery("tbl_users", "tbl_users.user_id","(email_id ='$email_id' OR phone='$phone') AND user_id!='$id' AND is_deleted='0'");		
		$objsel = $dbfunction->getFetchArray();
		
		if ($objsel["user_id"]!="")
        {
			$error1 = "1";
			$errormessage1 = "Email or Phone already exist";
		}
		
		elseif ($full_name == "")
        {
			$error1 = "1";
			$errormessage1 = "Please enter name";
		}
		elseif ($email_id == "")
        {
			$error1 = "1";
			$errormessage1 = "Please enter email";
		}
		elseif ($phone == "")
        {
			$error1 = "1";
			$errormessage1 = "Please enter phone number";
		}
		/*elseif ($username == "")
        {
			$error1 = "1";
			$errormessage1 = "Please enter username"; 
		}*/
		elseif ($password == "")
        {
			$error1 = "1";
			$errormessage1 = "Please enter password";
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
				$encode_password=base64_encode($password);
				//$dbfunction->InsertQuery("tbl_users", array("full_name" =>$full_name,"email_id" => $email_id,"cityId" =>$cityId,"districtId" => $districtId,"street" => $street,"building" => $building,"houseNo" => $houseNo,"address" => $address,"phone"=>$phone,"username" => $username,"password" => $encode_password,"wallet_balance"=>$wallet_balance,"is_active"=>$is_active,"created_on"=>date('Y-m-d H:i:s')));
				$dbfunction->InsertQuery("tbl_users", array("full_name" =>$full_name,"email_id" => $email_id,"password" => $encode_password,"wallet_balance"=>$wallet_balance,"is_active"=>$is_active,"created_on"=>date('Y-m-d H:i:s')));
				$lastInsertId = $dbfunction->getLastInsertedId();
				$urltoredirect = "captain_list.php?suc=" . $converter->encode("4");
				$generalFunction->redirect($urltoredirect);
			}
			else
            {
				$encode_password=base64_encode($password);
				//$updatearray =array("full_name" =>$full_name,"email_id" => $email_id,"cityId" =>$cityId,"districtId" => $districtId,"street" => $street,"building" => $building,"houseNo" => $houseNo,"address" => $address,"phone"=>$phone,"username" => $username,"password" => $encode_password,"wallet_balance"=>$wallet_balance,"is_active"=>$is_active,"updated_on"=>date('Y-m-d H:i:s'));
				$updatearray =array("tbl_users", array("full_name" =>$full_name,"email_id" => $email_id,"password" => $encode_password,"wallet_balance"=>$wallet_balance,"is_active"=>$is_active,"updated_on"=>date('Y-m-d H:i:s')));
				$dbfunction->UpdateQuery("tbl_users", $updatearray, "user_id='" .$id . "'");
				$urltoredirect = "captain_list.php" . ($urladd != "" ? $urladd . "&suc=" . $converter->encode("5") : "?suc=" . $converter->encode("5"));
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
				echo "Edit Customer :: Admin :: ".SITE_NAME;
			}
			else
			{
				echo "Add Customer :: Admin :: ".SITE_NAME;
			}
		?></title>
		<?php include("js-css-head.php"); ?>
		<?php include("meta-settings.php"); ?>
	</head>
	<body class="tooltips" onload="getDists(<?php echo $cityId?>,<?php echo $districtId?>)">
	
		<div class="wrapper page-content">
			<?php include("header.php"); ?>
			<!-- Sidebar menu & content wrapper -->
			    
				<!-- Sidebar Menu -->
				<?php include("leftside.php"); ?>
				<!-- // Sidebar Menu END -->
			<div id="page-content"> 
				<!-- Content -->
				<div id="content" class="container-fluid">
					<h1 class="page-heading"><?php echo $id==""?"Add":"Edit";?> Customer <small></small></h1>
					<!-- End page heading -->
					<span class="pull-right" ><a href="captain_list.php" class="btn btn-icon btn-primary glyphicons" title="View Customer"><i class="icon-plus-sign"></i>View Customer</a></span>
					<!-- Begin breadcrumb -->
					<ol class="breadcrumb default square rsaquo sm">
						<li><a href="dashboard.php"><i class="fa fa-home"></i></a></li>
						<li><a href="captain_list.php">Customer</a></li>
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
									<label class="col-lg-3 control-label">Full Name:</label>
									<div class="col-lg-5">
										<input type="text" class="form-control" name="full_name" value="<?php echo (isset($full_name) ? $full_name : ""); ?>" required />
									</div>
									<span class="errorstar">&nbsp;*</span>
								</div>

								
								<div class="form-group">
									<label class="col-lg-3 control-label">Email:</label>
									<div class="col-lg-5">
										<input type="text" class="form-control" name="email_id" value="<?php echo (isset($email_id) ? $email_id : ""); ?>" required />
									</div>
									<span class="errorstar">&nbsp;*</span>
								</div>
								
								<div class="form-group">
									<label class="col-lg-3 control-label">Phone:</label>
									<div class="col-lg-5">
										<input type="text" class="form-control" name="phone" value="<?php echo (isset($phone) ? $phone : ""); ?>" required />
									</div>
									<span class="errorstar">&nbsp;*</span>
								</div>

								<div class="form-group">
									<label class="col-lg-3 control-label">Password:</label>
									<div class="col-lg-5">
										<input type="password" class="form-control" name="password" value="<?php echo (isset($password) ? $password : ""); ?>" required />
									</div>
									<span class="errorstar">&nbsp;*</span>
								</div>

								<div class="form-group">
									<label class="col-lg-3 control-label">Retype password:</label>
									<div class="col-lg-5">
										<input type="password" class="form-control" name="confirmPassword" value="<?php echo (isset($password) ? $password : ""); ?>" required />
									</div>
									<span class="errorstar">&nbsp;*</span>
								</div>
											
								<div class="form-group">
									<label class="col-lg-3 control-label">Wallet Balance:</label>
									<div class="col-lg-5">
										<input type="text" class="form-control" name="wallet_balance" value="<?php echo (($wallet_balance>0) ? $wallet_balance : ""); ?>" placeholder="LIKE: 5 OR -5" />
									</div>
									<span class="errorstar">&nbsp;*</span>
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
											if ($_GET["user_id"] != "")
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
									<span class="span2"><button type="button" onClick="javascript: window.location.href = 'captain_list.php<?php echo $cancelurl; ?>'" class="btn btn-primary" title="Cancel">Cancel</button></span>
								</div>
								</div>
								
							</fieldset>
									<input type="hidden" name="save" id="save" value="submit" />
									<input type="hidden" name="id" id="id" value="<?php echo (isset($user_id) ? $user_id : ""); ?>" />
									<input type="hidden" name="action" id="action" value="<?php echo ((isset($_GET["user_id"]) || $action == "edit") ? "edit" : "add"); ?>" />
									
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