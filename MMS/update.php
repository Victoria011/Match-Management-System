<?php
   function redirectFailure(){
      // On failure - redirect back to the register page
      header("Location: editMatch.php");
      exit;
   }
   // connect database
   require("credentials.php");

   $id = $message = null;

   if ( !empty($_GET['id'])) {
      $id = $_REQUEST['id'];
   }

   if ( null==$id ) {
      redirectFailure();
   }

   if ( !empty($_POST)) {
      // validate input
      $valid = true;
      if (empty($_POST["time"])) {
         $message = 'Please enter time';
         $valid = false;
      }

      if (empty($_POST["duration"])) {
         $message = 'Please enter duration';
         $valid = false;
      }

      if (empty($_POST["date"])) {
         $message = 'Please enter date';
         $valid = false;
      }

      if (empty($_POST["location"])) {
         $message = 'Please enter location';
         $valid = false;
      }

      if (empty($_POST["capacity"])) {
         $message = 'Please enter capacity';
         $valid = false;
      }

      // Ensure time length >= 1 and < 255
      $time = filter_var($_POST["time"], FILTER_SANITIZE_STRING);
      if(!(strlen($time) > 0 and strlen($time) < 50)){
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
      if(!(strlen($date) > 0 and strlen($date) < 50)){
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

      // update data
      if ($valid) {
         $sql = "UPDATE Events  set StartTime = ?, Duration = ?, MatchDate =?, Info =?, Location =?, Capacity =? WHERE MatchId = ?";
         $q = $pdo->prepare($sql);
         $q->execute(array($time,$duration,$date,$info,$location,$capacity,$id));
         //TO DO disconnect
         header("Location: editMatch.php");
         exit;
      }
    } else {
        $sql = "SELECT * FROM Events where MatchId = ?";
        $q = $pdo->prepare($sql);
        $q->execute(array($id));
        $data = $q->fetch(PDO::FETCH_ASSOC);
        $time = htmlspecialchars($data['StartTime']);
        $duration = htmlspecialchars($data['Duration']);
        $date = htmlspecialchars($data['MatchDate']);
        $info = htmlspecialchars($data['Info']);
        $location = htmlspecialchars($data['Location']);
        $capacity = htmlspecialchars($data['Capacity']);
    }
?>

<!DOCTYPE html>
<html>
   <head>
      <meta charset="utf-8">
      <title>Match Management System | Update</title>
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
            <h1>Update Event</h1>
            <?php
            if (!empty($message)) {
               echo '<p>'.$message.'</p>';
            }
            elseif (isset($_SESSION["message"])) {
               $message = $_SESSION["message"];
               echo '<p>'.$message.'</p>';
            }
            ?>

            <form action="update.php?id=<?php echo $id?>" method="post">
               <input type="text" name="time" placeholder="Starting Time" value="<?php echo !empty($time)?$time:'';?>">
               <input name="duration" type="text" placeholder="Duration" value="<?php echo !empty($duration)?$duration:'';?>">
               <input name="date" type="text"  placeholder="Date" value="<?php echo !empty($date)?$date:'';?>">
               <input name="info" type="text"  placeholder="Information" value="<?php echo !empty($info)?$info:'';?>">
               <input name="location" type="text"  placeholder="Location" value="<?php echo !empty($location)?$location:'';?>">
               <input name="capacity" type="text"  placeholder="Capacity" value="<?php echo !empty($capacity)?$capacity:'';?>">
               <div class="form-actions">
                  <input type="submit" class="half_subBtn" name="subBtn" value="Create!">
                  <a class="button_white" href="editMatch.php">Back</a>
               </div>
            </form>
         </div> <!-- /container -->
      </section>

      <footer>
         <p>Match Management System</p>
      </footer>
   </body>
</html>
