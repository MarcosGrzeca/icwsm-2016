z<?php
require_once("config.php");

$sementes = array("drinking" ,"tequila" ,"wine" ,"vodka" ,"alcohol" ,"drunk" ,"liquor" ,"brew" ,"tour" ,"ale" ,"booze" ,"tasting" ,"cold" ,"alcoholic" ,"drink" ,"drinks" ,"hammered" ,"ipa" ,"beer" ,"champagne" ,"bud" ,"rum" ,"crawl" ,"brewing" , "pong");
chdir('C:\Program Files\R\R-3.4.1patched\bin\\');

foreach ($sementes as $key => $semente) {

	exec("Rscript.exe C:\Users\Marcos\Documents\GitHub\drunk\processadores\similares.R \"$semente\" 2>&1",  $output, $return_var);

	/*var_export($texto . "<br/>");
	var_export($output);
	var_export("<br/>");*/
	
	$correcoes = $words = array();
	if (count($output) == 1 && $output[0] == "NULL") {
		continue;
	} else {
		//var_export("TEXTO <br/>" . $texto);
		var_export($output);
	//	$words = array_values(array_filter(explode("\"", $output[1])));
	}

	break;
}

echo "<br/>";

//var_export($return_var);
?>