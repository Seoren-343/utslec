<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
</head>
<body>
    <form action="" method="post">
        <label for="email">Enter your email address:</label>
        <input type="email" id="email" name="email" required>
        <input type="submit" value="Reset Password">
    </form>
</body>
</html>

<?php
try {
    if (isset($_POST['email'])) {
        // Connect to the database
        $conn = new mysqli("localhost", "root", "", "uts_webprog_lec");

        // Check connection
        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }

        // Get the user's email
        $email = $conn->real_escape_string($_POST['email']);

        // Check if the email exists in the database
        if (!($result = $conn->query("SELECT * FROM users WHERE email='$email'"))) {
            throw new Exception("Failed to execute the SQL statement: " . mysqli_error($conn));
        }

        if ($result->num_rows > 0) {
            // Generate a random password reset token
            $token = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!$()*";
            $token = str_shuffle($token);
            $token = substr($token, 0, 10);

            // Store the token in the database
            if (!$conn->query("UPDATE users SET token='$token', 
                              tokenExpire=DATE_ADD(NOW(), INTERVAL 5 MINUTE)
                              WHERE email='$email'")) {
                throw new Exception("Failed to execute the SQL statement: " . mysqli_error($conn));
            }

            // Directly print the reset password link
            echo "
                A reset password link has been generated. Please click on the link below:<br>
                <a href='
                http://localhost/utslec/reset_password.php?email=$email&token=$token
                '>Reset Password</a><br><br>
            ";

            exit();
        } else {
            echo "Please check your inputs!";
        }
    }
} catch (Exception $e) {
    // Handle the exception
    echo "Error: " . $e->getMessage();
    exit();
}
?>
