<?
require_once("config.php");

$tweets = query("SELECT * FROM tweets WHERE situacao = 'P' LIMIT 500");

foreach (getRows($tweets) as $key => $value) {
	try {
		echo $value["id"] . "<br/>";
		$res = getTweetById($value["id"]);
		$resultado = json_decode($res);
		if (isset($resultado->errors)) {
			$situacao = "E";
			foreach ($resultado->errors as $key => $erro) {
				if ($erro->code == "88") {
					echo "EXCEDEU LIMITEs";
					break 2;
				}
			}
		} else {
			$situacao = "S";	
		}

		$update = "UPDATE `tweets` SET situacao = '" . $situacao . "', texto = '" . mysqli_real_escape_string(Connection::get(), $res) . "' WHERE id = "  . $value["id"];
		query($update);
	} catch (Exception $e) {
		debug("ERRO");
		debug($e->getMessage());		
	}
}

echo "FIM";

?>