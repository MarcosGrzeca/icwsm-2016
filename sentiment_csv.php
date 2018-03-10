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
	foreach (array("q0", "q1", "q2", "q3") as $question) {
		$sql = "SELECT COUNT(*) as totalTweets 
				FROM tweets t
				JOIN tweets_sentiment_type ts ON ts.idTweetInterno = t.idInterno ";
		if ($question == "q0") {
			$sql .=	"WHERE t.q1 = 0 ";
		} else {
			$sql .=	" WHERE t." . $question . " = 1 ";
		}
		
		$sql .= "AND ts." . $key . " > 0
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
fputcsv($output, array("Sentimento", "totalTweetsNaoAlcoolizados", "totalTweetsQ1", "totalTweetsQ2", "totalTweetsQ3"));
foreach ($sentimentos as $faixa => $faixas) {
	$put = array_merge(array($faixa), $faixas);
    fputcsv($output, $put);
  
}
fclose($output) or die("Can't close php://output");
?>