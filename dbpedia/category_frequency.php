<?php
require_once("../config.php");

$tweets = query("SELECT count(*) as total, subject FROM `resource_subject` GROUP by subject ORDER BY total DESC;");
$totalCategories = getNumRows($tweets);
$totalCategoriesSelecionados = round($totalCategories * 0.25);

$sql = "UPDATE `resource_subject` SET `escolhida` = 0;";
//query($sql);


$dados = [];
foreach (getRows($tweets) as $key => $escolhido) {
	if ($escolhido["total"] == 1) {
		continue;
	}
	$dados[] = $escolhido["total"];
}

echo "<pre>";

var_export($dados);

$quartil1Grafo = stats_stat_percentile($dados, 25);
$quartil3Grafo = stats_stat_percentile($dados, 75);

var_export($quartil1Grafo . "<br/>");
var_export($quartil3Grafo . "<br/>");
/*
$ind = 0;
$tweets = query("SELECT count(*) as total, subject FROM `resource_subject` GROUP by subject ORDER BY total DESC LIMIT $totalCategoriesSelecionados;");
foreach (getRows($tweets) as $key => $escolhido) {
	$sql = "UPDATE `resource_subject` SET `escolhida` = 1 WHERE `subject` = '" . escape($escolhido["subject"]) . "';";
	query($sql);
	//debug($sql);
	//break;
}

*/