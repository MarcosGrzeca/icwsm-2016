<?
require_once("config.php");

/*$res = getTweetById("509197123100110850");
debug($res);
die;*/

/*
- Converter hiperlink para #url
- Converter mentions #mntion
- Emoticons para aspectos positivos ou negativos
- Hashtags diferentes aspectos
- Truncate em palabras que algum caracter aparecia mais de duas vezes consecutivamente: druuuuuuuuuuuuunk -> druunk
*/

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
        $texto = str_ireplace($giriasRisada, " RISADA ", $texto);
    }
    $textAnt = "";
    while ($textAnt != $texto) {
        $textAnt = $texto;
        $texto = strtr($texto, $emoticonsRisos);
    }
    return trim($texto);
}

$emoticonsRisos = ["😂" => " RISADA ", "😹" => " RISADA ", "😆" => " RISADA ", "🤣" => " RISADA "];

$giriasRisada = array("ALOL", "LMAO", "LMBO", "LMFAO", "LOL", "ROFL", "ROTFL", "ROFLMAO", "ROTFLMAO", "ROFLMAOWPIMP", "ROTFLMAOWPIMP", "ROFLOL", "ROTFLOL");

$tweets = query("SELECT * FROM tweets WHERE situacao = 'N'");

foreach (getRows($tweets) as $key => $value) {
	try {
        $tweet = json_decode($value["texto"]);
        $textoFull = $tweet->text;
		$texto = $tweet->text;
		$hashTags = array();

        if ($texto != replaceAll($texto)) {
            debug($texto);
            debug(replaceAll($texto), "I");
        }

        $texto = replaceAll($texto);

		if (isset($tweet->entities->hashtags)) {
			foreach ($tweet->entities->hashtags as $key => $hashTag) {
				$hashTags[] = "#" . $hashTag->text;
			}
			if ($hashTags) {
				$texto = str_replace($hashTags, "", $texto);
			}
		}

		if (isset($tweet->entities->user_mentions)) {
			$users = array();
			foreach ($tweet->entities->user_mentions as $key => $user) {
				$users[] = "@" . $user->screen_name;
			}
			if ($users) {
				$texto = str_replace($users, "#mention", $texto);
			}
		}

		if (isset($tweet->entities->urls)) {
			$urls = array();
			foreach ($tweet->entities->urls as $key => $url) {
				$urls[] = $url->url;
			}
			if ($urls) {
				$texto = str_replace($urls, "#url", $texto);
			}
		}

        if (isset($tweet->entities->media)) {
            $media = array();
            foreach ($tweet->entities->media as $key => $url) {
                $media[] = $url->url;
            }
            if ($media) {
                $texto = str_replace($media, "#media", $texto);
            }
        }

        $texto = removeEmoji($texto);
        
        //Remover drunnnnnnnnnnnnnnnnnnnnk
        while (preg_match('/(.)\\1{2}/', $texto)) {
            $texto = preg_replace('/(.)\\1{2}/', '$1$1', $texto);
        }
		$update = "UPDATE `tweets` SET textoParserRisadaEmoticom = '" . mysqli_real_escape_string(Connection::get(), $textoFull) . "' WHERE id = "  . $value["id"];
        //debug($textoFull);
        debug($update);
        query($update);
	} catch (Exception $e) {
		debug("ERRO");
		debug($e->getMessage());		
	}
}

echo "FIM";

function checkEmoji($str) 
{
    $regexEmoticons = '/[\x{1F600}-\x{1F64F}]/u';
    preg_match($regexEmoticons, $str, $matches_emo);
    debug($matches_emo);
    if (!empty($matches_emo[0])) {
        return false;
    }
    
    // Match Miscellaneous Symbols and Pictographs
    $regexSymbols = '/[\x{1F300}-\x{1F5FF}]/u';
    preg_match($regexSymbols, $str, $matches_sym);
    debug($matches_emo);
    if (!empty($matches_sym[0])) {
        return false;
    }

    // Match Transport And Map Symbols
    $regexTransport = '/[\x{1F680}-\x{1F6FF}]/u';
    preg_match($regexTransport, $str, $matches_trans);
    debug($matches_emo);
    if (!empty($matches_trans[0])) {
        return false;
    }
   
    // Match Miscellaneous Symbols
    $regexMisc = '/[\x{2600}-\x{26FF}]/u';
    preg_match($regexMisc, $str, $matches_misc);
    debug($matches_emo);
    if (!empty($matches_misc[0])) {
        return false;
    }

    // Match Dingbats
    $regexDingbats = '/[\x{2700}-\x{27BF}]/u';
    preg_match($regexDingbats, $str, $matches_bats);
    debug($matches_emo);
    if (!empty($matches_bats[0])) {
        return false;
    }

    return true;
}

