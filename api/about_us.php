<?php
 require_once("include/config.php"); 
 mysqli_query($dbConn,"SET NAMES 'utf8'"); 
 $sql = mysqli_query($dbConn,"SELECT * FROM `tbl_contents` WHERE `field_key` = 'about_us' AND is_active='1' AND is_deleted ='0'")or die('opppsss');
 $row = mysqli_fetch_assoc($sql);
?>
<html>
<head>
<!--<title>About Us</title>-->
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<style>
 body 
 {
	font-family:"Myriad Pro Light";
 }
 h1
 {
    
	font-family:"Myriad Pro Light";
	font-size:36px
  }
  p
  {
	font-size:16px;
	text-align:justify;
  }
  ul li
  {
	font-family:"Myriad Pro Light";
	font-size:16px;
	line-height:1.5;
  }
  li
  {
 	text-align:justify
	
  }  
</style>
</head>

<?php
if($_REQUEST['lang']=='ar')
{ ?>
<body dir="rtl"> <?php echo stripcslashes($row['field_value_ar']);?> </body>
<?php
}
else
{ ?>
<body> <?php echo stripcslashes($row['field_value']);?> </body>
<?php }
?>
</html>