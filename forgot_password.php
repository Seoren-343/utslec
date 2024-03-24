<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="forgot_password.css">
</head>
<body>
    <form action="" method="post">
        <label for="email">Enter your email address:</label>
        <input type="email" id="email" name="email" required>
        <input type="submit" value="Reset Password">
        <div id="resetPasswordLink"></div>
    </form>
    <a href="login.php"><button>Back</button></a>
</body>
</html>

<?php
include("session_functions.php");
try {
    if (isset($_POST['email'])) {
        $conn = new mysqli("localhost", "root", "", "uts_webprog_lec");

        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }

        $email = $conn->real_escape_string($_POST['email']);
        if (!($result = $conn->query("SELECT * FROM users WHERE email='$email'"))) {
            throw new Exception("Failed to execute the SQL statement: " . mysqli_error($conn));
        }

        if ($result->num_rows > 0) {
            $token = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!$()*";
            $token = str_shuffle($token);
            $token = substr($token, 0, 10);

            if (!$conn->query("UPDATE users SET token='$token', 
                              tokenExpire=DATE_ADD(NOW(), INTERVAL 5 MINUTE)
                              WHERE email='$email'")) {
                throw new Exception("Failed to execute the SQL statement: " . mysqli_error($conn));
            }
            echo "
                <script>
                    document.getElementById('resetPasswordLink').innerHTML = `
                        A reset password link has been generated. Please click on the link below:<br>
                        <a href='
                        http://localhost/utslec/reset_password.php?email=$email&token=$token
                        '>Reset Password</a><br><br>
                    `;
                </script>
            ";

            exit();
        } else {
            echo "Please check your inputs!";
        }
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    exit();
}
?>

