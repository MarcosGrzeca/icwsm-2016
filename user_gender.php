<?php

require_once("config.php");

$idades = array();
$idades["Male"] = array();
$idades["Female"] = array();


foreach ($idades as $key => $value) {
	# code...

	$sql = "SELECT count(*) as totalUsers,
		SUM((SELECT count(DISTINCT(user_id)) FROM tweets WHERE user_id = u.id AND q1 = 1)) as totalUsersQ1,
		SUM((SELECT count(DISTINCT(user_id)) FROM tweets WHERE user_id = u.id AND q2 = 1)) as totalUsersQ2,
		SUM((SELECT count(DISTINCT(user_id)) FROM tweets WHERE user_id = u.id AND q3 = 1)) as totalUsersQ3,
		SUM((SELECT count(*) FROM tweets WHERE user_id = u.id)) as totalTweets,
		SUM((SELECT count(*) FROM tweets WHERE user_id = u.id AND q1 = 1)) as totalTweetsQ1,
		SUM((SELECT count(*) FROM tweets WHERE user_id = u.id AND q2 = 1)) as totalTweetsQ2,
		SUM((SELECT count(*) FROM tweets WHERE user_id = u.id AND q3 = 1)) as totalTweetsQ3
		-- , GROUP_CONCAT(u.id)
		FROM `user` u
		WHERE gender = '" . $key . "' 
		-- GROUP by age_face
		-- ORDER by age_face
		";

		//debug($sql);

	$res = query($sql);
	foreach (getRows($res) as $keyRegister => $register) {
		debug($register);
		$idades[$key] = $register;
	}

}

debug($idades);

$output = fopen("php://output",'w') or die("Can't open php://output");
header("Content-Type:application/csv"); 
header("Content-Disposition:attachment;filename=gender.csv"); 
fputcsv($output, array("Sexo", "totalUsers", "totalUsersQ1", "totalUsersQ2", "totalUsersQ3", "totalTweets", "totalTweetsQ1", "totalTweetsQ2", "totalTweetsQ3"));
foreach ($idades as $faixa => $faixas) {
	$put = array_merge(array($faixa), $faixas);
    fputcsv($output, $put);
  
}
fclose($output) or die("Can't close php://output");
?>