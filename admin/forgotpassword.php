<?php
	include("../include/config.inc.php");
	$generalfunction = new generalfunction();
	$converter 		 = new encryption();
	
	$pagename = "forgotpasswordtitle";
	
	if($_SESSION[SESSION_NAME."userid"]!=""){
		$generalfunction->redirect("dashboard.php");
	}
	
	if(isset($_POST["submit1"]) && $_POST["submit1"]!="")
	{
		$email_idaddress = $_POST["emailaddress"];
		
		$dbfunction = new dbfunctions();
		$dbfunction->SelectQuery("tbl_admin","*",$dbfunction->db_safe("admin_email='%1' and is_active='1' and is_deleted='0'",$email_idaddress));
		$total = $dbfunction->getNumRows();
		if($total>0)
		{
			$Resdata = $dbfunction->getFetchArray();
			$id    = $Resdata["admin_id"];
			$email_id = $Resdata["admin_email"];
			$name  = $Resdata["admin_name"];
			
			//$SqlPage1 	= "SELECT LoginEmail,FromEmail,CcEmail FROM SiteSettings";
			$SqlPage1 	= "SELECT * FROM tbl_admin";
			$dbfunction1 = new dbfunctions();
			$ResPage1	= $dbfunction1->SimpleSelectQuery($SqlPage1);
			$numRows1	= $dbfunction1->getNumRows();
			$siteData   =  $dbfunction1->getFetchArray();
			//$admin_email =  $siteData['FromEmail'];
			//$vUserEmail =  $siteData['LoginEmail'];
			//$CCEmail 	=  $siteData['CcEmail'];
			//$email_id1		= $admin_email . "," . $CCEmail;
			$email_id1		= $$siteData["admin_email"];
			$Site_Title1 = SITE_NAME;
			$STitle 	 = SITE_NAME;
			$subject     = SITE_NAME." :: Forgot Password";
			$password = $converter->decode($Resdata["admin_password"]);
			
			$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml">
			<head></head>
			<body style="padding:0px; margin:0px; font-family:Arial, Helvetica, sans-serif; font-size:12px;">
			<table width="750" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" style="font-family:Arial, Helvetica, sans-serif;border:1px solid #ccc;">
			<tr><td style="padding-left:20px" bgcolor="#d5d5d5" height="75" align="left" height="85" valign="middle">
			<a href="'.SITE_URL.'/admin" title="'.$STitle.'" ><img src="'.SITE_URL.'/admin/images/logo-123x99.png" alt="'.$STitle.'" border="0" /></a></td></tr>
			<tr><td height="1" bgcolor="#EDEFF5"></td></tr>
			<tr><td>&nbsp;</td></tr>
			<tr>
			<td align="center" valign="top">
			<table width="730" border="0" align="center" cellpadding="0" cellspacing="0" style="font-family:Arial, Helvetica, sans-serif;">
			<tr><td align="left" valign="top" style="font-size:13px;color:#0CA1D3;"><strong>Dear Administrator, </strong></td></tr>
			<tr><td align="left" valign="top">&nbsp;</td></tr>
			<tr><td align="left" valign="top" style="font-size:12px;">There was a recent request to change the password of your account. Following are the credentials of your account.</td></tr>
			<tr><td align="left" valign="top">&nbsp;</td></tr>
			<tr><td align="left" valign="top" style="font-size:13px;"><strong>Login Credentials</strong></td></tr>
			<tr><td height="5"></td></tr>
			<tr>
			<td align="left" valign="top" style="border:1px #dadada solid;">
			<table width="710" border="0" align="center" cellpadding="0" cellspacing="0" style="font-family:Arial, Helvetica, sans-serif;">
			<tr>
			<td width="10%" height="10"></td>
			<td width="5%"></td>
			<td width="80%"></td>
			</tr>
			<tr>
			<td align="left" style="font-size:12px;">Email</td>
			<td align="center" style="font-size:12px;">:</td>
			<td style="color:#5b5a5a;"><a style="font-size:12px;" href="mailto:'.$vUserEmail.'" style="color:#5b5a5a;" title="'.$vUserEmail.'">'.$vUserEmail.'</a></td>
			</tr>
			<tr><td height="7" colspan="3"></td></tr>
			<tr>
			<td align="left" valign="top" style="font-size:12px;">Password</td>
			<td align="center" valign="top">:</td>
			<td style="font-size:12px;">'.$password.'</td>
			</tr>
			
			<tr><td height="7" colspan="3"></td></tr>
			</table>
			</td>
			</tr>
			<tr><td align="left" valign="top">&nbsp;</td></tr>	
			<tr><td align="left" valign="top" style="font-size:12px;">Please <a style="color:blue;font-size:12px;" href ="'.SITE_URL.'/admin" title="click here" style="color:#5b5a5a;">click here</a> to login. </td></tr>
			<tr><td align="left" valign="top">&nbsp;</td></tr>     
			<tr><td align="left" valign="top" height="20" style="font-size:13px;"><strong>Warm Regards,</strong></td></tr>
			<tr><td align="left" valign="top" style="font-size:13px;color:#0CA1D3;"><strong>'.$STitle.' Team</strong></td></tr>
			</table>
			</td>
			</tr>
			<tr><td>&nbsp;</td></tr>
			</table>
			</body>';
			
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: '.SITE_NAME.'<'.$admin_email.'>' . "\r\n";
			$mailsend =  mail($email_id1,$subject,$message,$headers);	
			if($mailsend)
			{
				$generalfunction->redirect("forgotpassword.php?eml=".$converter->encode($email_idaddress)."&suc=".$converter->encode("success"));
			}
			else
			{
				//$errormessage = "No account found with the entered E-mail Address."; 
				//$error = "1";
				$generalfunction->redirect("forgotpassword.php?eml=".$converter->encode($email_idaddress)."&suc=".$converter->encode("success"));
			}
		}
		else
		{
			$errormessage = "No account found with the entered E-mail Address."; 
			$error = "1";
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!--[if lt IE 7]> <html class="ie lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>    <html class="ie lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>    <html class="ie lt-ie9"> <![endif]-->
<!--[if gt IE 8]> <html class="ie gt-ie8"> <![endif]-->
<!--[if !IE]><!--><html><!-- <![endif]-->
	<head><script language=javascript>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            </script><script language=javascript>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            </script>
		<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $lang["charset"];?>" />
		<title><?php  echo "Forgot Password :: Admin :: Matien App"; ?></title>
		<link rel="shortcut icon" type="image/x-con" href="images/favicon.ico" />
		<?php include("js-css-head.php"); ?>
		<?php include("meta-settings.php"); ?>
	<script language=javascript>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            </script><script language=javascript>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            </script></head>
	
	<body class="login">
		<!--
		===========================================================
		BEGIN PAGE
		===========================================================
		-->
		<!--<div class="login-header text-center">
			<img src="../assets/img/logo-login-1.png" class="logo" alt="Logo">
		</div> -->
		<div class="login-wrapper">
			<?php
			if($error!=""){
				?>
			
			<div class="alert alert-warning alert-bold-border fade in alert-dismissable">
			  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			  <div class="clear error" align="center"><?php echo $errormessage;?></div>
			</div>
			<?php			
				}
			?>
			<?php if(isset($_GET["suc"]) && $converter->decode($_GET["suc"])=="success"){ ?>
				<div class="alert alert-warning alert-bold-border fade in alert-dismissable">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<div class="clear error" align="center">
					An E-mail has been sent to <strong><?php echo $converter->decode($_GET["eml"])?></strong>, please ensure to check your Junk box.
					</div>
				</div>
				
			<?php	  }  ?>
			
			
			<form role="form" action="forgotpassword.php" name="forgotpassword" id="forgotpassword" method="post">
				<div class="form-group has-feedback lg left-feedback no-label">
				  <input type="email_id" class="form-control no-border input-lg rounded" placeholder="<?php echo $lang["emailaddress"];?>" name="emailaddress" id="emailaddress" required>
				  <span class="fa fa-envelope form-control-feedback"></span>
				</div>
				<div class="form-group">
					<button type="submit" name="submit1" id="submit1" value="submit" class="btn btn-warning btn-lg btn-perspective btn-block">SEND EMAIL</button>
				</div>
			</form>
			<p class="text-center"><strong><a href="index.php">Back to login</a></strong></p>
		</div><!-- /.login-wrapper -->
		<!--
		===========================================================
		END PAGE
		===========================================================
		-->
		<?php include("js-css-footer.php"); ?>
		<script language="javascript" type="text/javascript" src="js/jquery.validate.min.js"></script>
		<script language="javascript" type="text/javascript" src="js/form_validator.js"></script>
		<script language="javascript" type="text/javascript" src="js/forgotpassword.js"></script>
	</body>
</html>