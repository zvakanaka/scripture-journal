<?php
function loadDB() {
  $dbHost = "";
  $dbUser = "";
  $dbPassword = "";
  $dbName = "journal";
  //echo "Using local credentials: ";
  require("set_local_credentials.php");
  
  //echo "host:$dbHost:$dbPort dbName:$dbName user:$dbUser password:$dbPassword<br >\n";
  GLOBAL $db;
  $db = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPassword);
  
  return $db;
}
?>