<?php
	require_once('include/config.php');
	
	
	if(isset($_POST['check_number']))
	{
		/*if($_POST['lang'] == 'en')
			require_once('lang/en.php');
		elseif($_POST['lang'] == 'ar')
			require_once('lang/ar.php');
		else
			require_once('lang/en.php');*/
		$phone = ($_POST['phone_number']);
		$msg = ($_POST['message_data']);
		//$respons = send_sms($phone,$msg);
		require_once('include/smscountry.php');
		
		$res = json_decode($respons);
		print_R($respons);exit;
	}
	
?>
<html>
	<body align=center>
		<form method="POST" name="check_number">
			<!--<input type="text" name="lang" placeholder="en or ar"	required/>-->
			<textarea name="message_data" placeholder="Type message here" cols="22" required></textarea><br/><br/>
			<input type="text" name="phone_number" placeholder="0564066219" required/><br/><br/>
			<input type="submit" name="check_number" value="submit"/>
		</form>
	
	</body>
</html>