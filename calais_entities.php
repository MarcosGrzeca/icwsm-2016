<?php

//SELECT * FROM `tweets` WHERE calais IS NOT NULL AND calais NOT LIKE ('{"doc":{"info":%')

require_once("config.php");

//query("DELETE FROM tweets_nlp WHERE origem = 'C'");

$tweets = query("SELECT * FROM tweets WHERE calais IS NOT NULL AND idInterno NOT IN (SELECT idTweetInterno FROM tweets_nlp WHERE tweets_nlp.idTweetInterno = tweets.idInterno AND tweets_nlp.origem = 'C')");

debug(getNumRows($tweets));
foreach (getRows($tweets) as $key => $value) {
  try {
    $calais = json_decode($value["calais"], true);
    foreach ($calais as $keyC => $valueC) {
      if (isset($valueC["_typeGroup"]) && $valueC["_typeGroup"] == "entities") {
        $update = "INSERT INTO `tweets_nlp` (idTweetInterno, origem, tipo, palavra, type) VALUES ('" . $value["idInterno"]. "', 'C', 'E', '" . mysqli_real_escape_string(Connection::get(), $valueC["name"]) . "', '" . mysqli_real_escape_string(Connection::get(), $valueC["_type"]) . "')";
        query($update);
      }
    }
  } catch (Exception $e) {
    var_dump($e);
  }
}

echo "FIM";