<?php

require_once("../../config.php");

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
	foreach (array("q0", "q2") as $question) {
		$sql = "SELECT COUNT(*) as totalTweets 
				FROM tweets t
				JOIN tweets_sentiment_type ts ON ts.idTweetInterno = t.idInterno
				WHERE ts." . $key . " > 0 ";

		if ($question == "q0") {
			$sql .= " AND t.q1 = 0 ";
		} else {
			$sql .= " AND t.q2 = 1 ";
		}


		$sql .= "UNION ALL
				SELECT count(*) as totalTweets
				FROM tweets_amazon t
				JOIN tweets_sentiment_type ts ON ts.idTweetInterno = t.id
				WHERE ts." . $key . " > 0 ";
   
        if ($question == "q0") {
			$sql .= " AND q2 = 0 ";
		} else {
			$sql .= " AND q2 = 1 ";
		}
	
		debug($sql);

		$final = "SELECT SUM(totalTweets) as totalTweets FROM ( " . $sql . ") as x ";
		$res = query($final);

		foreach (getRows($res) as $keyRegister => $register) {
			$sentimentos[$key][$question] = $register["totalTweets"];
		}
	}
}

// var_dump($sentimentos);

$output = fopen("php://output",'w') or die("Can't open php://output");
header("Content-Type:application/csv"); 
header("Content-Disposition:attachment;filename=sentiment_type_amazon.csv"); 
fputcsv($output, array("Sentimento", "totalTweetsNaoAlcoolizados", "totalTweetsQ2"));
foreach ($sentimentos as $faixa => $faixas) {
	$put = array_merge(array($faixa), $faixas);
    fputcsv($output, $put);
  
}
fclose($output) or die("Can't close php://output");
?>