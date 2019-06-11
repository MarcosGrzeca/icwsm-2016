<?php

require_once("config.php");
$tweets = query(" SELECT *
                  FROM tweets_completo
                  WHERE situacao = 'N'
                  AND id NOT IN (
                    SELECT id
                    FROM tweets
                  )
                ");

foreach (getRows($tweets) as $key => $value) {
  $value["situacao"] = "M";
  try {

    $columns = implode('`, `',array_keys($value));
    $escaped_values = array_map([Connection::get(), "real_escape_string"], array_values($value));
    $values  = implode('", "', $escaped_values);
    $sql = "INSERT INTO `tweets` (`$columns`) VALUES (\"$values\")";
    query($sql);
  } catch (Exception $e) {
    var_dump($e);
  }
}

echo "FIM";