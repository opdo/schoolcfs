<?php
	require_once 'cfs.php';
	require_once 'myfunc.php';

	$helper = $fb->getRedirectLoginHelper();
	try {
		$accessToken = $helper->getAccessToken();
	} catch(Facebook\Exceptions\FacebookResponseException $e) {
		  // When Graph returns an error
		$_SESSION["error_msg"]  =  'Graph returned an error: ' . $e->getMessage();
		header("Location: login.php");
		exit;
	} catch(Facebook\Exceptions\FacebookSDKException $e) {
		  // When validation fails or other local issues
		$_SESSION["error_msg"]  =  'Facebook SDK returned an error: ' . $e->getMessage();
		header("Location: login.php");
		exit;
	}

	if (isset($accessToken)) {
		  // Logged in!
		$_SESSION['facebook_access_token'] = (string) $accessToken;
		$fb->setDefaultAccessToken($accessToken);
		try {
			$response = $fb->get('/me');
			$userNode = $response->getGraphUser();
		} catch(Facebook\Exceptions\FacebookResponseException $e) {
		  // When Graph returns an error
			$_SESSION["error_msg"]  =  'Graph returned an error: ' . $e->getMessage();
			header("Location: login.php");
			exit;
		} catch(Facebook\Exceptions\FacebookSDKException $e) {
		  // When validation fails or other local issues
			$_SESSION["error_msg"]  = 'Facebook SDK returned an error: ' . $e->getMessage();
			header("Location: login.php");
			exit;
		}
		$login = _Login($userNode->getID());
		if ($login["success"] == 1) {
			header("Location: admin.php");
			exit;
		}
		$_SESSION["error_msg"]  = $login["msg"];
		header("Location: login.php");
	}

?>