<?php 
include "./config.php";

if($_SERVER['REQUEST_METHOD'] == "PUT"){
    $jsonData = file_get_contents("php://input");
    $data = json_decode($jsonData);
    $token = $data->token;
    include_once "./auth.php";
    //get lecturer ID from URL
    $id = $_GET['id'];
    //check id is sent with request
  if(empty($id)){
    http_response_code(400);
    echo json_encode(array('error' => 'Unable to fetch ID!'));
    exit();
  }
  else{
    $sql = "UPDATE records SET user_status = 1 WHERE id ='$id'";
    $results = mysqli_query($db, $sql);
    if(!$results){
        http_response_code(400);
      echo json_encode(array('failed' => 'Unable to Approve Lecturer'));
      exit();
    }
    http_response_code(400);
    echo json_encode(array('success' => 'Lecturer Approved'));
  }
}
?>