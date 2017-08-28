<?php
require_once("config.php");
require_once("vendor/autoload.php");
$tweets = query("SELECT * FROM tweets WHERE localizacao100 IS NULL");

$fb = new Facebook\Facebook([
	'app_id' => '1923793047872398',
	'app_secret' => 'e38a48e27d4c5e306b35ccf551bbbaad',
	'default_graph_version' => 'v2.2',
	]);

//Minha
//EAAbVrfk9Y44BAIpGucHVn5BqrVKJMJfUTudcoN0FX5OXJMa1fMmRBlE3qm9lqeXIFX37Rv3gOZAOhoQIKopNz7zuXO1TPEYded6Qf51htqpwdFejZB33w4WK92tuZCvXMHVjF7IlmjUmrgYJeASs41MWkfC4IKb0VXlfMXZCagZDZD

//Canabis
//EAAbVrfk9Y44BAPen13bjn6TMf8jZBRio4aRZCVra62asuQ9kVWAToPcyUmnYqwB9I3ChpLcejTztHxSPxzh81zZB0wA2rVOWD1AVIFvyTB457uIZAUgZA0ZA8UkZAPMpC70z0WRZAzQthdHfP54jxqIArFhZC7u091GL9ZBcP3fEP3RQZDZD

//Nikolas
//EAAbVrfk9Y44BAC0HZCGbTUFprrjdS7v44Bie6uF8E5tlUtVWGxQuW2YYZCqZCAXoXO9fWDc4tcoq8iro0h4Xz5im2oA1RbP0Skp02VVlHFTsPyexXDu8ZAIeoq79ireqwQ8qboaZC5pGAF7l4eetJngkxjdfdqBl5d9xq6PZCz8AZDZD

//MO
//EAAbVrfk9Y44BAH6m7WFvWkt34ZC1RbI9iQEpaXGW9UUnZCCuWBU8WISEZAUGREOzw2MgQChMaQR9kGtjZA6WB5uo1d3DI9Ruqcwl3TkYJyz856sja6RKQlHJKoYDPKsJqWrmC33urg4FUlIj1HWCvCA4iV6kiGknNoZCYnxLqlgZDZD


//Magdiel
//EAAbVrfk9Y44BANw3APWHqBDw3ZBGpseAr0dvdrG3bPIklSXdBoeH59u83KppILEfSjZCWs5gT9rJZBGsYii3LxSE2xptXitSnZBl57qSfVfgbZCXwZBbI6ZAhy0ij1My9xpHMvpsjf1MFLuTWnQcQhDS5LLvojciHJMapoJlEWHRwZDZD

//Leo
//EAAbVrfk9Y44BAN9DYzkl8rqMylVVPfxeMOWExYCaFPpCLK9doCjlYZCO3UrHJojyVA9YDtnMlY1i1Xn9YfSuJyavxtvlRLlyYG97LxxitUxZCcTNUZCgDMnKbjAOYwYeewmU61NL2ViAbshHKeEX6lyvxoArQIWZBgrwZC7DiVQZDZD

//Adriano
//EAAbVrfk9Y44BAGOZACsdwnA5FHB607ZAhSpqBioEZCBiBajgUSUZA90ivpqwDXjxfxvoFi8z2YnwK6tuEe44alUqSUPtD5Yv6HsjSwEyL1XZAg19ND0xC21OgmHEDGF5IGQ3S4PPKvGt1wg5mYulXBAttADDydZA0wBUBX585oYAZDZD

//Oeslei
//EAAbVrfk9Y44BAKyCHC2yZCGcxC4ZBoxk7mI2ZAmCyrh1ZBSN2uBZBJYBLTOJnqBNbLVJzYwPGAQs85TbAEhZByhF39PnJbpHf3SO6oe5182QGqXWHWIvyGPKNhIZA1V63GFV8ryZCoJQ7Dw2AFrAZAhEgwccsrZBTFfHxexH1wrUIQZCAZDZD

