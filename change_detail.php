<?php
include("session_functions.php");
try {
    if (!isset($_SESSION["id"])) {
        header("Location: login.php");
        exit();
    }

    // Koneksi ke database
    // Include the database connection file
    include("db_config.php");

    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Get the user's id
    $user_id = $_SESSION["id"];

    // Check if the form is submitted
    if(isset($_POST["submit"])) {
        // Get the new value and the detail to be changed
        $newValue = '';
        $detail = '';

        if(isset($_POST["newEmail"])) {
            $newValue = $_POST["newEmail"];
            $detail = 'email';
        } elseif(isset($_POST["newName"])) {
            $newValue = $_POST["newName"];
            $detail = 'name';
        } elseif(isset($_POST["newAddress"])) {
            $newValue = $_POST["newAddress"];
            $detail = 'address';
        } elseif(isset($_POST["newGender"])) {
            $newValue = $_POST["newGender"];
            $detail = 'gender';
        } elseif(isset($_POST["newBirthdate"])) {
            $newValue = $_POST["newBirthdate"];
            $detail = 'birthdate';
        }

        // Escape the data
        $newValue = mysqli_real_escape_string($conn, $newValue);

        // Update the user data
        $query = "UPDATE users SET $detail = '$newValue' WHERE id = $user_id";
        if (!$conn->query($query)) {
            throw new Exception("Failed to execute the SQL statement: " . mysqli_error($conn));
        } else {
            $_SESSION["message"] = ucfirst($detail) . " changed successfully.";
        }
    }

    $conn->close();

    // Redirect back to the profile page
    header("Location: profile_nasabah.php");
    exit();
} catch (Exception $e) {
    // Handle the exception
    echo "Error: " . $e->getMessage();
    exit();
}
?>
