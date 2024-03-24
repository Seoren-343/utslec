<?php
include("session_functions.php");
try {
    if (!isset($_SESSION["id"])) {
        header("Location: login.php");
        exit();
    }
    include("db_config.php");

    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    $user_id = $_SESSION["id"];
    if(isset($_POST["submit"])) {
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
        $newValue = mysqli_real_escape_string($conn, $newValue);
        $query = "UPDATE users SET $detail = '$newValue' WHERE id = $user_id";
        if (!$conn->query($query)) {
            throw new Exception("Failed to execute the SQL statement: " . mysqli_error($conn));
        } else {
            $_SESSION["message"] = ucfirst($detail) . " changed successfully.";
        }
    }
    $conn->close();
    header("Location: profile_nasabah.php");
    exit();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    exit();
}
?>
