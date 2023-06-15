<?php

include "./config.php";

//enter new password

if($_SERVER['REQUEST_METHOD'] == "POST"){
  $jsonData = file_get_contents("php://input");
    $data = json_decode($jsonData);
    $password = mysqli_real_escape_string($db, $data->password);
    $cpassword = mysqli_real_escape_string($db, $data->cpassword);
  
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
    $token = $_GET['token'];
      
        // select email address of user from the password_reset table 
      $sql = "SELECT email FROM password_resets WHERE token='$token' LIMIT 1";
      $results = mysqli_query($db, $sql);
      $query = mysqli_fetch_assoc($results);
  
      if (!$query) {
        echo json_encode(array('error'=> 'Mail not sent'));
      }
      else{
        $email = $query['email'];
        $password = md5($password);
        $sql = "UPDATE records SET password='$password' WHERE email='$email'";
        $results = mysqli_query($db, $sql);
        if(!$results){
          echo json_encode(array('error' => 'Unable to complete request, please try again in few minutes.'));
        }
        else{
          echo json_encode(array('success' => 'Password Reset Successful!'));
        }
      }
        
}
  ?>