<?php

include "./config.php";

include_once './jwt_util.php';


if($_SERVER['REQUEST_METHOD'] == "POST"){
  $data = json_decode(file_get_contents("php://input", true));
  
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $password = mysqli_real_escape_string($db, $_POST['password']);
  if(empty($email) || empty($password)){
    echo json_encode(array('error' => 'All fields are required!'));
  }
  else{
  //if there is no error, check for authentication
    $password = md5($password);
    $query = "SELECT * FROM records WHERE email = '$email' AND password = '$password'";
    $result = mysqli_query($db, $query);
    if(mysqli_num_rows($result) == 1){
      $row = mysqli_fetch_array($result);
      $_SESSION['status'] = $row['user_status'];
      if($_SESSION['status'] == 0){
      echo json_encode(array('error' => 'Acount Not Verified, Check back Later!'));
      }else{
		
		$headers = array('alg'=>'HS256','typ'=>'JWT');
		$payload = array('id'=>$_SESSION['user_id'], 'exp'=>(time() + 60 * 60));

		$jwt = generate_jwt($headers, $payload);
    
//get logged in user details
    $login_data = array(
      "success"=> true,
      "message"=> "Login successfully!",
      "results" => array(
        "id"=> $row['id'],
        "fullname"=> $row['fullname'],
        "email"=> $row['email']
      ),
      "token"=> $jwt
    );
    echo json_encode(array($login_data));
    }
  }
    else{
      echo json_encode(array('error' => 'Incorrect Email or Password!'));

    }
  }
}

?>