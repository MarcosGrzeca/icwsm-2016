<?

//http://demo.dbpedia-spotlight.org/
//https://dbpedia.org/sparql

//Shedding Light on the Web of Documents

//http://www.w3.org/1999/02/22-rdf-syntax-ns#type


require_once("config.php");

$tweets = query("SELECT * FROM tweets WHERE localizacao IS NOT NULL LIMIT 2");

foreach (getRows($tweets) as $key => $value) {
	try {
		$tweet = json_decode($value["localizacao"]);
		debug($tweet);
		if (isset($tweet->data)) {
			foreach ($tweet->data as $keyL => $valueL) {
				$category = "";
				if (isset($valueL->category_list[0]->name)) {
					$category = $valueL->category_list[0]->name;
				}
				$zip = "";
				if (isset($valueL->location->zip)) {
					$zip = $valueL->location->zip;
				}
				$street = "";
				if (isset($valueL->location->street)) {
					$street = $valueL->location->street;
				}

				$insert = "INSERT INTO `tweets_localizacao` (idTweetInterno, name, category, street, zip) VALUES ('" . $value["idInterno"]. "', '" . mysqli_real_escape_string(Connection::get(), $valueL->name) . "', '" . mysqli_real_escape_string(Connection::get(), $category) . "', '" . mysqli_real_escape_string(Connection::get(), $street) . "', '" . mysqli_real_escape_string(Connection::get(), $zip) . "')";

				debug($insert);
//          		query($insert);
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