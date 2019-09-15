<?php

//386 diferentes


require_once("config.php");

$tweets = query("SELECT * FROM user WHERE gender = '' LIMIT 50");
//$tweets = query("SELECT * FROM tweets LIMIT 3");

foreach (getRows($tweets) as $key => $value) {
	$generoFinal = "";

	if (!empty($value["gender_face"]) && $value["gender_face"] == $value["genderizer_gender"]) {
		$generoFinal = $value["gender_face"];
	} else if (!empty($value["gender_face"]) && empty($value["genderizer_gender"])) {
		$generoFinal = $value["gender_face"];
	} else if (empty($value["gender_face"]) && !empty($value["genderizer_gender"])) {
		$generoFinal = $value["genderizer_gender"];
	} else {

		//debug("NEWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWwww");
		debug($value["id"]);
		debug($value);
		debug("++ " . $value["gender_face"]);
		debug("GE " . $value["genderizer_gender"]);
		
		debug($value["profile_url"]);
		debug($value["url"]);
		

		//break;
		continue;
	}

	$update = "UPDATE `user` SET gender = '" . escape($generoFinal) . "' WHERE id = " . $value["id"];
	query($update, false);	
	//debug($generoFinal);
}


/*
- Se iguais: mesmo
- Se um vazio: entao outro
- Se ambos em branco, verificar
- Se diferentes, verificar

386 vazios
- analise da foto
- analise da url
- analise da descrição
- analise do nome

Se nome feminino e duas pessoas fotos na foto: entao feminino
*/
?>