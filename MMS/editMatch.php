<?php
session_start();
$_SESSION["message"] = null;

require ("credentials.php");

if( isset($_SESSION['user_id']) ){

   //get user info
	$records = $pdo->prepare('SELECT UserId,Username,Password FROM Users WHERE UserId = :id');
	$records->bindParam(':id', $_SESSION['user_id']);
	$records->execute();
	$count = $records->rowCount();
	$results = $records->fetch(PDO::FETCH_ASSOC);

	$user = NULL;

	if( $count <= 0 || strcmp($results['Username'],'Admin01') == 1){
		$pdo = $records = $count = $results = null;
		header("Location: register.php");
		exit;
	}
}
else {
	$pdo = null;
	header("Location: register.php");
	exit;
}

?>

<!DOCTYPE html>
<html>
   <head>
      <meta charset="utf-8">
      <title>Match Management System | Edit Match</title>
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
				<h1>Edit Match</h1>

            <div  class="Match">
					<a class="button_solid" href="createMatch.php">Create Match</a>
					<a class="button_hollow" href="events.php">Back</a>
            </div>
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
	 				<th>Action</th>
	          </tr>
	        </thead>
	      <tbody>
				<?php
	            try {
						$sql = 'SELECT * FROM Events';
						foreach ($pdo->query($sql) as $row) {
							echo '<tr>';
							echo '<td>'. htmlspecialchars($row['MatchId']) . '</td>';
							echo '<td>'. htmlspecialchars($row['StartTime']) . '</td>';
							echo '<td>'. htmlspecialchars($row['Duration']) . '</td>';
							echo '<td>'. htmlspecialchars($row['MatchDate']) . '</td>';
							echo '<td>'. htmlspecialchars($row['Info']) . '</td>';
							echo '<td>'. htmlspecialchars($row['Location']) . '</td>';
							echo '<td>'. htmlspecialchars($row['Capacity']) . '</td>';
							echo '<td>';
						   	echo '<a class="button" href="read.php?id='.htmlspecialchars($row['MatchId']).'">Read</a>';
							   echo ' ';
								echo '<a  class="button" href="update.php?id='.htmlspecialchars($row['MatchId']).'">Update</a>';
								echo ' ';
								echo '<a  class="button" href="delete.php?id='.htmlspecialchars($row['MatchId']).'">Delete</a>';
								echo ' ';
								echo '<a  class="button" href="allocatePlayer.php?id='.htmlspecialchars($row['MatchId']).'">Allocate</a>';
							echo '</td>';
							echo '</tr>';
						}
	            } catch (Exception $e) {
						$pdo = $sql = null;
						header("Location: index.php");
						exit;
	            }
				?>
	      </tbody>
	    </table>
      </section>

      <footer>
         <p>Match Management System</p>
      </footer>
   </body>
</html>
