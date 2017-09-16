<?php

require_once("../config.php");
require('blockspring.php');

set_time_limit(0);
//set_time_limit(320);

function getArvoreCompleta($category, $nivel = 0) {
	if ($nivel > 3) {
		throw new Exception("Error Processing Request", 1);
	}
	
	$categoriesTmp = getCategoriesT($category);
	$categories = array();
	if (count($categoriesTmp) > 0) {
		foreach ($categoriesTmp as $key => $value) {
			try {
				$categories[$value] = array("nome" => $value);
				$tree = getArvoreCompleta($value, ($nivel + 1));
				if (count($tree)) {
					$categories[$value]["filhos"] = $tree;
				}
			} catch (Exception $e) {}
		}
	}
	return $categories;
}

function getCategoriesT($category) {
	$categories = array();
	$categoriesTmp = json_decode(getArvore($category));
	if ($categoriesTmp->query->categorymembers) {
		$categories = $categoriesTmp->query->categorymembers;
		while (isset($categoriesTmp->continue)) {
			$categoriesTmp = json_decode(getArvore($category, $categoriesTmp->continue->cmcontinue));
			$categories = array_merge($categories, $categoriesTmp->query->categorymembers);
		}
	}
	$cat = array();
	foreach ($categories as $key => $value) {
		$cat[] = $value->title;
	}
	return $cat;
}

function getArvoreCompletaNew($category, $nivel = 0) {
	if ($nivel > 3) {
		throw new Exception("Error Processing Request", 1);
	}
	
	$categoriesTmp = getCategoriesTNew($category);
	$categories = array();
	if (count($categoriesTmp) > 0) {
		foreach ($categoriesTmp as $key => $value) {
			try {
				$categories[$value] = array("nome" => $value);
				$tree = getArvoreCompletaNew($value, ($nivel + 1));
				if (count($tree)) {
					$categories[$value]["filhos"] = $tree;
				}
			} catch (Exception $e) {}
		}
	}
	return $categories;
}

function getCategoriesTNew($category) {
	$cats = Blockspring::runParsed("get-wikipedia-sub-categories", array("category" => "Ice hockey leagues in Canada"), array("api_key" => "br_71919_172db6c45274f6e73c502ef6698223c78d875639"))->params;
	return $cats["subcategories"];
}

function getArvore($category, $tokenContinue = "") {
	$url = "https://en.wikipedia.org/w/api.php?action=query&format=json&list=categorymembers&formatversion=2&cmtitle=" . urlencode($category) . "&cmtype=subcat&limit=500";
	if ($tokenContinue != "") {
		$url .= "&cmcontinue=" . $tokenContinue;
	}

	try {
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => $url, 
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_SSL_VERIFYHOST => 0,
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => array(
				'Api-User-Agent', 'marcosgrzeca@gmail.com',
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
			debug($url);
			throw new Exception("Resource " . $categorymembers . " - httpCode= " . $httpcode);
		}
		if ($err) {
			throw new Exception($err);
		}
		curl_close($curl);
	} catch (Exception $e) {
		throw $e;
	}
}

function getCategories($ids, $tokenContinue = "") {
	$ids = implode("|", array_values($ids));
	$url = "https://en.wikipedia.org/w/api.php?action=query&format=json&prop=categories&pageids=" . $ids . "&formatversion=2&clshow=!hidden&cllimit=500";
	if ($tokenContinue != "") {
		$url .= "&clcontinue=" . $tokenContinue;
	}

	try {
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => $url, 
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_SSL_VERIFYHOST => 0,
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => array(
				'Api-User-Agent', 'marcosgrzeca@gmail.com',
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
			debug($url);
			throw new Exception("CATEGORY " . $ids . " - httpCode= " . $httpcode);
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

echo "<pre>";
debug("INICIO " . date("H:i:s"));

$categoryTree = array();
$tweets = query("SELECT DISTINCT(category) as category FROM wikipedia_category wc WHERE category != '' AND NOT EXISTS (SELECT cl.categoriaPai FROM category_link cl WHERe cl.categoriaPai = category)");
foreach (getRows($tweets) as $key => $value) {
	$tree = getCategoriesTNew($value["category"]);
	foreach ($tree as $keyCat => $valueCat) {
		try {
			$insert = "INSERT INTO `category_link` (categoriaPai, categoriaFilho) VALUES ('" . escape($value["category"]) . "', '" . escape($valueCat) . "');";
			query($insert, false);
		} catch (Exception $e) {}
	}
}
?>