$tokens = array("EAAbVrfk9Y44BAIpGucHVn5BqrVKJMJfUTudcoN0FX5OXJMa1fMmRBlE3qm9lqeXIFX37Rv3gOZAOhoQIKopNz7zuXO1TPEYded6Qf51htqpwdFejZB33w4WK92tuZCvXMHVjF7IlmjUmrgYJeASs41MWkfC4IKb0VXlfMXZCagZDZD", "EAAbVrfk9Y44BAPen13bjn6TMf8jZBRio4aRZCVra62asuQ9kVWAToPcyUmnYqwB9I3ChpLcejTztHxSPxzh81zZB0wA2rVOWD1AVIFvyTB457uIZAUgZA0ZA8UkZAPMpC70z0WRZAzQthdHfP54jxqIArFhZC7u091GL9ZBcP3fEP3RQZDZD", "EAAbVrfk9Y44BAC0HZCGbTUFprrjdS7v44Bie6uF8E5tlUtVWGxQuW2YYZCqZCAXoXO9fWDc4tcoq8iro0h4Xz5im2oA1RbP0Skp02VVlHFTsPyexXDu8ZAIeoq79ireqwQ8qboaZC5pGAF7l4eetJngkxjdfdqBl5d9xq6PZCz8AZDZD", "EAAbVrfk9Y44BAH6m7WFvWkt34ZC1RbI9iQEpaXGW9UUnZCCuWBU8WISEZAUGREOzw2MgQChMaQR9kGtjZA6WB5uo1d3DI9Ruqcwl3TkYJyz856sja6RKQlHJKoYDPKsJqWrmC33urg4FUlIj1HWCvCA4iV6kiGknNoZCYnxLqlgZDZD", "EAAbVrfk9Y44BANw3APWHqBDw3ZBGpseAr0dvdrG3bPIklSXdBoeH59u83KppILEfSjZCWs5gT9rJZBGsYii3LxSE2xptXitSnZBl57qSfVfgbZCXwZBbI6ZAhy0ij1My9xpHMvpsjf1MFLuTWnQcQhDS5LLvojciHJMapoJlEWHRwZDZD", "EAAbVrfk9Y44BAN9DYzkl8rqMylVVPfxeMOWExYCaFPpCLK9doCjlYZCO3UrHJojyVA9YDtnMlY1i1Xn9YfSuJyavxtvlRLlyYG97LxxitUxZCcTNUZCgDMnKbjAOYwYeewmU61NL2ViAbshHKeEX6lyvxoArQIWZBgrwZC7DiVQZDZD", "EAAbVrfk9Y44BAGOZACsdwnA5FHB607ZAhSpqBioEZCBiBajgUSUZA90ivpqwDXjxfxvoFi8z2YnwK6tuEe44alUqSUPtD5Yv6HsjSwEyL1XZAg19ND0xC21OgmHEDGF5IGQ3S4PPKvGt1wg5mYulXBAttADDydZA0wBUBX585oYAZDZD", "EAAbVrfk9Y44BAKyCHC2yZCGcxC4ZBoxk7mI2ZAmCyrh1ZBSN2uBZBJYBLTOJnqBNbLVJzYwPGAQs85TbAEhZByhF39PnJbpHf3SO6oe5182QGqXWHWIvyGPKNhIZA1V63GFV8ryZCoJQ7Dw2AFrAZAhEgwccsrZBTFfHxexH1wrUIQZCAZDZD");

$iToken = 0;
foreach (getRows($tweets) as $key => $value) {
	try {
		$tweet = json_decode($value["texto"]);
		if (isset($tweet->geo->coordinates)) {
			try {
				$response = $fb->get('search?type=place&center=' . $tweet->geo->coordinates[0] . ', ' . $tweet->geo->coordinates[1] . '&fields=name,about,category_list,description,hours,location&distance=100', $tokens[$iToken]);
			} catch(Facebook\Exceptions\FacebookResponseException $e) {
				echo 'Graph returned an error: ' . $e->getMessage();
				throw $e;
			} catch(Facebook\Exceptions\FacebookSDKException $e) {
				echo 'Facebook SDK returned an error: ' . $e->getMessage();
				throw $e;
			}
			$update = "UPDATE `tweets` SET localizacao100 = '" . mysqli_real_escape_string(Connection::get(), $response->getBody()) . "' WHERE id = "  . $value["id"];
			query($update);
		}
	} catch (Exception $e) {
		if ($e->getMessage() == "(#613) Calls to this api have exceeded the rate limit.") {
			$iToken++;
			if (!isset($tokens[$iToken])) {
				echo "EXCEDEU LIMITE";
				break;
			}
		} else {
			var_dump($e);
			break;
		}
	}
}

echo "FIM";