<?php

$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$database = "covid19";

$conn = new mysqli($servername, $dbusername, $dbpassword, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
 
?>