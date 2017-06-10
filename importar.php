<?
require_once("config.php");

$tweets = query("SELECT * FROM tweets WHERE situacao = 'P' LIMIT 500");

foreach (getRows($tweets) as $key => $value) {
	try {
		$res = getTweetById($value["id"]);
		$resultado = json_decode($res);
		debug($resultado);
		if (isset($resultado->errors)) {
			$situacao = "E";
		} else {
			$situacao = "S";	
		}

		//query("UPDATE `tweets` SET situacao = '" . $situacao . "', texto = '" . mysqli_real_escape_string($res) . "' WHERE id = "  . $value["id"]);

		$update = "UPDATE `tweets` SET situacao = '" . $situacao . "', texto = '" . mysqli_real_escape_string(Connection::get(), $res) . "' WHERE id = "  . $value["id"];
		query($update);

	} catch (Exception $e) {
		debug("ERRO");
		debug($e->getMessage());		
	}
}

?>