<?php
//connect to database
require("credentials.php");

$match_id = $user_id = $username = null;

if (isset($_GET['id'])) {
   $match_id = $_REQUEST['id'];
}

if(!empty($_POST['match_id']) &&
   !empty($_POST['user_id']) &&
   !empty($_POST['username']) &&
   !empty($_POST["position"])) {

   $match_id = filter_var($_POST["match_id"], FILTER_SANITIZE_STRING);
   $user_id = filter_var($_POST["user_id"], FILTER_SANITIZE_STRING);
   $username = filter_var($_POST["username"], FILTER_SANITIZE_STRING);
   $position = filter_var($_POST["position"], FILTER_SANITIZE_STRING);

   // Prepared statement for inserting register
   $stmt = $pdo->prepare("INSERT INTO Register (UserId, Username, Position, MatchId) VALUES (:user, :username, :position, :match)");
   // Bind values to the specified parameters
   $stmt->bindParam(':user', $user_id);
   $stmt->bindParam(':username', $username);
   $stmt->bindParam(':position', $position);
   $stmt->bindParam(':match', $match_id);
   // Execute query
   $stmt->execute();
}
?>

<!DOCTYPE html>
<html>
   <head>
      <meta charset="utf-8">
      <title>Match Management System | Allocate</title>
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
            <h2>Allocate Player</h2>
            <div class="Match">
					<a class="button_hollow" href="editMatch.php">Back</a>
            </div>
            <table>
               <thead>
                  <tr>
                     <th>Player ID</th>
                     <th>Player Username</th>
                     <th>Player Telephone</th>
                     <th>Player Email</th>
                     <th>Player Position</th>
                     <th>Action</th>
                  </tr>
               </thead>
               <tbody>
                  <?php
                  try {
                     $sql = 'SELECT * FROM Users';
      					foreach ($pdo->query($sql) as $row) {
                        $user_id = htmlspecialchars($row['UserId']);
                        $username = htmlspecialchars($row['Username']);

                        //check whether user has registered this match
                        $stmt = $pdo->prepare("SELECT * FROM Register WHERE MatchId = :id AND UserId = :user_id");
                        $stmt->bindParam(':id', $match_id);
                        $stmt->bindParam(':user_id', $user_id);
                        $stmt->execute();
                        $results = $stmt->fetch(PDO::FETCH_ASSOC);
                        $count = $stmt->rowCount();
                        $register = 0;
                        if ($count > 0){
                           //IF player has registered
                           $register = 1;
                        }

      						echo '<tr>
                        <form action="allocatePlayer.php" method="post">';
      						echo '<td>'.$user_id.'</td>';
      						echo '<td>'.$username.'</td>';
      						echo '<td>'. htmlspecialchars($row['Phone']) . '</td>';
      						echo '<td>'. htmlspecialchars($row['Email']) . '</td>';
                        //select position
                        if ($register == 1) {
                           echo '<td>'. htmlspecialchars($results['Position']) . '</td>';
                        }
                        else {
                           echo '<td>
                           <select id="select" name="position">
                              <option value="--">Preferred Position</option>
                              <option value="Goalkeeper">Goalkeeper</option>
                              <option value="Defender">Defender</option>
                              <option value="Midfielder">Midfielder</option>
                              <option value="Striker">Striker</option>
                              </select>
                              </td>';
                        }
      						echo '<td>';
                        if ($register == 1) {
                           //if user has registered give remove option
                           echo '<a  class="button" href="removePlayer.php?user_id='.$user_id.'&match_id='.$match_id.'&page=1">Remove</a>';
                        } else {
                           //if not registered
                           echo '
                           <input class="hidden" name="match_id" value="'.$match_id.'">
                           <input class="hidden" name="user_id" value="'.$user_id.'">
                           <input class="hidden" name="username" value="'.$username.'">
                           <input type="submit" class="button" value="Allocate">';
                        }
                        echo '</td>
                           </form>
                        </tr>';
      					}
                  } catch (Exception $e) {
                     header("Location: editMatch.php");
                     exit;
                  }
                  ?>
               </tbody>
            </table>
         </div>
      </section>

      <footer>
         <p>Match Management System</p>
      </footer>

   </body>
</html>
