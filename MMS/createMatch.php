<?php
function redirectFailure(){
   // On success - redirect back to the order page
   header("Location: createMatch.php");
}

function redirectSuccess(){
   // On success - redirect to home page
   header("Location: events_user.php");
}

$message = null;
$valid = true;

if (!empty($_POST)) {

   if(isset($_POST["time"]) and isset($_POST["duration"]) and
      isset($_POST["date"]) and isset($_POST["location"]) and isset($_POST["capacity"]))
   {
      if (empty($_POST["time"]) ||
          empty($_POST["duration"]) ||
          empty($_POST["date"]) ||
          empty($_POST["location"]) ||
          empty($_POST["capacity"])) {
         $message = 'Please enter match information.';
         $valid = false;
      }

      // Ensure time length >= 1 and < 255
      $time = filter_var($_POST["time"], FILTER_SANITIZE_STRING);
      if(!(strlen($time) > 4 and strlen($time) < 9)){
         $message = 'Invalid Time';
         $valid = false;
      }

      // Ensure duration length >= 1 and < 255
      $duration = filter_var($_POST["duration"], FILTER_SANITIZE_STRING);
      if(! (strlen($duration) > 0 and strlen($duration) < 255)){
         $message = 'Invalid Duration';
         $valid = false;
      }

      // Ensure the date does not exceed the specified limit
      $date = filter_var($_POST["date"], FILTER_SANITIZE_STRING);
      if(!(strlen($date) > 7 and strlen($date) < 11)){
         $message = 'Invalid Date';
         $valid = false;
      }

      // Ensure the info (if provided) does not exceed the specified limit
      $info = filter_var($_POST["info"], FILTER_SANITIZE_STRING);
      if(!(strlen($info) < 255)){
         $message = 'Invalid Information';
         $valid = false;
      }

      // Ensure location length >= 1 and < 255
      $location = filter_var($_POST["location"], FILTER_SANITIZE_STRING);
      if(!(strlen($location) > 0 and strlen($location) < 255)){
         $message = 'Invalid Location';
         $valid = false;
      }

      // Ensure capacity length >= 1 and < 255
      $capacity = filter_var($_POST["capacity"], FILTER_SANITIZE_STRING);
      if(!(strlen($capacity) > 0 and strlen($capacity) < 255)){
         $message = 'Invalid Capacity';
         $valid = false;
      }

      // Include our database credentials
      require("credentials.php");

      try{
         //sql file
         $sql = file_get_contents('Events.sql');
         $pdo->exec($sql);

         $stmt = $pdo->prepare("SELECT * FROM Events WHERE StartTime = :stime AND MatchDate = :mdate AND Location = :location");
         $stmt->bindParam(':stime', $time);
         $stmt->bindParam(':mdate', $date);
         $stmt->bindParam(':location', $location);
         $stmt->execute();
         $count = $stmt->rowCount();
         if($count != 0) {
            $message = "Match EXISTS";
            $valid = false;
         }
         if ($valid) {
            // Our prepared statement for inserting events
            $stmt = $pdo->prepare("INSERT INTO Events (StartTime, Duration, MatchDate, Info, Location, Capacity) VALUES (:stime, :duration, :mdate, :info, :location, :capacity)");

            // Bind our values to the specified parameters
            $stmt->bindParam(':stime', $time);
            $stmt->bindParam(':duration', $duration);
            $stmt->bindParam(':mdate', $date);
            $stmt->bindParam(':info', $info);
            $stmt->bindParam(':location', $location);
            $stmt->bindParam(':capacity', $capacity);

            // Execute query
            $result = $stmt->execute();
            // If there are no exceptions, then the code will reach here - success
            redirectSuccess();
         }
      } catch(Exception $e){
         redirectFailure();
      }
   }
}
?>

<!DOCTYPE html>
<html>
   <head>
      <meta charset="utf-8">
      <title>Match Management System | Create Match</title>
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
                  <li class="current"><a href="events.php">Events</a></li>
                  <li><a href="login.php">Log In</a></li>
                  <li><a href="register.php">Register</a></li>
               </ul>
            </nav>
         </div>
      </header>

      <section id="showcase">
         <div class="container_high">
            <h1>Create Match</h1>

            <?php
            if (!empty($message)) {
               echo '<p>'.$message.'</p>';
            }
            elseif (isset($_SESSION["message"])) {
               $message = $_SESSION["message"];
               echo '<p>'.$message.'</p>';
            }
            ?>

            <form action="createMatch.php" method="post">
               <input type="text" name="time" placeholder="Starting Time*: HH:MM:SS">
               <input type="text" name="duration" placeholder="Duration*">
               <input type="text" name="date" placeholder="Date*: YYYY-MM-DD">
               <textarea name="info" placeholder="Give Description/Information on the Match"></textarea>
               <input type="text" name="location" placeholder="Location*">
               <input type="text" name="capacity" placeholder="Capacity*">
               <input name="subBtn" type="submit" class="half_subBtn" value="Create!">
               <a class="button_white" href="editMatch.php">Back</a>
            </form>
         </div>
      </section>

      <footer>
         <p>Match Management System</p>
      </footer>

   </body>
</html>
