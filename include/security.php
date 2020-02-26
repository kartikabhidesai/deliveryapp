<?php
if(!function_exists("__autoload")){
	include("config.inc.php");
}
if($_SESSION['IsAdminId']==""){
	header("location: index.php");
	exit;
}

if(isset($_SESSION['IsAdminId']) && $_SESSION['IsAdminId'] != "")
{
	$iAdminId = $_SESSION['IsAdminId'];
}
function isLogged(){
    if($_SESSION['IsAdminId']){ # When logged in this variable is set to TRUE
        return TRUE;
    }else{
        return FALSE;
    }
}
function logOut(){
    session_destroy();
}
function sessionX(){
    $logLength = 3600;
    $ctime = strtotime("now");

    if(!isset($_SESSION['sessionX'])){ 
        $_SESSION['sessionX'] = $ctime; 
    }else{
        if(((strtotime("now") - $_SESSION['sessionX']) > $logLength) && isLogged()){
            logOut();
			
			$Msg	= encryptString('Your Login session has Expired. please Login Again.');
            header("Location: index.php?Msg=".$Msg);
            exit;
        }else{
            $_SESSION['sessionX'] = $ctime;
        }
    }
}
sessionX(); 
?>