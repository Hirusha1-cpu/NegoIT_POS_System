<?php
require("plugin/phpMQTT/phpMQTT.php");

$server = "mqtt.negoit.info";     // change if necessary
$port = 1883;                     // change if necessary
if(isset($_GET['u'])&&isset($_GET['p'])&&isset($_GET['dev'])&&isset($_GET['ref1'])&&isset($_GET['ref2'])&&isset($_GET['to'])&&isset($_GET['text'])){
	$username = $_GET['u'];                   // set your username
	$password = $_GET['p'];                   // set your password
	$dev=$_GET['dev'];
	$number=$_GET['to'];
	$text=$_GET['text'];
	$ref1=$_GET['ref1'];
	$ref2=$_GET['ref2'];
	if(($username!='')&&($password !='')&&($dev!='')&&($number!='')&&($text!='')){
		$client_id = "phpMQTT-publisher"; // make sure this is unique for connecting to sever - you could use uniqid()
		$mqtt = new Bluerhinos\phpMQTT($server, $port, $client_id);
		if ($mqtt->connect(true, NULL, $username, $password)) {
			$mqtt->publish("/smsgw/$dev", "{\"com\":'sms_send',\"ref1\":'$ref1',\"ref2\":'$ref2',\"number\":'$number',\"text\":'$text'}", 0);
			$mqtt->close();
		} else {
		    echo "Time out!\n";
		}
	}
}
?>
