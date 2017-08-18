<?php

die;
//SELECT * FROM `tweets` WHERE calais IS NOT NULL AND calais NOT LIKE ('{"doc":{"info":%')

require_once("config.php");
//$tweets = query("SELECT * FROM tweets WHERE alchemy IS NOT NULL LIMIT 2500,1000");
$tweets = query("SELECT * FROM tweets WHERE alchemy IS NOT NULL ORDER by id LIMIT 4000,1000");

foreach (getRows($tweets) as $key => $value) {
  try {
    echo $value["id"] . "<br/>";
    $alchemy = json_decode($value["alchemy"], true);
    foreach ($alchemy as $keyC => $valueC) {
      $tipo = "";
      switch ($keyC) {
        case 'categories':
          $tipo = "C";
          break;
        case 'concepts':
          $tipo = "CO";
          break;
        case 'entities':
          $tipo = "E";
          break;
        case 'keywords':
          $tipo = "K";
          break;
        default:
      }
      //debug($tipo);
      if ($tipo == "") {
        continue;
      }

      foreach ($valueC as $keyTwo => $valueTwo) {
        if ($tipo == "C") {
          $insert = "INSERT INTO `tweets_nlp` (idTweetInterno, origem, tipo, palavra) VALUES ('" . $value["idInterno"]. "', 'A', '" . $tipo . "', '" . mysqli_real_escape_string(Connection::get(), $valueTwo["label"]) . "')";
          query($insert);
        } else if ($tipo == "CO") {
          $insert = "INSERT INTO `tweets_nlp` (idTweetInterno, origem, tipo, palavra) VALUES ('" . $value["idInterno"]. "', 'A', '" . $tipo . "', '" . mysqli_real_escape_string(Connection::get(), $valueTwo["text"]) . "')";
          query($insert);
        } else if ($tipo == "E") {
          $insert = "INSERT INTO `tweets_nlp` (idTweetInterno, origem, tipo, palavra, type) VALUES ('" . $value["idInterno"]. "', 'A', '" . $tipo . "', '" . mysqli_real_escape_string(Connection::get(), $valueTwo["text"]) . "', '" . mysqli_real_escape_string(Connection::get(), $valueTwo["type"]) . "')";
          query($insert);
        } else if ($tipo == "K") {
          $insert = "INSERT INTO `tweets_nlp` (idTweetInterno, origem, tipo, palavra) VALUES ('" . $value["idInterno"]. "', 'A', '" . $tipo . "', '" . mysqli_real_escape_string(Connection::get(), $valueTwo["text"]) . "')";
          query($insert);
        }
      }
    }
  } catch (Exception $e) {
    var_dump($e);
  }
}

echo "FIM";