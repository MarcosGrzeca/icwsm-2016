<?php

require_once("config.php");

$positivo = array();

if (($handle = fopen("q1_positivo.csv", "r")) !== FALSE) {
	$cont = 0;
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
    	if ($cont == 0) {
    		$cont++;
    		continue;
    	}

    	$positivo[$data[0]] = $data[1];

    	/*if ($cont > 20) {
    		break;
    	}
    	$cont++;*/
    }
}

$negativo = array();

if (($handle = fopen("q1_negativo.csv", "r")) !== FALSE) {
	$cont = 0;
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
    	if ($cont == 0) {
    		$cont++;
    		continue;
    	}
    	$negativo[$data[0]] = $data[1];
    	//$cont++;
    }
}

echo "<pre>";
//var_export($positivo);
echo "<br/>";
//var_export($negativo);

$indices = array();

foreach ($positivo as $key => $value) {
    if (!isset($negativo[$key])) {
        $negativo[$key] = 1;
    }
    $indices[$key] = $value / $negativo[$key];
}

echo "<br/>";
arsort($indices);
var_export($indices);