function getEmoticons($str) 
{
	$emoticons = [];
    /*$regexEmoticons = '/[\x{1F600}-\x{1F64F}]/u';
    preg_match($regexEmoticons, $str, $matches_emo);
    if (count($matches_emo)) {
    	$emoticons[] = $matches_emo[0];
    }
    
    // Match Miscellaneous Symbols and Pictographs
    $regexSymbols = '/[\x{1F300}-\x{1F5FF}]/u';
    preg_match($regexSymbols, $str, $matches_sym);
    if (count($matches_emo)) {
    	$emoticons[] = $matches_emo[0];
    }

    // Match Transport And Map Symbols
    $regexTransport = '/[\x{1F680}-\x{1F6FF}]/u';
    preg_match($regexTransport, $str, $matches_trans);
    if (count($matches_emo)) {
    	$emoticons[] = $matches_emo[0];
    }
   
    // Match Miscellaneous Symbols
    $regexMisc = '/[\x{2600}-\x{26FF}]/u';
    preg_match($regexMisc, $str, $matches_misc);
    if (count($matches_emo)) {
    	$emoticons[] = $matches_emo[0];
    }

    // Match Dingbats
    $regexDingbats = '/[\x{2700}-\x{27BF}]/u';
    preg_match($regexDingbats, $str, $matches_bats);
    if (count($matches_emo)) {
    	$emoticons[] = $matches_emo[0];
    }
*/

    preg_match_all('/([0-9#][\x{20E3}])|[\x{00ae}\x{00a9}\x{203C}\x{2047}\x{2048}\x{2049}\x{3030}\x{303D}\x{2139}\x{2122}\x{3297}\x{3299}][\x{FE00}-\x{FEFF}]?|[\x{2190}-\x{21FF}][\x{FE00}-\x{FEFF}]?|[\x{2300}-\x{23FF}][\x{FE00}-\x{FEFF}]?|[\x{2460}-\x{24FF}][\x{FE00}-\x{FEFF}]?|[\x{25A0}-\x{25FF}][\x{FE00}-\x{FEFF}]?|[\x{2600}-\x{27BF}][\x{FE00}-\x{FEFF}]?|[\x{2900}-\x{297F}][\x{FE00}-\x{FEFF}]?|[\x{2B00}-\x{2BF0}][\x{FE00}-\x{FEFF}]?|[\x{1F000}-\x{1F6FF}][\x{FE00}-\x{FEFF}]?/u', $str, $extrat);
    debug($extrat[0], "I");
   

    return $emoticons;
}

function removeEmoji($text) {

    $clean_text = "";

    // Match Emoticons
    $regexEmoticons = '/[\x{1F600}-\x{1F64F}]/u';
    $clean_text = preg_replace($regexEmoticons, '', $text);

    // Match Miscellaneous Symbols and Pictographs
    $regexSymbols = '/[\x{1F300}-\x{1F5FF}]/u';
    $clean_text = preg_replace($regexSymbols, '', $clean_text);

    // Match Transport And Map Symbols
    $regexTransport = '/[\x{1F680}-\x{1F6FF}]/u';
    $clean_text = preg_replace($regexTransport, '', $clean_text);

    // Match Miscellaneous Symbols
    $regexMisc = '/[\x{2600}-\x{26FF}]/u';
    $clean_text = preg_replace($regexMisc, '', $clean_text);

    // Match Dingbats
    $regexDingbats = '/[\x{2700}-\x{27BF}]/u';
    $clean_text = preg_replace($regexDingbats, '', $clean_text);


  	$clean_text = preg_replace('/([0-9#][\x{20E3}])|[\x{00ae}\x{00a9}\x{203C}\x{2047}\x{2048}\x{2049}\x{3030}\x{303D}\x{2139}\x{2122}\x{3297}\x{3299}][\x{FE00}-\x{FEFF}]?|[\x{2190}-\x{21FF}][\x{FE00}-\x{FEFF}]?|[\x{2300}-\x{23FF}][\x{FE00}-\x{FEFF}]?|[\x{2460}-\x{24FF}][\x{FE00}-\x{FEFF}]?|[\x{25A0}-\x{25FF}][\x{FE00}-\x{FEFF}]?|[\x{2600}-\x{27BF}][\x{FE00}-\x{FEFF}]?|[\x{2900}-\x{297F}][\x{FE00}-\x{FEFF}]?|[\x{2B00}-\x{2BF0}][\x{FE00}-\x{FEFF}]?|[\x{1F000}-\x{1F6FF}][\x{FE00}-\x{FEFF}]?/u', '', $clean_text);
    return $clean_text;
}

?>