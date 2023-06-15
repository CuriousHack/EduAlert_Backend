<?php
include './config.php';
$query = "SELECT COUNT(*) AS total_users FROM records";
$result = mysqli_query($db, $query);
if (!$result) {
    die('Query failed: ' . mysqli_error($connection));
}

$row = mysqli_fetch_assoc($result);
$totalUsers = $row['total_users'];

$response = [
    'total_users' => $totalUsers
];

$responseJson = json_encode($response);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

echo $responseJson;

?>






