<?php

require_once("../config.php");
require('blockspring.php');

set_time_limit(0);

function getCategoriesTNew($category) {
	$cats = Blockspring::runParsed("get-wikipedia-sub-categories", array("category" => $category), array("api_key" => "br_71919_172db6c45274f6e73c502ef6698223c78d875639"))->params;
	return $cats["subcategories"];
}

echo "<pre>";
debug("INICIO " . date("H:i:s"));

$categoryTree = array();
$tweets = query("SELECT DISTINCT(categoriaFilho) as categoriaFilho FROM category_link wc WHERE categoriaFilho != '' AND NOT EXISTS (SELECT cl.categoriaPai FROM category_link cl WHERe cl.categoriaPai = categoriaFilho)");
foreach (getRows($tweets) as $key => $value) {
	$tree = getCategoriesTNew($value["categoriaFilho"]);
	foreach ($tree as $keyCat => $valueCat) {
		try {
			$insert = "INSERT INTO `category_link` (categoriaPai, categoriaFilho) VALUES ('" . escape($value["categoriaFilho"]) . "', '" . escape("Category:" . $valueCat) . "');";
			query($insert, false);
		} catch (Exception $e) {}
	}
}
?>