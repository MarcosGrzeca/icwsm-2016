<?php
set_time_limit(0);
require_once("../config.php");
Connection::setBD("icwsm-2016");

$wordsGeneral = array();
$wordsDocs = array();
$wordsAlc = array();

$grams = array();
$tweets = query("SELECT id, textParser as texto, q1 FROM tweets WHERE situacao = 'S'");

echo "<pre>";

foreach (getRows($tweets) as $key => $value) {
	$words = array();
	foreach (explode("<br />", nl2br($value["texto"])) as $keyWa => $valueWa) {
		foreach (explode(" ", $valueWa) as $keyW => $valueW) {
			$substituir = array("<br />", PHP_EOL, "<br/>", "<br>", ",", ".");
			$words[] = strtolower(nl2br(str_replace($substituir, "", nl2br(trim($valueW)))));
		}
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
	$wordsAlc[$value["id"]] = $value["q1"];
}


#header('Content-Type: text/csv; charset=utf-8');
#header('Content-Disposition: attachment; filename=data.csv');

// create a file pointer connected to the output stream
$output = fopen('tweet_data_no.csv', 'w');

fputcsv($output, array_merge(array_merge(array("id"), $wordsGeneral), array("q1")));

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
	$arrayTmp["q1"] = $wordsAlc[$key];
	fputcsv($output, $arrayTmp);
}

fclose($output);
?>