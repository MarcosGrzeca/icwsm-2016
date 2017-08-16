<?php

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://gateway.watsonplatform.net/natural-language-understanding/api/v1/analyze?version=2017-02-27",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "{\r\n  \"text\": \"IBM is an American multinational technology company headquartered in Armonk, New York, United States, with operations in over 170 countries.\",\r\n  \"features\": {\r\n    \"entities\": {\r\n      \"emotion\": true,\r\n      \"sentiment\": true,\r\n      \"limit\": 2\r\n    },\r\n    \"keywords\": {\r\n      \"emotion\": true,\r\n      \"sentiment\": true,\r\n      \"limit\": 2\r\n    }\r\n  }\r\n}",
  CURLOPT_HTTPHEADER => array(
    "authorization: Basic MDM0ODY0NzYtNzE3Ni00ZjFjLTk0N2MtNDIyZmIxMjNlNzgyOjA4bFljRnNIZUtVZQ==",
    "cache-control: no-cache",
    "content-type: application/json",
    "postman-token: 13c09458-f3d3-3d9f-60ed-fbf1e3dc4495"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $response;
}