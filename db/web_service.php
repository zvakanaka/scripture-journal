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

  $email = $cleanData["user"];

  if ($action == "check-email") {
    
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
    
    $query = 'select * from entry where user_id = (select user_id from user where email = :email)'; 
    $stmnt = $db->prepare($query);
    $stmnt->bindParam(':email', $email);
    $stmnt->execute();

  $entries = '{"email": "'.$email.'", "entry":[';
  while($row = $stmnt->fetch())
  {
    $entryId = $row['entry_id'];
    $pastThought = $row['past_thought'];
    $ponderQuestion = $row['ponder_question'];
    $question = $row['question']);
    $date = $row['entry_date']);
    $entries .= '{"date":"'.$date.'","entryId":"'.$entryId
    .'","pastThought":"'.$pastThought
    .'","question":"'.$question
    .'","ponderQuestion":"'.$ponderQuestion.'"},';
  }
  //remove trailing comma
  $entries = rtrim($entries, ",");
  $entries .= ']}';

  echo $entries;
  
    /*$prize = '{"user": "'.$email.'", "journal":[';
    $prize .= '{"question":"'."variable here".'"},';
    //remove trailing comma
    $prize = rtrim($prize, ",");
    $prize .= ']}';
    echo $prize;*/
    
  } else if ($action == "insert-entry") {
    
    
    $prize = '{"user": "'.$email.'", "journal":[';
    $prize .= '{"question":"'."variable here".'"},';
    //remove trailing comma
    $prize = rtrim($prize, ",");
    $prize .= ']}';

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