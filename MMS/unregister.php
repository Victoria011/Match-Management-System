<?php
   session_start();
   // Include our database credentials
   require("credentials.php");

   $id = 0;

   if ( !empty($_GET['match_id'])) {
      $id = $_REQUEST['match_id'];
   }

   // delete data
   $stmt = $pdo->prepare("DELETE FROM Register WHERE MatchId = :match_id AND UserId = :user_id");
   $stmt->bindParam(':match_id', $id);
   $stmt->bindParam(':user_id', $_SESSION['user_id']);
   $result = $stmt->execute();
   if($result > 0){
      // TO DO disconnect
      header("Location: events_user.php");
      exit;
   }
   else {
      // TO DO disconnect
      header("Location: index.php");
      exit;
   }
?>
