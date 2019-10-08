<?php

require_once("../../config.php");

$idades = array();
$idades["<=17"] = array("inicio" => 1, "fim" => 17, "resultado" => array());
$idades["18-24"] = array("inicio" => 18, "fim" => 24, "resultado" => array());
$idades["25-34"] = array("inicio" => 25, "fim" => 34, "resultado" => array());
$idades["35-44"] = array("inicio" => 35, "fim" => 44, "resultado" => array());
$idades["45-54"] = array("inicio" => 45, "fim" => 54, "resultado" => array());
$idades[">=55"] = array("inicio" => 55, "fim" => 120, "resultado" => array());

$marcos = 1;

foreach ($idades as $key => $value) {
	# code...

	$sql = "SELECT gender, count(*) as totalUsers,
			SUM((SELECT count(DISTINCT(user_id)) FROM tweets WHERE user_id = u.id AND q2 = 1)) as totalUsersQ2,
			SUM((SELECT count(*) FROM tweets WHERE user_id = u.id)) as totalTweets,
			SUM((SELECT count(*) FROM tweets WHERE user_id = u.id AND q2 = 1)) as totalTweetsQ2,
			SUM((SELECT count(*) FROM tweets WHERE user_id = u.id AND q1 = 0)) as totalTweetsQ0
			FROM `user` u
			JOIN tweets t ON t.user_id = u.id
			WHERE age_face >= " . $value["inicio"] . " 
			AND age_face <= " . $value["fim"] . "
			AND gender IN ('Male', 'Female') 
			GROUP by gender";

	$res = query($sql);
	foreach (getRows($res) as $keyRegister => $register) {
		$idades[$key]["resultado"][$register["gender"]] = $register;
	}

	$sql2 = "SELECT gender, count(*) as totalUsers,
			SUM((SELECT count(DISTINCT(user_id)) FROM tweets_amazon WHERE user_id = u.id AND q2 = 1)) as totalUsersQ2,
			SUM((SELECT count(*) FROM tweets_amazon WHERE user_id = u.id)) as totalTweets,
			SUM((SELECT count(*) FROM tweets_amazon WHERE user_id = u.id AND q2 = 1)) as totalTweetsQ2,
			SUM((SELECT count(*) FROM tweets_amazon WHERE user_id = u.id AND q2 = 0)) as totalTweetsQ0
			FROM `user` u
			JOIN tweets_amazon ta ON ta.user_id = u.id
			WHERE age_face >= " . $value["inicio"] . " 
			AND age_face <= " . $value["fim"] . "
			AND gender IN ('Male', 'Female') 
			GROUP by gender";

	$res = query($sql2);

	foreach (getRows($res) as $keyRegister => $register) {
		$tmpMarcos = &$idades[$key]["resultado"][$register["gender"]];

		$finalArray = [];
		foreach ($register as $k=>$subArray) {
			if ($k == "gender") {
				continue;
			}
			if (isset($tmpMarcos[$k])) {
				$tmpMarcos[$k] += $subArray;
			}
		}
	}
}

$output = fopen("php://output",'w') or die("Can't open php://output");
header("Content-Type:application/csv"); 
header("Content-Disposition:attachment;filename=gender_age_amazon.csv"); 
fputcsv($output, array("faixa", "gender", "totalUsers", "totalUsersQ2", "totalTweets", "totalTweetsQ2", "totalTweetsQ0"));
foreach ($idades as $faixa => $faixas) {
	foreach ($faixas["resultado"] as $sexo) {
		$put = array_merge(array($faixa), $sexo);
    	fputcsv($output, $put);
    }
}
fclose($output) or die("Can't close php://output");

?>