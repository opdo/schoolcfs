<?php
session_start();
if (isset($_GET["captcha"])) {
	if (isset($_SESSION["capcha_code"])) create_image($_SESSION["capcha_code"]);
	else create_image("ERROR!");
}
function create_random() {
	$md5_hash = md5(rand(0,999));
	return substr($md5_hash, 15, 5);
}
function create_image($security_code)
{
	$width = 100;
	$height = 30;
	$image = imagecreate($width, $height);
	$white = imagecolorallocate($image, 255,255,255);
	$black = imagecolorallocate($image, 32,134,191);
	ImageFill($image, 0, 0, $black);
	imagestring($image, 5, 30, 6, $security_code, $white);
	header("Content-Type: image/jpeg");
	ImageJpeg($image);
	ImageDestroy($image);
}
?>