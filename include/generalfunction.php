<?php
	
	class generalfunction
	{
		/*
			This file created for general function is use for valid proper data.
			Created Date : 02/01/2009
		*/
		// This function use for encrypyion a query string
		var $strcode1 = array('','0','1','2','3','4','5','6','7','8','9','a','A','b','B','c','C','d','D','e','E','f','F','g','G','h','H','i','I','j','J','k','K','l','L','m','M','n','N','o','O','p','P','q','Q','r','R','s','S','t','T','u','U','v','V','w','W','x','X','y','Y','z','Z',' ');
		function encrypt($string) 
		{
			$key = "6r9qEJg6";
			$result = "";
			for($i=0; $i<strlen($string); $i++) 
			{
				$char = substr($string, $i, 1);
				$keychar = substr($key, ($i % strlen($key))-1, 1);
				$char = chr(ord($char)+ord($keychar));
				$result.=$char;
			}
			return base64_encode($result);
		}
		function getSuccessMessage($message)
        {
			return '<div class="alert alert-success alert-block fade in alert-dismissable">
			 <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<strong>' . $message . '</strong></div>';
		}
		function getErrorMessage($message)
        {
			return '<div class="alert alert-danger alert-block fade in alert-dismissable">
            <button data-dismiss="alert" class="close" type="button">&times;</button>
            <strong>' . $message . '</strong></div>';
		}
		function YMDHIStoFormat($date)
        {
			return date("d/m/Y", strtotime($date));
			//			return date("d/m/Y H:i:s",strtotime($date));
		}
		//This function use for decryption a query string
		function decrypt($string) 
		{
			$key = "6r9qEJg6";
			$result = "";
			$string = base64_decode($string);
			for($i=0; $i<strlen($string); $i++) 
			{
				$char = substr($string, $i, 1);
				$keychar = substr($key, ($i % strlen($key))-1, 1);
				$char = chr(ord($char)-ord($keychar));
				$result.=$char;
			}
			return $result;
		}
		function getURLAfterParam($parameters, $removearray)
        {
			if ($parameters == "")
            return "";
			$parametersexp = explode("&", substr($parameters, 1));
			$returnvalue = "";
			if (!is_array($removearray))
            {
				$removearray = array($removearray);
			}
			for ($i = 0; $i < count($parametersexp); $i++)
            {
				$keyvalue = explode("=", $parametersexp[$i]);
				if (!in_array($keyvalue[0], $removearray))
                {
					if ($returnvalue == "")
                    {
						$returnvalue = "?" . $keyvalue[0] . "=" . $keyvalue[1];
					}
					else
                    {
						$returnvalue .= "&" . $keyvalue[0] . "=" . $keyvalue[1];
					}
				}
			}
			return $returnvalue;
		}
		function getAdminEmail($ccemail = FALSE)
        {
			$dbfunction = new dbfunctions();
			$dbfunction->SelectQuery("SiteSettings", "*", "SiteSettingsId='1'");
			$objdata = $dbfunction->getFetchArray();
			if ($ccemail === TRUE)
            {
				$returnarray = array();
				$returnarray["adminemail"] = stripslashes($objdata["LoginEmail"]);
				$returnarray["FromEmail"] = stripslashes($objdata["FromEmail"]);
				$returnarray["ccemail"] = stripslashes($objdata["CcEmail"]);
				return $returnarray;
			}
			else
            {
				return stripslashes($objdata["FromEmail"]);
			}
		}
		function SendHTMLMail($to,$from,$subject,$content,$attachments=array())
		{
			$mail = new phpmailer();
			$mail->SetFrom($from,SITE_NAME);
			$mail->AddAddress($to);
			$mail->Subject = $subject;
			for($a = 0; $a < count($attachments); $a++){
				$mail->AddAttachment($attachments[$i][1],$attachments[$i][0]);
			}
			$mail->MsgHTML($content);
			$mail->Send();
		}
		function getOrdering($pagename, $extravars, $selected, $converter)
        {
			$returndata = '<div class="row-fluid"><div class="span12 padtop5 padbot5"><div class="dataTables_length"><div class="pagination margin-none"><ul>';
			for ($i = 65; $i <= 90; $i++)
            {
				$returndata .= '<li' . (chr($i) == $converter->decode($selected) ? ' class="active"' : "") . '><a href="' . $managepage . '?sort=' . $converter->encode(chr($i)) . "&" . substr($extravars, 1) . '" title="' . chr($i) . '">' . chr($i) . '</a></li>';
			}
			$returndata .= '<li' . ($selected == "" ? ' class="active"' : "") . '><a href="' . $pagename . $extravars . '" title="ALL">ALL</a></li>';
			$returndata .= "</ul></div></div></div></div>";
			return $returndata;
		}
		
		//This function use for check valid emailid	
		function validEmail($email)
		{
			if(eregi("^[a-zA-Z0-9_]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$]", $email)) 
			{
				return false;
			}
		}
		//This function use for check valid alpha numeric data.
		
		function validAlphaNum($value)
		{
			if(ctype_alnum($value))
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		//This function use for check valid alpha character	
		function validAlphaChar($value)
		{
			if(ctype_alpha($value))
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		//This function use for check valid Numeric data
		function validNum($value)
		{
			if(ctype_digit($value))
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		//This function use for check valid image file		
		function validImage($ImageName)
		{
			$ext = array(1 => "jpg", 2 => "JPG", 3 => "jpeg", 4 => "JPEG", 5 => "gif", 6 => "GIF", 7 => "png", 8 => "PNG", 9 => "pjpeg", 10 => "PJPEG");
			$ImageExt = substr(strrchr($ImageName,"."),1);
			if(in_array($ImageExt,$ext))
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		//This function use for return valid file attechment
		function validAttachment($ImageName)
		{
			$ext = array("jpg","JPG","jpeg","JPEG","gif","GIF","png","PNG","pjpeg","PJPEG","TTIF","ttif","PDF","pdf","DOC","doc","DOCX","docx","XLS","xls","XLSX","xlsx","CSV","csv");
			$ImageExt = substr(strrchr($ImageName,"."),1);
			if(in_array($ImageExt,$ext))
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		//This function use for return valid file name
		function validFileName($string)
		{
			// Replace other special chars
			$specialCharacters = array(
			'%' => '',
			'?' => '',
			'.' => '',
			'@' => '',
			'+' => '',
			'=' => '',
			' ' => '',
			'\\' => '',
			'/' => '',
			'~' => '',
			'`' => '',
			'^' => '',
			'\'' => '',
			'\"' => '',
			'*' => '',
			'!' => '',
			':' => '',
			'<' => '',
			'>' => '',
			'|' => '',
			'#' => '',
			);
			
			while (list($character, $replacement) = each($specialCharacters)) 
			{
				$string = str_replace($character, '' . $replacement . '', $string);
			}
			$string = strtr($string,
			"¿¡¬√ƒ≈·‚„‰Â“”‘’÷ÿÚÛÙıˆ¯»… ÀËÈÍÎ«ÁÃÕŒœÏÌÓÔŸ⁄€‹˘˙˚¸ˇ—Ò",
			"AAAAAAaaaaaaOOOOOOooooooEEEEeeeeCcIIIIiiiiUUUUuuuuyNn"
			);
			return $string;
		}
		//This function use for return valid file extation
		function getExtention($FileName)
		{
			$extation = substr(strrchr($FileName,"."),1);
			return $extation;
		}
		//This function use for return valid file extation
		function getFileName($FileName)
		{
			$extation = substr(strrchr($FileName,"."),1);
			$name = str_replace($extation,'',$FileName);
			return $name;
		}
		//This function use for check SQL keywords will not enter user.
		function SQLInjection($value)
		{
			$keyword = array(1 => "select", 2 => "insert", 3 => "update", 4 => "delete", 5 => "kill", 
			6 => "from", 7 => "where", 8 => "like", 9 => "drop", 10 => "truncate", 
			11 => "not", 12 => "create", 13 => "database", 14 => "valid", 15 => "alter", 
			16 => "modify", 17 => "table", 18 => "--", 19 => "error", 20 => "fetch",
			21 => "modifies", 22 => "replace", 23 => "terminated", 24 => "unlock", 25 => "lock",
			26 => "upgrade", 27 => "out", 28 => "rename", 29 => "exit", 30 => "warning", 31=> "<", 32 => ">", 
			33=>"<script>",34=>"</script>",35=>"<script",36=>"script>");
			
			$val 	= strtolower($value);
			$len	= strlen($val);
			//echo $len;
			for($i=0;$i<$len;$i++)
			{
				$word = split(" ",$val);
				//print_r($word);
				if(in_array($word[$i],$keyword))
				{
					return $value;
				}
			}
		}
		//This function use for valid mysql date format.
		function validMysqlDate($Day,$Month,$Year)
		{
			$MysqlDate = $Year."-".$Month."-".$Day;
			return $MysqlDate;
		}
		function AUS_Pincode_Length($pincode) 
		{
			if(strlen($pincode) != 4)
			{
				return false;
			}
			else
			{
				return true;
			}
			
		}
		function UK_Pincode_Length($pincode) 
		{
			if((strlen($pincode) < 6) || (strlen($pincode) > 10))
			{
				return false;
			}
			else
			{
				return true;
			}
			
		}
		function US_Pincode_Length($pincode) 
		{
			if((strlen($pincode) < 8) || (strlen($pincode) > 10))
			{
				return false;
			}
			else
			{
				return true;
			}
			
		}
		function valid_AUS_Pincode($pincode)
		{
			if(ctype_digit($pincode))
			{
				return true;
			}
			else
			{
				return false;
			}
		}	 
		function valid_UK_Pincode($pincode)
		{
			if(ctype_alnum($pincode))
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		function valid_US_Pincode($pincode)
		{
			if(ctype_alnum($pincode))
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		function valid_VIN_Number($VinNum)
		{
			if(strlen($VinNum) != "17")
			{
				echo "The number of characters you have entered (".strlen($VinNum).") does not match a valid VIN number.";
				exit;
			}
			$VinNum = strtoupper($VinNum);
			$Model = array("A", "B", "C", "D", "E", "F", "G", "H", "J", "K", "L", "M", "N", "P", "R", "S", "T", "V", "W", "X", "Y");
			$Weight = array("8", "7", "6", "5", "4", "3", "2", "10", "9", "8", "7", "6", "5", "4", "3", "2");
			$Char = array("A", "B", "C", "D", "E", "F", "G", "H", "J", "K", "L", "M", "N", "P", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2", "3", "4", "5", "6", "7", "8", "9");
			$CharVals = array("1", "2", "3", "4", "5", "6", "7", "8", "1", "2", "3", "4", "5", "7", "9", "2", "3", "4", "5", "6", "7", "8", "9", "0", "1", "2", "3", "4", "5", "6", "7", "8", "9");
			$VinChars = array();
			$Counter = 0;
			
			foreach ($Char as $CurrChar)
			{
				$VinChars[$CurrChar] = $CharVals[$Counter];
				$Counter++;
			}
			$CheckDigits = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "X");
			$Counter = 0;
			$VinArray = array();
			
			for ($i = 0; $i < 17; $i++)
			{
				if ($i!=8)
				{
					$VinArray[$Counter] = substr($VinNum, $i, 1);
					$Counter++;
				}
			}
			$Total = 0;
			for ($i = 0; $i < 16; $i++)
			{
				$ThisDigit = $VinArray[$i];
				$ThisTotal = $Weight[$i] * $VinChars[$ThisDigit];
				$Total = $Total + $ThisTotal;
			}
			$Remainder = fmod($Total, 11);
			if (substr($VinNum, 8, 1)!= $CheckDigits[$Remainder])
			{
				echo "VIN number is not valid.<br />";
			}
			else
			{
				echo "VIN number is valid.<br />";
			}
			echo "Computed check digit: ".$CheckDigits[$Remainder]."<br />";
			echo "Your check digit: ".substr($VinNum, 8, 1);
		}
		function justclean($string)
		{
			// Replace other special chars
			$specialCharacters = array(
			'#' => '',
			'$' => '',
			'%' => '',
			'&' => '',
			'@' => '',
			'.' => '',
			'Ä' => '',
			'+' => '',
			'=' => '',
			'ß' => '',
			'\\' => '',
			'/' => '',
			);
			
			while (list($character, $replacement) = each($specialCharacters)) 
			{
				$string = str_replace($character, '-' . $replacement . '-', $string);
			}
			$string = strtr($string,
			"¿¡¬√ƒ≈&#65533; ·‚„‰Â“”‘’÷ÿÚÛÙıˆ¯»… ÀËÈÍÎ«ÁÃÕŒœÏÌÓÔŸ⁄€‹˘˙˚¸ˇ—Ò",
			"AAAAAAaaaaaaOOOOOOooooooEEEEeeeeCcIIIIiiiiUUUUuuuuyNn"
			);
			
			// Remove all remaining other unknown characters
			$string = preg_replace('/[^a-zA-Z0-9\-]/', ' ', $string);
			$string = preg_replace('/^[\-]+/', '', $string);
			$string = preg_replace('/[\-]+$/', '', $string);
			$string = preg_replace('/[\-]{2,}/', ' ', $string);
			
			return $string;
		}
		function GenerateCode($length = 8)
		{
			$random= "";
			srand((double)microtime()*1000000);
			$data .= "0123456789";
			for($i = 0; $i < $length; $i++)
			{
				$random .= substr($data, (rand()%(strlen($data))), 1);
			}
			return $random;
		}
		function GeneratePassword($length = 8)
		{
			$password= "";
			srand((double)microtime()*1000000);
			$data .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
			for($i = 0; $i < $length; $i++)
			{
				$password .= substr($data, (rand()%(strlen($data))), 1);
			}
			return $password;
		}
		function GenerateFileName($length = 10)
		{
			$flname = "";
			$data	= "";
			srand((double)microtime()*1000000);
			$data .= "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
			for($i = 0; $i < $length; $i++)
			{
				$flname .= substr($data, (rand()%(strlen($data))), 1);
			}
			return $flname;
		}
		function dateFormat($Formate,$date,$time=0)
		{
			$datetime	= explode(" ",$date);
			$dt 		= explode("-",$datetime[0]);
			if(count($datetime) == 2)
			{
				$Time		= $datetime[1];
			}
			$Year		= $dt[0];
			$Month		= $dt[1];
			$Day		= $dt[2];
			
			if($Formate != "")
			{
				if($Formate == 'dd/mm/yyyy')
				{
					if($time == 1)
					{
						$Date = $Day.'/'.$Month.'/'.$Year." ".$Time;
					}
					else
					{
						$Date = $Day.'/'.$Month.'/'.$Year;
					}
					return $Date;
				}
				if($Formate == 'dd-mm-yyyy')
				{
					if($time == 1)
					{
						$Date = $Day.'-'.$Month.'-'.$Year." ".$Time;
					}
					else
					{
						$Date = $Day.'-'.$Month.'-'.$Year;
					}
					return $Date;
				}
				elseif($Formate == 'dd/mm/yy')
				{
					$yy = substr($Year,2);
					if($time == 1)
					{
						$Date = $Day.'/'.$Month.'/'.$yy." ".$Time;
					}
					else
					{
						$Date = $Day.'/'.$Month.'/'.$yy;
					}
					return $Date;
				}
				elseif($Formate == 'dd-mm-yy')
				{
					$yy = substr($Year,2);
					if($time == 1)
					{
						$Date = $Day.'-'.$Month.'-'.$yy." ".$Time;
					}
					else
					{
						$Date = $Day.'-'.$Month.'-'.$yy;
					}
					return $Date;
				}
				elseif($Formate == 'mm/dd/yyyy')
				{
					if($time == 1)
					{
						$Date = $Month.'/'.$Day.'/'.$Year." ".$Time;
					}
					else
					{
						$Date = $Month.'/'.$Day.'/'.$Year;
					}
					return $Date;
				}
				elseif($Formate == 'mm-dd-yyyy')
				{
					if($time == 1)
					{
						$Date = $Month.'-'.$Day.'-'.$Year." ".$Time;
					}
					else
					{
						$Date = $Month.'-'.$Day.'-'.$Year;
					}
					return $Date;
				}
				elseif($Formate == 'mm/dd/yy')
				{
					$yy = substr($Year,2);
					if($time == 1)
					{
						$Date = $Month.'/'.$Day.'/'.$yy." ".$Time;
					}
					else
					{
						$Date = $Month.'/'.$Day.'/'.$yy;
					}
					return $Date;
				}
				elseif($Formate == 'mm-dd-yy')
				{
					$yy = substr($Year,2);
					if($time == 1)
					{
						$Date = $Month.'-'.$Day.'-'.$yy." ".$Time;
					}
					else
					{
						$Date = $Month.'-'.$Day.'-'.$yy;
					}
					return $Date;
				}
				elseif($Formate == 'yyyy/mm/dd')
				{
					if($time == 1)
					{
						$Date = $Year.'/'.$Month.'/'.$Day." ".$Time;
					}
					else
					{
						$Date = $Year.'/'.$Month.'/'.$Day;
					}
					return $Date;
				}
				elseif($Formate == 'yyyy-mm-dd')
				{
					if($time == 1)
					{
						$Date = $Year.'-'.$Month.'-'.$Day." ".$Time;
					}
					else
					{
						$Date = $Year.'-'.$Month.'-'.$Day;
					}
					return $Date;
				}
				elseif($Formate == 'yy/mm/dd')
				{
					$yy = substr($Year,2);
					if($time == 1)
					{
						$Date = $yy.'/'.$Month.'/'.$Day." ".$Time;
					}
					else
					{
						$Date = $yy.'/'.$Month.'/'.$Day;
					}
					return $Date;
				}
				elseif($Formate == 'yy-mm-dd')
				{
					$yy = substr($Year,2);
					if($time == 1)
					{
						$Date = $yy.'-'.$Month.'-'.$Day." ".$Time;
					}
					else
					{
						$Date = $yy.'-'.$Month.'-'.$Day;
					}
					return $Date;
				}
				elseif($Formate == 'd-f-yyyy')
				{
					if($Month == "01")
					{
						$Month = 'January';
					}
					elseif($Month == "02")
					{
						$Month = 'February';
					}
					elseif($Month == "03")
					{
						$Month = 'March';
					}
					elseif($Month == "04")
					{
						$Month = 'April';
					}
					elseif($Month == "05")
					{
						$Month = 'May';
					}
					elseif($Month == "06")
					{
						$Month = 'June';
					}
					elseif($Month == "07")
					{
						$Month = 'July';
					}
					elseif($Month == "08")
					{
						$Month = 'August';
					}
					elseif($Month == "09")
					{
						$Month = 'September';
					}
					elseif($Month == "10")
					{
						$Month = 'October';
					}
					elseif($Month == "11")
					{
						$Month = 'November';
					}
					elseif($Month == "12")
					{
						$Month = 'December';
					}
					if($time == 1)
					{
						$Date = $Day.' '.$Month.', '.$Year." ".$Time;
					}
					else
					{
						$Date = $Day.' '.$Month.', '.$Year;
					}
					return $Date;		
				}
				elseif($Formate == 'd-sf-yyyy')
				{
					if($Month == "01")
					{
						$Month = 'Jan';
					}
					elseif($Month == "02")
					{
						$Month = 'Feb';
					}
					elseif($Month == "03")
					{
						$Month = 'March';
					}
					elseif($Month == "04")
					{
						$Month = 'April';
					}
					elseif($Month == "05")
					{
						$Month = 'May';
					}
					elseif($Month == "06")
					{
						$Month = 'June';
					}
					elseif($Month == "07")
					{
						$Month = 'July';
					}
					elseif($Month == "08")
					{
						$Month = 'August';
					}
					elseif($Month == "09")
					{
						$Month = 'Sept';
					}
					elseif($Month == "10")
					{
						$Month = 'Oct';
					}
					elseif($Month == "11")
					{
						$Month = 'Nov';
					}
					elseif($Month == "12")
					{
						$Month = 'Dec';
					}
					if($time == 1)
					{
						$Date = $Day.' '.$Month.', '.$Year." ".$Time;
					}
					else
					{
						$Date = $Day.' '.$Month.', '.$Year;
					}
					return $Date;		
				}
				elseif($Formate == 'd-f-yy')
				{
					if($Month == "01")
					{
						$Month = 'January';
					}
					elseif($Month == "02")
					{
						$Month = 'February';
					}
					elseif($Month == "03")
					{
						$Month = 'March';
					}
					elseif($Month == "04")
					{
						$Month = 'April';
					}
					elseif($Month == "05")
					{
						$Month = 'May';
					}
					elseif($Month == "06")
					{
						$Month = 'June';
					}
					elseif($Month == "07")
					{
						$Month = 'July';
					}
					elseif($Month == "08")
					{
						$Month = 'August';
					}
					elseif($Month == "09")
					{
						$Month = 'September';
					}
					elseif($Month == "10")
					{
						$Month = 'October';
					}
					elseif($Month == "11")
					{
						$Month = 'November';
					}
					elseif($Month == "12")
					{
						$Month = 'December';
					}
					$yy = substr($Year,2);
					if($time == 1)
					{
						$Date = $Day.' '.$Month.', '.$yy." ".$Time;
					}
					else
					{
						$Date = $Day.' '.$Month.', '.$yy;
					}
					return $Date;
				}
				elseif($Formate == 'f-d-yyyy')
				{
					if($Month == "01")
					{
						$Month = 'January';
					}
					elseif($Month == "02")
					{
						$Month = 'February';
					}
					elseif($Month == "03")
					{
						$Month = 'March';
					}
					elseif($Month == "04")
					{
						$Month = 'April';
					}
					elseif($Month == "05")
					{
						$Month = 'May';
					}
					elseif($Month == "06")
					{
						$Month = 'June';
					}
					elseif($Month == "07")
					{
						$Month = 'July';
					}
					elseif($Month == "08")
					{
						$Month = 'August';
					}
					elseif($Month == "09")
					{
						$Month = 'September';
					}
					elseif($Month == "10")
					{
						$Month = 'October';
					}
					elseif($Month == "11")
					{
						$Month = 'November';
					}
					elseif($Month == "12")
					{
						$Month = 'December';
					}
					if($time == 1)
					{
						$Date = $Month.' '.$Day.', '.$Year." ".$Time;
					}
					else
					{
						$Date = $Month.' '.$Day.', '.$Year;
					}
					return $Date;		
				}
				elseif($Formate == 'f-d-yy')
				{
					if($Month == "01")
					{
						$Month = 'January';
					}
					elseif($Month == "02")
					{
						$Month = 'February';
					}
					elseif($Month == "03")
					{
						$Month = 'March';
					}
					elseif($Month == "04")
					{
						$Month = 'April';
					}
					elseif($Month == "05")
					{
						$Month = 'May';
					}
					elseif($Month == "06")
					{
						$Month = 'June';
					}
					elseif($Month == "07")
					{
						$Month = 'July';
					}
					elseif($Month == "08")
					{
						$Month = 'August';
					}
					elseif($Month == "09")
					{
						$Month = 'September';
					}
					elseif($Month == "10")
					{
						$Month = 'October';
					}
					elseif($Month == "11")
					{
						$Month = 'November';
					}
					elseif($Month == "12")
					{
						$Month = 'December';
					}
					$yy = substr($Year,2);
					if($time == 1)
					{
						$Date = $Month.' '.$Day.', '.$yy." ".$Time;
					}
					else
					{
						$Date = $Month.' '.$Day.', '.$yy;
					}
					return $Date;
				}
			}
		}
		function FormatedDate($date)
		{
			$datetime	= explode(" ",$date);
			$dt 		= explode("-",$datetime[0]);
			$Year		= $dt[0];
			$Month		= $dt[1];
			$Day		= $dt[2];
			
			if($Month == "01")
			{
				$Month = 'January';
			}
			elseif($Month == "02")
			{
				$Month = 'February';
			}
			elseif($Month == "03")
			{
				$Month = 'March';
			}
			elseif($Month == "04")
			{
				$Month = 'April';
			}
			elseif($Month == "05")
			{
				$Month = 'May';
			}
			elseif($Month == "06")
			{
				$Month = 'June';
			}
			elseif($Month == "07")
			{
				$Month = 'July';
			}
			elseif($Month == "08")
			{
				$Month = 'August';
			}
			elseif($Month == "09")
			{
				$Month = 'September';
			}
			elseif($Month == "10")
			{
				$Month = 'October';
			}
			elseif($Month == "11")
			{
				$Month = 'November';
			}
			elseif($Month == "12")
			{
				$Month = 'December';
			}
			$Date = $Month." ".$Day.", "." ".$Year." ".$datetime[1];
			return $Date;
		}
		function FormatedDateTime($date,$time)
		{
			$dt 	= explode("-",$date);
			$Year	= $dt[0];
			$Month	= $dt[1];
			$Day	= $dt[2];
			
			if($Month == "01")
			{
				$Month = 'January';
			}
			elseif($Month == "02")
			{
				$Month = 'February';
			}
			elseif($Month == "03")
			{
				$Month = 'March';
			}
			elseif($Month == "04")
			{
				$Month = 'April';
			}
			elseif($Month == "05")
			{
				$Month = 'May';
			}
			elseif($Month == "06")
			{
				$Month = 'June';
			}
			elseif($Month == "07")
			{
				$Month = 'July';
			}
			elseif($Month == "08")
			{
				$Month = 'August';
			}
			elseif($Month == "09")
			{
				$Month = 'September';
			}
			elseif($Month == "10")
			{
				$Month = 'October';
			}
			elseif($Month == "11")
			{
				$Month = 'November';
			}
			elseif($Month == "12")
			{
				$Month = 'December';
			}
			$DateTime = $Day."."." ".$Month.""." ".$Year." "."at"." ".$time;
			return $DateTime;
		}
		function breakString($inputString)
		{
			$len 	= ceil(strlen($inputString)/2);
			$len1	= strlen($inputString)-$len;
			$str1	= substr($inputString,0,$len);
			$str2	= substr($inputString,$len);
			return 	$str1.'<br>'.$str2;
		}
		function FCKEditor($Name,$Value,$Height,$Width,$ToolBarSet)
		{
			include("fckeditor/fckeditor.php");
			$sBasePath	= 'fckeditor/';
			$oFCKeditor = new FCKeditor($Name);
			$oFCKeditor->BasePath = $sBasePath;
			$oFCKeditor->Value=$Value;
			$oFCKeditor->Height=$Height;
			$oFCKeditor->Width=$Width;
			$oFCKeditor->ToolbarSet=$ToolBarSet;
			$oFCKeditor->Create();
		}
		//a function that will split a string into an array chunked with $num lenght.
		function strsplt($thetext,$num=1)
		{
			$arr=array();
			$i=0;
			while ($i<strlen($thetext))
			{
				$y=substr($thetext,$i,$num);
				if (isset($y))
				{
					$arr[]=$y;
				}
				$i=$i+$num;
			}
			return $arr;
		}
		//a function that will locate a key of an array
		function key_locator($code,$strcode)
		{
			while (list($key, $val) = each($strcode)) 
			{
				if ($val==$code)
				{
					$x=$key;
				}
			}
			return $x;
		}
		//a function that will array the sum of key and the random number.
		function add_random_key($thetext)
		{
			global $strcode;
			$newcode=array();
			$rnd=rand(1,intval(count($strcode))-2);
			for ($i=0;$i<strlen($thetext);$i++)
			{
				$x=key_locator(substr($thetext,$i,1),$strcode);
				$temp=$x+$rnd;
				if($temp>intval(count($strcode)-1))
				{
					$temp=$temp-intval(count($strcode)-1);
				}
				$newcode[]=$temp;
				$temp="";
			}
			$newcode[]=$rnd;
			return $newcode;
		}
		//A function that will convert from key to value from an array
		function convert_keyto_value($thetext)
		{
			global $strcode;
			$a=add_random_key($thetext);
			while (in_array(count($strcode)-1,$a)==true)
			{
				$a=add_random_key($thetext);
			}
			for ($i=0;$i<strlen($thetext)+1;$i++)
			{
				@$output.=$strcode[$a[$i]];
			}
			return $output;
		}
		//A function that will remove the random value and back to original value
		function derandomized($thetext)
		{
			global $strcode;;
			$arr=strsplt($thetext,intval(strlen($thetext)-1));
			for ($x=0;$x<strlen($thetext)-1;$x++)
			{
				$s=key_locator(substr($arr[0],$x,1),$strcode);
				$t=key_locator($arr[1],$strcode);
				$newcode=$s-$t;
				if($newcode<0)
				{
					$newcode=$newcode+intval(count($strcode)-1);
				}
				if($newcode==0&&$s<>0)
				{
					$newcode=count($strcode)-1;
				}
				$output.=$strcode[$newcode];
			}
			return $output;
		}
		//A function that will randomly chunk a string then add them as one string.
		function encryptString($thetext)
		{
			global $strcode;
			$nstr = $this->strsplt($thetext,2);
			for($i=0;$i<count($nstr);$i++)
			{
				@$output.=convert_keyto_value($nstr[$i]);
			}
			return $output;
		}
		//A function that will remove the random values from chunked string and regrouped
		//them as original string.
		function decryptString($thetext)
		{
			global $strcode;;
			$nstr = $this->strsplt($thetext,3);
			for ($i=0;$i<count($nstr);$i++)
			{
				$output.=derandomized($nstr[$i]);
			}
			return $output;
		} 
		function getFavicon()
		{
			if(file_exists('images/favicon.ico'))
			{
				$favicon = '<link rel="SHORTCUT ICON" href="images/favicon.ico">';
			} 
			return $favicon;
		}
		function getRealIpAddr()
		{
			if(!empty($_SERVER['HTTP_CLIENT_IP']))
			//check ip from share internet
			{
				$ip=$_SERVER['HTTP_CLIENT_IP'];
			}
			elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
			//to check ip is pass from proxy
			{
				$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
			}
			else
			{
				$ip=$_SERVER['REMOTE_ADDR'];
			}
			return $ip;
		}
		function DisplayPagingMutli($start,$total,$link,$len,$param='')
		{
			$en = $start +$len;
			if($start==0)
			{
				if($total>0)
				{
					$start1=1;
				}
				else 
				{
					$start1=0;
				}
			}
			else 
			{
				$start1=$start+1;
			}
			if($en>$total)
			{
				$en = $total;
			}
			if($total!=0)
			{
				$pagecount=($total % $len)?(intval($total / $len)+1):($total / $len);
			}
			else
			{
				$norec = "<span align='center'><br>No Results found<br></span>";
				$pagecount=0;
				return $norec;
			}
			$norec = "<table  cellpadding='0' cellspacing='0' width='100%' border='0' style='text-align:left' class='paging'><tr>";
			$norec .=  "<td style='width:30%;vertical-align:bottom;'>".LABEL_SHOWING." $start1 - $en ".LABEL_OF." $total ".LABEL_RECORDS.".</td>";
			$norec .=  "<td style='width:70%;text-align:right;vertical-align:bottom;'>";
	        if($en>$len)
	        {
				$en1=$start-$len;
				$norec .= "<a href='$link&nbsp;start=$en1' title='".LABEL_PREVIOUS."'>&laquo; ".LABEL_PREVIOUS."</a><span class='text1'>&nbsp;|&nbsp;</span>" ;
			}
			else
			$norec .= "<span>&laquo; ".LABEL_PREVIOUS."</span><span class='text1'>&nbsp;|&nbsp;</span>" ;
			
			// Caliculating Page Values
			$numstr="";
			$curpage=intval(($start+1)/$len)+1;
			if($pagecount > $len)
			{
				
				$istart=(intval($curpage/$len) * 10)+1;
				if($istart + $len > $pagecount)
				$istart=$pagecount - 9;
				$pagecount=$len;
			}
			else
			$istart=1;
			for($i=$istart;$i<$pagecount+$istart;$i++)
			{
				$ed=($i-1)*$len;
				if($start!=$ed)
				{
					$numstr.= "<a href='$link&nbsp;start=$ed'title='$i'>$i</a><span>&nbsp;|&nbsp;</span>";
				}
				else
				{ 
					$numstr.= "<span><strong>$i</strong></span><span>&nbsp;|&nbsp;</span>";
				}
			}
			$norec .= $numstr;
	        if($en<$total)
			{
				$en2=$start+$len;
				$norec .= "<a href='$link&nbsp;start=$en2' title='".LABEL_NEXT."'>".LABEL_NEXT." &raquo;</a>" ;
			}
			else
			{
				$norec .= "<span>".LABEL_NEXT." &raquo;</span>" ;
			}
			$norec .= "</td></tr></table>";
			return $norec;  
		}
		function DisplayPaging($start,$total,$link,$len,$param='')
		{
			$en = $start +$len;
			if($start==0)
			{
				if($total>0)
				{
					$start1=1;
				}
				else 
				{
					$start1=0;
				}
			}
			else 
			{
				$start1=$start+1;
			}
			if($en>$total)
			{
				$en = $total;
			}
			if($total!=0)
			{
				$pagecount=($total % $len)?(intval($total / $len)+1):($total / $len);
			}
			else
			{
				$norec = "<span align='center'><br>No Results found<br></span>";
				$pagecount=0;
				return $norec;
			}
			$norec = "<table cellpadding='0' cellspacing='0' align='left' width='100%' border='0'><tr>";
			$norec .=  "<td width='40%'  height='25' align='left'>Showing $start1 - $en of $total Records.</td>";
			$norec .=  "<td width='60%' align='right' valign='bottom'>";
	        if($en>$len)
	        {
				$en1=$start-$len;
				$norec .= "<a href='$link&start=$en1' title='Previous'>&laquo; Previous</a><span class='text1'>&nbsp;|&nbsp;</span>" ;
			}
			else
			$norec .= "<span>&laquo; Previous</span><span class='text1'>&nbsp;|&nbsp;</span>" ;
			
			// Caliculating Page Values
			$numstr="";
			$curpage=intval(($start+1)/$len)+1;
			if($pagecount > $len)
			{
				
				$istart=(intval($curpage/$len) * 10)+1;
				if($istart + $len > $pagecount)
				$istart=$pagecount - 9;
				$pagecount=$len;
			}
			else
			$istart=1;
			for($i=$istart;$i<$pagecount+$istart;$i++)
			{
				$ed=($i-1)*$len;
				if($start!=$ed)
				{
					$numstr.= "<a href='$link&start=$ed'title='$i'>$i</a><span>&nbsp;|&nbsp;</span>";
				}
				else
				{ 
					$numstr.= "<span><strong><span class='pagingSelected'>$i</span></strong></span><span>&nbsp;|&nbsp;</span>";
				}
			}
			$norec .= $numstr;
	        if($en<$total)
			{
				$en2=$start+$len;
				$norec .= "<a href='$link&start=$en2' title='Next'>Next &raquo;</a>" ;
			}
			else
			{
				$norec .= "<span>Next &raquo;</span>" ;
			}
			$norec .= "</td></tr></table>";
			return $norec;  
		}
		function validSEOTitle($string)
		{
			// Replace other special chars
			$specialCharacters = array(
			'%' => '',
			'?' => '',
			'.' => '',
			'@' => '',
			'+' => '',
			'=' => '',
			' ' => '-',
			'\\' => '',
			'/' => '',
			'~' => '',
			'`' => '',
			'^' => '',
			'\'' => '',
			'\"' => '',
			'*' => '',
			'!' => '',
			':' => '',
			'<' => '',
			'>' => '',
			'|' => '',
			'#' => '',
			'(' => '',
			')' => '',
			'$' => '',
			'&' => '',
			',' => '',
			);
			
			while (list($character, $replacement) = each($specialCharacters)) 
			{
				$string = str_replace($character, '' . $replacement , $string);
			}
			$string = strtr($string,
			"¿¡¬√ƒ≈·‚„‰Â“”‘’÷ÿÚÛÙıˆ¯»… ÀËÈÍÎ«ÁÃÕŒœÏÌÓÔŸ⁄€‹˘˙˚¸ˇ—Ò",
			"AAAAAAaaaaaaOOOOOOooooooEEEEeeeeCcIIIIiiiiUUUUuuuuyNn"
			);
			return strtolower($string);
		}
		function getPageName()
		{
			$PageName	= substr(strrchr($_SERVER['PHP_SELF'],'/'),1);
			return $PageName;
		}
		function highlight_search_criteria($search_results, $search_criteria)
		{
			//echo $search_criteria;exit;
			if (empty($search_criteria)) 
			{
				return $search_results;
			} 
			else
			{
				$start_tag = '<span style="background-color:#F7DD00; font-weight:bold; color:#4B4949;">';
				$end_tag = '</span>';
				$highlighted_results = $start_tag.$search_criteria.$end_tag;
				//echo $highlighted_results;
				return eregi_replace($search_criteria, $highlighted_results, $search_results);
			}
		}
		//function to find country and city from IP address
		function countryCityFromIP($ipAddr)
		{
			
			//verify the IP address for the
			ip2long($ipAddr)== -1 || ip2long($ipAddr) === false ? trigger_error("Invalid IP", E_USER_ERROR) : "";
			$ipDetail=array(); //initialize a blank array
			
			//get the XML result from hostip.info
			$xml = file_get_contents("http://api.hostip.info/?ip=".$ipAddr);
			
			//get the city name inside the node <gml:name> and </gml:name>
			preg_match("@<Hostip>(\s)*<gml:name>(.*?)</gml:name>@si",$xml,$match);
			$ipDetail['city']=$match[2]; 
			preg_match("@<countryName>(.*?)</countryName>@si",$xml,$matches);
			$ipDetail['country']=$matches[1];
			
			//get the country name inside the node <countryName> and </countryName>
			preg_match("@<countryAbbrev>(.*?)</countryAbbrev>@si",$xml,$cc_match);
			$ipDetail['country_code']=$cc_match[1]; //assing the country code to array
			return $ipDetail;
		}
		
		function validImageFile($filearray)
		{
			$returnvar = 0;
			$fileexp = explode(".",$filearray["name"]);
			$extension = strtolower($fileexp[count($fileexp)-1]);
			if($extension!='jpg'  && $extension != 'jpeg' && $extension!="png" && $extension!="gif" && $extension!="bmp")
			{
				$returnvar = 1;
			}
			elseif(strtolower($filearray["type"])!="image/png" && strtolower($filearray["type"])!="image/png24" && strtolower($filearray["type"])!="image/jpeg"  && strtolower($filearray["type"])!="image/jpg" && strtolower($filearray["type"])!="image/gif")
			{
				$returnvar = 1;
			}
			else
			{
				$filename = md5(time()).".".$extension;
				move_uploaded_file($filearray["tmp_name"],UPLOAD_IMAGE_PATH.$filename);
				$thumbfilename = "thumb_".$filename;
				$thumbfilenamebig = "thumbbig_".$filename;
				$thumbnail = new thumbnail();
				$thumbnail->load(UPLOAD_IMAGE_PATH.$filename);
				$thumbnail->resize(68,68);
				$thumbnail->save(UPLOAD_IMAGE_PATH.$thumbfilename);
				$thumbnail->resize(113,113);
				$thumbnail->save(UPLOAD_IMAGE_PATH.$thumbfilenamebig);
			}
			return $returnvar."~".$filename;
		}
		
		function getSiteInfo()
		{
			$dbfunction = new dbfunctions();
			$dbfunction->SelectQuery("tbl_admin","*","iAdminId='1'");
			$objsel = $dbfunction->getFetchArray();
			return $objsel;
		}
		
		function NewDateFormat($date)
		{
			$substr = substr($date,0,10);
			$year = substr($substr,0,4);
			$month = substr($substr,5,2);
			$day = substr($substr,8,2);
			return $day."/".$month."/".$year;
		}
		
		function SendMail($from,$to,$subject,$message)
		{
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
			$headers .= 'From: '.$from.'' . "\r\n";
			
			@mail($to,$subject,$message,$headers);
		}
		/*function redirect($filename,$msg='')
			{
			if($msg!='')
			{
			$filename .='?'.$msg;
			}
			?>
			<script type="text/javascript">
			window.location.href = "<?php echo $filename;?>";
			</script>
			<?php 
		}*/
		
		function redirect($pagename){
			if(!@header("location: ".$pagename)){
				echo '<script language="javascript">window.location.href = "'.$pagename.'"</script>';
			}
			exit;
		}
		function encryptPassword($PassVal)
		{
			$newPass = base64_encode($PassVal);
			return $newPass;
		}
		function decryptPassword($PassVal)
		{
			$newPass = base64_decode($PassVal);
			return $newPass;
		}
		
		function DateTimeFormatDb($date)
		{
			$dtarr 	= explode(" ",$date);
			$dtarr1 = explode("-",$dtarr[0]);
			$dtarr2 = explode(":",$dtarr[1]);
			$mnth 	= $dtarr1[0];
			$yr  	= $dtarr1[2];
			$dt  	= $dtarr1[1];
			$hh	 	= $dtarr2[0];
			$min 	= $dtarr2[1];	
			return  $yr."-".$mnth."-".$dt." ".$hh.":".$min;
		}
		
		function DateTimeFormatDisplay($date)
		{
			$dtarr = explode(" ",$date);
			$dtarr1 = explode("-",$dtarr[0]);
			$dtarr2 = explode(":",$dtarr[1]);
			$mnth 	= $dtarr1[1];
			$yr  	= $dtarr1[0];
			$dt  	= $dtarr1[2];
			$hh	 	= $dtarr2[0];
			$min 	= $dtarr2[1];	
			return  $mnth."-".$dt."-".$yr." ".$hh.":".$min;
		}
		
		
		function CSVExport($title,$data,$filename) {
			$csv_cont = "";
			$csv_content = "";
			if(!empty($filename)) {
				$filename = $filename."_".time().".csv";
				$csv_header = implode(",",$title)."\r\n";
				foreach($data as $d) {
					$csv_cont .= implode(",",$d)."\r\n";
				}
				header("Content-type:text/octect-stream");
				header("Content-Disposition:attachment;filename=$filename");
				echo $csv_content .= $csv_header.$csv_cont;
				exit;
			}
		}
		function CSVExportSave($title,$data,$filename) {
			$csv_cont = "";
			$csv_content = "";
			if(!empty($filename)) {
				$filename = $filename."_".time().".csv";  
				$csv_header = implode(",",$title)."\r\n";
				foreach($data as $d) {
					$csv_cont .= implode(",",$d)."\r\n";
				}
				//header("Content-type:text/octect-stream");
				//header("Content-Disposition:attachment;filename=$filename");
				$csv_content .= $csv_header.$csv_cont;
				$attachmenFile = 'report/'.$filename;
				$fp = fopen($attachmenFile,'w');
				fwrite($fp,$csv_content);
				fclose($fp);
				return $attachmenFile;
			}
		}
		function CSVExport1($title,$data,$filename) {
			$csv_cont = "";
			$csv_content = "";
			if(!empty($filename)) {
				$filename = $filename."_".time().".csv";  
				$csv_header = implode(",",$title)."\r\n";
				foreach($data as $d) {
					$csv_cont .= implode(",",$d)."\r\n";
				}
				header("Content-type:text/octect-stream");
				header("Content-Disposition:attachment;filename=$filename");
				return $csv_content .= $csv_header.$csv_cont;
				exit;
			}
		}		
		
		function importCSV($filename)
		{
			//$allowedsite = array('ADL', 'AKL', 'CHC', 'BNE', 'CBR', 'MEL', 'PER', 'SYD', 'TPANZ');
			if (($handle = fopen($filename, "r")) !== FALSE) {
				while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
					$row++;
					if($row==1) { continue; }
					$num = count($data);
					$categoryidofrow = "";
					$locationidofrow = "";
					$courseidofrow = "";
					$coursedateins = "";
					$coursetime = "";
					$insertlocation = "0";
					
					for ($c=0; $c < $num; $c++) {
						/*if(in_array($data[0], $allowedsite))
						{*/
						$courseurl = mysql_real_escape_string($data[7]);
						$insertlocation = '1';
						if($c==0)
						{
							$qrysel = "select * from tbl_location where vCode='".mysql_real_escape_string($data[$c])."'";
							$ressel = mysql_query($qrysel) or die(mysql_error());
							$totalsel = mysql_num_rows($ressel);
							$objrow = mysql_fetch_array($ressel);
							$locationidofrow = $objrow["iLocationId"];
						}
						elseif($c==2)
						{
							$qrysel1 = "select * from tbl_category where vCategoryName='".$data[$c]."'";
							$ressel1 = mysql_query($qrysel1);
							$totalsel1 = mysql_num_rows($ressel1);
							$objrow1 = mysql_fetch_array($ressel1);
							$categoryidofrow = $objrow1["iCategoryId"];
						}
						elseif($c==3)
						{
							$qrysel2 = "select * from tbl_course where vClassCode='".$data[$c]."'";
							$ressel2 = mysql_query($qrysel2);
							$totalsel2 = mysql_num_rows($ressel2);
							$ClassCode = $data[3];	
							
							if($totalsel2<=0)
							{
								$qryins2 = "Insert into tbl_course (vCourseName, vLocation, dCreatedDate, dUpdatedDate, eStatus, vCategory, vUrl, vClassCode) values ('".addslashes($data[4])."', '', NOW(), NOW(), '1', '".$categoryidofrow."', '".$courseurl."', '".$ClassCode."')";
								mysql_query($qryins2) or die(mysql_error());
								$courseidofrow = mysql_insert_id();
							}
							else
							{
								$objrow2 = mysql_fetch_array($ressel2);
								$courseidofrow = $objrow2["iCourseId"];
							}
						}
						elseif($c==5)
						{
							$coursedate = str_replace("AM","",$data[$c]);	
							$coursedate = str_replace("PM","",$data[$c]);	
							$coursedate = explode(" ",$coursedate);
							
							$coursedate1 = explode("/",$coursedate[0]);
							$cousedatedb = $coursedate1[2]."-".$coursedate1[1]."-".$coursedate1[0];
							$cousedatetime = $coursedate1[2]."-".$coursedate1[1]."-".$coursedate1[0];
							$coursedateins = $cousedatetime;
							$coursetime = $coursedate[1];
						}
						elseif($c==6)
						{
							$courseenddate = str_replace("AM","",$data[$c]);	
							$courseenddate = str_replace("PM","",$data[$c]);	
							$courseenddate = explode(" ",$courseenddate);
							
							$courseenddate1 = explode("/",$courseenddate[0]);
							$couseenddatedb = $courseenddate1[2]."-".$courseenddate1[1]."-".$courseenddate1[0];
							$couseenddatetime = $courseenddate1[2]."-".$courseenddate1[1]."-".$courseenddate1[0];
							$courseenddateins = $couseenddatetime;
							$courseendtime = $courseenddate[1];
						}
						//}
					}
					
					if($insertlocation=="1")
					{
						$qrychk = "select * from tbl_course_detail where iCourse='".$courseidofrow."' and iLocation='".$locationidofrow."' and dDate='".$coursedateins."' and endDate='".$courseenddateins."' and tTime='".$coursetime."' and eTime='".$courseendtime."'";
						$reschk = mysql_query($qrychk);
						$totalchk = mysql_num_rows($reschk);
						
						if($totalchk<=0)
						{
							$qryinsc = "Insert into tbl_course_detail (iCourse, iLocation, dCreatedDate, dDate, endDate , dUpdatedDate, tTime, eTime) values('".$courseidofrow."', '".$locationidofrow."', NOW(), '".$coursedateins."', '".$courseenddateins."', NOW(), '".$coursetime."', '".$courseendtime."')";
							mysql_query($qryinsc) or die(mysql_error());
						}
					}
				}
			}		
		}
		
		// FRON FUNCTION FOR CMS PAGE
		function getPageContant($pageid)
		{
			
			$dbobjpage		= new dbfunctions();
			$sql   			= "select * from tbl_pages where isdeleted ='0' and status='1' and id=".$this->decrypt($pageid)."";
			$selectpage		= $dbobjpage->SimpleSelectQuery($sql);
			$totalpage		=$dbobjpage->getNumRows();
			if($totalpage >0)
			{
				$data = $dbobjpage->getFetchArray();
				return $data;
			}
		}
		function getIdFromSlug($slug)
		{
			$dbobjuser		= new dbfunctions();
			$dbobjuser->SelectQuery("tbl_pages","id",$dbobjuser->db_safe("slug_name='%1'",$slug),"");
			$totaluser 	= $dbobjuser->getNumRows();
			if($totaluser > 0)
			{
				$pageinfo = $dbobjuser->getFetchArray();
				return 	$pageinfo['id'];
				}else{
				return 0;	
			}
			
		}
		function getPageTitle($pagetitle = "")
		{ 
			/*$dbsitetitle =	new dbfunctions(); 
				$dbsitetitle->SelectQuery("tbl_admin",'vSiteName',$dbsitetitle->db_safe("iAdminId='%1'",'1'));
				$numRows = $dbsitetitle->getNumRows();
				if($numRows > 0){
				$Resdata 	= $dbsitetitle->getFetchArray();
				$SiteTitle 	= $Resdata["vSiteName"];
			}*/
			$SiteTitle = SITE_NAME;
			if(isset($_REQUEST['pageid']) && $_REQUEST['pageid'] !="" ||  $_GET['slug'] !='')
			{ 
				$pageid         = $_REQUEST['pageid'];
				$slugname		= $_REQUEST['slug'];
				$dbobjpage		= new dbfunctions();
				if($slugname != ""){
					$sql   			= "select id,page_title,seo_title from tbl_pages where isdeleted='0' and status='1' and slug_name='".$slugname."'";
					}else{
					$sql   		= "select id,page_title,seo_title from tbl_pages where isdeleted='0' and status='1' and id=".$this->decrypt($pageid)."";
				}
				$selectpage		= $dbobjpage->SimpleSelectQuery($sql);
				$totalpage		=$dbobjpage->getNumRows();
				
				
				if($totalpage >0)
				{
					$datatitle = $dbobjpage->getFetchArray(); 
					return $datatitle['seo_title']." | ".$SiteTitle;
					}else{
					return $pagetitle." | ".$SiteTitle;
				}
			}
			else if($pagetitle != '')
			{ 
				return $pagetitle." | ".$SiteTitle;
				
				}else{
				return "Home - ".$SiteTitle;
			}
		}
		function checkpageactive($id)
		{
			$where = "id= '".($id)."' and status='1'";
			$dbobjuser		= new dbfunctions();
			$dbobjuser->SelectQuery("tbl_pages","id",$where,"");
			$totaluser 	= $dbobjuser->getNumRows();
			if($totaluser > 0)
			{
				return 	true;
				}else{
				return false;	
			}
		}
		function includeJS()
		{
			$file_name   = $_SERVER['PHP_SELF'];
			$explode 	 = explode(".",$file_name);
			$jsfile_name = $explode[0];
			return $jsfile_name;
			// echo $jsfile_name;
		}
		
		function getSlug( $title, $raw_title = '', $context = 'display' ) {
			$title = strip_tags($title);
			// Preserve escaped octets.
			$title = preg_replace('|%([a-fA-F0-9][a-fA-F0-9])|', '---$1---', $title);
			// Remove percent signs that are not part of an octet.
			$title = str_replace('%', '', $title);
			// Restore octets.
			$title = preg_replace('|---([a-fA-F0-9][a-fA-F0-9])---|', '%$1', $title);
		/*
			if (seems_utf8($title)) {
				if (function_exists('mb_strtolower')) {
					$title = mb_strtolower($title, 'UTF-8');
				}
				$title = utf8_uri_encode($title, 200);
			}
		*/
			$title = strtolower($title);
			$title = preg_replace('/&.+?;/', '', $title); // kill entities
			$title = str_replace('.', '-', $title);

			if ( 'save' == $context ) {
				// Convert nbsp, ndash and mdash to hyphens
				$title = str_replace( array( '%c2%a0', '%e2%80%93', '%e2%80%94' ), '-', $title );

				// Strip these characters entirely
				$title = str_replace( array(
					// iexcl and iquest
					'%c2%a1', '%c2%bf',
					// angle quotes
					'%c2%ab', '%c2%bb', '%e2%80%b9', '%e2%80%ba',
					// curly quotes
					'%e2%80%98', '%e2%80%99', '%e2%80%9c', '%e2%80%9d',
					'%e2%80%9a', '%e2%80%9b', '%e2%80%9e', '%e2%80%9f',
					// copy, reg, deg, hellip and trade
					'%c2%a9', '%c2%ae', '%c2%b0', '%e2%80%a6', '%e2%84%a2',
					// acute accents
					'%c2%b4', '%cb%8a', '%cc%81', '%cd%81',
					// grave accent, macron, caron
					'%cc%80', '%cc%84', '%cc%8c',
				), '', $title );

				// Convert times to x
				$title = str_replace( '%c3%97', 'x', $title );
			}

			$title = preg_replace('/[^%a-z0-9 _-]/', '', $title);
			$title = preg_replace('/\s+/', '-', $title);
			$title = preg_replace('|-+|', '-', $title);
			$title = trim($title, '-');

			return $title;
		}


	}
?>					