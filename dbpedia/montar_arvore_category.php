<?php
require_once("../config.php");

$tree = array();
$tweets = query("SELECT count(*) as total, subject FROM `resource_subject` GROUP by subject ORDER BY total DESC");
$totalCategories = getNumRows($tweets);

$dados = [];
foreach (getRows($tweets) as $key => $escolhido) {
	if ($escolhido["total"] == 1) {
		continue;
	}

	if (! isset($tree[$escolhido["subject"]])) {
		$tree[$escolhido["subject"]] = array("value" => $escolhido["subject"], "relacoes" => array());
		$relacoes = query("SELECT * FROM `bridge_subject` WHERE filho = '" . escape($escolhido["subject"]) . "';");
		foreach (getRows($relacoes) as $key => $pai) {
			$tree[$escolhido["subject"]]["relacoes"][] =  $pai["pai"];

			if (!isset($tree[$pai["pai"]])) {
				$tree[$pai["pai"]] = array("value" => $pai["pai"], "relacoes" => array());		
			}
		}
	}
	$dados[] = $escolhido["total"];
}


//http://dbpedia.org/resource/Category:
//echo "<pre>";
//var_export($tree);


$quartil1Grafo = stats_stat_percentile($dados, 25);
$quartil3Grafo = stats_stat_percentile($dados, 75);

var_export($quartil1Grafo . "<br/>");
var_export($quartil3Grafo . "<br/>");
