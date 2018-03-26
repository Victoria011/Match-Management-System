<?php
   //connect database
   require ("credentials.php");
   $match_id = null;
   $user_id = null;
   if ( !empty($_GET['id'] )) {
      $match_id = $_REQUEST['id'];
   }

   if ( null==$match_id ) {
      header("Location: login.php");
      exit;
   } else {
      try {
         $sql = "SELECT * FROM Events where MatchId = ?";
         $q = $pdo->prepare($sql);
         $q->execute(array($match_id));
         $data = $q->fetch(PDO::FETCH_ASSOC);
      } catch (Exception $e) {
         $pdo = $sql = $q = $data = null;
         header("Location: editMatch.php");
         exit;
      }
   }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Match Management System | Read</title>
    <link rel="stylesheet" type="text/css" href="css/index.css">
    <link href="fonts/OpenSans.css" rel="stylesheet">
</head>

<body>
   <header>
      <div class="container">
         <div id="logo">
            <h1 class="highlight">Match Management System</h1>
         </div>
         <nav>
            <ul>
               <li><a href="index.php">Home</a></li>
               <li class="current"><a href="events.php">Events</a></li>
               <li><a href="login.php">Log in</a></li>
               <li><a href="register.php">Register</a></li>
            </ul>
         </nav>
      </div>
   </header>

   <section id="showcase">
      <div class="container_high">
         <h2>Read a Match</h2>
         <table>
            <thead>
               <tr>
                  <th>Match ID</th>
                  <th>Starting Time</th>
                  <th>Duration</th>
                  <th>Match Date</th>
                  <th>Information</th>
                  <th>Location</th>
                  <th>Capacity</th>
               </tr>
            </thead>
            <tbody>
               <tr>
                  <td><?php echo htmlspecialchars($data['MatchId']);?></td>
                  <td><?php echo htmlspecialchars($data['StartTime']);?></td>
                  <td><?php echo htmlspecialchars($data['Duration']);?></td>
                  <td><?php echo htmlspecialchars($data['MatchDate']);?></td>
                  <td><?php echo htmlspecialchars($data['Info']);?></td>
                  <td><?php echo htmlspecialchars($data['Location']);?></td>
                  <td><?php echo htmlspecialchars($data['Capacity']);?></td>
               </tr>
            </tbody>
            <thead>
               <tr>
                  <th>Player ID</th>
                  <th>Player Username</th>
                  <th>Action</th>
               </tr>
            </thead>
            <tbody>
               <?php
               //connect to database
            //   require("credentials.php");
               try {
                 //check if Register exist
                 $result = $pdo->query("SHOW TABLES LIKE 'Register'");
                 $count = $result->rowCount();

                 //check if has any player registered
                 $rows = $pdo->prepare("SELECT * FROM Register WHERE MatchId = :match_id");
                 $rows->bindParam(':match_id', $match_id);
                 $rows->execute();
                 $countPlayer = $rows->rowCount();
                 if($count == 1 && $countPlayer > 0) {
                    // Iterate through each row of our results
      				  foreach ($rows as $row) {
                       $userId = htmlspecialchars($row['UserId']);
                       echo '
                       <tr>
                         <td>'.htmlspecialchars($row['UserId']).'</td>
                         <td>'.htmlspecialchars($row['Username']).'</td>
                         <td><a class="button" href="removePlayer.php?user_id='.$userId.'&match_id='.$match_id.'&page=0">Remove Player</a></td>
                       </tr>';
                    }
                 }
               } catch (Exception $e) {
                  $pdo = $match_id = $user_id = null;
                  header("Location: editMatch.php");
                  exit;
               }
               ?>
            </tbody>
         </table>
         <div>
            <br>
            <a class="button_hollow" href="editMatch.php">Back</a>
         </div>
      </div>
   </section>

   <footer>
      <p>Match Management System</p>
   </footer>
</body>
</html>
