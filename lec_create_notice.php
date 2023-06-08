<?php

include "./config.php";
require "./vendor/autoload.php";

if($_SERVER['REQUEST_METHOD'] == "POST"){
    $jsonData = file_get_contents("php://input");
    $data = json_decode($jsonData);

    //get data from input
    $token = $data->token;
    $institute = mysqli_real_escape_string($db, $data->institute);
    $department = mysqli_real_escape_string($db, $data->department);
    $level = mysqli_real_escape_string($db, $data->level);
    $mode = mysqli_real_escape_string($db, $data->mode);
    $subject = mysqli_real_escape_string($db, $data->subject);
    $message = mysqli_real_escape_string($db, $data->message);
    $time = time() - 3600;
    $date = date('Y-m-d H:i:s', $time);

    include_once "./auth.php";

    //get lecturer name from database
    $sql = "SELECT * FROM records WHERE email = '$email' LIMIT 1";
    $result= mysqli_query($db, $sql);
    $user = mysqli_fetch_assoc($result);
    $fullname = $user['fullname'];
    if(!$result){
        echo json_encode(array('error' => "unable to fetch user data"));
        exit();
    }
    $query = "INSERT INTO notice (author, message_subject, message_info, institute, department, stud_level, mode, date_created) VALUES ('$fullname', '$subject', '$message', '$institute', '$department', '$level', '$mode', '$date')";
    $create = mysqli_query($db, $query);
    if(!$create){
        echo json_encode(array('error' => "unable to create notice"));
    }
    else{
        echo json_encode(array('success' => "Notice created successfully!"));
    }
}