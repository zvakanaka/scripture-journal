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

  //echo "Using local credentials: ";
  //change to require if needed
  include("set_local_credentials.php");

//echo "host:$dbHost:$dbPort dbName:$dbName user:$dbUser password:$dbPassword<br >\n";
GLOBAL $db;
$db = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPassword);
return $db;
}
?>
