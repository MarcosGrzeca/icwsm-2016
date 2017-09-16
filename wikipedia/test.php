<?php
require('blockspring.php');
echo "<pre>";
var_export(Blockspring::runParsed("get-wikipedia-sub-categories", array("category" => "Ice hockey leagues in Canada"), array("api_key" => "br_71919_172db6c45274f6e73c502ef6698223c78d875639"))->params);
echo "</pre>";
