<?php
ini_set("log_errors", 1);
ini_set("error_log", "/tmp/php-error.log");
error_log( "Hello, errors!" );

$postedData = $HTTP_RAW_POST_DATA;
$cleanData = json_decode($postedData, true);

$action = $cleanData["action"];

require 'load_db.php';
try {
  GLOBAL $db;
  $db = loadDB();

  if ($action == "check-email") {
    $email = $cleanData["user"];
    
    $userCheckQuery = 'select user_id from user where email = :email';
    $userCheckStmnt = $db->prepare($userCheckQuery);
    $userCheckStmnt->bindParam(':email', $email);
    $userCheckStmnt->execute();
    $row = $userCheckStmnt->fetch();
    if (!$row) {
      $insertUserQuery = 'insert into user values(null, :email, null)';
      $insertUserStmnt = $db->prepare($insertUserQuery);
      //$insertUserStmnt->bindParam(':name', $user);
      $insertUserStmnt->bindParam(':email', $email);
      $insertUserStmnt->execute();
    }
  
    $prize = '{"user": "'.$email.'", "journal":[';
    $prize .= '{"question":"'."variable here".'"},';
    //remove trailing comma
    $prize = rtrim($prize, ",");
    $prize .= ']}';
    
/*        $prize = '{"user": "'.$email.'", "journal":[';
    $prize .= '{"question":"'."variable here".'"},';
    //remove trailing comma
    $prize = rtrim($prize, ",");
    $prize .= ']}';*/
  
    echo $prize;
  }
}
catch (Exception $ex)
{
  $error = '{"error": "'.$ex'"}';
  echo $error;
  die();
};

die();
?>