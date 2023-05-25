<?php
include "./config.php";

if($_SERVER['REQUEST_METHOD'] == "GET"){
    if(!isset($_SESSION['username'])){
    echo json_encode(array('error' => 'You Are Not Logged In!'));
}
else{
    //sendReply(400, "You're not Logged In!");
    unset($_SESSION['username']);
    session_destroy();
    //sendReply(200, "You have been Logged Out!");
    echo json_encode(array('success' => 'You have been Logged Out!'));
}
}
?>