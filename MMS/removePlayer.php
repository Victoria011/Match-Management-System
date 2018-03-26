<?php
   $match_id = null;
   $user_id = null;
   $page = null;
   if ( isset($_GET['user_id']) && isset($_GET['match_id']) && isset($_GET['page'])) {
      $user_id = $_REQUEST['user_id'];
      $match_id = $_REQUEST['match_id'];
      $page = $_REQUEST['page'];
   }

   if ($user_id == null || $match_id == null) {
      header("Location: editMatch.php");
      exit;
   }
   else {
      // connect database
      require("credentials.php");

      // delete data
      $stmt = $pdo->prepare("DELETE FROM Register WHERE MatchId = :match_id AND UserId = :user_id");
      $stmt->bindParam(':match_id', $match_id);
      $stmt->bindParam(':user_id', $user_id);
      $result = $stmt->execute();
      if ($page == 1) {
         header("Location: allocatePlayer.php?id=$match_id");
         exit;
      }
      header("Location: read.php?id=$match_id");
      exit;
   }

?>
