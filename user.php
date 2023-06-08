<?php

include "./config.php";
require "./vendor/autoload.php";

if($_SERVER['REQUEST_METHOD'] == "POST"){
    $jsonData = file_get_contents("php://input");
    $data = json_decode($jsonData);

    $token = $data->token;

    include_once "./auth.php";
    

    $login_data = array(
        "success"=> true,
        "message"=> "user data fetched successfully!",
        "results" => array(
          "id"=> $userId,
          "email"=> $email
        ),
        "token"=> $token
      );
      echo json_encode(array($login_data));
}



?>