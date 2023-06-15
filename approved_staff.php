<?php
include "./config.php";
require "./vendor/autoload.php";
//require "./errors.php";
if($_SERVER['REQUEST_METHOD'] == "POST"){
    $jsonData = file_get_contents("php://input");
    $data = json_decode($jsonData);
    $token = $data->token;
    include_once "./auth.php";

    $query = "SELECT id,id_num, email, fullname FROM records WHERE access_level = 'staff' AND user_status = 1";
    $result = mysqli_query($db, $query);
        if (!$result) {
            http_response_code(400);
            echo json_encode(array('error' => 'Unable to fetch data!'));
            exit();
        }
    $users = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $users[] = $row;
    }
    http_response_code(200);
    echo json_encode(array('success' => $users));
}
?>