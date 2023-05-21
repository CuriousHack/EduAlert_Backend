<?php

//localhost server connection

$server = "localhost";
$username = "root";
$password = "";
$database = "edualert";

//hosted database connection
// $server = "sql313.epizy.com";
// $username = "epiz_34249959";
// $password = "a7taOkP5UFRm5Nd";
// $database = "epiz_34249959_edualert";

$db = mysqli_connect($server, $username, $password, $database);
    if(!$db){
        die("connection failed" . mysqli_connect_error());
    }
    ?>