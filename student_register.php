<?php

include "./config.php";

if($_SERVER['REQUEST_METHOD'] == "POST"){
    $fullname = mysqli_real_escape_string($db, $_POST['fullname']);
    $matric = mysqli_real_escape_string($db, $_POST['user_id']);
    $institute = mysqli_real_escape_string($db,$_POST['institute']);
    $department = mysqli_real_escape_string($db,$_POST['department']);
    $level = mysqli_real_escape_string($db, $_POST['level']);
    $mode = mysqli_real_escape_string($db,$_POST['mode']);
    $phone = $_POST['phone'];
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $password = mysqli_real_escape_string($db,$_POST['password']);
    $cpassword = mysqli_real_escape_string($db,$_POST['cpassword']);
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