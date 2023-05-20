<?php

include "./config.php";

if($_SERVER['REQUEST_METHOD'] == "POST"){
    $fullname = mysqli_real_escape_string($db, $_POST['fullname']);
    $id_num = mysqli_real_escape_string($db, $_POST['user_id']);
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $phone = $_POST['phone'];
    $password = mysqli_real_escape_string($db,$_POST['password']);
    $cpassword = mysqli_real_escape_string($db,$_POST['cpassword']);
    $access = "staff";
    $status = 0;

    //validating the user input
    if(empty($fullname) || empty($id_num) || empty($email) || empty($phone) || empty($password) || empty($cpassword)){
        sendReply(400, "All Fields are required");
    }
    //check if the two password matches(Case sensitivity included)
    if($password !== $cpassword){
        sendReply(400, "Both password do not match");
    }
    //check if user already exist
    $user_check = "SELECT * FROM records where id_num='$id_num' or email='$email' LIMIT 1";
  $result = mysqli_query($db, $user_check);
  $user = mysqli_fetch_assoc($result);

  if($user){
    if($user['id_num'] === $id_num){
      sendReply(400, "Staff ID already exist");
    }
    if($user['email'] === $email){
      sendReply(400, "Email already exist");
    }
  }
  //finally register the user if there are no error
    $password = md5($password);
    $query = "INSERT INTO records (fullname, id_num, email, phone, password, access_level, user_status) VALUES ('$fullname', '$id_num', '$email', '$phone', '$password', '$access', '$status')";
    mysqli_query($db, $query);
    $_SESSION['user_id'] = $id_num;
    $_SESSION['fullname'] = $fullname;
    sendReply(200, "Registration Successful! Your Account is Under Review.");
}

?>