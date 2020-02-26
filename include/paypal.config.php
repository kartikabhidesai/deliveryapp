<?php
//include("config.inc.php");
$currency = "$"; //Currency sumbol or code
//paypal settings
$PayPalMode 			= 'sandbox';  // sandbox or live
$PayPalApiUsername 		= 'anas.kadival-facilitator_api1.gmail.com'; //PayPal API Username
$PayPalApiPassword 		= '1409053709'; //Paypal API password
$PayPalApiSignature 	= 'AFcWxV21C7fd0v3bYYYRCpSSRl31AKPtIQK02g5iGVdQm0Ut2V18rh4r'; //Paypal API Signature
$PayPalCurrencyCode 	=  "USD"; //Paypal Currency Code
$PayPalReturnURL 		= BASE_URL.'index.php?p=6'; //Point to process.php page
$PayPalCancelURL 		= BASE_URL.'checkout'; //Cancel URL if user clicks cancel
$PayPalNotifyURL        = BASE_URL.'notify_payment'; //Cancel URL if user clicks cancel
?>