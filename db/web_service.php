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
  /*
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
    }*/
  
    $prize = '{"user": "'.$email.'", "journal":[';
    $prize .= '{"question":"'."variable here".'"},';
    //remove trailing comma
    $prize = rtrim($prize, ",");
    $prize .= ']}';
  
    echo $prize;
  }
}
catch (Exception $ex)
{
  //$error = '{"error": "'.$ex'"}';
  //echo $error;
  die();
};

/*}
catch (Exception $ex)
{
  $error = '{"error": "'.$ex'"}';
  echo $error;
  die();
};

die();*/
/*
//decide whether to insert. alternatively select only
$shouldInsert = $cleanData["hours"];

if ($shouldInsert) {
  $hours = $cleanData["hours"];
  $appointLocation = $cleanData["location"];
  $message = $cleanData["message"];
}

require 'load_db.php';
try {
  GLOBAL $db;
  $db = loadDB();

  if ($shouldInsert) {
    //does user name exist?
    //fetch whether exists
    //if not, insert
     $userCheckQuery = 'select name from user where email = :email';
      $userCheckStmnt = $db->prepare($userCheckQuery);
      $userCheckStmnt->bindParam(':name', $user);
      $userCheckStmnt->bindParam(':email', $email);
      $userCheckStmnt->execute();
      $row = $userCheckStmnt->fetch();
      if (!$row) {
        $insertUserQuery = 'insert into user values(null, :name, :email, null)';
        $insertUserStmnt = $db->prepare($insertUserQuery);
        $insertUserStmnt->bindParam(':name', $user);
        $insertUserStmnt->bindParam(':email', $email);
        $insertUserStmnt->execute();
      }

    foreach ($hours as $hour) {  
      $hour .= ":00:00";
      $insertQuery = 'insert into appointment values(null, :date, :hour, :location ,(SELECT user_id from user where name = :user), :message)';
      $insertStmnt = $db->prepare($insertQuery);
      $insertStmnt->bindParam(':date', $date);
      $insertStmnt->bindParam(':hour', $hour);
      $insertStmnt->bindParam(':location', $appointLocation);
      $insertStmnt->bindParam(':user', $user);
      $insertStmnt->bindParam(':message', $message);

      $insertStmnt->execute();
    }
  }
  $query = 'select appointment_time, location, name, email from appointment a join user u on a.user_id = u.user_id where appointment_date = :date'; 
  $stmnt = $db->prepare($query);
  $stmnt->bindParam(':date', $date);
  $stmnt->execute();

  $appoints = '{"date": "'.$date.'", "times":[';
  while($row = $stmnt->fetch())
  {
    $location = $row['location'];
    $user = $row['name'];
    $email = $row['email'];
    $hour = ltrim($row['appointment_time'], "0");//remove times beginning with 0
    $hour = current(explode(':', $hour));
    $appoints .= '{"hour":"'.$hour.'","location":"'.$location.'","user":"'.$user.'","email":"'.$email.'"},';
  }
  //remove trailing comma
  $appoints = rtrim($appoints, ",");
  $appoints .= ']}';

  echo $appoints;

}
catch (Exception $ex)
{
  echo "Error with DB. ".$ex;
  die();
};

die();*/
?>