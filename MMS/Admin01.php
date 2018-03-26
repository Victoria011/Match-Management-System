<?php
// if no Admin01 insert one
$adname = filter_var("Admin01", FILTER_SANITIZE_STRING);
$stmt = $pdo->prepare("SELECT * FROM Users WHERE Username = :name");
$stmt->bindParam(':name', $adname);
$stmt->execute();
$count = $stmt->rowCount();
if($count == 0){
   $ademail = filter_var("Admin01@gmail.com", FILTER_SANITIZE_STRING);
   $adtel = filter_var("123123123", FILTER_SANITIZE_STRING);
   $adposition = filter_var("--", FILTER_SANITIZE_STRING);
   $aduname = filter_var("Admin01", FILTER_SANITIZE_STRING);
   $adpw = password_hash("123", PASSWORD_BCRYPT);

   //insert
   $stmt = $pdo->prepare("INSERT INTO Users (Name, Email, Phone, Position, Username, Password)VALUES (:name, :email, :phone, :position, :username, :password)");

    // Bind our values to the specified parameters
    $stmt->bindParam(':name', $adname);
    $stmt->bindParam(':email', $ademail);
    $stmt->bindParam(':phone', $adtel);
    $stmt->bindParam(':position', $adposition);
    $stmt->bindParam(':username', $aduname);
    $stmt->bindParam(':password', $adpw);

    // Execute query
    $stmt->execute();
}
?>
