<?php
require_once("../config.php");

$tweets = query("SELECT count(*) as total, subject FROM `resource_subject` GROUP by subject ORDER BY total DESC;");
$totalCategories = getNumRows($tweets);
$totalCategoriesSelecionados = round($totalCategories * 0.25);

$sql = "UPDATE `resource_subject` SET `escolhida` = 0;";
query($sql);
	

$ind = 0;
$tweets = query("SELECT count(*) as total, subject FROM `resource_subject` GROUP by subject ORDER BY total DESC LIMIT $totalCategoriesSelecionados;");
foreach (getRows($tweets) as $key => $escolhido) {
	$sql = "UPDATE `resource_subject` SET `escolhida` = 1 WHERE `subject` = '" . escape($escolhido["subject"]) . "';";
	query($sql);
	//debug($sql);
	//break;
}