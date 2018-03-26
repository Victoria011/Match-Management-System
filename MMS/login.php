<?php
session_start();

require ("vendor/password_compat/lib/password.php");

//connect database
require ("credentials.php");

//create User table if not exist
$sql = file_get_contents('Users.sql');
$pdo->exec($sql);

//create Admin01 if not exist
require ("Admin01.php");
//create User table if not exist
$sql = file_get_contents('Users.sql');
$pdo->exec($sql);

$message = '';
if (isset($_SESSION['user_id'])) {
   $message = "You have already logged in";
   echo '
   <script src="jquery/jquery-3.2.1.min.js"></script>
   <script type="text/javascript">
   $(document).ready(function(){
         $("#login").hide();
         $("#logout").show();
   });
   </script>';
}
else {
   echo '
   <script src="jquery/jquery-3.2.1.min.js"></script>
   <script type="text/javascript">
   $(document).ready(function(){
         $("#login").show();
         $("#logout").hide();
   });
   </script>';
}
if (!empty($_POST)) {
   if(!empty($_POST['username']) && !empty($_POST['password'])){
      $records = $pdo->prepare("SELECT * FROM Users WHERE Username LIKE :name");
      $records->bindParam(':name', $_POST['username']);
      $records->execute();
      $results = $records->fetch(PDO::FETCH_ASSOC);
      $count = $records->rowCount();

   	if( $count > 0 && password_verify($_POST['password'], $results['Password']) ){
   		$_SESSION['user_id'] = $results['UserId'];
         $pdo = $records = $results = $count = null;
   		header("Location: index.php");
         exit;
   	} else {
   		$pwmessage = 'Sorry, the username or password is incorrect.';
   	}
   }
   else {
      $pwmessage = 'Username or Password cannot be empty.';
   }
}

?>

<!DOCTYPE html>
<html>
   <head>
      <meta charset="utf-8">
      <title>Match Management System | Login</title>
      <link rel="stylesheet" type="text/css" href="css/index.css">
      <link href="fonts/OpenSans.css" rel="stylesheet">
   </head>
   <body>
      <header>
         <div class="container">
            <div id="logo">
               <h1>Match Management System</h1>
            </div>
            <nav>
               <ul>
                  <li><a href="index.php">Home</a></li>
                  <li><a href="events.php">Events</a></li>
                  <li class="current"><a href="login.php">Log In</a></li>
                  <li><a href="register.php">Register</a></li>
               </ul>
            </nav>
         </div>
      </header>

      <section id="showcase">
         <div class="container_log">
            <h1>Log In</h1>

            <div id="logout">
               <?php
               if (!empty($message)) {
                  echo '<p>'.$message.'</p>';
               }
               ?>
               <br>
               <a href="logout.php" class="button_hollow">LOGOUT</a>
            </div>

            <div id="login">
               <?php
                  if (!empty($pwmessage)) {
                     echo '<p>'.$pwmessage.'</p>';
                  }
               ?>
               <form action="login.php" method="post">
                  <input type="text" name="username" placeholder="Username*">
                  <input type="password" name="password" placeholder="Password*">
                  <input name="subBtn" type="submit" value="Log in!">
               </form>
            </div>

         </div>
      </section>

      <footer>
         <p>Match Management System</p>
      </footer>

   </body>
</html>
