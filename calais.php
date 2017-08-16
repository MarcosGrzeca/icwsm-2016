<?php

require_once("config.php");
$tweets = query("SELECT * FROM tweets LIMIT 2");

foreach (getRows($tweets) as $key => $value) {
  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.thomsonreuters.com/permid/calais",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => $value["textParser"],
    CURLOPT_SSL_VERIFYHOST => 0,
    CURLOPT_SSL_VERIFYPEER => 0,
    CURLOPT_HTTPHEADER => array(
      "cache-control: no-cache",
      "content-type: text/raw",
      "outputformat: application/json",
      "postman-token: c504d8e2-8be6-26c3-c0c9-b4e38b509cc0",
      "x-ag-access-token: 5k2EFOFxFOIAUl5e9AJXDuJVM7x03nxd"
    ),
  ));

  $response = curl_exec($curl);
  $err = curl_error($curl);

  curl_close($curl);

  if ($err) {
    echo "cURL Error #:" . $err;
  } else {
    echo $response;
    debug($response);
  }
}