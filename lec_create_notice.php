<?php

include "./config.php";
require "./vendor/autoload.php";
include "./smtp.php";

// send by  email
// Create a new PHPMailer instance
$mail->setFrom('akandelateef0@gmail.com', 'EduAlert');
$mail->addReplyTo('edualertsys@gmail.com');
$mail->isHTML(true);

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
    $time = time() + 3600;
    $date = date('Y-m-d H:i:s', $time);
    
    if(empty($institute) || empty($department) || empty($level) || empty($mode) || empty($subject) || empty($message)){
        http_response_code(400);
        echo json_encode(array('error' => "All Fields are required!"));
        exit();
    }
    $mail->Subject = "$subject";
    include_once "./auth.php";

    //get lecturer name from database
    $sql = "SELECT * FROM records WHERE email = '$email' LIMIT 1";
    $result= mysqli_query($db, $sql);
    $user = mysqli_fetch_assoc($result);
    $fullname = $user['fullname'];
    if(!$result){
        http_response_code(400);
        echo json_encode(array('error' => "unable to fetch user data"));
        exit();
    }
    $query = "INSERT INTO notice (author, message_subject, message_info, institute, department, stud_level, mode, date_created) VALUES ('$fullname', '$subject', '$message', '$institute', '$department', '$level', '$mode', '$date')";
    $create = mysqli_query($db, $query);
    if(!$create){
        http_response_code(400);
        echo json_encode(array('error' => "unable to create notice"));
    }
    else{
        //retrieve recipients from database
        $query = "SELECT email, fullname FROM records WHERE department = '$department' AND mode = '$mode' AND user_level = '$level'";
        $result = mysqli_query($db, $query);
        while ($user = mysqli_fetch_object($result)) {
        $recipientEmail = $user->email;
        $recipientName = $user->fullname;
        
        // get email from input
        $mail->clearAddresses();
        $mail->addAddress($recipientEmail, $recipientName);
        // HTML body
        
        $mail->Body = <<<HTML
        Hi $recipientName,<br> $message.
        HTML;
        if(!$mail->send()){
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid Email Address!'));
                exit();
          }
        }
        http_response_code(200);
        echo json_encode(array('success' => 'Notice created successfully!'));
    }
}
?>