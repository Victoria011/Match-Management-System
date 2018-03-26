<?php
session_start();
$_SESSION["message"] = null;

//Connect database
require ("credentials.php");

if( isset($_SESSION['user_id']) ){

	$records = $pdo->prepare('SELECT UserId,Username,Password FROM Users WHERE UserId = :id');
	$records->bindParam(':id', $_SESSION['user_id']);
	$records->execute();
	$results = $records->fetch(PDO::FETCH_ASSOC);
	$count = $records->rowCount();

	$user = NULL;

	if( count($results) > 0){
		$pdo = $records = $results = $count = null;
      header("Location: events_user.php");
		exit;
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
                  <li><a href="index.html">Home</a></li>
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
					  </tr>
				  </thead>
				  <tbody>
			     <?php
              // connect database
				  require("credentials.php");

				  try {
					  //create Events if not exists
 					  $sql = file_get_contents('Events.sql');
 					  $qr = $pdo->exec($sql);

					  //create Register if not exists
					  $sql = file_get_contents('Register.sql');
					  $qr = $pdo->exec($sql);

				     // get current & future event
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
					  $pdo = null;
				     header("Location: index.html");
					  exit;
				  }
			     ?>
				  </tbody>
			   </table>
			   <h3>Please login or register to attend matches</h3>
			</div>
      </section>
      <footer>
         <p>Match Management System</p>
      </footer>
   </body>
</html>
