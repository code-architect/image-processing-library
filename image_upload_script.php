<?php
/**
 * @Author: Code-Architect 
 * 
 */ 

 // Access the $_FILES global variable for this specific file being uploaded
$file_name = $_FILES["upload_file"]["name"];
$file_temp = $_FILES["upload_file"]["tmp_name"];
$file_type = $_FILES["upload_file"]["type"];
$file_size = $_FILES["upload_file"]["size"];
$file_error_msg = $_FILES["upload_file"]["error"];

// Filter filename
//$file_name = preg_replace('#[^a-z._0-9]#i', '', $file_name);

$file_blast = explode(".", $file_name);
$file_ext = $file_blast[1];

// Rename the file with unique name
//$file_name = time().rand().".".$file_ext;


//Image upload error handle
if(!$file_temp){
	echo "ERROR: Please brows for a file before clicking the upload button";
	exit();	
	
} elseif($file_size > 5242880){	//Larger than 5 mb
	echo "ERROR: Your File was larger than 5 mb";
	unlink($file_temp);	// remove from temp file
	exit();
	
} elseif(!preg_match("/.(gif|jpg|png)$/i", $file_name) ){
	echo "ERROR: Your file was not .png, .jpg or .gif";
	unlink($file_temp);
	exit();
	
} elseif($file_error_msg == 1){
	echo "ERROR: An error occurred while processing the file";
	exit();
}

// Moving the file into uploads folder
$moveResult = move_uploaded_file($file_temp, "uploads/$file_name");

// check if it's been moved
if($moveResult != true){
	echo "ERROR: File upload failed. Try Again";
	unlink($file_temp);
	exit();
}

// start resize
// include img_library.php file
include('img_library.php');
$target_file = "uploads/$file_name";
$resize_file = "uploads/resized_$file_name";
$wmax = 200;
$hmax = 150;
img_resize($target_file, $resize_file, $wmax, $hmax, $file_ext);
// end resize

// Convert to JPEG starts
if(strtolower($file_ext) != 'jpg'){
	$target_file = "uploads/$file_name";
	$new_jpg = "uploads/".$file_blast[0].".jpg";
	image_convert_to_jpg($target_file, $new_jpg, $file_ext);
}
// Convert to JPEG ends

// start thumbnail function

$target_file = "uploads/resized_$file_name";
$thumbnail = "uploads/thumb_$file_name";
$wthumb = 200;
$hthumb = 150;
img_thumb($target_file, $thumbnail, $wthumb, $hthumb, $file_ext);

// end thumbnail function

// watermarking start

$target_file = "uploads/$file_name";
$watermark = "watermark.png";
$new_file = "uploads/watermark_".$file_blast[0].".jpg";
img_watermark($target_file, $watermark, $new_file);

// watermarking ends

echo "Success";

echo "The file named <strong>$file_name</strong> uploaded successfuly.<br /><br />"; 
echo "It is <strong>$file_size</strong> bytes in size.<br /><br />"; 
echo "It is an <strong>$file_type</strong> type of file.<br /><br />"; 
echo "The file extension is <strong>$file_ext</strong><br /><br />"; 
echo "The Error Message is: $file_error_msg";