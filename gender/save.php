<?php

//var_dump($_REQUEST);

require_once("../config.php");

foreach ($_REQUEST as $key => $value) {
	if (!empty($value)) {
		$update = "UPDATE user SET gender = '" . $value . "', genero_manualmente = 'S' WHERE id = " . $key . ";";
		var_dump($update);
		query($update);
	}
	//$tweets = query("SELECT * FROM user WHERE gender = '' LIMIT 50");
}

header("Location: index.php");

?>