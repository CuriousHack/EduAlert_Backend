<?php
$db = mysqli_connect("localhost", "root", "", "edualert");
    if(!$db){
        die("connection failed" . mysqli_connect_error());
    }
    ?>