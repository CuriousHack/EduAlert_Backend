<?php
include "./db_connect.php";

$current_time = time();
$new_time = $current_time;
$new_datetime = date('Y-m-d H:i:s', $new_time);

$sql = "DELETE FROM `password_resets` WHERE `exp_time` <= '$new_datetime'";

mysqli_query($db, $sql);

?>;