<?
require_once("config.php");
$wordsAr = array();
$tweets = query("SELECT * FROM tweets WHERE situacao = 'N' AND nlp IS NOT NULL AND id NOT IN (SELECT idTweet FROM tweets_anotacoes WHERE tweets_anotacoes.idTweet = tweets.id)");

$i = 0;
$sqls = "";

foreach (getRows($tweets) as $key => $value) {
	$nlp = json_decode($value["nlp"]);
	foreach ($nlp as $key => $parte) {
		if ($parte->type == "word") {
			foreach ($parte->features->POS as $keyP => $p) {
				if (trim($sqls) != "") {
					$sqls .= ",";
				}
				$sqls .= "(" . $value["id"] . ", " . $parte->start . ", " . $parte->end . ", '" . mysqli_real_escape_string(Connection::get(), $p) . "')";
			}
		}
	}

	$i++;

	if ($i % 100 == 0) {
		$sql = "INSERT INTO `tweets_anotacoes` (idTweet, start, end, pos) VALUES " . $sqls;
		query($sql);
		$sqls = "";
	}
}
if ($sqls != "") {
	$sql = "INSERT INTO `tweets_anotacoes` (idTweet, start, end, pos) VALUES " . $sqls;
	query($sql);
}
?>