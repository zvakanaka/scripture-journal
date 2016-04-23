<?php
function loadDB() {
ini_set("log_errors", 1);
ini_set("error_log", "/tmp/php-error.log");
error_log("Load db fr the last timenenene");

//DANGER: place creds in required file unless you have mafia protection
$dbHost = "";
$dbUser = "";
$dbPassword = "";
$dbName = "journal";

require("set_local_credentials.php");

GLOBAL $db;
$db = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPassword);
return $db;
}
?>
