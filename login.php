<?php

include "./config.php";

if($_SERVER['REQUEST_METHOD'] == "POST"){
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $password = mysqli_real_escape_string($db, $_POST['password']);
  //check if truly a value is entered
  if(empty($email) || empty($password)){
    sendReply(400, "All fields are required!");
  }
  //if there is no error, check for authentication
    $password = md5($password);
    $query = "SELECT * FROM records WHERE email = '$email' AND password = '$password'";
    $result = mysqli_query($db, $query);
    if(mysqli_num_rows($result) == 1){
      $row = mysqli_fetch_array($result);
      $_SESSION['user_id'] = $row['id_num'];
      $_SESSION['username'] = $row['fullname'];
      $_SESSION['status'] = $row['user_status'];
      if($_SESSION['status'] == 0){
      sendReply(400, "Account Not Verified, Check Back Later!");
      }
      sendReply(200, "Welcome ". $_SESSION['username']);
    }
    else{
      sendReply(400, "Incorrect Email or Password!");
    }

}

?>