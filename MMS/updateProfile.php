<?php
   session_start();
   // On failure - redirect back to the register page
   function redirectFailure(){
      header("Location: index.php");
      exit;
   }

   // Connect database
   require("credentials.php");
   //initialize
   $id = null;
   $message = null;
   $nameError = $emailError = $phoneError = $usernameError = "";

   //get user id
   if(isset($_SESSION['user_id'])) {
      $id = $_SESSION['user_id'];
   }

    if ($id == null) {
        redirectFailure();
    }

    if (!empty($_POST)) {
       // validate input
       $valid = true;
       if (empty($_POST["name"])) {
          $message = 'Please enter name';
          $valid = false;
       }

       if (empty($_POST["email"])) {
          $message = 'Please enter email';
          $valid = false;
       }

       if (empty($_POST["username"])) {
          $message = 'Please enter username';
          $valid = false;
       }

       // Ensure name length >= 1 and < 255
       $name = filter_var($_POST["name"], FILTER_SANITIZE_STRING);
       if(!(strlen($name) > 0 and strlen($name) < 255)){
          $message = 'Invalid Name';
          $valid = false;
          // redirectFailure();
       }

        // Ensure email length >= 1 and < 255
        $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
        if(! (filter_var($email, FILTER_SANITIZE_STRING) and strlen($email) < 255)){
           $message = 'Invalid Email';
           $valid = false;
           // redirectFailure();
        }

        // Ensure the phone does not exceed the specified limit
        $phone = filter_var($_POST["phone"], FILTER_SANITIZE_STRING);
        if(!(strlen($phone) < 25)){
           $message = 'Invalid Phone';
           $valid = false;
           //redirectFailure();
        }

        // Ensure the username does not exceed the specified limit
        $username = filter_var($_POST["username"], FILTER_SANITIZE_STRING);
        if(!(strlen($username) > 0 and strlen($username) < 50)){
           $message = 'Invalid Username';
           $valid = false;
           //redirectFailure();
        }

        // update data
        if ($valid) {
            $sql = "UPDATE Users set Name = ?, Email = ?, Phone =?, Username =? WHERE UserId = ?";
            $q = $pdo->prepare($sql);
            $q->execute(array($name,$email,$phone,$username,$id));
            //TO DO disconnect
            header("Location: index.php");
            exit;
        }
    } else {
        $sql = "SELECT * FROM Users where UserId = ?";
        $q = $pdo->prepare($sql);
        $q->execute(array($id));
        $data = $q->fetch(PDO::FETCH_ASSOC);
        $name = $data['Name'];
        $email = $data['Email'];
        $phone = $data['Phone'];
        $username = $data['Username'];

        //TO DO disconnect
    }
?>


<!DOCTYPE html>
<html>
   <head>
      <meta charset="utf-8">
      <title>Match Management System | Update Profile</title>
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
         <div class="container_high">
            <h1>Update Profile</h1>
            <?php
            if (!empty($message)) {
               echo "<p>$message</p>";
            }
            elseif (isset($_SESSION["message"])) {
               $message = $_SESSION["message"];
               echo "<p>$message</p>";
            }
            ?>
            <div class="form">
               <form action="updateProfile.php?id=<?php echo $id?>" method="post">
                  <input name="name" type="text" placeholder="Name*" value="<?php echo !empty($name)?$name:'';?>">
                  <input name="email" type="email" placeholder="Email*" value="<?php echo !empty($email)?$email:'';?>">
                  <input name="phone" type="text"  placeholder="Phone" value="<?php echo !empty($phone)?$phone:'';?>">
                  <input name="username" type="text"  placeholder="Username*" value="<?php echo !empty($username)?$username:'';?>">
                  <div id="updateProfile">
                     <input type="submit" class="half_subBtn" name="subBtn" value="Update!">
                     <a class="button_white" href="index.php">Back</a>
                  </div>
               </form>
            </div>
         </div>
      </section>

      <footer>
         <p>Match Management System</p>
      </footer>
   </body>
</html>
