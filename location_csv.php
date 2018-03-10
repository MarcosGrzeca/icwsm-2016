<?php

require_once("config.php");

$q = "q3";
$sql = "SELECT category, count(*) as total
		FROM tweets t
		JOIN tweet_localizacao tl ON tl.idTweetInterno = t.idInterno
		WHERE " . $q . " = 1
		GROUP by category
		ORDER by 2 desc
	";

$output = fopen("php://output",'w') or die("Can't open php://output");
header("Content-Type:application/csv"); 
header("Content-Disposition:attachment;filename=location_" . $q .".csv"); 
fputcsv($output, array("Categoria", "Total"));

$res = query($sql);
foreach (getRows($res) as $keyRegister => $register) {
	fputcsv($output, $register);
}
fclose($output) or die("Can't close php://output");
?>