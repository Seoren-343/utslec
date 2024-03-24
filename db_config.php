<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "uts_webprog_lec";

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
