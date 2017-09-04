<?php

require_once("../config.php");

set_time_limit(0);

function getTypesResource($resource) {
	try {
		$curl = curl_init();

		$query = "PREFIX dbres: <http://dbpedia.org/resource/>
		PREFIX  rdfs:   <http://www.w3.org/2000/01/rdf-schema#> 
		PREFIX  rdf:    <http://www.w3.org/1999/02/22-rdf-syntax-ns#>

		SELECT ?type WHERE
		{ 
			<" . $resource . "> rdf:type ?type.
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

function getSubClasses($resource) {
	try {

		$curl = curl_init();

		$query = "PREFIX dbres: <http://dbpedia.org/resource/>
		PREFIX  rdfs:   <http://www.w3.org/2000/01/rdf-schema#> 
		PREFIX  rdf:    <http://www.w3.org/1999/02/22-rdf-syntax-ns#>

		SELECT ?type ?x  WHERE
		{ 
			<" . $resource . "> rdf:type ?type.
			?x  rdfs:subClassOf  ?type

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
				"postman-token: 69a0f1bc-8421-f4df-1f5f-804dce3ea7fd"
				),
			));

		$response = curl_exec($curl);
		$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		$err = curl_error($curl);
		curl_close($curl);

		if ($httpcode == 200) {
			return $response;
		} else {
			throw new Exception("Resource " . $resource . " - httpCode= " . $httpcode);
		}
		if ($err) {
			throw new Exception($err);
		}
	} catch (Exception $e) {
		throw $e;
	}
}

function getCountSC($resource) {
	try {

		$curl = curl_init();

		$query = "PREFIX dbres: <http://dbpedia.org/resource/>
		PREFIX  rdfs:   <http://www.w3.org/2000/01/rdf-schema#> 
		PREFIX  rdf:    <http://www.w3.org/1999/02/22-rdf-syntax-ns#>

		SELECT ?type (COUNT(?x) as ?valueSum) WHERE
		{ 
			<" . $resource . "> rdf:type ?type.
			?x  rdfs:subClassOf  ?type

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
				"postman-token: 69a0f1bc-8421-f4df-1f5f-804dce3ea7fd"
				),
			));

		$response = curl_exec($curl);
		$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		$err = curl_error($curl);
		curl_close($curl);

		if ($httpcode == 200) {
			return $response;
		} else {
			throw new Exception("Resource " . $resource . " - httpCode= " . $httpcode);
		}
		if ($err) {
			throw new Exception($err);
		}
	} catch (Exception $e) {
		throw $e;
	}
}

/*query("UPDATE conceito SET resourceCompleto = NULL");
query("TRUNCATE TABLE type;");
query("TRUNCATE TABLE resource_type;");
query("TRUNCATE TABLE bridge;");*/

$salvarBD = true;

if ($salvarBD) {
	$tweets = query("SELECT * FROM conceito WHERE sucesso = 1 AND resourceCompleto IS NULL");
} else {
	$tweets = query("SELECT * FROM conceito WHERE sucesso = 1 AND resourceCompleto IS NOT NULL");
}

echo "<pre>";

echo "hora Inicio " . date("H:i:s") . "<br/>";

foreach (getRows($tweets) as $key => $conceito) {
	//$types = array();	
	try {

		$typesLocais = array();
		$retorno = json_decode(getTypesResource($conceito["resource"]), true);

		if ($salvarBD) {
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
	} catch (Exception $e) {

	}
}
echo "Hora fim " . date("H:i:s") . "<br/>";

var_export(json_encode($types));

//var_export($types);
echo "</pre>";

/*try {
	$myfile = fopen("types.txt", "w");
	fwrite($myfile, json_encode($types));
	fclose($myfile);
} catch (Exception $e) {
	debug("Nao foi possivel criar o arquivo");
}*/
?>