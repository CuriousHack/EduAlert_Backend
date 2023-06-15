<?php
include "./config.php";
require "./vendor/autoload.php";

if($_SERVER['REQUEST_METHOD'] == "POST"){
    $jsonData = file_get_contents("php://input");
    $data = json_decode($jsonData);
    $token = $data->token;
//check token authentication
    include_once "./auth.php";
    $course_title = mysqli_real_escape_string($db, $data->title);
    $course_code = mysqli_real_escape_string($db, $data->code);
    $assigned_staff = mysqli_real_escape_string($db, $data->lecturer);

    if(empty($course_title) || empty($course_code) || empty($assigned_staff)){
        http_response_code(400);
        echo json_encode(array('error' => "All Fields are required"));
        exit();
    }
    //check if course already exist
    $query = "SELECT * FROM courses WHERE course_code = '$course_code' OR course_title = '$course_title 'LIMIT 1";
    $request = mysqli_query($db, $query);
    $new_request = mysqli_fetch_assoc($request);
    if($new_request){
        if($new_request['course_code'] === $course_code){
            http_response_code(400);
            echo json_encode(array('error' => 'Course Code Already Exist!'));
            exit();
        }
        if($new_request['course_title'] === $course_title){
            http_response_code(400);
            echo json_encode(array('error' => "Course Title Already Exist"));
            exit();
        }
    }
    $sql = "INSERT INTO courses (course_code, course_title, staff) VALUES ('$course_code', '$course_title', '$assigned_staff')";
    $result = mysqli_query($db, $sql);
    if(!$result){
        http_response_code(400);
        echo json_encode(array('error' => "Unable to create course"));
        exit();
    }
    http_response_code(200);
    echo json_encode(array('success' => 'Course Created Successfully.'));

}
?>