<?php
	$generalfunction = new generalfunction();
	if(!isset($_SESSION[SESSION_NAME."userid"]) || $_SESSION[SESSION_NAME."userid"]==""){
		$generalfunction->redirect("index.php");
	}
	$session = session_id(); // Change By Anas
?>