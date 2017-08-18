<?php
require_once("../config.php");
require_once("../vendor/autoload.php");

$fb = new Facebook\Facebook([
	'app_id' => '1923793047872398',
	'app_secret' => 'e38a48e27d4c5e306b35ccf551bbbaad',
	'default_graph_version' => 'v2.2',
]);

try {
  $response = $fb->get('search?type=place&center=43.23714524, -77.56040334&fields=name,about,category_list,description,hours,location&distance=500', 'EAAbVrfk9Y44BAIpGucHVn5BqrVKJMJfUTudcoN0FX5OXJMa1fMmRBlE3qm9lqeXIFX37Rv3gOZAOhoQIKopNz7zuXO1TPEYded6Qf51htqpwdFejZB33w4WK92tuZCvXMHVjF7IlmjUmrgYJeASs41MWkfC4IKb0VXlfMXZCagZDZD');
} catch(Facebook\Exceptions\FacebookResponseException $e) {
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}
debug($response->getBody());
