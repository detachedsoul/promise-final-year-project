<?php

function dbConnect() {
    $server = "localhost";
    $username = "root";
    $password = "";
    $database = "complaint_form";

    $conn = mysqli_connect($server, $username, $password, $database);
    if(!$conn){
        die("Connection failed: " . mysqli_connect_error());
    }

    return $conn;
}
