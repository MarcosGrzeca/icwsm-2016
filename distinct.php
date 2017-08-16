<?
require_once("config.php");
Connection::setBD("alemao");


$wordsAr = array();

//$tweets = query("SELECT * FROM conversa WHERE type NOT IN ('D') "); #5333
$tweets = query("SELECT * FROM conversa WHERE content IN ('A', 'P', 'N', 'R', 'T') "); #4696

foreach (getRows($tweets) as $key => $value) {
	try {
		if ($value["alc"] == "cna") {
			$value["alc"] = "na";
		}
		foreach (explode(" ", $value["texto"]) as $keyW => $valueW) {
			$valueW = strtolower($valueW);
			if (!isset($wordsAr[$valueW])) {
				$wordsAr[$valueW] = array("a" => 0, "na" => 0, "ids" => array());
			}


			$wordsAr[$valueW][$value["alc"]] = $wordsAr[$valueW][$value["alc"]] + 1;
			$wordsAr[$valueW]["ids"][] = $value["id"];
		}
	} catch (Exception $e) {
		debug("ERRO");
		debug($e->getMessage());		
	}
}

$ids = array();

$i = 0;
foreach ($wordsAr as $key => $value) {
	if ($value["a"] == 0 || $value["na"] == 0) {
		//echo $key . "<br/>";
		foreach ($value["ids"] as $keyI => $valueI) {
			if (!in_array($valueI, $ids)) {
				$ids[] = $valueI;
			}	
		}
		$i++;
	}
}

echo json_encode($ids);
echo "<br/><br/>";
echo count($ids);
//echo "FINAL" . $i;


?>