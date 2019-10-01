<?php

require_once("../config.php");

echo "<html>";
echo "<head>
<style>
.card {
  box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
  transition: 0.3s;
}

.card:hover {
  box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
}

.container {
  padding: 2px 16px;
}
</style>

</head>";

echo "<body><form action='save.php'>";

$random = rand(0, 50);
$random = 0;
$tweets = query("SELECT * FROM user WHERE gender = '' LIMIT " . $random . ", 25");

foreach (getRows($tweets) as $key => $value) {
	//echo "<img src='" . $value["profile_url"] . "'/>";

	echo '<div class="card">
		  <img src="' . $value["profile_url"] . '" alt="Avatar" style="width:40%">
		  <div class="container">
		    <h4><b>' . $value["screen_name"] . '</b></h4>
		    <h4><b>' . $value["name"] . '</b></h4>
		    <p>' . $value["description"] . '</p>
		    <a href="https://twitter.com/' . $value["screen_name"] . '" target="_blank">Perfil</a>';

	if (!empty($value["url"])) {
		echo '<br/><a href="' . $value["url"] . '" target="_blank">' . $value["url"] . '</a>';
	}

	echo 	"<br/><select name='" . $value["id"] . "'>
				<option value=''>Não definido</option>
				<option value='Male'>Homem</option>
				<option value='Female'>Mulher</option>
				<option value='Trans'>Trans</option>
				<option value='Indisponível'>Indisponível</option>
			</select>";
	echo '</div></div><br/><br/>';
}

echo "<br/><input type='submit' value='salvar'/></form></body></html>";

?>