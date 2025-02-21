<?
require_once("config.php");

$tweets = query("SELECT * FROM tweets WHERE localizacao100 IS NOT NULL AND idInterno NOT IN (SELECT idTweetInterno FROM tweet_localizacao)");

foreach (getRows($tweets) as $key => $value) {
	try {
		$tweet = json_decode($value["localizacao100"]);
		if (isset($tweet->data)) {
			foreach ($tweet->data as $keyL => $valueL) {
				$category = "";
				if (isset($valueL->category_list[0]->name)) {
					$category = $valueL->category_list[0]->name;
				}
				$zip = "";
				if (isset($valueL->location->zip)) {
					$arZip = explode(",", $valueL->location->zip);
					$zip = $arZip[0];
				}
				$street = "";
				if (isset($valueL->location->street)) {
					$street = $valueL->location->street;
				}

				$insert = "INSERT INTO `tweet_localizacao` (idTweetInterno, name, category, street, zip, distance) VALUES ('" . $value["idInterno"]. "', '" . mysqli_real_escape_string(Connection::get(), $valueL->name) . "', '" . mysqli_real_escape_string(Connection::get(), $category) . "', '" . mysqli_real_escape_string(Connection::get(), $street) . "', '" . mysqli_real_escape_string(Connection::get(), $zip) . "', '100')";
	          		query($insert);
				break;
			}
		}
	} catch (Exception $e) {
		debug("ERRO");
		debug($e->getMessage());
	}

}
echo "FIM";

?>