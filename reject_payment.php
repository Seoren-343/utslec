<?php
// reject_payment.php
session_start();

if (!isset($_SESSION["id"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit();
}

// Include the database connection file
include("db_config.php");

// Get the payment ID from the URL
$id = $_GET["id"];

// Update the status of the payment to 'rejected'
$query = "UPDATE savings SET status = 'rejected' WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: verifikasi_admin.php");
exit();
?>
