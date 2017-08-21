<?
require_once("config.php");

$tweets = query("SELECT * FROM tweets WHERE data IS NULL");

foreach (getRows($tweets) as $key => $value) {
	try {
		$tweet = json_decode($value["texto"]);
		$data = date("Y-m-d H:i:s", strtotime($tweet->created_at));
		$dataConvertida = date("Y-m-d H:i:s", strtotime("-4 hours", strtotime($tweet->created_at)));
		$diaSemana = date("D", strtotime($dataConvertida));
		$hora = date("H", strtotime($dataConvertida));
		$update = "UPDATE `tweets` SET data = '" . $data . "', dataConvertida = '" . $dataConvertida . "', diaSemana = '" . $diaSemana . "', hora = '" . $hora . "' WHERE id = "  . $value["id"];
		query($update);
	} catch (Exception $e) {
		debug("ERRO");
		debug($e->getMessage());
	}

}
echo "FIM";

?>