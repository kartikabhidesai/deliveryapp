<?php
include('../include/config.php');
include("classes/class.phpmailer.php"); // include the class name

$mail = new PHPMailer(); // create a new object
$mail->IsSMTP(); // enable SMTP
$mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
$mail->SMTPAuth = false; // authentication enabled
$mail->SMTPSecure = ''; // secure transfer enabled REQUIRED for Gmail
$mail->Host = SMTP_HOST;
$mail->SMTPAuth = false;
$mail->SMTPAutoTLS = false; 
$mail->Port = SMTP_PORT;
$mail->IsHTML(true);
$mail->Username = SMTP_UNAME;
$mail->Password = SMTP_PASS;
$mail->SetFrom(SMTP_UNAME);
$mail->Subject = $_POST['subject'];
$mail->Body = $_POST['message'];
$mail->AddAddress($_POST['to']);


 if(!$mail->Send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
 } else {
    echo "Message has been sent";
 }
?>