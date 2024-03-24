<?php
    include("session_functions.php");
    try {
        if (isset($_POST['reset_password'])) {
            $conn = new mysqli("localhost", "root", "", "uts_webprog_lec");
            if ($conn->connect_error) {
                throw new Exception("Connection failed: " . $conn->connect_error);
            }

            $email = $conn->real_escape_string($_POST['email']);
            $new_password = $conn->real_escape_string($_POST['new_password']);
            $confirm_password = $conn->real_escape_string($_POST['confirm_password']);

            if ($new_password != $confirm_password) {
                throw new Exception("Passwords do not match.");
            }
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            if (!$conn->query("UPDATE users SET password='$hashed_password' WHERE email='$email'")) {
                throw new Exception("Failed to execute the SQL statement: " . $conn->error);
            }

            echo "Your password has been reset successfully!";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="reset_password.css">
</head>
<body>
    <form action="" method="post">
        <label for="email">Enter your email address:</label>
        <input type="email" id="email" name="email" required>
        <input type="password" name="new_password" placeholder="Enter new password" required>
        <input type="password" name="confirm_password" placeholder="Confirm new password" required>
        <button type="submit" name="reset_password">Reset Password</button>
        <div class="login-button-container">
            <a href="login.php"><button>Login</button></a>
        </div>
    </form>
    <a href="forgot_password.php"><button>Back</button></a> 
</body>
</html>
