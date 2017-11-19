<?php


$remover = array();
for ($i = 0; $i < 200; $i++) {
	$remover[] = 'X' . $i;
	$remover[] = 'x' . $i;
}


echo "<pre>";
var_export(implode(",", $remover));