<?php

include "./config.php";

if($_SERVER['REQUEST_METHOD'] == "POST"){
  $jsonData = file_get_contents("php://input");
    $data = json_decode($jsonData);

    $fullname = mysqli_real_escape_string($db, $data->fullname);
    $id_num = mysqli_real_escape_string($db, $data->user_id);
    $email = mysqli_real_escape_string($db, $data->email);
    $phone = $data->phone;
    $password = mysqli_real_escape_string($db,$data->password);
    $cpassword = mysqli_real_escape_string($db,$data->cpassword);
    $access = "staff";
    $status = 0;

    //validating the user input
    if(empty($fullname) || empty($id_num) || empty($email) || empty($phone) || empty($password) || empty($cpassword)){
        echo json_encode(array('error' => 'All Fields are Required!'));
        exit();
    }
    //check if the two password matches(Case sensitivity included)
    if($password !== $cpassword){
        echo json_encode(array('error' => 'Both Password do not Match!'));
        exit();
    }
    //check if user already exist
    $user_check = "SELECT * FROM records where id_num='$id_num' or email='$email' LIMIT 1";
  $result = mysqli_query($db, $user_check);
  $user = mysqli_fetch_assoc($result);

  if($user){
    if($user['id_num'] === $id_num){
      echo json_encode(array('error' => 'Staff ID Already Exist!'));
      exit();
    }
    if($user['email'] === $email){
      echo json_encode(array('error' => 'Email Already Exist!'));
      exit();
    }
  }
  //finally register the user if there are no error
    $password = md5($password);
    $query = "INSERT INTO records (fullname, id_num, email, phone, password, access_level, user_status) VALUES ('$fullname', '$id_num', '$email', '$phone', '$password', '$access', '$status')";
    mysqli_query($db, $query);
    $_SESSION['user_id'] = $id_num;
    $_SESSION['fullname'] = $fullname;
    echo json_encode(array('success' => 'Registration Successful! Your Account is Under Review.'));
}

?>