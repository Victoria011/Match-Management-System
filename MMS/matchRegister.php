<?php
session_start();

//connect database
require("credentials.php");

// if user login
if( isset($_SESSION['user_id']) ){

	$records = $pdo->prepare('SELECT UserId,Username,Password FROM Users WHERE UserId = :id');
	$records->bindParam(':id', $_SESSION['user_id']);
	$records->execute();
   $count = $records->rowCount();
	$results = $records->fetch(PDO::FETCH_ASSOC);

	$userId = null;
	$username = null;
   $position = null;

	if( $count > 0){
		$userId = htmlspecialchars($results['UserId']);
		$username = htmlspecialchars($results['Username']);
      $position = filter_var($_POST["position"], FILTER_SANITIZE_STRING);
	}
   else {
		// if not login in go to register page
      header("Location: register.php");
      exit;
   }
}
else {
   // if not logged in go to login page
   header("Location: login.php");
   exit;
}

//get match id
$matchId = null;
if ( !empty($_POST["match_id"] )) {
	 $matchId = filter_var($_POST["match_id"], FILTER_SANITIZE_STRING);
}
else {
   //match id empty--sth wrong
	header("Location: events_user.php");
   exit;
}

//insert in to table Register
try {
   //creating new table
   $sql = file_get_contents('Register.sql');
   $pdo->exec($sql);

   //check users have registered this match or not
	$stmt = $pdo->prepare("SELECT * FROM Register WHERE UserId = :id");
   $stmt->bindParam(':id', $userId);
   $stmt->execute();
   $count = $stmt->rowCount();
   if($count > 0) {
      header("Location: events_user.php");
      exit;
   }

   // Prepared statement for inserting register
   $stmt = $pdo->prepare("INSERT INTO Register (UserId, Username, Position, MatchId) VALUES (:user, :username, :position, :match)");
   // Bind our values to the specified parameters
   $stmt->bindParam(':user', $userId);
   $stmt->bindParam(':username', $username);
   $stmt->bindParam(':position', $position);
   $stmt->bindParam(':match', $matchId);
   // Execute query
   $stmt->execute();

   // If there are no exceptions, then the code will reach here - success
   header("Location: events_user.php");
   exit;

} catch (Exception $e) {
   header("Location: events.php");
   exit;
}
?>
