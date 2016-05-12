<?php
// To see errors in console:
// tail -f /tmp/php-error.log
ini_set("log_errors", 1);
ini_set("error_log", "/tmp/php-error.log");
error_log( "Web service!" );

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
  
  } else if ($action == "insert-entry") {
    $insertEntryQuery = 'insert into entry values (null, (select user_id from user where email = :email), :insertThought, :insertPonder, :insertQuestion, :insertPrompting, :insertShare, utc_date)';
    $insertEntryStmnt = $db->prepare($insertEntryQuery);
    $insertEntryStmnt->bindParam(':email', $email);
      
    $insertThought = $cleanData["thoughts"];
    $insertPonder = $cleanData["ponder"];
    $insertQuestion = $cleanData["question"];
    $insertPrompting = $cleanData["promptings"];
    $insertShare = $cleanData["share"];
    
    $insertEntryStmnt->bindParam(':insertThought', $insertThought);
    $insertEntryStmnt->bindParam(':insertPonder', $insertPonder);
    $insertEntryStmnt->bindParam(':insertQuestion', $insertQuestion);
    $insertEntryStmnt->bindParam(':insertPrompting', $insertPrompting);
    $insertEntryStmnt->bindParam(':insertShare', $insertShare);
    $insertEntryStmnt->execute();
    
  } else if ($action == "get-entry-details") {
      $getEntryQuery = 'select entry_id, past_thought, ponder_question, question, sharing, prompting, entry_date from entry where user_id = (select user_id from user where email = :email) and entry_id = :entryId'; 
      $getEntryStmnt = $db->prepare($getEntryQuery);
      $getEntryStmnt->bindParam(':email', $email);
      $getEntryStmnt->bindParam(':entryId', $cleanData["entryId"]);
      $getEntryStmnt->execute();

      $detailedEntries = '{"user": "'.$email.'",';
      $getEntryRow = $getEntryStmnt->fetch();
      if ($getEntryRow)
      {
          $entryId = $getEntryRow['entry_id'];
          $pastThought = $getEntryRow['past_thought'];
          $ponderQuestion = $getEntryRow['ponder_question'];
          $question = $getEntryRow['question'];
          $share = $getEntryRow['sharing'];
          $prompting = $getEntryRow['prompting'];
          $date = $getEntryRow['entry_date'];
          
          $detailedEntries .= '"date":"'.$date.'","entryId":"'.$entryId
          .'","pastThought":"'.$pastThought
          .'","question":"'.$question
          .'","share":"'.$share
          .'","promptings":"'.$prompting
          .'","ponderQuestion":"'.$ponderQuestion.'"';
      }
      else {
        $detailedEntries .= '"error":"ENTRY_ID '.$cleanData["entryId"].' NOT FOUND"';
      }
      $detailedEntries .= '}';
      echo $detailedEntries;//here ya go
  }

  if ($action != "get-entry-details") {
    $query = 'select entry_id, entry_date from entry where user_id = (select user_id from user where email = :email)'; 
    $stmnt = $db->prepare($query);
    $stmnt->bindParam(':email', $email);
    $stmnt->execute();
    //TODO: maybe put this in a function so insert can update
    $entries = '{"user": "'.$email.'", "entry":[';
    $entryRow = $stmnt->fetch();//NOTE: is this line overwritten in the loop
    if ($entryRow)
    {
      do {
      $entryId = $entryRow['entry_id'];
        $date = $entryRow['entry_date'];
        
        $entries .= '{"date":"'.$date.'","entryId":"'.$entryId.'"},';
      } while($entryRow = $stmnt->fetch()); 
    }
    //remove trailing comma
    $entries = rtrim($entries, ",");
    $entries .= ']}';
    echo $entries;//here ya go
  }
}
catch (Exception $ex)
{
  $error = '{"error": "'.$ex.'"}';
  echo $error;
  die();
};

die();
?>
