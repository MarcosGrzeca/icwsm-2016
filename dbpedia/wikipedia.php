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

$salvarBD = true;

if ($salvarBD) {
	$tweets = query("SELECT * FROM conceito WHERE sucesso = 1 AND wikiID IS NULL LIMIT 300");
} else {
	$tweets = query("SELECT * FROM conceito WHERE sucesso = 1 AND resourceTypes IS NOT NULL");
}

echo "<pre>";

echo "hora Inicio " . date("H:i:s") . "<br/>";

foreach (getRows($tweets) as $key => $conceito) {
	try {
		$retorno = json_decode(getWikiId($conceito["resource"]), true);
		foreach ($retorno["results"]["bindings"] as $keyWiki => $wiki) {
			$sql = "UPDATE `conceito` SET wikiID = '" . escape($wiki["wikiID"]["value"]) . "' WHERE id = '" . $conceito["id"] . "';";
			query($sql);
		}
		//debug($retorno);

		/*if (is_array($retorno)) {
			foreach ($retorno as $keyConceito => $valueConceito) {
				# code...

				if ($keyConceito == "http://dbpedia.org/ontology/wikiPageID") {
					debug($valueConceito);
				}
			}

		}*/
		//var_export($retorno);
//		debug($retorno);

		/*if ($salvarBD) {
			$sql = "UPDATE `conceito` SET resourceCompleto = '" . escape(json_encode($retorno)) . "' WHERE id = '" . $conceito["id"] . "';";
			query($sql);
		}

		foreach ($retorno["results"]["bindings"] as $keyType => $type) {
			$typesLocais[] = $type["type"]["value"];
			if (!isset($types[$type["type"]["value"]])) {
				$types[$type["type"]["value"]] = array("value" => $type["type"]["value"], "count" => 0, "relacoes" => array());
			}
		}
		$retornoSubClasses = json_decode(getSubClasses($conceito["resource"]), true);
		foreach ($retornoSubClasses["results"]["bindings"] as $key => $value) {
			if (isset($types[$value["x"]["value"]])) {
				if (!in_array($value["type"]["value"], $types[$value["x"]["value"]]["relacoes"])) {
					$types[$value["x"]["value"]]["relacoes"][] = $value["type"]["value"];
					try {
						if ($salvarBD) {
							$sql = "INSERT INTO bridge VALUES ('" . escape($value["x"]["value"]) . "', '" . escape($value["type"]["value"]) . "');";
							query($sql, false);
						}
					} catch (Exception $e) {}
					if (!isset($types[$value["type"]["value"]])) {
						$types[$value["type"]["value"]] = array("value" => $value["type"]["value"], "count" => 0, "relacoes" => array());
					}
					if (!in_array($value["type"]["value"], $typesLocais)) {
						$typesLocais[] = $value["type"]["value"];
					}
				}
			}
		}
		$retornoSum = json_decode(getCountSC($conceito["resource"]), true);
		foreach ($retornoSum["results"]["bindings"] as $key => $value) {
			$types[$value["type"]["value"]]["count"] = $value["valueSum"]["value"];
			try {
				if ($salvarBD) {
					$sql = "INSERT INTO `type` (type, sum) VALUES ('" . escape($value["type"]["value"]) . "', '" . escape($value["valueSum"]["value"]) . "');";
					query($sql, false);
				}
			} catch (Exception $e) {}
		}

		foreach ($typesLocais as $keyCT => $valueCT) {
			try {
				if ($salvarBD) {
					$sql = "INSERT INTO `resource_type` (resource, type) VALUES ('" . escape($conceito["resource"]) . "', '" . escape($valueCT) . "');";
					query($sql, false);
				}
			} catch (Exception $e) {}
		}
		*/
	} catch (Exception $e) {
		debug($e->getMessage());
	}
}
/*try {
	$myfile = fopen("types.txt", "w");
	fwrite($myfile, json_encode($types));
	fclose($myfile);
} catch (Exception $e) {
	debug("Nao foi possivel criar o arquivo");
}*/
?>