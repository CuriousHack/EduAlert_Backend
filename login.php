<?php

include "./config.php";

require "./vendor/autoload.php";
use \Firebase\JWT\JWT;


if($_SERVER['REQUEST_METHOD'] == "POST"){
    $jsonData = file_get_contents("php://input");
    $data = json_decode($jsonData);
 
    $email = mysqli_real_escape_string($db, $data->email);
    $password = mysqli_real_escape_string($db, $data->password);
  
  if(empty($email) || empty($password)){
    http_response_code(400);
    echo json_encode(array('error' => 'All fields are required!'));
  }
  else{
  //if there is no error, check for authentication
    $password = md5($password);
    $query = "SELECT * FROM records WHERE email = '$email' AND password = '$password'";
    $result = mysqli_query($db, $query);
    if(mysqli_num_rows($result) == 1){
      $row = mysqli_fetch_array($result);
      $status = $row['user_status'];
      if($status == 0){
        http_response_code(400);
      echo json_encode(array('error' => 'Acount Not Verified, Check back Later!'));
      }else{
        //generate JWT token
        $iss = "Edualert";
        $iat = time();
        $nbf = $iat + 10;
        $exp = $iat + 3600;
        $aud = "Edualert_users";

        $secret_key = "eDU_aLERT";
        $payload = array(
            "iss" => "Edualert",
            "iat" => $iat,
            "nbf" => $nbf,
            "exp" => $exp,
            "aud" => $aud,
            "data" => array(
                "id" => $row['id'],
                "email" => $row['email']
        ));
        $jwt = JWT::encode($payload, $secret_key, "HS256");
    //get for logged in user role
    if($row['access_level'] == "student"){
      //get logged in user details
    $login_data = array(
      "success"=> true,
      "message"=> "Login successfully!",
      "results" => array(
        "id"=> $row['id'],
        "fullname"=> $row['fullname'],
        "matric" => $row['id_num'],
        "institute" => $row['institute'],
        "department" => $row['department'],
        "level" => $row['user_level'],
        "phone" => $row['phone'],
        "email"=> $row['email'],
        "access_level"=> $row['access_level']
      ),
      "token"=> $jwt
    );
    }elseif ($row['access_level'] == "staff") {
      //get logged in staff details
    $login_data = array(
      "success"=> true,
      "message"=> "Login successfully!",
      "results" => array(
        "id"=> $row['id'],
        "fullname"=> $row['fullname'],
        "reg_num" => $row['id_num'],
        "email"=> $row['email'],
        "phone" => $row['phone'],
        "access_level"=> $row['access_level']
      ),
      "token"=> $jwt
    );
    }else{
    //get logged in details of other users
    $login_data = array(
      "success"=> true,
      "message"=> "Login successfully!",
      "results" => array(
        "id"=> $row['id'],
        "fullname"=> $row['fullname'],
        "email"=> $row['email'],
        "access_level"=> $row['access_level']
      ),
      "token"=> $jwt
    );
  }
    http_response_code(200);
    echo json_encode(array($login_data));
    }
  }
    else{
      http_response_code(400);
      echo json_encode(array('error' => 'Incorrect Email or Password!'));

    }
  }
}

?>