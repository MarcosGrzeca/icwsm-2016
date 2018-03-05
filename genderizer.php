<?php

require_once("config.php");
$tweets = query("SELECT * FROM user WHERE genderizer IS NULL");

foreach (getRows($tweets) as $key => $value) {
  try {
    $ret = json_decode(genderizer($value), true);
    debug($ret);

    $genero = null;
    $probabilidade = 0;
    if (!empty($ret["gender"])) {
      $genero = ucfirst($ret["gender"]);
      $probabilidade = $ret["probability"];
    }

    $update = "UPDATE `user` SET genderizer = '" . escape(json_encode($ret)) . "', genderizer_gender = '" . escape($genero) . "', genderizer_prob = '" . escape($probabilidade) . "' WHERE id = "  . $value["id"];
    query($update);
  } catch (Exception $e) {
    var_dump($e);
  }
}

function genderizer($dados) {
  $name = preg_replace("/[^a-zA-Z_\s]+/", "", $dados["name"]);
  $nameTmp = explode(" ", trim($name));
  $name = $nameTmp[0];

  $curl = curl_init();

  $url = "https://api.genderize.io/?name=" . urlencode($name);
  if ($dados["lang"] == "en") {
    $url .= "&language_id=en&country_id=us";
  }

  debug($url);

  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_SSL_VERIFYHOST => 0,
    CURLOPT_SSL_VERIFYPEER => 0,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
      "Cache-Control: no-cache",
      "Postman-Token: 82e6467e-aff1-e6c5-1655-31adbff8428a"
    ),
  ));
  
  $response = curl_exec($curl);
  $err = curl_error($curl);

  $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

  debug($httpcode);
  curl_close($curl);

  if ($err) {
    throw new Exception($err, 99);
  } else if ($httpcode != 200) {
    throw new Exception($response, 1);
  }
  return $response;
}

echo "FIM";