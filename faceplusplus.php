<?php

require_once("config.php");
$tweets = query("SELECT * FROM user LIMIT 1");

foreach (getRows($tweets) as $key => $value) {
  try {
    $ret = json_decode(getFacePlusPlus($value), true);
    debug($ret);

    $genero = null;
    $idade = 0;


    foreach ($ret["faces"] as $key => $face) {
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

  } catch (Exception $e) {
    var_dump($e);
  }
}

function getFacePlusPlus($dados) {
  $curl = curl_init();

  debug($dados);
  debug($dados["profile_url"]);

  curl_setopt_array($curl, array(
    //CURLOPT_URL => "https://api-us.faceplusplus.com/facepp/v3/detect",
    CURLOPT_URL => "https://api-us.faceplusplus.com/facepp/v3/detect?api_key=loHT_cpbv8Uyb2tU72k2vp9nUbnlvEAu&api_secret=uqRdEiaXNShttVUnTyMbktq-9ebbZZDq&image_url=" . urlencode($dados["profile_url"]) . "&return_attributes=gender%2Cage%2Csmiling%2Cheadpose%2Cfacequality%2Cblur%2Ceyestatus%2Cemotion%2Cethnicity%2Cbeauty%2Cmouthstatus%2Ceyegaze%2Cskinstatus",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_SSL_VERIFYHOST => 0,
    CURLOPT_SSL_VERIFYPEER => 0,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
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