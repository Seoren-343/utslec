<?php

try {
    // Include the database connection file
    include("db_config.php");

    // Get the user id from the URL
    $user_id = $_GET['id'];

    // Delete the user data from the savings table
    $query = "DELETE FROM savings WHERE user_id = $user_id";
    if (!($result = mysqli_query($conn, $query))) {
        throw new Exception("Failed to execute the SQL statement: " . mysqli_error($conn));
    }

    // Delete the user data from the users table
    $query = "DELETE FROM users WHERE id = $user_id";
    if (!($result = mysqli_query($conn, $query))) {
        throw new Exception("Failed to execute the SQL statement: " . mysqli_error($conn));
    }

    // Redirect back to the users list
    header("Location: users_list_admin.php");
    exit();
} catch (Exception $e) {
    // Handle the exception
    echo "Error: " . $e->getMessage();
    exit();
}
?>
