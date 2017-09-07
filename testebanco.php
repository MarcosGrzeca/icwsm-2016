<?php
require_once("config.php");

echo date("H:i:s") . "<br/>";

$tweets = query("SELECT * FROM tweets");
echo "<pre>";
foreach (getRows($tweets) as $keyTweet => $tweet) {
	//var_export($tweet);	
}
echo date("H:i:s") . "<br/>";

echo "</pre>";

?>