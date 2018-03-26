<?php
session_start();

// Connect database
require("credentials.php");

//enable to use password_hash
require("vendor/password_compat/lib/password.php");

// set error message
$_SESSION["message"]=null;

function redirectFailure(){
   // On failure - redirect back to the register page
   header("Location: register.php");
   exit;
}

function redirectSuccess($uname){
   // Connect database
   require("credentials.php");
   try {
      $records = $pdo->prepare("SELECT * FROM Users WHERE Username LIKE :name");
      $records->bindParam(':name', $uname);
      $records->execute();
      $count = $records->rowCount();
      echo $count;
      $results = $records->fetch(PDO::FETCH_ASSOC);
      $_SESSION['user_id'] = $results['UserId'];
      // On success - redirect to home page
      header("Location: index.php");
      exit;
   } catch (Exception $e) {
      header("Location: login.php");
      exit;
   }
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

if (empty($_POST)) {
   redirectFailure();
}

if (empty($_POST["name"])) {
   // passErrMsg("Name cannot be empty.");
   $_SESSION["message"] = "Name cannot be empty.";
   redirectFailure();
}
elseif (empty($_POST["email"])) {
   $_SESSION["message"] = "Email cannot be empty.";
   redirectFailure();
}
elseif (empty($_POST["username"])) {
   $_SESSION["message"] = "Username cannot be empty.";
   redirectFailure();
}
elseif (empty($_POST["password"])) {
   $_SESSION["message"] = "Password cannot be empty.";
   redirectFailure();
}
elseif (empty($_POST["confirm_password"])) {
   $_SESSION["message"] = "Passwords are not consistent.";
   redirectFailure();
}
elseif (strcmp($_POST["password"],$_POST["confirm_password"]) != 0) {
   // passErrMsg("Passwords are not consistent.");
   $_SESSION["message"] = "Passwords are not consistent.";
   redirectFailure();
}

if(isset($_POST["name"]) and isset($_POST["email"]) and
   isset($_POST["username"]) and isset($_POST["password"]))
{
   // Ensure Name length >= 1 and < 255
   $name = test_input(filter_var($_POST["name"], FILTER_SANITIZE_STRING));
   if(!(strlen($name) > 0 and strlen($name) < 255)){
      $_SESSION["message"] = "Invalid Name";
      redirectFailure();
   }

   // Ensure email length >= 1 and < 255
   $email = test_input(filter_var($_POST["email"], FILTER_SANITIZE_EMAIL));
   if(!(strlen($email) > 0 and strlen($email) < 255)){
      $_SESSION["message"] = "Invalid Email";
      redirectFailure();
   }

   // Ensure the tel number (if provided) does not exceed the specified limit
   $tel = test_input(filter_var($_POST["phone"], FILTER_SANITIZE_STRING));
   if(!(strlen($tel) < 25)){
      $_SESSION["message"] = "Invalid Telephone Number";
      redirectFailure();
   }

   // Ensure the position  is a valid value
   $position = test_input(filter_var($_POST["position"], FILTER_SANITIZE_STRING));
   if(!(strlen($position) < 25)){
      $_SESSION["message"] = "Invalid Position";
      redirectFailure();
   }

   // Ensure the username is provided and is of valid length
   $uname = test_input(filter_var($_POST["username"], FILTER_SANITIZE_STRING));
   if(!(strlen($uname) > 0 and strlen($uname) < 50)){
      $_SESSION["message"] = "Invalid Username";
      redirectFailure();
   }

   // Ensure password length > 0 and < 255
   $pword = password_hash($_POST['password'], PASSWORD_BCRYPT);
   if(!(strlen($pword) > 0 and strlen($pword) < 255)){
      $_SESSION["message"] = "Invalid Password";
      redirectFailure();
   }

   $_SESSION["message"] = null;
   // If reach here - then have valid (and safe) data
   // Now store it in the DB

try{
   //create User table if not exist
   $sql = file_get_contents('Users.sql');
   $pdo->exec($sql);

   $stmt = $pdo->prepare("SELECT * FROM Users WHERE Username = :name");
   $stmt->bindParam(':name', $uname);
   $stmt->execute();
   $count = $stmt->rowCount();
   if($count != 0) {
      $_SESSION["message"] = "Username Exists";
      redirectFailure();
   }

   //create Admin01 if not exist
   require ("Admin01.php");

   // prepared statement for inserting user
   $stmt = $pdo->prepare("INSERT INTO Users (Name, Email, Phone, Position, Username, Password)VALUES (:name, :email, :phone, :position, :username, :password)");

   // Bind our values to the specified parameters
   $stmt->bindParam(':name', $name);
   $stmt->bindParam(':email', $email);
   $stmt->bindParam(':phone', $tel);
   $stmt->bindParam(':position', $position);
   $stmt->bindParam(':username', $uname);
   $stmt->bindParam(':password', $pword);

   // Execute query
   $stmt->execute();

   // If there are no exceptions, then the code will reach here - success
   redirectSuccess($uname);

} catch(Exception $e){
   $_SESSION["message"] = "Sorry, something wrong...";
   redirectFailure();
}
}
?>
