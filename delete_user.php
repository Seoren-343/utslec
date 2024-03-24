<?php
include("session_functions.php");
try {
    include("db_config.php");

    $user_id = $_GET['id'];

    $query = "DELETE FROM savings WHERE user_id = $user_id";
    if (!($result = mysqli_query($conn, $query))) {
        throw new Exception("Failed to execute the SQL statement: " . mysqli_error($conn));
    }

    $query = "DELETE FROM users WHERE id = $user_id";
    if (!($result = mysqli_query($conn, $query))) {
        throw new Exception("Failed to execute the SQL statement: " . mysqli_error($conn));
    }
    header("Location: users_list_admin.php");
    exit();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    exit();
}
?>
