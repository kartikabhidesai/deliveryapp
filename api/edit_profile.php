<?php

require_once('include/config.php');
require_once('include/init.php');
require_once('include/thumb.php');

if ($language == 'en')
    require_once('lang/en.php');
elseif ($language == 'ar')
    require_once('lang/ar.php');
else
    require_once('lang/en.php');

$conn = new Database;
$data = new DataManipulator;
$jsonArray = array();

if ($_POST['user_id'] != '')
    $user_id = $_POST['user_id'];
else
    $err = $lang["REQ_PARA"] . $lang["USER_ID"];

if ($_POST['firebase_userid'] != '')
    $postdata['firebase_userid'] = trim($_POST['firebase_userid']);

if ($_POST['email_id'] != '')
    $postdata['email_id'] = trim($_POST['email_id']);

if ($_POST['full_name'] != '')
    $postdata['full_name'] = mysqli_real_escape_string($dbConn, $_POST['full_name']);

if ($_POST['phone'] != '')
    $postdata['phone'] = trim($_POST['phone']);

if ($_POST['password'] != '')
    $postdata['password'] = md5(convert_en($_POST['password']));

if ($_POST['notification_enabled'] != '')
    $postdata['notification_enabled'] = $_POST['notification_enabled'];

if ($_POST['language'] != '')
    $postdata['language'] = $_POST['language'];

$postdata['device_id'] = $device_id;
$postdata['device_token'] = ($_POST['device_token'] != '') ? $_POST['device_token'] : $device_token;
$postdata['device_type'] = ($_POST['device_type'] != '') ? $_POST['device_type'] : $device_type;

if (!empty($_FILES['profile_pic'])) {
    $path = '../uploads/profile/';
    $userf = $_FILES['profile_pic']['tmp_name'];
    $fname = $_FILES['profile_pic']['name'];
    $ext = pathinfo($fname, PATHINFO_EXTENSION);
    $img = time() . "_" . "profile." . $ext;
    if (move_uploaded_file($userf, $path . $img)) {
        $postdata['profile_pic'] = $img;
        createthumb($path . $img, $path . '150x150/' . $img, 150, 150);
    }
}

if ($err != '') {
    $jsonArray['Success'] = '0';
    $jsonArray['Message'] = $err;
    show_output($jsonArray);
}

$postdata['updated_on'] = date('Y-m-d H:i:s');

$sql = $conn->get_record_set("SELECT * FROM `tbl_users` WHERE `user_id`!='$user_id' AND phone LIKE '%" . substr($postdata['phone'], -9) . "' AND phone!='' AND is_deleted='0'");
$row = $conn->records_to_array($sql);
if (empty($postdata['phone'])) {
    $data->update("tbl_users", $postdata, array("user_id" => $user_id));
    $jsonArray['Success'] = '1';
    $jsonArray['Message'] = $lang["SUCCESSFUL"];
    $sql = $conn->get_record_set("SELECT * FROM `tbl_users` WHERE user_id='$user_id' AND is_deleted ='0'");
    $rows = $conn->records_to_array($sql);
    foreach ($rows as $row1) {
        $row1['password'] = '';
        if ((PHOTO_URL . "profile/" . $row1['profile_pic']) && $row1['profile_pic'] != '')
            $row1['profile_pic_150'] = ($row1['profile_pic'] != '') ? PHOTO_URL . "profile/" . $row1['profile_pic'] : '';
        else
            $row1['profile_pic_150'] = '';
        $row1['profile_pic'] = ($row1['profile_pic'] != '') ? PHOTO_URL . "profile/" . $row1['profile_pic'] : '';
        $row1['total_points'] = strval($row1['total_customer_points'] + $row1['total_captain_points']);
        $jsonArray['is_verified'] = $row1['is_verified'];
        if ($row1['full_name'] == '')
            $jsonArray['completed_profile'] = '0';
        else
            $jsonArray['completed_profile'] = '1';
        $jsonArray['is_new_phone'] = '0';
        $jsonArray['detail'] = $row1;
        edit_profile_mail($postdata);
        show_output($jsonArray);
        
    }
}
elseif (empty($row)) {
    $row = $data->select("tbl_users", "phone", array("user_id" => $user_id));
    //if($postdata['phone']!='')
    //{
    if ($row[0]['phone'] != $postdata['phone']) {
        $digits = 4;
        $postdata['activation_code'] = ($dev == true) ? "1234" : rand(pow(10, $digits - 1), pow(10, $digits) - 1);
        $sms_text = $lang["SIGNUP_SMS_TEXT"] . $postdata['activation_code'];
        // SMS VERIFICATION CODE
        send_sms($postdata['phone'], $sms_text);
        $postdata['temp_phone'] = $postdata['phone'];
        //$postdata['phone'] = $row[0]['phone'];
        $is_new_phone = '1';
    } else {
        $is_new_phone = '0';
    }
    //}
    $data->update("tbl_users", $postdata, array("user_id" => $user_id));

    $jsonArray['Success'] = '1';
    $jsonArray['Message'] = $lang["SUCCESSFUL"];
    $sql = $conn->get_record_set("SELECT * FROM `tbl_users` WHERE user_id='$user_id' AND is_deleted ='0'");
    $rows = $conn->records_to_array($sql);
    foreach ($rows as $row1) {
        $row1['password'] = '';
        if ((PHOTO_URL . "profile/" . $row1['profile_pic']) && $row1['profile_pic'] != '')
            $row1['profile_pic_150'] = ($row1['profile_pic'] != '') ? PHOTO_URL . "profile/" . $row1['profile_pic'] : '';
        else
            $row1['profile_pic_150'] = '';
        $row1['profile_pic'] = ($row1['profile_pic'] != '') ? PHOTO_URL . "profile/" . $row1['profile_pic'] : '';
        $row1['total_points'] = $row1['total_customer_points'] + $row1['total_captain_points'];
        $jsonArray['is_verified'] = $row1['is_verified'];
        if ($row1['full_name'] == '')
            $jsonArray['completed_profile'] = '0';
        else
            $jsonArray['completed_profile'] = '1';
        $jsonArray['is_new_phone'] = $is_new_phone;
        $jsonArray['detail'] = $row1;
        edit_profile_mail($postdata);
        show_output($jsonArray);
        
    }
}
/* else if($postdata['email_id'] !='')
  {
  $jsonArray['Success'] = '0';
  $jsonArray['Message'] = $lang["EMAIL_EXIST_ALREADY2"];
  } */
else {
    $jsonArray['Success'] = '0';
    $jsonArray['Message'] = $lang["PHONE_EXIST_ALREADY"];
}
edit_profile_mail($postdata);
show_output($jsonArray);

function edit_profile_mail($postdata) {
    //print_r($postdata['full_name']);exit;
    include('smtpmail/sendmail.php');
    $userData['{{firstname}}'] = $postdata['full_name'];
    $email = $postdata['email_id'];
    $subject = 'Here Delivery Welcome you';
    $attach = '';
    $userData = json_encode($userData);
    $message = createtemplate('emailtemplate/edit-profile-welcome-mail.php', $userData);
    send_mail($email, $subject, $message, $attach);
    
}

?>