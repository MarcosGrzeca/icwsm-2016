<?php
set_time_limit(0);
require_once("../config.php");
Connection::setBD("icwsm-2016");

$headers = array();


$dados = array();
$row = 1;
if (($handle = fopen("maFinal_no.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 10000, ",")) !== FALSE) {
    	if ($row == 1) {
    		$headers = $data;
    		foreach ($headers as $key => $value) {
    			$dados[$value] = array();
    		}
    	} else {
    		foreach ($data as $key => $value) {
    			$dados[$headers[$key]][] = intval($value);
    		}
    	}
    	$row++;
    }
    fclose($handle);
}

echo "<pre>";
$jaAgrupados = array();
$i = 0;
$words = array();
foreach ($headers as $key => $value) {
	$exp = explode("_", $value);
	if (count($exp) == 2 && ($exp[1] != $exp[0])) {
		$inv = $exp[1] . "_" . $exp[0];
		if (in_array($inv, $headers) && !in_array($value, $jaAgrupados)) {
			$jaAgrupados[] = $inv;
			$dados[$value] = array_map(function (...$arrays) {
				    return array_sum($arrays);
				}, $dados[$value], $dados[$inv]);
			$i++;
		}
	}
}

$head = array();
foreach ($headers as $key => $value) {
	if (!in_array($value, $jaAgrupados)) {
		$head[] = $value;
	}
}
foreach ($jaAgrupados as $key => $value) {
	unset($dados[$value]);
}

#header('Content-Type: text/csv; charset=utf-8');
#header('Content-Disposition: attachment; filename=data.csv');

// create a file pointer connected to the output stream
$output = fopen('tweet_data_my.csv', 'w');

fputcsv($output, $head);
for ($i = 0; $i < count($dados["resposta"]); $i++) {
	$arr = array();
	foreach ($head as $key => $value) {
		$arr[$value] = $dados[$value][$i];
	}
	fputcsv($output, $arr);
}
fclose($output);
?>