<?php

//SELECT * FROM `tweets` WHERE alchemy IS NOT NULL AND alchemy LIKE ('%error%')

require_once("config.php");
$tweets = query("SELECT * FROM `tweets` WHERE alchemy IS NOT NULL AND alchemy LIKE ('%error%')");

foreach (getRows($tweets) as $key => $value) {
  try {    
    $parametros = array("text" => $value["textParser"], "features" => array(), "language" => "en");
    $parametros["features"]["entities"] = array("emotion" => true, "sentiment" => true, "limit" => 10);
    $parametros["features"]["keywords"] = array("emotion" => true, "sentiment" => true, "limit" => 5);
    $parametros["features"]["categories"] = array("emotion" => true, "sentiment" => true, "limit" => 5);
    $parametros["features"]["concepts"] = array("emotion" => true, "sentiment" => true, "limit" => 5);
    //$parametros["features"]["semantic_roles"] = array("emotion" => true, "sentiment" => true, "limit" => 6);
    //$parametros["features"]["sentiment"] = array("emotion" => true, "sentiment" => true, "limit" => 6);
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://gateway.watsonplatform.net/natural-language-understanding/api/v1/analyze?version=2017-02-27",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_SSL_VERIFYHOST => 0,
      CURLOPT_SSL_VERIFYPEER => 0,
      //CURLOPT_POSTFIELDS => "{\r\n  \"text\": \"IBM is an American multinational technology company headquartered in Armonk, New York, United States, with operations in over 170 countries.\",\r\n  \"features\": {\r\n    \"entities\": {\r\n      \"emotion\": true,\r\n      \"sentiment\": true,\r\n      \"limit\": 2\r\n    },\r\n    \"keywords\": {\r\n      \"emotion\": true,\r\n      \"sentiment\": true,\r\n      \"limit\": 2\r\n    }\r\n  }\r\n}",
      CURLOPT_POSTFIELDS => json_encode($parametros),
      CURLOPT_HTTPHEADER => array(
        "authorization: Basic MDM0ODY0NzYtNzE3Ni00ZjFjLTk0N2MtNDIyZmIxMjNlNzgyOjA4bFljRnNIZUtVZQ==",
        "cache-control: no-cache",
        "content-type: application/json",
        "postman-token: 13c09458-f3d3-3d9f-60ed-fbf1e3dc4495"
      ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);
    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    
    curl_close($curl);
    if ($err) {
      echo "cURL Error #:" . $err;
      throw new Exception($err, 1);
    } else {
      $update = "UPDATE `tweets` SET alchemy = '" . mysqli_real_escape_string(Connection::get(), $response) . "' WHERE id = "  . $value["id"];
      query($update);
    }
  } catch (Exception $e) {
    var_dump($e);
  }
}

echo "FIM";