<?php

require_once("config.php");

$positivo = array();

echo "<pre>";


$matriz = array(0 => array(0 => 0, 1 => 0), 1 => array(0 => 0, 1 => 0));

$positivo = array("drinking", "tequila", "wine", "vodka", "alcohol", "drunk", "liquor", "brew", "tour", "ale", "booze", "tasting", "cold", "alcoholic", "drink", "drinks", "hammered", "ipa", "beer", "champagne", "bud", "rum", "crawl", "brewing", "pong", "bar", "beverage", "beyonce", "bottle", "bottles", "breakfast", "bullandbearpub", "can", "company", "genesee", "get", "glass", "good", "house", "ice", "light", "max", "much", "nice", "one", "pale", "people", "pub", "qtyler1495", "really", "risada", "scotch", "shit", "still", "store", "turn", "two", "amp", "dinosaur", "grill", "que", "water", "fucked", "blast", "cans", "athletic", "rochester", "kids", "someone", "wait", "amellywood", "party", "wanna", "final", "cowles", "time", "great", "fucking", "tonight", "cream", "held", "marijuana", "pretty", "love", "better", "fall", "last", "irish");

$header = array();

if (($handle = fopen("validate.csv", "r")) !== FALSE) {
	$cont = 0;
    while (($data = fgetcsv($handle, 100000, ",")) !== FALSE) {
    	if ($cont == 0) {
    		$cont++;

            foreach ($data as $key => $value) {
                if (in_array($value, $positivo)) {
                    $header[$value] = $key;
                }
            }
    		continue;
    	} 

        $achou = 0;
        foreach ($header as $key => $value) {
            if ($data[$value] == 1) {
                $achou = 1;
                break;
            }
        }

        $matriz[$data[0]][$achou]++;
        //var_export($achou);   
    	if ($cont > 200) {
    	//	break;
    	}
    	$cont++;
    }
}

var_export($matriz);
/*
echo "<br/>";
arsort($indices);
var_export($indices);
*/