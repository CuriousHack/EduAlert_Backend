<?php
include "./config.php";

if($_SERVER['REQUEST_METHOD'] == "GET"){
    if(!isset($_SESSION['username']))
    sendReply(400, "You're not Logged In!");
    unset($_SESSION['username']);
    session_destroy();
    sendReply(200, "You have been Logged Out!");
}

?>