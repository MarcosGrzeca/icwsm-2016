<?
require_once("config.php");

$nlp = query("SELECT palavra FROM tweets_nlp WHERE origem = 'C'");

$palavras = [];
foreach ($nlp as $key => $value) {
	$palavras[] = $value["palavra"];
}

if (($handle = fopen("2008.csv", "r")) !== FALSE) {
    $headers = fgetcsv($handle, 20000, ",");

    $arInserir = array();
    foreach ($headers as $key => $value) {
    	if (!in_array($value, array("idInterno", "resposta", "emoticonPos", "emoticonNeg", "localCount", "organizationCount", "moneyCount", "personCount", "numeroErros", "numeroConjuncoes", "palavroes", "name", "category", "adjetivo", "substantivo", "adverbio", "verbo", "turno", "emotiom", "emotiomH", "diaSemana"))) {
    		$arInserir[$key] = $value;
    	}
    }
    while (($data = fgetcsv($handle, 20000, ",")) !== FALSE) {
        foreach ($data as $key => $value) {
    		if ($value > 0 && isset($arInserir[$key])) {
		    	$insert = "INSERT INTO `tweets_gram` (idTweetInterno, palavra, tipo) VALUES ('" . $data[0]. "', '" . escape($arInserir[$key]) . "', '1')";
		    	query($insert);
		    }
	   	}
    }
}
echo "FIM";
?>