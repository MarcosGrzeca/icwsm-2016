<?php

require_once("config.php");

$sentimentos = array();
$sentimentos["anger"] = array();
$sentimentos["anticipation"] = array();
$sentimentos["disgust"] = array();
$sentimentos["fear"] = array();
$sentimentos["joy"] = array();
$sentimentos["sadness"] = array();
$sentimentos["surprise"] = array();
$sentimentos["trust"] = array();
$sentimentos["negative"] = array();
$sentimentos["positive"] = array();

foreach ($sentimentos as $key => $sentimento) {
	foreach (array("q1", "q2", "q3") as $question) {
		$sql = "SELECT COUNT(*) as totalTweets 
				FROM tweets t
				JOIN tweets_sentiment_type ts ON ts.idTweetInterno = t.idInterno
				WHERE t." . $question . " = 1 
				AND ts." . $key . " > 0
		";

		debug($sql);
		$res = query($sql);
		foreach (getRows($res) as $keyRegister => $register) {
			$sentimentos[$key][$question] = $register["totalTweets"];
		}
	}
}

$output = fopen("php://output",'w') or die("Can't open php://output");
header("Content-Type:application/csv"); 
header("Content-Disposition:attachment;filename=sentiment_type.csv"); 
fputcsv($output, array("Sentimento", "totalTweetsQ1", "totalTweetsQ2", "totalTweetsQ3"));
foreach ($sentimentos as $faixa => $faixas) {
	$put = array_merge(array($faixa), $faixas);
    fputcsv($output, $put);
  
}
fclose($output) or die("Can't close php://output");
?>