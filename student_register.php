<?php

include "./config.php";

if($_SERVER['REQUEST_METHOD'] == "POST"){
  $jsonData = file_get_contents("php://input");
    $data = json_decode($jsonData);
 
    $fullname = mysqli_real_escape_string($db, $data->fullname);
    $matric = mysqli_real_escape_string($db, $data->user_id);
    $institute = mysqli_real_escape_string($db,$data->institute);
    $department = mysqli_real_escape_string($db,$data->department);
    $level = mysqli_real_escape_string($db, $data->level);
    $mode = mysqli_real_escape_string($db,$data->mode);
    $phone = $data->phone;
    $email = mysqli_real_escape_string($db, $data->email);
    $password = mysqli_real_escape_string($db, $data->password);
    $cpassword = mysqli_real_escape_string($db, $data->cpassword);
    $access = "student";
    $status = 1;

    //validating the user input
    if(empty($fullname) || empty($matric) || empty($institute) || empty($department) || empty($level) || empty($mode)
    || empty($phone) || empty($email) || empty($password) || empty($cpassword)){
        echo json_encode(array('error' => 'All Fields are Required!'));
        exit();
    }
    //check if the two password matches(Case sensitivity included)
    else if($password !== $cpassword){
        echo json_encode(array('error' => 'Both Password do not Match!'));
        exit();
    }
    //check if user already exist
    $user_check = "SELECT * FROM records where id_num='$matric' or email='$email' LIMIT 1";
  $result = mysqli_query($db, $user_check);
  $user = mysqli_fetch_assoc($result);

  if($user){
    if($user['id_num'] === $matric){
      echo json_encode(array('error' => 'Matric Number Already Exist!'));
        exit();
    }
    if($user['email'] === $email){
      echo json_encode(array('error' => 'Email Already Exist!'));
        exit();
    }
  }
  //finally register the user if there are no error
    $password = md5($password);
    $query = "INSERT INTO records (fullname, id_num, institute, department, user_level, mode, phone, email, password, access_level, user_status) VALUES ('$fullname', '$matric', '$institute', '$department', '$level', '$mode', '$phone', '$email', '$password', '$access', '$status')";
    mysqli_query($db, $query);
    $_SESSION['user_id'] = $matric;
    $_SESSION['fullname'] = $fullname;
    echo json_encode(array('success' => 'Registration Successful!'));
}

?>