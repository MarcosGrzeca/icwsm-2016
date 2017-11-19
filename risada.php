<?php
//Lista de slang https://en.wiktionary.org/wiki/Appendix:English_internet_slang

require_once("config.php");

$emoticonsRisos = '[{
	"name": "joy",
	"emoji": "😂",
	"polarity": 3
}, {
	"name": "joy_cat",
	"emoji": "😹",
	"polarity": 3
}, {
	"name": "laughing",
	"emoji": "😆",
	"polarity": 1
}, {
	"name": "rofl",
	"emoji": "🤣",
	"polarity": 4
}]';

$emoticonsRisos = ["😂" => "RISADA", "😹" => "RISADA", "😆" => "RISADA", "🤣" => "RISADA"];

$giriasRisada = array("ALOL", "LMAO", "LMBO", "LMFAO", "LOL", "ROFL", "ROTFL", "ROFLMAO", "ROTFLMAO", "ROFLMAOWPIMP", "ROTFLMAOWPIMP", "ROFLOL", "ROTFLOL");

function replaceAll($texto) {
	global $giriasRisada, $emoticonsRisos;
	$texto = " " . $texto . " ";
	
	$textAnt = "";
	while ($textAnt != $texto) {
		$textAnt = $texto;
		$texto = preg_replace("/[^\w][hakeHAKE]{3,}[^\w]/", ' RISADA ', $texto);
	}
	$textAnt = "";
	while ($textAnt != $texto) {
		$textAnt = $texto;
		$texto = str_ireplace($giriasRisada, "RISADA", $texto);
	}
	$textAnt = "";
	while ($textAnt != $texto) {
		$textAnt = $texto;
		$texto = strtr($texto, $emoticonsRisos);
	}
	return trim($texto);
}

echo "<pre>";

$tweets = query("SELECT * FROM tweets LIMIT 2050");

echo getNumRows($tweets) . "<br/>";

foreach (getRows($tweets) as $keyTweet => $tweet) {
	try {
		$texto = $tweet["textoParserEmoticom"];
	    //debug($texto);
		$textoParser = replaceAll($texto);
		if ($textoParser != $texto) {
			debug($texto, "I");
			debug($textoParser, "W");
		}
			
	} catch (Exception $e) {
		var_dump($e->getMessage());
	}
	continue;

	//textoParserRisadaEmoticom
	$regex = "@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@";
	$texto = preg_replace($regex, '', $texto);
	if (isset($output)) {
		unset($output);
	}

	/*var_export($texto . "<br/>");
	var_export($output);
	var_export("<br/>");*/
	
	$correcoes = $words = array();
	if (count($output) == 1 && $output[0] == "NULL") {
		continue;
	} else {
		//var_export("TEXTO <br/>" . $texto);
		//var_export($output);
		$words = array_values(array_filter(explode("\"", $output[1])));
		unset($words[0]);
		foreach ($words as $key => $value) {
			if (trim($value) == "") {
				unset($words[$key]);
			}
		}
		$words = array_values($words);
		$totalErros = count($words);
		//debug($words);
		//debug($totalErros);

		$correcoes = array();
		$indWord = 0;
		foreach ($output as $key => $value) {
			if ($key <= 2) {
				continue;
			}

			if (trim($value) == "") {
				$indWord++;
			} else {
				if (!isset($correcoes[$indWord])) {
					$correcoes[$indWord] = array();
				}
				$wordsCorrecoes = array_values(array_filter(explode("\"", $value)));
				unset($wordsCorrecoes[0]);
				foreach ($wordsCorrecoes as $key => $value) {
					if (trim($value) == "") {
						unset($wordsCorrecoes[$key]);
					}
				}
				$correcoes[$indWord] = array_merge($correcoes[$indWord], $wordsCorrecoes);
			}
		}

		foreach ($words as $key => $value) {
			if (in_array(strtolower($value), $correcoes[$key])) {
				unset($words[$key]);
				unset($correcoes[$key]);
			}
			if (in_array(strtoupper($value), $girias)) {
				unset($words[$key]);
				unset($correcoes[$key]);
			}
			if (in_array(strtolower($value), $redesSociais)) {
				unset($words[$key]);
				unset($correcoes[$key]);
			}
			if (in_array(strtolower($value), $errosConhecidos)) {
				unset($words[$key]);
				unset($correcoes[$key]);
			}
			if (strlen($value) <= 2) {
				unset($words[$key]);
				unset($correcoes[$key]);
			}

			if (isset($words[$key]) && isset($correcoes[$key])) {
				foreach ($correcoes[$key] as $keyCorrecao => $valueCorrecao) {
					if (strlen($value) >= strlen($valueCorrecao)) {
						if (levenshtein(strtolower($value), strtolower($valueCorrecao), 1, 2, 1) == 1) {
							$keyWordTwo = 0;
							$sucesso = true;

							$palavra = $words[$key];
							for ($keyW = 0; $keyW < strlen($palavra); $keyW++) {
								if (isset($valueCorrecao[$keyWordTwo]) && $palavra[$keyW] == $valueCorrecao[$keyWordTwo]) {
									$keyWordTwo++;
								} else if ($keyWordTwo > 0 && $palavra[$keyW] == $valueCorrecao[($keyWordTwo - 1)]) {
								} else {
									$sucesso = false;
								}
							}
							if ($sucesso) {
								//debug("MATCH " . $words[$key] . "  ----- " . $valueCorrecao);
								unset($words[$key]);
								unset($correcoes[$key]);
							}
						}
					}
					if ($keyCorrecao > 2) {
						break;
					}
				}
			}
		}

		//var_export("<br/>Final Erros ====>>>> " . $texto . "<br/>");
		//var_export($words);

		$sql = "UPDATE tweets SET `erroParseado` = " . count($words) . " WHERE id = " . $tweet["id"];
		query($sql);

		foreach ($words as $wordErro) {
			$sql = "INSERT INTO tweets_erro_new (idTweetInterno, palavra, versao) VALUES ('" . $tweet["idInterno"] . "', '" . escape(utf8_encode($wordErro)) . "', 3);";
			query($sql);
		}
	}
}

echo "<br/>";

//var_export($return_var);
?>