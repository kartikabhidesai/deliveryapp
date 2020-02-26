<?php
	include("../include/config.inc.php");
	
	$generalfunction = new generalfunction();
	$converter = new encryption();
	if (isset($_COOKIE["remember"]) && $_COOKIE["remember"] != "")
    {
		$cookieval = $converter->decode($_COOKIE["remember"]);
		$dbfunction = new dbfunctions();
		$dbfunction->SelectQuery("tbl_admin", "*", $dbfunction->db_safe("CookieId='%1' and is_active='1' and isis_deleted='0'", $cookieval));
		$total = $dbfunction->getNumRows();
		if ($total > 0)
        {
			$objsel = $dbfunction->getFetchArray();
			$_SESSION[SESSION_NAME . "userid"] = $objsel["admin_id"];
			$_SESSION[SESSION_NAME . "admin_email"] = stripslashes($objsel["admin_email"]);
			$_SESSION[SESSION_NAME . "admin_password"] = stripslashes($objsel["admin_password"]);
			$generalfunction->redirect("dashboard.php");
			
		}
	}
	
	if (isset($_POST["submit1"]) && $_POST["submit1"] != "")
    {
		$myfilter = new inputfilter();
		$email_id = $myfilter->process($_POST["emailaddress"]);
		$password = $myfilter->process($_POST["password"]);
		
		if ($email_id == "")
        {
			$error1 = "1";
			$errormessage1 = "Please enter E-mail Address";
		}
		elseif ($password == "")
        {
			$error1 = "1";
			$errormessage1 = "Please enter Password";
		}
	}
	
	
	
	$pagename = "login";
	if ($_SESSION[SESSION_NAME . "userid"] != "")
    {
		$generalfunction->redirect("dashboard.php");
		
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!--[if lt IE 7]> <html class="ie lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>    <html class="ie lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>    <html class="ie lt-ie9"> <![endif]-->
<!--[if gt IE 8]> <html class="ie gt-ie8"> <![endif]-->
<!--[if !IE]><!--><html><!-- <![endif]-->
    <head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $lang["charset"]; ?>" />
        <title><?php  echo "Login :: Admin :: ".SITE_NAME; ?></title>
		<link rel="shortcut icon" type="image/x-con" href="images/favicon.png" />
        <?php include("js-css-head.php"); ?>
        <?php include("meta-settings.php"); ?>
	</head>
	
    <body class="login">
        
		
		<!--
		===========================================================
		BEGIN PAGE
		===========================================================
		-->
		<!--<div class="login-header text-center">
			<img src="../assets/img/logo-login-1.png" class="logo" alt="Logo">-
			
		</div>-->
		<div class="login-wrapper">
			
			
									<?php
									if ($_GET["err"] != "")
                                    {
										?>
									<div class="alert alert-warning alert-bold-border fade in alert-dismissable">
									<?php	
										$error = $converter->decode($_GET["err"]);
										if ($error == "1")
                                        {
										?>
										<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
										<?php echo "Invalid Email or Password"; //$lang["noaccountfound"]; ?>
                                      
                                        <?php
										}
										else if ($error == "2")
                                        {
										?>
										<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
										<?php echo "Invalid Email or Password"; ?>

                                        <?php
										} ?>
										</div>
									<?php
									}
									
									if (isset($error1) && $error1 == "1")
                                    {
									?>
								 <div class="alert alert-warning alert-bold-border fade in alert-dismissable">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
									<?php echo $errormessage1; ?>
                                 </div>
                                    <?php
									
									}
								?>
			
			<form role="form" method="post" action="password.php" name="loginform" id="loginform">
				<div class="form-group has-feedback lg left-feedback no-label">
				  <input type="text" class="form-control no-border input-lg rounded"  value="<?php echo isset($_SESSION['ccadmin_email'])?trim($_SESSION['ccadmin_email']):"" ?>" maxlength="320"  placeholder="<?php echo $lang["emailaddress"]; ?>" name="emailaddress" id="emailaddress" required autofocus>
				  <span class="fa fa-user form-control-feedback"></span>
				</div>
				<div class="form-group has-feedback lg left-feedback no-label">
				  <input type="password" class="form-control no-border input-lg rounded" value="<?php echo isset($Email)?trim($Email):"" ?>" type="password" maxlength="16" placeholder="Password" name="password" id="password" required>
				  <span class="fa fa-unlock-alt form-control-feedback"></span>
				</div>
				<div class="form-group">
				  <div class="checkbox">
					<label>
					  <input class="i-yellow-flat" type="checkbox" value="1" name="rememberme" id="rememberme"> <?php echo $lang["rememberme"]; ?>
					</label>
				  </div>
				</div>
				<div class="form-group">
					<button name="submit1" id="submit1" value="submit"  type="submit" title="<?php echo $lang["signin"]; ?>" class="btn btn-warning btn-lg btn-perspective btn-block"><?php echo $lang["signin"]; ?></button>
				</div>
			</form>
			<p class="text-center"><strong><a alt="Forgot your Password" title="Forgot your Password?" href="forgotpassword.php">Forgot your password?</a></strong></p>
			<!--<p class="text-center">or</p>
			<p class="text-center"><strong><a href="register.html">Create new account</a></strong></p>-->
		</div><!-- /.login-wrapper -->
		<!--
		===========================================================
		END PAGE
		===========================================================
		-->
        <?php //include("js-css-footer.php"); ?>
        <!--<script language="javascript" type="text/javascript" src="js/jquery.validate.min.js"></script>
        <script language="javascript" type="text/javascript" src="js/form_validator.js"></script>
        <script language="javascript" type="text/javascript" src="js/login.js"></script>-->
	</body>
</html>