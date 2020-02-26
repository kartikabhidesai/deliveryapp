<?php
class userfunction
{
	function getDirectoryName($v)
	{
		$dir = array();
		$dir[0]  = '../ads/';
		$dir[1]  = 'ads/';
		return $dir[$v];
	}
	function fetchCodeString($v)
	{
		$c = array();
		$c[1] = 'abcdefghijklmnopqrstuvwxyz';
		$c[2] = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$c[3] = '1234567890';
		$c[4] = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$c[5] = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
		return $c[$v];
	}
	function generateRandomCode($length, $codeString)
	{
		$chars =$codeString;
		$myCode = "";
		while (strlen($myCode) < $length) {
		$myCode .= $chars[mt_rand(0,strlen($chars))];
		}
		return $myCode;
	}
	//create thumbnail
	function createthumb($old_file,$new_file,$new_w,$new_h)
	{
		if (preg_match('/.jpg|.jpeg|.JPG/',$old_file)){
			$src_img=imagecreatefromjpeg($old_file);
		}
		if (preg_match('/.gif|.GIF/',$old_file)){
			$src_img=imagecreatefromgif($old_file);
		}
		$old_x=imageSX($src_img);
		$old_y=imageSY($src_img);
		if ($old_x > $old_y) {
			$thumb_w=$new_w;
			$thumb_h=$old_y*($new_h/$old_x);
		}
		if ($old_x < $old_y) {
			$thumb_w=$old_x*($new_w/$old_y);
			$thumb_h=$new_h;
		}
		if ($old_x == $old_y) {
			$thumb_w=$new_w;
			$thumb_h=$new_h;
		}
		$dst_img=ImageCreateTrueColor($thumb_w,$thumb_h);
		imagecopyresampled($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y);
		if (preg_match("/.gif|.GIF/",$old_file))
		{
			imagegif($dst_img,$new_file,100);
		} else {
		imagejpeg($dst_img,$new_file,100);
		}
		imagedestroy($dst_img);
		imagedestroy($src_img);
	}
	function cropImage($dst_w,$dst_h,$image)
	{
		$file =$image;
		$output =$file;
		// Get dimensions of existing image
		$dim = getimagesize($file);
		//create blank canvas at new size
		$canvas = imagecreatetruecolor($dst_w,$dst_h);
		//copy original image
		$piece = imagecreatefromjpeg($file);
		$src_w= $dim[0] / 2;
		$src_h = $dim[1] / 2;
		$src_x = $src_w - floor($dst_w/2);
		$src_y= $src_h - floor($dst_h/2);
		// Generate the cropped image
		imagecopyresized($canvas, $piece, 0,0, $src_x, $src_y, $dst_w, $dst_h, $dst_w, $dst_h);
		// Write image or fail
		imagejpeg($canvas,$output,100);
		// Clean-up
		imagedestroy($canvas);
		imagedestroy($piece);
	}
		//IMAGE UPLOAD
	function uploadFile($l,$f){
		$t =$l.$f['name'];
		if(move_uploaded_file($f['tmp_name'],$t)){
		}
		return $t;
	}
	function generateCroppedImage($sourceImage,$targetImage,$canvasWidth,$canvasHeight)
	{
		$info = getimagesize($sourceImage);
		if($info[0]>$info[1]){
		$fold = round(($canvasHeight/$canvasWidth)*100);
		$percentDifference = round(($info[1]/$info[0]) * 100);
		if($percentDifference >$fold){
			$percentDifference =$fold;
		}
		$thumbMaxSize = round(($canvasHeight/$percentDifference)*100);
		}
		else
		{
			$fold = round(($canvasWidth/$canvasHeight)*100);
			$percentDifference = round(($info[0]/$info[1]) * 100);
			if($percentDifference >$fold){
			$percentDifference =$fold;
			}
			$thumbMaxSize = round(($canvasWidth/$percentDifference)*100);
		}
		createthumb($sourceImage,$targetImage,$thumbMaxSize,$thumbMaxSize);
		cropImage($canvasWidth,$canvasHeight,$targetImage);
	}
	function getSiteName($Modname)
	{
		global $dbObj;
		$dbObj->SelectQuery("SiteSettings",'sitename',$dbObj->db_safe("id='%1'",'1'));
		$numRows = $dbObj->getNumRows();
		if($numRows > 0)
		{
			$Resdata 	= $dbObj->getFetchArray();
			$SiteTitle 	= $Resdata["sitename"];
			$SiteName	= $SiteTitle . " :: Admin :: ".$Modname;
		}
		return $SiteName;
	}
	function getSiteTitle()
	{
		global $dbObj;
		$dbObj->SelectQuery("SiteSettings",'sitename',$dbObj->db_safe("id='%1'",'1'));
		$numRows = $dbObj->getNumRows();
		if($numRows > 0)
		{
			$Resdata 	= $dbObj->getFetchArray();
			$SiteTitle 	= $Resdata["sitename"];
		}
		return $SiteTitle;
	}
	function getSiteTitleTAG()
	{
		global $dbObj;
		$dbObj->SelectQuery("SiteSettings",'vTitleTAG',$dbObj->db_safe("id='%1'",'1'));
		$numRows = $dbObj->getNumRows();
		if($numRows > 0)
		{
			$Resdata 	= $dbObj->getFetchArray();
			$SiteTitle 	= $Resdata["vTitleTAG"];
		}
		return $SiteTitle;
	}
	function getSiteMetaKey()
	{
		global $dbObj;
		$dbObj->SelectQuery("SiteSettings",'tMetaKeywords',$dbObj->db_safe("id='%1'",'1'));
		$numRows = $dbObj->getNumRows();
		if($numRows > 0)
		{
			$Resdata 	= $dbObj->getFetchArray();
			$SiteTitle 	= $Resdata["tMetaKeywords"];
		}
		return $SiteTitle;
	}
	function getSiteMetaDesc()
	{
		global $dbObj;
		$dbObj->SelectQuery("SiteSettings",'tMetaDescription',$dbObj->db_safe("id='%1'",'1'));
		$numRows = $dbObj->getNumRows();
		if($numRows > 0)
		{
			$Resdata 	= $dbObj->getFetchArray();
			$SiteTitle 	= $Resdata["tMetaDescription"];
		}
		return $SiteTitle;
	}
	function getSiteGA()
	{
		global $dbObj;
		$dbObj->SelectQuery("SiteSettings",'tGACode',$dbObj->db_safe("id='%1'",'1'));
		$numRows = $dbObj->getNumRows();
		if($numRows > 0)
		{
			$Resdata 	= $dbObj->getFetchArray();
			$SiteTitle 	= $Resdata["tGACode"];
		}
		return $SiteTitle;
	}
	function getSiteWebCode()
	{
		global $dbObj;
		$dbObj->SelectQuery("SiteSettings",'tWebMasterCode',$dbObj->db_safe("id='%1'",'1'));
		$numRows = $dbObj->getNumRows();
		if($numRows > 0)
		{
			$Resdata 	= $dbObj->getFetchArray();
			$SiteTitle 	= $Resdata["tWebMasterCode"];
		}
		return $SiteTitle;
	}
	function getAdminSupportEmail()
	{
		global $dbObj;
		$dbObj->SelectQuery("SiteSettings",'FromEmail',$dbObj->db_safe("id='%1'",'1'));
		$numRows = $dbObj->getNumRows();
		if($numRows > 0)
		{
			$Resdata 	= $dbObj->getFetchArray();
			$SiteEmil	= $Resdata["FromEmail"];
		}
		return $SiteEmil;
	}
	function getAdminEmail()
	{
		global $dbObj;
		$dbObj->SelectQuery("SiteSettings",'admin_email',$dbObj->db_safe("id='%1'",'1'));
		$numRows = $dbObj->getNumRows();
		if($numRows > 0)
		{
			$Resdata 	= $dbObj->getFetchArray();
			$AdminEmil	= $Resdata["admin_email"];
		}
		return $AdminEmil;
	}
	function getSitePaging()
	{
		global $dbObj;
		$dbObj->SelectQuery("SiteSettings",'PageSetting',$dbObj->db_safe("SiteSettingsId='%1'",'1'));
		$numRows = $dbObj->getNumRows();
		if($numRows > 0)
		{
			$Resdata 	= $dbObj->getFetchArray();
			$SitePage 	= $Resdata["PageSetting"];
		}
		return $SitePage;
	}
	function getFrontSitePaging()
	{
		global $dbObj;
		$dbObj->SelectQuery("SiteSettings",'pagesetting_front',$dbObj->db_safe("id='%1'",'1'));
		$numRows = $dbObj->getNumRows();
		if($numRows > 0)
		{
			$Resdata 	= $dbObj->getFetchArray();
			$SitePage 	= $Resdata["pagesetting_front"];
		}
		return $SitePage;
	}
	function getAdminName($AdminId)
	{
		global $dbObj;
		$dbObj->SelectQuery("tbl_admin",'vAdminName',$dbObj->db_safe("iAdminId='%1'",$AdminId));
		$numRows = $dbObj->getNumRows();
		if($numRows > 0)
		{
			$Resdata 	= $dbObj->getFetchArray();
			$UsrName	= $Resdata["vAdminName"];
		}
		return $UsrName;
	}
	function getCommDesc()
	{
		global $dbObj;
		$dbObj->SelectQuery("tbl_pagemgmt",'tPageDescription',$dbObj->db_safe("iPageId='%1'",1));
		$numRows = $dbObj->getNumRows();
		if($numRows > 0)
		{
			$Resdata 	= $dbObj->getFetchArray();
			if(strlen($Resdata["tPageDescription"]) > 120)
			{
				$UsrName	= substr($Resdata["tPageDescription"],0,120)."...";
			}
			else
			{
				$UsrName	= $Resdata["tPageDescription"];
			}
		}
		return $UsrName;
	}
	function getResiDesc()
	{
		global $dbObj;
		$dbObj->SelectQuery("tbl_pagemgmt",'tPageDescription',$dbObj->db_safe("iPageId='%1'",2));
		$numRows = $dbObj->getNumRows();
		if($numRows > 0)
		{
			$Resdata 	= $dbObj->getFetchArray();
			if(strlen($Resdata["tPageDescription"]) > 120)
			{
				$UsrName	= substr($Resdata["tPageDescription"],0,120)."...";
			}
			else
			{
				$UsrName	= $Resdata["tPageDescription"];
			}
		}
		return $UsrName;
	}
}
?>