<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
</head>
<body>
    <?php
    // Include the database connection file
    try {
        if (isset($_POST['reset_password'])) {
            // Connect to the database
            $conn = new mysqli("localhost", "root", "", "uts_webprog_lec");

            // Check connection
            if ($conn->connect_error) {
                throw new Exception("Connection failed: " . $conn->connect_error);
            }

            $email = $conn->real_escape_string($_POST['email']);
            $new_password = $conn->real_escape_string($_POST['new_password']);
            $confirm_password = $conn->real_escape_string($_POST['confirm_password']);

            // Check if passwords match
            if ($new_password != $confirm_password) {
                throw new Exception("Passwords do not match.");
            }

            // Update the user's password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            if (!$conn->query("UPDATE users SET password='$hashed_password' WHERE email='$email'")) {
                throw new Exception("Failed to execute the SQL statement: " . $conn->error);
            }

            echo "Your password has been reset successfully!";
        }
    } catch (Exception $e) {
        // Handle the exception
        echo "Error: " . $e->getMessage();
    }
    ?>

    <form action="reset_password.php" method="post">
        <input type="email" name="email" placeholder="Enter your email address" required>
        <input type="password" name="new_password" placeholder="Enter new password" required>
        <input type="password" name="confirm_password" placeholder="Confirm new password" required>
        <button type="submit" name="reset_password">Reset Password</button>
    </form>
    <a href="login.php"><button>Login</button></a>
</body>
</html>
