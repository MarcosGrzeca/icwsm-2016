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
	$cats = Blockspring::runParsed("get-wikipedia-sub-categories", array("category" => $category), array("api_key" => "br_71919_172db6c45274f6e73c502ef6698223c78d875639"))->params;
	return $cats["subcategories"];
}

echo "<pre>";
debug("INICIO " . date("H:i:s"));

$categoryTree = array();
$tweets = query("SELECT DISTINCT(category) as category FROM wikipedia_category wc WHERE category != '' AND NOT EXISTS (SELECT cl.categoriaPai FROM category_link cl WHERe cl.categoriaPai = category)");
foreach (getRows($tweets) as $key => $value) {
	$tree = getCategoriesTNew($value["category"]);
	foreach ($tree as $keyCat => $valueCat) {
		try {
			$insert = "INSERT INTO `category_link` (categoriaPai, categoriaFilho) VALUES ('" . escape($value["category"]) . "', '" . escape("Category:" . $valueCat) . "');";
			query($insert, false);
		} catch (Exception $e) {}
	}
}
?>