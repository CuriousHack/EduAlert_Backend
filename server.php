<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
include_once "./db_connect.php";
include_once "./errors.php";

session_start();

$errors = array();

if($_SERVER[REQUEST_METHOD] == "POST" && isset($_POST[reg_staff]))
register_staff($db);

if($_SERVER[REQUEST_METHOD] == "POST" && isset($_POST[reg_user]))
register_user($db);

if($_SERVER[REQUEST_METHOD] == "POST" && isset($_POST[login]))
login($db);

if($_SERVER[REQUEST_METHOD] == "GET" && isset($_POST[logout]))
logout($db);



function login($db){
$email = mysqli_real_escape_string($db, $_POST['email']);
$password = mysqli_real_escape_string($db, $_POST['password']);
  //check if truly a value is entered
  if(empty($email) || empty($password)){
    sendReply(400, "All fields are required!");
  }
  //if there is no error, check for authentication
    $password = md5($password);
    $query = "SELECT * FROM staff_record s, user_record u WHERE '$email' = s.email AND '$password' = s.password OR u.email = '$email' AND u.password = '$password'";
    $result = mysqli_query($db, $query);
    if(mysqli_num_rows($result) == 1){
      $row = mysqli_fetch_array($result);
      $_SESSION['user_id'] = $row['id'];
      #$_SESSION['logged_in'] = true;
      $_SESSION['username'] = $row['fullname'];
      sendReply(200, "Welcome ". $_SESSION['username']);
    }
    else{
      sendReply(400, "Incorrect Email or Password!");
    }

}
function register_staff($db){}
function register_user($db){}
function logout($db){
    if(!isset($_SESSION['username']))
    sendReply(400, "You're not Logged In!");
    unset($_SESSION['username']);
    session_destroy();
    sendReply(200, "You have been Logged Out!");

}

?>