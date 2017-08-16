<?php
set_time_limit(0);

require_once("../config.php");
Connection::setBD("alemao");

$wordsGeneral = array();
$wordsDocs = array();
$wordsAlc = array();

$grams = array();
$tweets = query("(SELECT id, texto, alc, repetitions, longpauses FROM conversa WHERE alc = 'a' LIMIT 3000) UNION (SELECT id, texto, alc, repetitions, longpauses FROM conversa WHERE alc != 'a' LIMIT 3000)");

foreach (getRows($tweets) as $key => $value) {
	$words = array();
	foreach (explode(" ", $value["texto"]) as $keyW => $valueW) {
		$words[] = strtolower($valueW);
	}

	foreach ($words as $keyW => $valueW) {
		if (isset($words[$keyW]) && isset($words[$keyW + 1])) {
			$string = $words[$keyW] . "__" . $words[$keyW + 1];
			if (!in_array($string, $wordsGeneral)) {
				$stringInv = $words[$keyW + 1] . "__" . $words[$keyW];
				if (in_array($stringInv, $wordsGeneral)) {
					//$string = $stringInv;
				}
			}
			$wordsGeneral[] = $string;
			$wordsDocs[$value["id"]][$string] = $string;
		}
	}
	$wordsAlc[$value["id"]] = $value["alc"];
}


#header('Content-Type: text/csv; charset=utf-8');
#header('Content-Disposition: attachment; filename=data.csv');

// create a file pointer connected to the output stream
$output = fopen('data_no.csv', 'w');

fputcsv($output, array_merge(array_merge(array("id"), $wordsGeneral), array("alc")));

$arrayFinal = array();
foreach ($wordsDocs as $key => $value) {
	$arrayTmp = array("id" => $key);
	foreach ($wordsGeneral as $keyW => $valueW) {
		if (isset($value[$valueW])) {
			$arrayTmp[$valueW] = 1;
		} else {
			$arrayTmp[$valueW] = 0;
		}
	}
	$arrayTmp["alc"] = $wordsAlc[$key];
	fputcsv($output, $arrayTmp);
}

fclose($output);
?>