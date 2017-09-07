<?php
echo "Check Spelling Installation";
$pspell_link = pspell_new("en");
print_r($pspell_link);
if (pspell_check($pspell_link, "colour"))
{ echo "This is a valid spelling"; }
else
{ echo "Sorry, wrong spelling"; }
?>