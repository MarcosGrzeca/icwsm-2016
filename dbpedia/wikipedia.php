<?php

require_once("../config.php");

set_time_limit(0);

function getWikiId($resource) {
	
	try {
		$curl = curl_init();

		$query = "SELECT ?wikiID WHERE
		{ 
			<" . $resource . "> <http://dbpedia.org/ontology/wikiPageID> ?wikiID.
		}
		";

		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://dbpedia.org/sparql",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_SSL_VERIFYHOST => 0,
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => "query=" . urlencode($query) . "&default-graph-uri=http%3A%2F%2Fdbpedia.org",
			CURLOPT_HTTPHEADER => array(
				"accept: application/json",
				"cache-control: no-cache",
				"content-type: application/x-www-form-urlencoded",
				"postman-token: 98d3a3cc-71eb-0ae1-9840-4f2055f42ac7"
				),
			));

		$response = curl_exec($curl);
		$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		$err = curl_error($curl);

		if ($httpcode == 200) {
			return $response;
		} else {
			throw new Exception("Resource " . $resource . " - httpCode= " . $httpcode);
		}
		if ($err) {
			throw new Exception($err);
		}
		curl_close($curl);
	} catch (Exception $e) {
		throw $e;
	}
}

$tweets = query("SELECT * FROM conceito WHERE sucesso = 1 AND wikiID IS NULL");

foreach (getRows($tweets) as $key => $conceito) {
	try {
		$retorno = json_decode(getWikiId($conceito["resource"]), true);
		foreach ($retorno["results"]["bindings"] as $keyWiki => $wiki) {
			$sql = "UPDATE `conceito` SET wikiID = '" . escape($wiki["wikiID"]["value"]) . "' WHERE id = '" . $conceito["id"] . "';";
			query($sql);
		}
	} catch (Exception $e) {
		debug($e->getMessage());
	}
}
?>