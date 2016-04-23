<?php
function loadDB() {
ini_set("log_errors", 1);
ini_set("error_log", "/tmp/php-error.log");
error_log("Load db fr the last timenenene");

$dbHost = "127.0.0.1";
//WARNING: do not do this
$dbUser = "root";
$dbPassword = "";
$dbName = "journal";

  //DANGER: uncomment and place creds in that file unless you have mafia protection
  //require("set_local_credentials.php");

GLOBAL $db;
$db = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPassword);
return $db;
}
?>
