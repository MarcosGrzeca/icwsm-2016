<?php
require_once("config.php");
require_once("vendor/autoload.php");
$tweets = query("SELECT * FROM tweets WHERE localizacao IS NULL LIMIT 1000");

$fb = new Facebook\Facebook([
	'app_id' => '1923793047872398',
	'app_secret' => 'e38a48e27d4c5e306b35ccf551bbbaad',
	'default_graph_version' => 'v2.2',
	]);


foreach (getRows($tweets) as $key => $value) {
	try {
		$tweet = json_decode($value["texto"]);
		if (isset($tweet->geo->coordinates)) {
			try {
				$response = $fb->get('search?type=place&center=' . $tweet->geo->coordinates[0] . ', ' . $tweet->geo->coordinates[1] . '&fields=name,about,category_list,description,hours,location&distance=500', 'EAAbVrfk9Y44BAIpGucHVn5BqrVKJMJfUTudcoN0FX5OXJMa1fMmRBlE3qm9lqeXIFX37Rv3gOZAOhoQIKopNz7zuXO1TPEYded6Qf51htqpwdFejZB33w4WK92tuZCvXMHVjF7IlmjUmrgYJeASs41MWkfC4IKb0VXlfMXZCagZDZD');
			} catch(Facebook\Exceptions\FacebookResponseException $e) {
				echo 'Graph returned an error: ' . $e->getMessage();
				throw $e;
			} catch(Facebook\Exceptions\FacebookSDKException $e) {
				echo 'Facebook SDK returned an error: ' . $e->getMessage();
				throw $e;
			}
			$update = "UPDATE `tweets` SET localizacao = '" . mysqli_real_escape_string(Connection::get(), $response->getBody()) . "' WHERE id = "  . $value["id"];
			query($update);
		}
	} catch (Exception $e) {
		var_dump($e);
		if ($e->getMessage() == "Calls to this api have exceeded the rate limit") {
			echo "EXCEDEU LIMITE";
			break;
		}
		break;
	}
}

echo "FIM";