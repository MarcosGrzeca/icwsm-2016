<?
require_once("config.php");
Connection::setBD("rds");

$tweets = query("SELECT id, irreg FROM conversa WHERE irregularities IS NULL");

foreach (getRows($tweets) as $key => $value) {
	try {
		$exp = explode("|", $value["irreg"]);
		//$update = "UPDATE `conversa` SET `irregularities` = " . $exp[1 - 1] . ", `hesitations` = " . $exp[2 - 1] . ", `shortpauses` = " . $exp[3 - 1] . ", `longpauses` = " . $exp[4 - 1] . ", `wordlengthening` = " . $exp[5 - 1] . ", `wrongpronunciations` = " . $exp[6 - 1] . ", `repetitions` = " . $exp[7 - 1] . ", `correctionaltruncations` = " . $exp[8 - 1] . ", `interruptions` = " . $exp[9 - 1] . " WHERE id = "  . $value["id"];
		query($update);
	} catch (Exception $e) {
		debug("ERRO");
		debug($e->getMessage());		
	}
}

echo "FIM";

?>