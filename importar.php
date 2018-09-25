<?php
require_once("config.php");

$tweets = query("SELECT * FROM tweets_completo WHERE situacao = 'E'");

$ind = 0;
foreach (getRows($tweets) as $key => $value) {
	try {
		$res = getTweetById($value["id"]);
		$resultado = json_decode($res);
		if (isset($resultado->errors)) {
			$situacao = "E";
			foreach ($resultado->errors as $key => $erro) {
				if ($erro->code == "88") {
					echo "EXCEDEU LIMITEs " . $ind;
					//break 2;
					sleep(300);
				}
			}
		} else {
			echo $value["id"] . "<br/>";
			echo $res;
			debug($resultado);
			$situacao = "N";

			$update = "UPDATE `tweets_completo` SET situacao = '" . $situacao . "', texto = '" . mysqli_real_escape_string(Connection::get(), $res) . "' WHERE id = "  . $value["id"];
			query($update);
		}

		/*$update = "UPDATE `tweets` SET situacao = '" . $situacao . "', texto = '" . mysqli_real_escape_string(Connection::get(), $res) . "' WHERE id = "  . $value["id"];
		query($update);*/
	} catch (Exception $e) {
		debug("ERRO");
		debug($e->getMessage());		
	}
	$ind++;
}
echo "FIM";

?>