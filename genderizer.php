<?php

require_once("config.php");
$tweets = query("SELECT * FROM user LIMIT 1");

foreach (getRows($tweets) as $key => $value) {
  try {
    $ret = json_decode(genderizer($value), true);
    debug($ret);

    $genero = null;
    $idade = 0;

    /*foreach ($ret["faces"] as $key => $face) {
      if (!empty($face["attributes"]["gender"]["value"])) {
        $genero = $face["attributes"]["gender"]["value"];
      }
      if (!empty($face["attributes"]["age"]["value"])) {
        $idade = $face["attributes"]["age"]["value"];
      }
    }

    debug($genero);
    debug($idade);
    
    $update = "UPDATE `user` SET faceplusplus = '" . escape($ret) . "', gender_face = '" . escape($genero) . "', age_face = '" . escape($idade) . "' WHERE id = "  . $value["id"];
    query($update);
    */

  } catch (Exception $e) {
    var_dump($e);
  }
}

function genderizer($dados) {
  $curl = curl_init();

  debug($dados);
  debug($dados["profile_url"]);

  $curl = curl_init();

  $url = "https://api.genderize.io/?name=" . url_enccode($dados["name"]);
  if ($dados["lang"] == "en") {
    $url .= "&language_id=en&country_id=us";
  }

  curl_setopt_array($curl, array(
    CURLOPT_URL => $url
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
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
  curl_close($curl);

  if ($err) {
    throw new Exception($err, 99);
  } else if ($httpcode != 200) {
    throw new Exception($response, 1);
  }
  return $response;
}

echo "FIM";