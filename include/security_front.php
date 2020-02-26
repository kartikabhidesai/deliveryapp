<?php
	if(!function_exists("__autoload")){
		include("config.inc.php");
	}
	if($_SESSION['vendorid']=="")
	{
		header("location: index.php");
		exit;
	}
	/* if(intval($_SESSION['subscription'])==0 && intval($_SESSION['free_trial'])!=1 && intval($_SESSION['VendorActive']) > 0)
	{
		header("location: /vendor-payment");
		exit;
	} */
	// else if(intval($_SESSION['free_trial'])===1 && intval($_SESSION['VendorActive']) > 0)
	// {
		
	// }
	
	function isLoggedVendor(){
		if($_SESSION['vendorid']){ # When logged in this variable is set to TRUE
			return TRUE;
			}else{
			return FALSE;
		}
	}
	function isRegistered()
	{
		if($_SESSION['registration'])
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	function logOut(){
		session_destroy();
	}
	
	function sessionY()
	{
		$logLength = 3600;
		$ctime = strtotime("now");
		if(!isset($_SESSION['sessionY'])){
			$_SESSION['sessionY'] = $ctime;
			}else{
			if(((strtotime("now") - $_SESSION['sessionY']) > $logLength) && isLoggedVendor()){
				logOut();
				
				$Msg	= encryptString('Your Login session has been Expired. please Login Again.');
				header("Location: index.php");
				exit;
				}else{
				$_SESSION['sessionY'] = $ctime;
			}
		}
	}
	sessionY();
	
?>