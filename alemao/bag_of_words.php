<?
require_once("config.php");
Connection::setBD("alemao");

$bagOfWords = array();
$names = array();

try {
	$sql = "SELECT id, alc, texto FROM conversa LIMIT 0,200";
	$res = query($sql);
	foreach (getRows($res) as $key => $value) {
		debug($value);
		//$bagOfWords
	}
} catch (Exception $e) {
	debug("ERRO");
	debug($e->getMessage());		
}
?>