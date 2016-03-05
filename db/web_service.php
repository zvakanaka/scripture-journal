<?php
ini_set("log_errors", 1);
ini_set("error_log", "/tmp/php-error.log");
error_log( "Web service!" );
// echo "Im an ambadextrious omnivore";
$postedData = $HTTP_RAW_POST_DATA;
$cleanData = json_decode($postedData, true);

//common variables from POST data
$action = $cleanData["action"];
$email = $cleanData["user"];

require 'load_db.php';
try {
  GLOBAL $db;
  $db = loadDB();
  if ($action == "check-email") {
    $userCheckQuery = 'select user_id from user where email = :email';
    $userCheckStmnt = $db->prepare($userCheckQuery);
    $userCheckStmnt->bindParam(':email', $email);
    $userCheckStmnt->execute();
     $row = $userCheckStmnt->fetch();
    if (!$row) {
      $insertUserQuery = 'insert into user values(null, :email, null)';
      $insertUserStmnt = $db->prepare($insertUserQuery);
      $insertUserStmnt->bindParam(':email', $email);
      $insertUserStmnt->execute();
    }
  
    $query = 'select entry_id, past_thought, ponder_question, question, entry_date from entry where user_id = (select user_id from user where email = :email)'; 
    $stmnt = $db->prepare($query);
    $stmnt->bindParam(':email', $email);
    $stmnt->execute();
    //TODO: maybe put this in a function so insert can update
    $entries = '{"user": "'.$email.'", "entry":[';
    $entryRow = $stmnt->fetch();
    if ($entryRow)
    {
      while($entryRow = $stmnt->fetch())
      {
        $entryId = $entryRow['entry_id'];
        $pastThought = $entryRow['past_thought'];
         $ponderQuestion = $entryRow['ponder_question'];
        $question = $entryRow['question'];
        $date = $entryRow['entry_date'];
      
        $entries .= '{"date":"'.$date.'","entryId":"'.$entryId
        .'","pastThought":"'.$pastThought
        .'","question":"'.$question
        .'","ponderQuestion":"'.$ponderQuestion.'"},';
      }
    }
    //remove trailing comma
    $entries = rtrim($entries, ",");
    $entries .= ']}';
    echo $entries;//here ya go
  } else if ($action == "insert-entry") {
    $insertEntryQuery = 'insert into entry values (null, (select user_id from user where email = :email), :insertThought, :insertPonder, :insertQuestion, :insertPrompting, :insertShare, utc_date)';
    $insertEntryStmnt = $db->prepare($insertEntryQuery);
    $insertEntryStmnt->bindParam(':email', $email);
    
    $insertThought = $cleanData["insertThought"];
    $insertPonder = $cleanData["insertPonder"];
    $insertQuestion = $cleanData["insertQuestion"];
    $insertPrompting = $cleanData["insertPrompting"];
    $insertShare = $cleanData["insertShare"];
    
    $insertEntryStmnt->bindParam(':insertThought', $insertThought);
    $insertEntryStmnt->bindParam(':insertPonder', $insertPonder);
    $insertEntryStmnt->bindParam(':insertQuestion', $insertQuestion);
    $insertEntryStmnt->bindParam(':insertPrompting', $insertPrompting);
    $insertEntryStmnt->bindParam(':insertShare', $insertShare);
    $insertEntryStmnt->execute();
  
  }
}
catch (Exception $ex)
{
  //$error = '{"error": "'.$ex'"}';
  //echo $error;
  die();
};

die();
?>