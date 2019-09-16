<?php
require_once("../config.php");
require_once("../vendor/autoload.php");

$fb = new Facebook\Facebook([
	'app_id' => '1923793047872398',
	'app_secret' => 'e38a48e27d4c5e306b35ccf551bbbaad',
	'default_graph_version' => 'v2.2',
]);

$helper = $fb->getRedirectLoginHelper();
$loginUrl = $helper->getLoginUrl('http://ec2-18-229-150-122.sa-east-1.compute.amazonaws.com/icwsm-2016/facebook/fb-callback.php', array());
echo '<a href="' . $loginUrl . '">Log in with Facebook!</a>';