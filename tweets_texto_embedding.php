<?php
require_once("config.php");

set_time_limit(0);

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

$tweets = query("SELECT id, texto FROM tweets WHERE textEmbeddingComErrorHandling IS NULL LIMIT 500");

foreach (getRows($tweets) as $key => $value) {
    try {
        $tweet = json_decode($value["texto"]);
        $textoFull = $tweet->text;
        $texto = $tweet->text;
        $hashTags = array();

        //$texto = replaceAll($texto);

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

        //$texto = removeEmoji($texto);
        
        //Remover drunnnnnnnnnnnnnnnnnnnnk
        while (preg_match('/(.)\\1{2}/', $texto)) {
            $texto = preg_replace('/(.)\\1{2}/', '$1$1', $texto);
        }

        $textoSemHashaTagsApenasValidar = $texto;
        if (isset($tweet->entities->hashtags)) {
            foreach ($tweet->entities->hashtags as $key => $hashTag) {
                $hashTags[] = "#" . $hashTag->text;
            }
            if ($hashTags) {
                $textoSemHashaTagsApenasValidar = str_replace($hashTags, "", $texto);
            }
        }

        // $update = "UPDATE `tweets` SET textEmbedding = '" . mysqli_real_escape_string(Connection::get(), $texto) . "' WHERE id = "  . $value["id"];
        // query($update);

        $texotIni = $texto;
        // debug($texto);
        $totalErros = 0;
        $spell = spell($textoSemHashaTagsApenasValidar);
        $spellJSON = json_decode($spell);

        if (count($spellJSON->corrections)) {
            $totalErros = 0;
            foreach ($spellJSON->corrections as $palavraErro => $palavrasSimilares) {
                if (ehErro($palavraErro, $palavrasSimilares, $texto)) {
                    $totalErros++;                  
                } else {
                }
            }
        }

        if ($texotIni != $texto) {
            $marcos = 10;
            debug("TEXTO CORRIGIDO " . $totalErros);
            debug($texto);
            debug("===================="); 
        }

        $update = "UPDATE `tweets` SET textEmbeddingComErrorHandling = '" . mysqli_real_escape_string(Connection::get(), $texto) . "' WHERE id = "  . $value["id"];
        query($update);
    } catch (Exception $e) {
        debug("ERRO");
        debug($e->getMessage());
        die($e);
    }
}

echo "RESPONSE";

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

function spell($text) {
  $token = "lGazgCQaIgmshqsTCM14e16ZFWoXp1eRDWOjsnvwvYEM3SUdn1";
  if (rand(0, 1)) {
    $token = "9v2CvSfHZAmshXDNOhNV3qHyQeaap1Ggt0hjsneNotKCh7n7Ja";
  }

  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_URL => "https://montanaflynn-spellcheck.p.mashape.com/check/?text=" . urlencode($text),
    CURLOPT_SSL_VERIFYHOST => 0,
    CURLOPT_SSL_VERIFYPEER => 0,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
        "Cache-Control: no-cache",
        "X-Mashape-Key: " . $token
    ),
  ));
    
  $response = curl_exec($curl);
  $err = curl_error($curl);
  $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

  curl_close($curl);

  if ($err) {
    throw new Exception($err, 1);
  } else {
    if ($httpcode == 200) {
        return $response;
    }
    throw new Exception($response, 1);
  }
}

function replaceRisos($texto) {
    $texto = " " . $texto . " ";
    $textAnt = "";
    while ($textAnt != $texto) {
        $textAnt = $texto;
        $texto = preg_replace("/[^\w][hakeHAKE]{3,}[^\w]/", ' ', $texto);
    }
    return trim($texto);
}

function replace_full(&$text, $error, $replacement) {
    $pattern = "/\b" . $error . "\b/i";
    if (preg_match_all($pattern, $text, $matches)) {
        if (count($matches) == 1) {
            $text = preg_replace($pattern, $replacement, $text);
        }
    }
 }

function ehErro($palavraComErro, $sugestoes = array(), &$originalText) {
    $girias = array("LOL", "OMG", "ILY", "LMAO", "WTF", "PPL", "IDK", "TBH", "BTW", "THX", "SMH", "FFS", "AMA", "FML", "TBT", "JK", "IMO", "YOLO", "ROFL", "MCM", "IKR", "FYI", "BRB", "GG", "IDC", "TGIF", "NSFW", "ICYMI", "STFU", "WCW", "IRL", "BFF", "OOTD", "FTW", "Txt", "HMU", "HBD", "TMI", "NM", "GTFO", "NVM", "DGAF", "FBF", "DTF", "FOMO", "SMFH", "OMW", "POTD", "LMS", "GTG", "ROFLMAO", "TTYL", "AFAIK", "LMK", "PTFO", "SFW", "HMB", "TTYS", "FBO", "TTYN");
    $redesSociais = array("facebook", "youtube", "whatsapp", "snapchat", "twitter", "instagram", "snapchats");

    $errosConhecidos = array("crossfit", "mardigras", "mardi", "gras", "mard", "gra", "#mention", "#media");

    if (strlen($palavraComErro) <= 2) {
        return false;
    }

    if (in_array(strtoupper($palavraComErro), $girias)) {
        return false;
    }
    if (in_array(strtolower($palavraComErro), $redesSociais)) {
        return false;
    }
    if (in_array(strtolower($palavraComErro), $errosConhecidos)) {
        return false;
    }

    $palavraComErro = strtolower($palavraComErro);

    foreach ($sugestoes as $keySugestao => $sugestao) {
        $sugestao = strtolower($sugestao);

        if ($sugestao == $palavraComErro) {
            return false;
        }

        if (strlen($palavraComErro) >= strlen($sugestao)) {
            if (levenshtein($palavraComErro, $sugestao, 1, 2, 1) == 1) {
                $keyWordTwo = 0;
                $sucesso = true;

                for ($keyW = 0; $keyW < strlen($palavraComErro); $keyW++) {
                    if (isset($sugestao[$keyWordTwo]) && $palavraComErro[$keyW] == $sugestao[$keyWordTwo]) {
                        $keyWordTwo++;
                    } else if ($keyWordTwo > 0 && $palavraComErro[$keyW] == $sugestao[($keyWordTwo - 1)]) {
                    } else {
                        $sucesso = false;
                    }
                }
                if ($sucesso) {
//                  debug("CASE levenshtein(str1, str2)");
                    replace_full($originalText, $palavraComErro, $sugestao);
                    return false;
                }
            }
        }
    }
    return true;
}