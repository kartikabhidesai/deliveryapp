<?php
/*function createthumb($name,$filename,$new_w,$new_h)
{
	if(exif_imagetype($name) == IMAGETYPE_JPEG){$src_img=imagecreatefromjpeg($name);}
	if(exif_imagetype($name) == IMAGETYPE_PNG){$src_img=imagecreatefrompng($name);}
	if(exif_imagetype($name) == IMAGETYPE_GIF){$src_img=imagecreatefromgif($name);}
	
	$old_x=imagesx($src_img);
	$old_y=imagesy($src_img);
	
	if($new_w >= $old_x)
	{
		$thumb_w=$old_x;
	}
	else if($new_w < $old_x)
	{
		$thumb_w=$new_w;
	}
	
	if($new_h >= $old_x)
	{
		$thumb_h=$old_x;
	}
	else if($new_h < $old_x)
	{
		$thumb_h=$new_h;
	}
	
	$dst_img=imagecreatetruecolor($thumb_w,$thumb_h);
	
	imagecopyresampled($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y); 
	if(exif_imagetype($name) == IMAGETYPE_GIF)
	{
		imagegif($dst_img,$filename); 
	} 
	else if(exif_imagetype($name) == IMAGETYPE_PNG)
	{
		imagepng($dst_img,$filename); 
	}
	else
	{
		imagejpeg($dst_img,$filename); 
	}
	imagedestroy($dst_img); 
}*/
function createthumb($source_image_path, $thumbnail_image_path,$THUMBNAIL_IMAGE_MAX_WIDTH,$THUMBNAIL_IMAGE_MAX_HEIGHT)
{
    list($source_image_width, $source_image_height, $source_image_type) = getimagesize($source_image_path);
    switch ($source_image_type) {
        case IMAGETYPE_GIF:
            $source_gd_image = imagecreatefromgif($source_image_path);
            break;
        case IMAGETYPE_JPEG:
            $source_gd_image = imagecreatefromjpeg($source_image_path);
            break;
        case IMAGETYPE_PNG:
            $source_gd_image = imagecreatefrompng($source_image_path);
            break;
	default :
	    $source_gd_image = imagecreatefromjpeg($source_image_path);
            break;
    }
	
    if ($source_gd_image === false) {
        return false;
    }
    $source_aspect_ratio = $source_image_width / $source_image_height;
    $thumbnail_aspect_ratio = $THUMBNAIL_IMAGE_MAX_WIDTH / $THUMBNAIL_IMAGE_MAX_HEIGHT;
    if ($source_image_width <= $THUMBNAIL_IMAGE_MAX_WIDTH && $source_image_height <= $THUMBNAIL_IMAGE_MAX_HEIGHT) {
        $thumbnail_image_width = $source_image_width;
        $thumbnail_image_height = $source_image_height;
    } elseif ($thumbnail_aspect_ratio > $source_aspect_ratio) {
        $thumbnail_image_width = (int) ($THUMBNAIL_IMAGE_MAX_HEIGHT * $source_aspect_ratio);
        $thumbnail_image_height = $THUMBNAIL_IMAGE_MAX_HEIGHT;
    } else {
        $thumbnail_image_width = $THUMBNAIL_IMAGE_MAX_WIDTH;
        $thumbnail_image_height = (int) ($THUMBNAIL_IMAGE_MAX_WIDTH / $source_aspect_ratio);
    }

    $thumbnail_gd_image = imagecreatetruecolor($thumbnail_image_width, $thumbnail_image_height);
    imagealphablending($thumbnail_gd_image, false);
    imagesavealpha($thumbnail_gd_image, true);
    imagecopyresampled($thumbnail_gd_image, $source_gd_image, 0, 0, 0, 0, $thumbnail_image_width, $thumbnail_image_height, $source_image_width, $source_image_height);

    //$img_disp = imagecreatetruecolor($THUMBNAIL_IMAGE_MAX_WIDTH,$THUMBNAIL_IMAGE_MAX_HEIGHT);
    $img_disp = imagecreatetruecolor($thumbnail_image_width,$thumbnail_image_height);
    imagealphablending($img_disp, false);
    imagesavealpha($img_disp, true);
    //$backcolor = imagecolorallocate($img_disp,255,255,255);
    //imagefill($img_disp,0,0,$backcolor);

    imagecopyresampled($img_disp, $thumbnail_gd_image, (imagesx($img_disp)/2)-(imagesx($thumbnail_gd_image)/2), (imagesy($img_disp)/2)-(imagesy($thumbnail_gd_image)/2), 0, 0, imagesx($thumbnail_gd_image), imagesy($thumbnail_gd_image), imagesx($thumbnail_gd_image), imagesy($thumbnail_gd_image));

    switch ($source_image_type) {
	   case IMAGETYPE_GIF:
	        imagejpeg($img_disp, $thumbnail_image_path, 90);
	        break;
	   case IMAGETYPE_JPEG:
	        imagejpeg($img_disp, $thumbnail_image_path, 90);
	        break;
	   case IMAGETYPE_PNG:
	        imagepng($img_disp, $thumbnail_image_path);
	        break;
           default :
                imagejpeg($img_disp, $thumbnail_image_path, 90);
	        break;
    }

    imagedestroy($source_gd_image);
    imagedestroy($thumbnail_gd_image);
    imagedestroy($img_disp);
    return true;
}
?>