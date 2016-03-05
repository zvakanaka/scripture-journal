<?php
function loadDB() {
  ini_set("log_errors", 1);
ini_set("error_log", "/tmp/php-error.log");
error_log( "Load db fr the last ltiemenenene" );

$dbHost = "";
$dbUser = "";
$dbPassword = "";
$dbName = "journal";

  // Not in the openshift environment
  //echo "Using local credentials: ";
  require("set_local_credentials.php");

//echo "host:$dbHost:$dbPort dbName:$dbName user:$dbUser password:$dbPassword<br >\n";
GLOBAL $db;
$db = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPassword);
return $db;
}
?>