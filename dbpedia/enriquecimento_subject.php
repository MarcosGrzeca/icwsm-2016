<?php

require_once("../config.php");

set_time_limit(0);

function getSubjectsResource($resource) {
	try {
		$curl = curl_init();

		$query = 	"PREFIX dbres: <http://dbpedia.org/resource/>
					PREFIX  rdfs:   <http://www.w3.org/2000/01/rdf-schema#> 
					PREFIX  rdf:    <http://www.w3.org/1999/02/22-rdf-syntax-ns#>

					SELECT ?subject WHERE
					{ 
						<" . $resource . "> dct:subject ?subject.
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
		$query = 	"PREFIX dbres: <http://dbpedia.org/resource/>
					PREFIX  rdfs:   <http://www.w3.org/2000/01/rdf-schema#> 
					PREFIX  rdf:    <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
					PREFIX  cat: 	<http://www.w3.org/2004/02/skos/core#broader>


					SELECT ?subject ?x  WHERE
					{ 
						<" . $resource . "> dct:subject ?subject.
						?x skos:broader ?subject
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

function getSuperClasses($resource) {
	try {
		$curl = curl_init();
		$query = 	"PREFIX dbres: <http://dbpedia.org/resource/>
					PREFIX  rdfs:   <http://www.w3.org/2000/01/rdf-schema#> 
					PREFIX  rdf:    <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
					PREFIX  cat: 	<http://www.w3.org/2004/02/skos/core#broader>


					SELECT ?subject ?x  WHERE
					{ 
						<" . $resource . "> dct:subject ?subject.
						?subject skos:broader ?x
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


$salvarBD = true;

echo "<pre>";
echo "hora Inicio " . date("H:i:s") . "<br/>";

$resources = array("http://dbpedia.org/resource/Adam", "http://dbpedia.org/resource/Andy_Carroll", "http://dbpedia.org/resource/Andy_Townsend", "http://dbpedia.org/resource/Bobby_Tambling", "http://dbpedia.org/resource/Charlie_Adam", "http://dbpedia.org/resource/Chris_Waddle", "http://dbpedia.org/resource/Daniel_Agger", "http://dbpedia.org/resource/Didier_Drogba", "http://dbpedia.org/resource/Frank_Lampard", "http://dbpedia.org/resource/Geoff_Hurst", "http://dbpedia.org/resource/Glen_Johnson_(English_footballer)", "http://dbpedia.org/resource/Graham_Norton", "http://dbpedia.org/resource/Jay_Spearing", "http://dbpedia.org/resource/Jimmy_Armfield", "http://dbpedia.org/resource/John_Terry", "http://dbpedia.org/resource/Jurgen,_A_Comedy_of_Justice", "http://dbpedia.org/resource/Ken_Dodd", "http://dbpedia.org/resource/Kenny_Dalglish", "http://dbpedia.org/resource/Lady_Gaga", "http://dbpedia.org/resource/Lisa_Simpson", "http://dbpedia.org/resource/Liverpool", "http://dbpedia.org/resource/Liverpool_F.C.", "http://dbpedia.org/resource/London", "http://dbpedia.org/resource/Louisville,_Kentucky", "http://dbpedia.org/resource/Luis_SuÃ¡rez", "http://dbpedia.org/resource/Manchester", "http://dbpedia.org/resource/Norway", "http://dbpedia.org/resource/Paul_the_Apostle", "http://dbpedia.org/resource/Roberto_Di_Matteo", "http://dbpedia.org/resource/Roy_Keane", "http://dbpedia.org/resource/Steven_Gerrard", "http://dbpedia.org/resource/United_Nations", "http://dbpedia.org/resource/Uruguay");

$subjects = array();

$resources = array(
"http://dbpedia.org/resource/Vodka", 
"http://dbpedia.org/resource/Beer", 
"http://dbpedia.org/resource/Absolut_Vodka", 
"http://dbpedia.org/resource/Wine");

$resources = array(array("resource" => "http://dbpedia.org/resource/Influenza"));


$tweets = query("SELECT * FROM conceito WHERE sucesso = 1 AND resourceCompleto IS NOT NULL");
foreach (getRows($tweets) as $key => $conceito) {

//foreach ($resources as $key => $conceito) {

	try {
		$subjectsLocais = array();
		$retorno = json_decode(getSubjectsResource($conceito["resource"]), true);
		foreach ($retorno["results"]["bindings"] as $keyType => $type) {
			$subjectsLocais[] = $type["subject"]["value"];
			if (!isset($subjects[$type["subject"]["value"]])) {
				$subjects[$type["subject"]["value"]] = array("value" => $type["subject"]["value"], "relacoes" => array());
			}
		}

		$retornoSubClasses = json_decode(getSubClasses($conceito["resource"]), true);
		foreach ($retornoSubClasses["results"]["bindings"] as $key => $value) {
			if (!isset($subjects[$value["x"]["value"]])) {
				$subjects[$value["x"]["value"]] = array("value" => $value["x"]["value"], "relacoes" => array($value["subject"]["value"]));
				incluirBridge($value["x"]["value"], $value["subject"]["value"]);
			}
			if (!in_array($value["x"]["value"], $subjectsLocais)) {
				$subjectsLocais[] = $value["x"]["value"];
			}
		}

		$retornoSuperClasses = json_decode(getSuperClasses($conceito["resource"]), true);
		foreach ($retornoSuperClasses["results"]["bindings"] as $key => $value) {
			if (!isset($subjects[$value["x"]["value"]])) {
				$subjects[$value["x"]["value"]] = array("value" => $value["x"]["value"], "relacoes" => array());
			}
			if (!in_array($subjects[$value["x"]["value"]], $subjects[$value["subject"]["value"]]["relacoes"])) {
				$subjects[$value["subject"]["value"]]["relacoes"][] = $value["x"]["value"];
				incluirBridge($value["subject"]["value"], $value["x"]["value"]);
			}
			if (!in_array($value["x"]["value"], $subjectsLocais)) {
				$subjectsLocais[] = $value["x"]["value"];
			}
		}
		foreach ($subjectsLocais as $keyCT => $valueCT) {
			try {
				if ($salvarBD) {
					$sql = "INSERT INTO `resource_subject` (resource, subject) VALUES ('" . escape($conceito["resource"]) . "', '" . escape($valueCT) . "');";
					query($sql, true);
				}
			} catch (Exception $e) {}
		}
	} catch (Exception $e) {}
}

function incluirBridge($filho, $pai) {
	global $salvarBD;
	if ($salvarBD) {
		$sql = "INSERT INTO bridge_subject VALUES ('" . escape($filho) . "', '" . escape($pai) . "');";
		query($sql, false);
	}
}

//var_export($subjectsLocais);

echo "SUBJECT<br/><br/><br/>";
var_export(json_encode($subjects));
echo "</pre>";
?>