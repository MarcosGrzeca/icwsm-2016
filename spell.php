<?php
#PHP Source Code
require "phpspellcheck/include.php";

$mySpell = new SpellCheckButton();
$mySpell->InstallationPath = "/phpspellcheck/";
$mySpell->Fields = "ALL";
echo $mySpell->SpellImageButton();

$mySpell = new SpellAsYouType();
$mySpell->InstallationPath = "/phpspellcheck/";
$mySpell->Fields = "ALL";
echo $mySpell->Activate();


?>