<?php
/**
 * @Author: Code-Architect 
 * @Version: 1.0
 */ 
 
 // ------------- RESIZE IMAGE FUNCTION -------------
function img_resize($target, $newcopy, $w, $h, $ext) {
	 list($w_orig, $h_orig) = getimagesize($target); 
	 $scale_ratio = $w_orig / $h_orig;
	 
	 if (($w / $h) > $scale_ratio) {
	 	$w = $h * $scale_ratio; 
	 } else { 
	 	$h = $w / $scale_ratio; 
	 }
	 
	 $img = "";
	 $ext = strtolower($ext);
	 
	 if ($ext == "gif"){ 
	 	$img = imagecreatefromgif($target);
	 }else if($ext =="png"){ 
	    $img = imagecreatefrompng($target);
	 } else { 
	    $img = imagecreatefromjpeg($target);
	 }
		$tci = imagecreatetruecolor($w, $h);
		 // imagecopyresampled(dst_img, src_img, dst_x, dst_y, src_x, src_y, dst_w, dst_h, src_w, src_h)
		 imagecopyresampled($tci, $img, 0, 0, 0, 0, $w, $h, $w_orig, $h_orig); 
		 imagejpeg($tci, $newcopy, 80); 
}

 // ------------- THUMBNAIL (CROP) FUNCTION -------------
	 
function img_thumb($target, $newcopy, $w, $h, $ext) {
	
	 list($w_orig, $h_orig) = getimagesize($target); 
	 $src_x = ($w_orig / 2) - ($w / 2); 
	 $src_y = ($h_orig / 2) - ($h / 2); 
	 $ext = strtolower($ext); $img = ""; 
	 
	 if ($ext == "gif"){
	 	 $img = imagecreatefromgif($target); 
	 } else if($ext =="png"){
	 	 $img = imagecreatefrompng($target); 
	 } else {
	 	 $img = imagecreatefromjpeg($target); 
	 } 
	 $tci = imagecreatetruecolor($w, $h); 
	 imagecopyresampled($tci, $img, 0, 0, $src_x, $src_y, $w, $h, $w, $h); 
	 if ($ext == "gif"){
	 	 imagegif($tci, $newcopy); 
	 } else if($ext =="png"){
	 	 imagepng($tci, $newcopy); 
	 } else {
	 	 imagejpeg($tci, $newcopy, 84); 
	 } 
}

// -------------- IMAGE CONVERTING FUNCTION ---------------
function image_convert_to_jpg($target, $newcopy, $ext) {		
	list($w_orig, $h_orig) = getimagesize($target); 
	$ext = strtolower($ext); $img = ""; 
	
	if ($ext == "gif"){
		 $img = imagecreatefromgif($target); 
	} else if($ext =="png"){
		 $img = imagecreatefrompng($target); 
	} 
	$tci = imagecreatetruecolor($w_orig, $h_orig); 
	imagecopyresampled($tci, $img, 0, 0, 0, 0, $w_orig, $h_orig, $w_orig, $h_orig); 
	imagejpeg($tci, $newcopy, 84); 
}


 // ------------- WATERMARK FUNCTION -------------	 

function img_watermark($target, $wtrmrk_file, $newcopy) {
	
	 $watermark = imagecreatefrompng($wtrmrk_file); 
	 imagealphablending($watermark, false); 
	 imagesavealpha($watermark, true); 
	 $img = imagecreatefromjpeg($target); 
	 $img_w = imagesx($img); $img_h = imagesy($img); 
	 $wtrmrk_w = imagesx($watermark); 
	 $wtrmrk_h = imagesy($watermark); 
	 
	 // For centering the watermark on any image	 
	 $dst_x = ($img_w / 2) - ($wtrmrk_w / 2); 
	 $dst_y = ($img_h / 2) - ($wtrmrk_h / 2); 
	 	 
	 imagecopy($img, $watermark, $dst_x, $dst_y, 0, 0, $wtrmrk_w, $wtrmrk_h); 
	 imagejpeg($img, $newcopy, 100); 
	 
	 // No need to keep these images,so destroy them 
	 imagedestroy($img); 
	 imagedestroy($watermark); 
} 
 