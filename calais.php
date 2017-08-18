<?php


//SELECT * FROM `tweets` WHERE calais IS NOT NULL AND calais NOT LIKE ('{"doc":{"info":%')

require_once("config.php");
$tweets = query("SELECT * FROM tweets WHERE calais IS NULL LIMIT 500");

foreach (getRows($tweets) as $key => $value) {
  try {    
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
      throw new Exception($err, 1);
    } else {
      /*echo $response;
      debug($response);*/

      if (trim($response) == "You exceeded the concurrent request limit for your license key. Please try again later or contact support to upgrade your license.") {
        //throw new Exception($response, 1);
        echo "Excedeu limite<br/>";
        break;
      } else {
        $update = "UPDATE `tweets` SET calais = '" . mysqli_real_escape_string(Connection::get(), $response) . "' WHERE id = "  . $value["id"];
        query($update);
      }
    }
  } catch (Exception $e) {
    var_dump($e);
  }
}

echo "FIM";