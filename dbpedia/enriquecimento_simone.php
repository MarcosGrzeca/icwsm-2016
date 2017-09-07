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


$salvarBD = false;

echo "<pre>";
echo "hora Inicio " . date("H:i:s") . "<br/>";

$resources = array("http://dbpedia.org/resource/Adam", "http://dbpedia.org/resource/Andy_Carroll", "http://dbpedia.org/resource/Andy_Townsend", "http://dbpedia.org/resource/Bobby_Tambling", "http://dbpedia.org/resource/Charlie_Adam", "http://dbpedia.org/resource/Chris_Waddle", "http://dbpedia.org/resource/Daniel_Agger", "http://dbpedia.org/resource/Didier_Drogba", "http://dbpedia.org/resource/Frank_Lampard", "http://dbpedia.org/resource/Geoff_Hurst", "http://dbpedia.org/resource/Glen_Johnson_(English_footballer)", "http://dbpedia.org/resource/Graham_Norton", "http://dbpedia.org/resource/Jay_Spearing", "http://dbpedia.org/resource/Jimmy_Armfield", "http://dbpedia.org/resource/John_Terry", "http://dbpedia.org/resource/Jurgen,_A_Comedy_of_Justice", "http://dbpedia.org/resource/Ken_Dodd", "http://dbpedia.org/resource/Kenny_Dalglish", "http://dbpedia.org/resource/Lady_Gaga", "http://dbpedia.org/resource/Lisa_Simpson", "http://dbpedia.org/resource/Liverpool", "http://dbpedia.org/resource/Liverpool_F.C.", "http://dbpedia.org/resource/London", "http://dbpedia.org/resource/Louisville,_Kentucky", "http://dbpedia.org/resource/Luis_SuÃ¡rez", "http://dbpedia.org/resource/Manchester", "http://dbpedia.org/resource/Norway", "http://dbpedia.org/resource/Paul_the_Apostle", "http://dbpedia.org/resource/Roberto_Di_Matteo", "http://dbpedia.org/resource/Roy_Keane", "http://dbpedia.org/resource/Steven_Gerrard", "http://dbpedia.org/resource/United_Nations", "http://dbpedia.org/resource/Uruguay");

$types = array();

$resources = array(
"http://dbpedia.org/resource/Vodka", 
"http://dbpedia.org/resource/Beer", 
"http://dbpedia.org/resource/Absolut_Vodka", 
"http://dbpedia.org/resource/Wine");

$resources = array("http://dbpedia.org/resource/Influenza");

foreach ($resources as $key => $conceito) {
	try {
		$typesLocais = array();
		$retorno = json_decode(getTypesResource($conceito), true);
		debug($retorno["results"]["bindings"]);

		foreach ($retorno["results"]["bindings"] as $keyType => $type) {
			$typesLocais[] = $type["type"]["value"];
			if (!isset($types[$type["type"]["value"]])) {
				$types[$type["type"]["value"]] = array("value" => $type["type"]["value"], "count" => 0, "relacoes" => array());
			}
		}
		$retornoSubClasses = json_decode(getSubClasses($conceito), true);

		foreach ($retornoSubClasses["results"]["bindings"] as $key => $value) {
			if (isset($types[$value["x"]["value"]])) {
				if (!in_array($value["type"]["value"], $types[$value["x"]["value"]]["relacoes"])) {
					$types[$value["x"]["value"]]["relacoes"][] = $value["type"]["value"];
					if (!isset($types[$value["type"]["value"]])) {
						$types[$value["type"]["value"]] = array("value" => $value["type"]["value"], "count" => 0, "relacoes" => array());
					}
					if (!in_array($value["type"]["value"], $typesLocais)) {
						$typesLocais[] = $value["type"]["value"];
					}
				}
			}
		}
		$retornoSum = json_decode(getCountSC($conceito), true);
		foreach ($retornoSum["results"]["bindings"] as $key => $value) {
			$types[$value["type"]["value"]]["count"] = $value["valueSum"]["value"];
		}		
	} catch (Exception $e) {

	}
}

//var_export($typesLocais);


echo "<br/><br/>Hora fim " . date("H:i:s") . "<br/><br/><br/>";

var_export($types);

//var_export(json_encode($types));
echo "</pre>";

/*try {
	$myfile = fopen("types.txt", "w");
	fwrite($myfile, json_encode($types));
	fclose($myfile);
} catch (Exception $e) {
	debug("Nao foi possivel criar o arquivo");
}*/
?>