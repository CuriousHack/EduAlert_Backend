<?php

include "./config.php";
require "./vendor/autoload.php";

if($_SERVER['REQUEST_METHOD'] == "POST"){
    $jsonData = file_get_contents("php://input");
    $data = json_decode($jsonData);

    $token = $data->token;

    include_once "./auth.php";
//fetch student record
    $sql = "SELECT * FROM records WHERE email = '$email' LIMIT 1";
    $result= mysqli_query($db, $sql);
    $user = mysqli_fetch_assoc($result);

   $institute = $user['institute'];
   $department = $user['department'];
   $level = $user['user_level'];
   $mode = $user['mode'];

   $query = "SELECT * FROM notice WHERE department = '$department'AND stud_level = '$level' AND mode = '$mode'";
   $notice_query = mysqli_query($db, $query);
   if(!$notice_query){
    echo json_encode(array('error' => 'Unable to retrieve notice'));
    exit();
   }
    while ($row = mysqli_fetch_array($notice_query)) {
    echo json_encode($row);
}
}