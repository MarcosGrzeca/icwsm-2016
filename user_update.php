<?php
require_once("config.php");

$tweets = query("SELECT * FROM user WHERE faceplusplus IS NULL OR faceplusplus = ''");
//$tweets = query("SELECT * FROM tweets LIMIT 3");

foreach (getRows($tweets) as $key => $value) {
	try {
		$tweteer = getUserById($value["id"]);
		$tweteer = json_decode($tweteer, true);
	    
		if (isset($tweteer["errors"])) {
			foreach ($tweteer["errors"] as $key => $erro) {
				//debug("ERRO");
				//debug($erro["code"]);
				if ($erro["code"] == "50" || $erro["code"] == "63") {
					//User not found - processar do tweet
					//$tweteer = $usuario["user"];
					//break;
				}
				if ($erro["code"] == "88") {
					echo "EXCEDEU LIMITEs " . $ind;
					break 2;
					//sleep(300);
				}
				//throw new Exception(json_encode($tweteer["errors"]), 1);
			}
			continue;
		}

		$user_id = $tweteer["id"];
		$description = $tweteer["description"];
		$location = $tweteer["location"];
		$name = $tweteer["name"];
		$screen_name = $tweteer["screen_name"];
		$lang = $tweteer["lang"];
		$url = $tweteer["url"];
		$time_zone = $tweteer["time_zone"];
		$profile_image_url = $tweteer["profile_image_url"];
		$profile_image_url = str_replace("_normal", "_400x400", $profile_image_url);

		$update = "UPDATE `user` SET profile_url = '" . escape($profile_image_url) . "', location = '" . escape($location) . "', name = '" . escape($name) . "', description = '"  . escape($description) . "', screen_name = '"  . escape($screen_name) . "', lang = '"  . escape($lang) . "', url = '"  . escape($url) . "' WHERE id = " . $value["id"];
		//debug($update);
		query($update, false);
	} catch (Exception $e) {
		debug($user_id);
		debug($value["id"]);
		debug($get . " --- " . $num);
		print_r($e->getMessage());
		die;
	}
}
?>