<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "barangay-relief";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Database connection failed: ".
    $conn->connect_error);
}
?>