<?php
    // Include our database credentials
    require("credentials.php");

    $id = 0;
    if ( !empty($_GET['id'])) {
        $id = $_REQUEST['id'];
    }

    if ( !empty($_POST)) {
        // keep track post values
        $id = $_POST['id'];

        // delete data
        $sql = "DELETE FROM Events WHERE MatchId = ?";
        $q = $pdo->prepare($sql);
        $q->execute(array($id));
        header("Location: editMatch.php");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Match Management System | Delete</title>
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
      <div class="container_log">
          <h1>Delete Events</h1>
             <form action="delete.php" method="post">
             <input type="hidden" name="id" value="<?php echo $id;?>"/>
             <p>Are you sure to delete ?</p>
                <button class="button_solid" type="submit" >Yes</button>
                <a class="button_hollow" href="editMatch.php">No</a>
            </form>
      </div> <!-- /container -->
   </section>
   <footer>
      <p>Match Management System</p>
   </footer>
</body>
</html>
