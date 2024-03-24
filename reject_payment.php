<?php
include("session_functions.php");
try {
    if (!isset($_SESSION["id"]) || $_SESSION["role"] !== "admin") {
        header("Location: login.php");
        exit();
    }
    include("db_config.php");
    if (!isset($_GET["id"])) {
        throw new Exception("Payment ID is not set in the URL.");
    }
    $id = $_GET["id"];
    $query = "UPDATE savings SET status = 'rejected' WHERE id = ?";
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
    echo "Error: " . $e->getMessage();
    exit();
}
?>
