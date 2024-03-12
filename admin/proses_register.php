<?php
session_start();

// Check if the admin is logged in, else redirect to login page
if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

// Check if registration data is present
if (!isset($_SESSION['registration_data'])) {
    header('Location: ../login.php');
    exit();
}

// Database connection parameters
$hostname = "localhost";
$username = "root";
$password = "";
$database = "uts_webprog_lec";

// Create connection
$conn = new mysqli($hostname,$username,$password,$database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare statement for inserting data
$stmt = $conn->prepare("INSERT INTO users (email, password, nama, alamat, jenis_kelamin, tanggal_lahir, bukti) VALUES ($email, $password, $nama, $alamat, $jenis_kelamin, $tanggal_lahir, $bukti)");

if ($stmt === false) {
    die("Failed to prepare statement: " . $conn->error);
}

// Bind parameters
$stmt->bind_param("sssssss", 
    $_SESSION['registration_data']['email'],
    $_SESSION['registration_data']['name'],
    $_SESSION['registration_data']['alamat'],
    $_SESSION['registration_data']['jenis_kelamin'],
    $_SESSION['registration_data']['tanggal_lahir'],
    $_SESSION['registration_data']['bukti']
);

// Execute query
if (!$stmt->execute()) {
    die("Failed to execute query: " . $stmt->error);
}

// Close statement and connection
$stmt->close();
$conn->close();

// Clear registration data from session
unset($_SESSION['registration_data']);

header('Location: ../login.php');
exit();
?>