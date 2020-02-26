<?php


function send_mail($to, $subject, $message, $attachment) {
 
    $url = BASE_URL_API . "smtpmail/smtpgmail.php";
      
  
    $data = array("to" => $to, "subject" => $subject, "message" => $message, "attachment" => $attachment);
    $timeout = 3600;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_AUTOREFERER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);    # required for https urls
    $content = curl_exec($ch);
    return $content;
}

function  createtemplate($template,$data){
	
    $decodeData = json_decode($data,true);
    $page = file_get_contents(BASE_URL_API.'smtpmail/'.$template);
    
    foreach($decodeData as $key => $value){
        $page = str_replace($key,$value,$page);
    }
	
    return $page;
}


