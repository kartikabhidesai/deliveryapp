<?php
include('include/config.php');
include('smtpmail/sendmail.php');

$userData['{{firstname}}'] = 'kartik';
$userData['{{lastname}}'] = 'Desai';
$userData['{{mono}}'] = '1234567890';
$email = 'kartikdesai123@gmail.com';
$subject = 'Welcome Message';
$attach = '';
$userData = json_encode($userData);
$message = createtemplate('emailtemplate/welcome.php',$userData);

$dd = send_mail($email, $subject, $message, $attach);
print_r($dd);


?>