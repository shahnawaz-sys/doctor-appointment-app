<?php
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "Doc-Appointment";


$conn = mysqli_connect($servername, $username, $password, $dbname);

if ($conn) {
    // echo "connection is ok";
} else {
    echo "connection is faild";
}
