<?php
	include("js-css-head.php");
	include("../include/config.inc.php");
	$converter 		 = new encryption();
	$generalfunction = new generalfunction();
	if (isset($_POST["submit1"]) && $_POST["submit1"] != "")
    {
		if ($_POST["emailaddress"] != "" && $_POST["password"] != "")
        {
			$username = $_POST["emailaddress"];
			$password = $converter->encode($_POST["password"]);
			$dbfunction = new dbfunctions();
			$dbfunction->SelectQuery("tbl_admin", "*", $dbfunction->db_safe("admin_email='%1' and is_active='1' and is_deleted='0'", $username));
			$objsel = $dbfunction->getFetchArray();
			//print_r($objsel);exit;
			$total = $dbfunction->getNumRows();
			if ($total > 0)
            {
				//$objsel = $dbfunction->getFetchArray();
				$Password = $objsel['admin_password'];
				if($password==$Password)
				{
					$_SESSION[SESSION_NAME . "userid"] = $objsel["admin_id"];
					$_SESSION[SESSION_NAME . "displayname"] = stripslashes($objsel["admin_name"]);
					$_SESSION[SESSION_NAME . "username"] = stripslashes($objsel["admin_email"]);
					if ($_POST["rememberme"] != "")
					{
						$expire = date('Y-m-d H:i:s') + (60 * 60 * 24 * 3000);
						$cookieid = md5(session_id() . date('Y-m-d H:i:s'));
						setcookie("remember", $converter->encode($cookieid), $expire);
						$dbfunction->UpdateQuery("tbl_admin", array("CookieId" => $cookieid), $dbfunction->db_safe("admin_id='%1'", $objsel["admin_id"]));
					}
					$_SESSION['login_suc'] = "1";
					$generalfunction->redirect("dashboard.php");
				}
				else
				{
					$generalfunction->redirect("index.php?err=" . $converter->encode("2"));
				}
			}
			else
            {
				$generalfunction->redirect("index.php?err=" . $converter->encode("1"));
			}
		}
		else
        {
			$generalfunction->redirect("index.php?err=" . $converter->encode("1"));
		}
	}
	else
    {
		$generalfunction->redirect("index.php?err=" . $converter->encode("1"));
	}
?>