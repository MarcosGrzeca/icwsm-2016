<?php

require_once("../config.php");

set_time_limit(0);

function getFilhos($category) {
	$categorias = array();
	$tweets = query("SELECT * FROM category_link WHERE categoriaPai = '" . escape($category) . "';");
	foreach (getRows($tweets) as $key => $value) {
		$categorias[] = $value["categoriaFilho"];
	}
	return $categorias;
}

$salvarBD = true;

echo "<pre>";
debug("INICIO " . date("H:i:s"));
$categoryTree = array();

$tweets = query("SELECT DISTINCT(category) as category FROM wikipedia_category wc WHERE category != '' LIMIT 5");
foreach (getRows($tweets) as $key => $value) {
	if (!$categoryTree[$value["category"]]) {
		$categoryTree[$value["category"]] = array("value" => $value["category"], "relacoes" => array());
	}
	foreach (getFilhos($value["category"]) as $catFilho) {
		if (!isset($categoryTree[$catFilho])) {
			$categoryTree[$catFilho] = array("value" => $catFilho, "relacoes" => array());
		}
		$categoryTree[$catFilho]["relacoes"][] = $value["category"];
	}
}

var_export(str_replace("Category:", "", json_encode($categoryTree)));
debug("FIM " .date("H:i:s"));
?>