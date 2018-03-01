<?php
require_once("config.php");

$tweets = query("SELECT * FROM tweets WHERE user_id = 0");

foreach (getRows($tweets) as $key => $value) {
	$usuario = json_decode($value["texto"], true);
	$usuario = $usuario["user"];
	//debug($usuario);

	$user_id = $usuario["id"];
	$description = $usuario["description"];
	$location = $usuario["location"];
	$name = $usuario["name"];
	$screen_name = $usuario["screen_name"];
	$lang = $usuario["lang"];
	$url = $usuario["url"];
	$time_zone = $usuario["time_zone"];
	$profile_image_url = $usuario["profile_image_url"];
	$profile_image_url = str_replace("_normal", "_400x400", $profile_image_url);

	/*
	debug("user_id " . $user_id);
	debug("description " . $description);
	debug("location " . $location);
	debug("name " . $name);
	debug("profile_image_url " . $profile_image_url);
	*/

	try {
		$get = "SELECT * FROM user WHERE id = '" . escape($user_id) . "'";
		$ret = query($get);


		if (getNumRows($ret) == 0) {
			$insert = "INSERT INTO `user` (id, profile_url, location, name, description, screen_name, lang, url) VALUES ('" . escape($user_id) . "', '" . escape($profile_image_url) . "', '" . escape($location) . "', '" . escape($name) . "', '"  . escape($description) . "', '"  . escape($screen_name) . "', '"  . escape($lang) . "', '"  . escape($url) . "')";
			query($insert);	
		}

		$inser = "UPDATE tweets SET user_id = '" . escape($user_id) . "' WHERE id = '" . $value["id"] . "'";
		$ret = query($inser);
	} catch (Exception $e) {
		print_r($e->getMessage());
		die;
	}
/*	

	$insert = "INSERT INTO `conceito` (palavra, resource, json, types, sucesso) VALUES ('" . escape($value["palavra"]) . "', '" . escape($valueR["@URI"]) . "', '" . escape($response) . "', '" . escape($valueR["@types"]) . "', '1')";
	query($insert);
*/	
	/*die;
	try {
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => "http://model.dbpedia-spotlight.org/en/annotate",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_SSL_VERIFYHOST => 0,
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => "text=" . $value["palavra"] . "&confidence=0.35",
			CURLOPT_HTTPHEADER => array(
				"accept: application/json",
				"cache-control: no-cache",
				"content-type: application/x-www-form-urlencoded",
				"postman-token: 5a0e0b03-9587-f575-3ae4-8f8fa233b677"
				),
			));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
			echo "cURL Error #:" . $err;
		} else {
			$res = json_decode($response, true);
			if (isset($res["Resources"])) {
				foreach ($res["Resources"] as $keyR => $valueR) {
					$insert = "INSERT INTO `conceito` (palavra, resource, json, types, sucesso) VALUES ('" . escape($value["palavra"]) . "', '" . escape($valueR["@URI"]) . "', '" . escape($response) . "', '" . escape($valueR["@types"]) . "', '1')";
					query($insert);
				}
				break;
			}
			$insert = "INSERT INTO `conceito` (palavra, resource, json, types, sucesso) VALUES ('" . escape($value["palavra"]) . "', '', '" . escape($response) . "', '', '0')";
			query($insert);
		}
	} catch (Exception $e) {
		debug("ERRO");
		debug($e->getMessage());
	}*/
}
$i++;
?>