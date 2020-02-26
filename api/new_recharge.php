<?php

	require_once("include/config.php");
	require_once("include/init.php");
	//include_once("include/notification.php");
	include_once("../smtpmail/mail.php");
	
	if($lang == 'en')
		require_once('lang/en.php');
	elseif($lang == 'ar')
		require_once('lang/ar.php');
	else
		require_once('lang/en.php');
		
	$conn=new Database;
	$data = new DataManipulator;
	$jsonArray = array();
	$d=array();	
	
	if($_POST['user_id']!='')
		$postdata['user_id'] = $_POST['user_id'];
	else
		$err = $lang["REQ_PARA"].$lang["USER_ID"];
	
	if($_POST['package_id']!='')
		$postdata['package_id'] = $_POST['package_id'];
	else
		$err = $lang["REQ_PARA"]."package_id";
	
	if($_POST['transaction_id']!='')
		$postdata['transaction_id'] = $_POST['transaction_id'];
	else
		$err = $lang["REQ_PARA"]."transaction_id";
	
	if($_POST['transaction_detail']!='')
		$postdata['transaction_detail'] = $_POST['transaction_detail'];
	//else
		//$err = $lang["REQ_PARA"]."transaction_detail";
	
	$pck = $data->select("tbl_recharge_packages","*" , array("package_id"=>$_POST['package_id']));
	$postdata['recharge_amount'] = $pck[0]['recharge_amount'];
	$postdata['extra_discount_perc'] = $pck[0]['extra_discount_perc'];
	$postdata['total_amount'] = $pck[0]['total_amount'];
	
	$usr = $data->select("tbl_users","wallet_balance" , array("user_id"=>$_POST['user_id']));
	$postdata['old_balance'] = $usr[0]['wallet_balance'];
	$postdata['new_balance'] = $postdata['old_balance'] + $postdata['total_amount'];
	$postdata['status'] = "Approved";
	$postdata['created_on'] = date("Y-m-d H:i:s");
	if($err!='')
	{
		$jsonArray['Success']='0';
		$jsonArray['Message']=$err;
		show_output($jsonArray); 
	}
	
	$sql = $conn->get_record_set("SELECT user_id FROM tbl_recharge_history	WHERE user_id='".$postdata['user_id']."' 
	AND package_id='".$postdata['package_id']."' AND status='Approved'");
	$rows = $conn->records_to_array($sql);
	//if(empty($rows))
	if(true)
	{
		
		$recharge_id = $data->insert( "tbl_recharge_history" , $postdata );
		$data->update("tbl_users",array("wallet_balance"=>$postdata['new_balance']) , array("user_id"=>$_POST['user_id']));
		$jsonArray['Success']='1';
		$jsonArray['Message']=$lang["ACCOUNT_RECHARGE_SUCCESSFULLY"];
		
		{
			
					/******************************/
					 /*********SEND BODY************/
					 /******************************/
					if($lang == 'en')
						require_once('lang_code/en.php');
					elseif($lang == 'ar')
						require_once('lang_code/ar.php');
					else
						require_once('lang_code/en.php');
					
					/*$sql1 = $conn->get_record_set("SELECT rec.*,tec.provider,tec.name,tec.comapny_name,tec.email_id,tec.phone,ban.bank_name
					FROM tbl_recharge_history rec 
					INNER JOIN tbl_user tec ON rec.user_id=tec.user_id
					INNER JOIN tbl_banks ban ON rec.bank_id=ban.bank_id
					WHERE recharge_id='".$recharge_id."'");
					$hist = $conn->records_to_array($sql1);
					$tech_name = (($hist[0]["provider"]=='1')?$hist[0]["name"]:$hist[0]["comapny_name"]);
					$tech_name = (($tech_name=='')?$hist[0]["name"]:$tech_name);
					$message_body = '<table style="margin: 0px !important;">
							<tr>
								<td width="30%"><b>'.$lang_code["Freelancer/Company"].'</b></td>
								<td> : </td>
								<td>'.$tech_name.'</td>
							</tr>
							<tr>
								<td width="30%"><b>'.$lang_code["SP_Email"].'</b></td>
								<td> : </td>
								<td>'.$hist[0]["email_id"].'</td>
							</tr>
							<tr>
								<td width="30%"><b>'.$lang_code["SP_Phone"].'</b></td>
								<td> : </td>
								<td>'.$hist[0]["phone"].'</td>
							</tr>
							<tr>
								<td width="30%"><b>'.$lang_code["Recharge_Amount"].'</b></td>
								<td> : </td>
								<td>'.$hist[0]["recharge_amount"].' SAR</td>
							</tr>
							
							<tr>
								<td width="30%"><b>'.$lang_code["Bank"].'</b></td>
								<td> : </td>
								<td>'.$hist[0]["bank_name"].'</td>
							</tr>
							<tr>
								<td width="30%"><b>'.$lang_code["Status"].'</b></td>
								<td> : </td>
								<td>Pending</td>
							</tr>
							<tr>
								<td width="30%"><b>'.$lang_code["Received_At"].'</b></td>
								<td> : </td>
								<td>'.date("Y-m-d H:i").'</td>
							</tr>
					</table>';
					$side = ($u_lang == 'ar')?"rtl":"ltr";
					$align = ($u_lang == 'ar')?"right":"left";
					$comma = ($u_lang == 'ar')?"?":",";
					 
					 $mail1	= new sendmail; 
					 $email_data = $mail1->read_body_admin("adhman_email.html");
					 $content1=str_replace("##HELLO##",$lang_code["HELLO"], $email_data);
					 $content2=str_replace("##REGARDS##",$lang_code["REGARDS"], $content1);
					 $content3=str_replace("##TEAM##",$lang_code["TEAM"], $content2);
					 $content4=str_replace("##USERNAME##",$name, $content3);
					 $content5=str_replace("##SIDE##",$side, $content4);
					 $content6=str_replace("##ALIGN##",$align, $content5);
					 $content7=str_replace("##COMMA##",$comma, $content6);
					 $content=str_replace("##BODYCONTENT##",$message_body, $content7);
					 $body = str_replace("##SITENAME##",APP_TITLE, $content);			
					if($lang == 'ar')
					$subject="طلب شحن جديد";
					else
					$subject="New Recharge Request";
					$mail1->mail_send(CONTACT_EMAIL,$subject,$body);
					$mail1->mail_send($hist[0]["email_id"],$subject,$body);	*/
			
					
		}
	}
	else
	{
		$jsonArray['Success']= '0';
		$jsonArray['Message']= $lang["ALREADY_EXIST"];
	}
	show_output($jsonArray);
