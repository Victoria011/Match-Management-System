<?php
session_start();
$_SESSION["message"] = null;

//connect database
require ("credentials.php");

if( isset($_SESSION['user_id']) ){

	//get user info
	$records = $pdo->prepare('SELECT UserId,Username,Password FROM Users WHERE UserId = :id');
	$records->bindParam(':id', $_SESSION['user_id']);
	$records->execute();
   $count = $records->rowCount();
	$results = $records->fetch(PDO::FETCH_ASSOC);

	$user = NULL;

	if( $count > 0){
		$user = $results;
		if ($user['Username'] == "Admin01") {
         // if is admin then show edit button
         echo '
         <script src="jquery/jquery-3.2.1.min.js"></script>
         <script type="text/javascript">
            $(document).ready(function(){
               $("#editMatch").show();
            });
         </script>';
		}
		else {
         // if is player then hide edit button
         echo '<script src="jquery/jquery-3.2.1.min.js"></script>
         <script type="text/javascript">
            $(document).ready(function(){
               $("#editMatch").hide();
            });
         </script>';
		}
	}
}
?>

<!DOCTYPE html>
<html>
   <head>
      <meta charset="utf-8">
      <title>Match Management System | Events</title>
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
			<div class="container_events">
				<h2>Current & Future Events</h2>

            <div class="Match" id="editMatch">
					<a href="editMatch.php" class="button_hollow">Edit Match</a>
	         </div>

				<table>
				  <thead>
					 <tr>
						<th>Match Id</th>
						<th>Starting Time</th>
						<th>Duration</th>
						<th>Match Date</th>
						<th>Information</th>
						<th>Location</th>
						<th>Capacity</th>
						<th>Available Space</th>
                  <th>Preferred Position</th>
						<th>Register</th>
					 </tr>
				  </thead>
				<tbody>
			   <?php
				//connect to database
			   require("credentials.php");
				try {
					//create Events if not exists
					$sql = file_get_contents('Events.sql');
					$qr = $pdo->exec($sql);

					//create Register if not exists
					$sql = file_get_contents('Register.sql');
					$qr = $pdo->exec($sql);

				   // get current & future events
				   $rows = $pdo->query("SELECT * FROM Events WHERE (MatchDate > CURDATE() OR (MatchDate = CURDATE() AND StartTime > CURTIME()))");
					//if has current & future events
					$count = $rows->rowCount();
					if ($count > 0) {

				   // Iterate through each row of our results
				   foreach ($rows as $row) {
                  $matchid = htmlspecialchars($row['MatchId']);

                  //calculate available space
				      $stmt = $pdo->prepare("SELECT * FROM Register WHERE MatchId = :id");
				      $stmt->bindParam(':id', $matchid);
				      $stmt->execute();
				      $count = $stmt->rowCount();
                  $availSpace = htmlspecialchars($row['Capacity']) - $count;
                  if($availSpace < 0) {
                     $availSpace = 0;
                  }
                  //check whether user registered this match
                  $stmt = $pdo->prepare("SELECT * FROM Register WHERE MatchId = :id AND UserId = :user_id");
                  $user_id = htmlspecialchars($_SESSION['user_id']);
                  $stmt->bindParam(':id', $matchid);
                  $stmt->bindParam(':user_id', $user_id);
                  $stmt->execute();
                  $results = $stmt->fetch(PDO::FETCH_ASSOC);
                  $count = $stmt->rowCount();
                  $register = 0;
                  if ($count > 0){
                     //If player has registered
                     $register = 1;
                     $position = htmlspecialchars($results['Position']);
                  }
				  ?>
              <!-- print table content -->
				  <tr>
					 <td> <?php print($matchid);?></td>
					 <td> <?php print(htmlspecialchars($row['StartTime']));?></td>
					 <td> <?php print(htmlspecialchars($row['Duration']));?></td>
					 <td> <?php print(htmlspecialchars($row['MatchDate']));?></td>
					 <td> <?php print(htmlspecialchars($row['Info']));?></td>
					 <td> <?php print(htmlspecialchars($row['Location']));?></td>
					 <td> <?php print(htmlspecialchars($row['Capacity']));?></td>
					 <td> <?php print($availSpace);?></td>

                <form action="matchRegister.php" method="post">
                <td> <?php
                if ($register == 1) {
                  //  if user registered
                   print($position);
                } else {
                   //select preferred position
                   echo '
                   <select id="select" name="position">
                     <option value="--">Preferred Position</option>
                     <option value="Goalkeeper">Goalkeeper</option>
                     <option value="Defender">Defender</option>
                     <option value="Midfielder">Midfielder</option>
                     <option value="Striker">Striker</option>
                   </select>'; } ?>
                </td>
                <td><?php
                if ($register == 1) {
                   //if user registered give unregister option
                   echo '<a  class="button" href="unregister.php?match_id='.$matchid.'">Unregister</a>';
                } elseif ($availSpace > 0) {
                   //if has availSpace
                   echo '<input class="hidden" name="match_id" value="'.$matchid.'">
                         <input type="submit" class="button" value="Register!">';
                } else {
                   //if no space
                   echo 'FULL';
                }?>
              </td>
              </form>
				  </tr>
				  <?php
			           }
		           }
		           else {
			           echo '<tr>
						  <td colspan="8">No Current/Future Events</td>
						  </tr>';
		         }
				} catch (Exception $e) {
					//disconnect
					$pdo = $sql = $qr = null;
               header("Location: index.html");
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
