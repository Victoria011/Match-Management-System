<?php
session_start();
$_SESSION["message"] = null;

//connect database
require ("credentials.php");

$user = null;

if (isset($_SESSION['user_id'])) {
   // check whether loged in
   $records = $pdo->prepare('SELECT UserId,Username,Password FROM Users WHERE UserId = :id');
	$records->bindParam(':id', $_SESSION['user_id']);
	$records->execute();
   $count = $records->rowCount();

	if( $count > 0){
      //get user information
      $results = $records->fetch(PDO::FETCH_ASSOC);
		$user = $results;
	}
}
else {
   $pdo = $records = $results = $count = $user = null;
   header("Location: index.html");
   exit;
}
?>

<!DOCTYPE html>
<html>
   <head>
      <meta charset="utf-8">
      <title>Match Management System | Welcome</title>
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
                  <li class="current"><a href="index.php">Home</a></li>
                  <li><a href="events.php">Events</a></li>
                  <li><a href="login.php">Log in</a></li>
                  <li><a href="register.php">Register</a></li>
               </ul>
            </nav>
         </div>
      </header>

      <section id="showcase">
         <div class="container_home">
            <p class="welcome">WELCOME TO THE</p>
            <h1>Match Management System</h1>

            <!-- if user loged in then show username -->
            <?php
            if (!empty($user)) {
               echo '<p>Welcome '.htmlspecialchars($user['Username']).'!</p>';
               echo '<p>You have successfully logged in</p>
                     <a href="updateProfile.php" class="button_solid">UPDATE PROFILE</a>
                     <a href="logout.php" class="button_hollow">LOGOUT</a>
               ';
            }
            else {
               echo '<div>
                     <a href="login.php" class="button_solid">LOGIN</a>
                     <a href="register.php" class="button_hollow">REGISTER</a>
                     </div>';
            }
            ?>
         </div>
      </section>

      <footer>
         <p>Match Management System</p>
      </footer>
   </body>
</html>
