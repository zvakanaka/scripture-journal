<?php
function loadDB() {
  ini_set("log_errors", 1);
ini_set("error_log", "/tmp/php-error.log");
error_log( "Load db" );

$dbHost = "";
$dbUser = "";
$dbPassword = "";
$dbName = "journal";
$openShiftVar = getenv('OPENSHIFT_MYSQL_DB_HOST');
//if not on openshift
if ($openShiftVar === null || $openShiftVar == "")
{
  // Not in the openshift environment
  //echo "Using local credentials: ";
  require("set_local_credentials.php");
}
else//on openshift
{
  // In the openshift environment
  //echo "Using openshift credentials: ";
  $dbHost = getenv('OPENSHIFT_MYSQL_DB_HOST');
  $dbPort = getenv('OPENSHIFT_MYSQL_DB_PORT');
  $dbUser = getenv('OPENSHIFT_MYSQL_DB_USERNAME');
  $dbPassword = getenv('OPENSHIFT_MYSQL_DB_PASSWORD');
}
//echo "host:$dbHost:$dbPort dbName:$dbName user:$dbUser password:$dbPassword<br >\n";
GLOBAL $db;
$db = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPassword);
return $db;
}
?>