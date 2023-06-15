<?php

//localhost server connection

// $server = "localhost";
// $username = "root";
// $password = "";
// $database = "edualert";

//hosted database connection
$server = "localhost:3306";
$username = "skinxski_edualert";
$password = "#Curious~98";
$database = "skinxski_edualert";

$db = mysqli_connect($server, $username, $password, $database);
    if(!$db){
        die("connection failed" . mysqli_connect_error());
    }
    ?>