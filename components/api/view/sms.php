<?php
	$jasonArray["pcount"]=$sms_pcount;
	$jasonArray["ref1"]=$sms0_ref1;
	$jasonArray["ref2"]=$sms0_ref2;
	$jasonArray["to"]=$sms0_to;
	$jasonArray["text"]=$sms0_text;

$myJSON = json_encode($jasonArray);

echo $myJSON;

?>