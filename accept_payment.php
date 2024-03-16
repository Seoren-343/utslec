<?php
// accept_payment.php
session_start();

try {
    if (!isset($_SESSION["id"]) || $_SESSION["role"] !== "admin") {
        header("Location: login.php");
        exit();
    }

    // Include the database connection file
    include("db_config.php");

    // Get the payment ID from the URL
    if (!isset($_GET["id"])) {
        throw new Exception("Payment ID is not set in the URL.");
    }
    $id = $_GET["id"];

    // Update the status of the payment to 'verified'
    $query = "UPDATE savings SET status = 'verified' WHERE id = ?";
    if (!($stmt = $conn->prepare($query))) {
        throw new Exception("Failed to prepare the SQL statement: " . $conn->error);
    }
    if (!$stmt->bind_param("i", $id)) {
        throw new Exception("Failed to bind parameters: " . $stmt->error);
    }
    if (!$stmt->execute()) {
        throw new Exception("Failed to execute the SQL statement: " . $stmt->error);
    }

    header("Location: verifikasi_admin.php");
    exit();
} catch (Exception $e) {
    // Handle the exception
    echo "Error: " . $e->getMessage();
    exit();
}
?>
