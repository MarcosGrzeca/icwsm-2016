<?

//http://demo.dbpedia-spotlight.org/
//https://dbpedia.org/sparql

//Shedding Light on the Web of Documents

//http://www.w3.org/1999/02/22-rdf-syntax-ns#type

require_once("config.php");

$i = 0;
while ($i < 5000) {
	$tweets = query("SELECT * FROM tweets_nlp WHERE tipo = 'E' AND NOT EXISTS (SELECT * FROM conceito WHERE conceito.palavra = tweets_nlp.palavra) LIMIT 1");
	foreach (getRows($tweets) as $key => $value) {
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
				CURLOPT_POSTFIELDS => "text=" . $value["palavra"] . "&confidence=0.25",
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
		}

	}
	$i++;
}
echo "FIM";

?>