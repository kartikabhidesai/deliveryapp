<?php
require_once("../api/v1/include/config.php");
$dbConn=mysqli_connect( DBSERVER, DBUSERNAME, DBPASSWORD, DBNAME) or die ( "Database connection cannot be found" ) ;
$show_form = FALSE;

	$token = ($_GET['t']);
	$query = "SELECT custId FROM tbl_users WHERE emailVerificationCode = '$token' AND emailVerificationCode!=''";
	$result = mysqli_query($dbConn,$query);
	$row = mysqli_fetch_assoc($result);
	extract($row);
	
			$subject="مرحبا بكم في التطبيق لدينا";
			$message ='<body dir="rtl">';
			$message .= "مرحبا يا ".$fullName." ،";
			$message .= "اسعدنا تحميلك وتسجيلك معنا! <br/ >
				يهمنا ان نخبرك بالتالي:<br/ >
				1.  بسهولة بإمكانك مراقبة سلوكك الشرائي من خلال الاطلاع على تاريخ مشترياتك<br/ >
				2.  كل منتجاتنا طازجة ومنتقاه بعناية لأنها تمر بمراحل فحص جودة المنتجات <br/ >
				3.   اذا لاحظت اي شي مو عاجبك ،  عطنا خبر وبنعمل على ارضاءك<br/ >
				4.   نعمل بشكل مستمر للتطور وتقديم اخر وافضل الخدمات لراحتك<br/ >
				5.   اذا اعجبتك خدماتنا او منتجاتنا اخبر من تحب عنا:<br/ >
						تطبيق الايفون : http://tiny.cc/maa<br/ >
					   تطبيق الاندرويد: http://tiny.cc/maaAndroid<br/ >
						تقييم التطبيق في موقع معروف :  https://maroof.sa/19957<br/ >";
		
			$message .='مع التحية ، <br/ >
				تطبيق ماتين<br/ ><br/ >

				عند بابك بالضبط';
			$message .='</body>';
			//$attach = '../../assets/img/MATIEN APP.jpg';

	if($custId == "")
	{
		echo "<h2 style='text-align:center'>Invalid Access.</h2>";
		return;
	}
	else
	{
		$query = "UPDATE tbl_users SET emailVerified='1',emailVerificationCode='' WHERE custId = $custId";
	   		if(mysqli_query($dbConn,$query)) {
	   			echo "<h2 style='text-align:center'>Your email has been verified. Thank you!</h2>";
	   			//send_mail($email,$subject,$message,$attach);
	   		} else {
	   			echo "<p style='text-align:center'>There was an error: Try again please.</p>";
	   			$show_form = TRUE;
	   		}
	}	
?>
