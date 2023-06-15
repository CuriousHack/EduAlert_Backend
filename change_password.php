<?php 

include "./config.php";

if($_SERVER['REQUEST_METHOD'] == "POST"){
    $jsonData = file_get_contents("php://input");
    $data = json_decode($jsonData);
    $token = $data->token;

    include_once "./auth.php";

    $old_pass = mysqli_real_escape_string($db, $data->old_password);
    $new_pass = mysqli_real_escape_string($db, $data->new_password);
    $con_pass = mysqli_real_escape_string($db, $data->confirm_password);

    //check if values are entered
  if(empty($old_pass) || empty($new_pass) || empty($con_pass)){
      http_response_code(400);
    echo json_encode(array('error' => 'All fields are required!'));
    exit();
  }
  //check if new password and old password are the same
  if($new_pass === $old_pass){
      http_response_code(400);
    echo json_encode(array('error' => 'Old and New Password cannot be the same!'));
    exit();
  }
  //check if new password and confirm password are the same
  if($new_pass !== $con_pass){
      http_response_code(400);
    echo json_encode(array('error' => 'Both Password and Confirm Password Must be the same!'));
    exit();
  }
  //check if old password is correct
  $old_pass = md5($old_pass);
  $query = "SELECT * FROM records WHERE email = '$email'";
    $result = mysqli_query($db, $query);
    if(mysqli_num_rows($result) == 1){
      $row = mysqli_fetch_array($result);
    if($row['password'] !== $old_pass){
        http_response_code(400);
      echo json_encode(array('error' => 'Old Password not correct!'));
    }
    else{
      $new_pass = md5($new_pass);
      $sql = "UPDATE records SET password='$new_pass' WHERE email='$email'";
      $results = mysqli_query($db, $sql);
      if(!$results){
          http_response_code(400);
        echo json_encode(array('failed' => 'Password not Updated'));
        exit();
      }
      http_response_code(200);
      echo json_encode(array('success' => 'Password Updated'));
    }
  }
  
}

?>