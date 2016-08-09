<?php
	// Facebook App ID
	session_start();
	require_once 'Facebook/autoload.php';
	$fb = new Facebook\Facebook([
		'app_id' => '',
		'app_secret' => '',
		'default_graph_version' => 'v2.7',
		]);
	date_default_timezone_set("Asia/Ho_Chi_Minh");
	$sql_username = "";
	$sql_password = "";
	$sql_host = "";
	$sql_database = "";
	if ($_SERVER['SERVER_NAME'] == "localhost") {
		$sql_username = "root";
		$sql_password = "";
		$sql_host = "localhost";
		$sql_database = "cfs_dblogin";
	}
	$GLOBALS['conn'] = mysqli_connect($sql_host,$sql_username,$sql_password,$sql_database) or die("Khong ket noi duoc voi database");
	mysqli_set_charset($conn, 'utf8');
	$SETTING_DOMAIN = 'http://'.$_SERVER['SERVER_NAME'].'/';
	$SETTING_NAME_CFS = "Hutech";
	$SETTING_LINK_CFS = "https://www.facebook.com/HutechConfessions";
?>