<?php
session_start();
//initialize message
$message = null;

if(isset($_SESSION['user_id'])) {
   $message = 'You have already logged in!';
}
?>

<!DOCTYPE html>
<html>
   <head>
      <meta charset="utf-8">
      <title>Match Management System | Register</title>
      <link rel="stylesheet" type="text/css" href="css/index.css">
      <link href="fonts/OpenSans.css" rel="stylesheet">
      <script type="text/javascript" src="validation.js"></script>
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
                  <li><a href="login.php">Log In</a></li>
                  <li class="current"><a href="register.php">Register</a></li>
               </ul>
            </nav>
         </div>
      </header>

      <section id="showcase">
         <div class="container_high">
            <h1>Register</h1>
            <p id="errMsg"></p>
            <p id="phpmsg">
               <?php
               if (!empty($message)) {
                  echo $message;
               }
               elseif (!empty($_SESSION["message"])) {
                  $message = $_SESSION["message"];
                  echo $message;
               }
               ?>
            </p>

            <div class="form">
               <form onsubmit="return validation(this);" method="post" action="validation.php">
                  <input id="name" type="text" name="name" placeholder="Name*">
                  <input id="email" type="email" name="email" placeholder="Email*">
                  <input id="phone" type="text" name="phone" placeholder="Telephone">
                  <select id="position" name="position">
                     <option value="--">Preferred Position</option>
                     <option value="Goalkeeper">Goalkeeper</option>
                     <option value="Defender">Defender</option>
                     <option value="Midfielder">Midfielder</option>
                     <option value="Striker">Striker</option>
                  </select>
                  <input id="username" type="text" name="username" placeholder="Username*">
                  <input id="password" type="password" name="password" placeholder="Password*">
                  <input id="confirm_password" type="password" name="confirm_password" placeholder="Comfirm Password*">
                  <input name="subBtn" type="submit" value="Register!">
               </form>
            </div>
         </div>
      </section>

      <footer>
         <p>Match Management System</p>
      </footer>
   </body>
</html>
