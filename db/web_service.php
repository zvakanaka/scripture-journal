  <?php
ini_set("log_errors", 1);
ini_set("error_log", "/tmp/php-error.log");
error_log( "Web service!" );
// echo "Im an ambadextrious omnivore";
$postedData = $HTTP_RAW_POST_DATA;
$cleanData = json_decode($postedData, true);

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
  }
  $query = 'select entry_id, past_thought, ponder_question, question, entry_date from entry where user_id = (select user_id from user where email = :email)'; 
    $stmnt = $db->prepare($query);
    $stmnt->bindParam(':email', $email);
    $stmnt->execute();

$entries = '{"user": "'.$email.'", "entry":[';
$entryRow = $stmnt->fetch();
if ($entryRow)
{
  echo "booooogers";
/* while($entryRow = $stmnt->fetch())
  {
    $entryId = $entryRow['entry_id'];
    $pastThought = $entryRow['past_thought'];
    $ponderQuestion = $entryRow['ponder_question'];
    $question = $entryRow['question']);
    $date = $entryRow['entry_date']);
    $entries .= '{"date":"'.$date.'","entryId":"'.$entryId
    .'","pastThought":"'.$pastThought
    .'","question":"'.$question
    .'","ponderQuestion":"'.$ponderQuestion.'"},';
  }*/
}
  //remove trailing comma
  $entries = rtrim($entries, ",");
  $entries .= ']}';

  echo $entries;
  
  //echo "dude";
  }
catch (Exception $ex)
{
  
};
//{
  //$error = '{"error": "'.$ex'"}';
  //echo $error;
  die();
//};
/*

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
    
/*    $query = 'select entry_id, past_thought, ponder_question, question, entry_date from entry where user_id = (select user_id from user where email = :email)'; 
    $stmnt = $db->prepare($query);
    $stmnt->bindParam(':email', $email);
    $stmnt->execute();

  $entries = '{"user": "'.$email.'", "entry":[';
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
  */
  /*$prize = '{"user": "'.$email.'", "journal":[';
    $prize .= '{"question":"'."variable here".'"},';
    //remove trailing comma
    $prize = rtrim($prize, ",");
    $prize .= ']}';
    echo $prize;//*/
    /*
  }
}
catch (Exception $ex)
{
  $error = '{"error": "'.$ex'"}';
  echo $error;
  die();
};*/

die();
?>