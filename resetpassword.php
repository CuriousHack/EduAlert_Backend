<?php

include "./config.php";

//enter new password

if($_SERVER['REQUEST_METHOD'] == "POST"){
    $password = mysqli_real_escape_string($db, $_POST['password']);
    $cpassword = mysqli_real_escape_string($db, $_POST['cpassword']);
  
    // Grab to token that came from the email link
    //$token = $_SESSION['token'];
    if (empty($password) || empty($cpassword)) {
        echo json_encode(array('error' => 'All Fields are Required!'));
        exit();
    }
    if ($password !== $cpassword) {
        echo json_encode(array('error' => 'Password do not Match!'));
        exit();
    }
    if(isset($_GET['token'])){
        $token = mysqli_real_connect($_GET['token']);

        // select email address of user from the password_reset table 
      $sql = "SELECT email FROM password_resets WHERE token='$token' LIMIT 1";
      $results = mysqli_query($db, $sql);
      $email = mysqli_fetch_assoc($results)['email'];

      if ($email) {
        $password = md5($password);
        $sql = "UPDATE records SET password='$password' WHERE email='$email'";
        $results = mysqli_query($db, $sql);
        echo json_encode(array('success' => 'Password Reset Successful!'));
      }
    }
      
  
      
    }
  ?>