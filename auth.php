<?php

//Authentication of JWT token
require "./vendor/autoload.php";
use \Firebase\JWT\JWT;
use Firebase\JWT\Key;

    $secret_key = "eDU_aLERT";

try {
    $decodedToken = JWT::decode($token, new Key($secret_key, 'HS256'));
    // Access the decoded token data
    $userId = $decodedToken->data->id;
    $email = $decodedToken->data->email;
    
    // Perform additional authorization checks or data retrieval if needed
} catch (Exception $e) {
    if ($e instanceof \Firebase\JWT\ExpiredException) {
        // Handle expired token
        echo json_encode(array('error' => 'Token Expired'));
        exit();
        // Perform additional actions like redirecting to login or refresh token endpoint
    } else {
        // Handle invalid token
        echo json_encode(array('error' => 'Access Denied'));
        exit();
        // Perform additional actions like redirecting to login or returning an appropriate response
    }
}

?>