<?php
include("session_functions.php");
try {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST["username"];
        $password = $_POST["password"];

        // Validasi dan sanitasi input

        // Include the database connection file
        include("db_config.php");

        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }

        // Query untuk mengecek login
        $stmt = $conn->prepare("SELECT * FROM users WHERE name=?");
        if (!$stmt) {
            throw new Exception("Failed to prepare the SQL statement: " . $conn->error);
        }
        if (!$stmt->bind_param("s", $username)) {
            throw new Exception("Failed to bind parameters: " . $stmt->error);
        }
        if (!$stmt->execute()) {
            throw new Exception("Failed to execute the SQL statement: " . $stmt->error);
        }
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password']) || $password == $row['password']) { //dont forget to remove the || in here to maximize security
                // Password is correct, now start the session
                $_SESSION["id"] = $row["id"];
                $_SESSION["username"] = $row["name"];
                $_SESSION["role"] = $row["roles"];
                $_SESSION["savings_id"] = $row["savings_id"]; // Store the savings_id in the session

                // Redirect ke halaman sesuai peran (admin/nasabah)
                if ($_SESSION["role"] == "admin") {
                    header("Location: home_admin.php");
                } else {
                    header("Location: home_nasabah.php");
                }
            } else {
                echo "Invalid password.";
            }
        } else {
            // Redirect non-registered users to register.php
            header("Location: register.php");
        }

        $stmt->close();
        $conn->close();
    }
} catch (Exception $e) {
    // Handle the exception
    echo "Error: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login.css">
    <title>Login</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script>
        function validateForm() {
            var username = document.forms["loginForm"]["username"].value;
            var password = document.forms["loginForm"]["password"].value;
            var recaptcha = grecaptcha.getResponse();
            if (username == "" || password == "" || recaptcha.length == 0) {
                alert("All fields must be filled out");
                return false;
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <div class="login-box">
            <h2>Login</h2>
            <form name="loginForm" method="post" action="" onsubmit="return validateForm()">
                <label for="username">Username:</label>
                <input type="text" name="username" required>
                <label for="password">Password:</label>
                <input type="password" name="password" required>
                <a href="forgot_password.php">Forgot Password?</a>
                <div class="g-recaptcha" data-sitekey="6LeydpQpAAAAABDQiYoztJxiWhJZurUr9fJ8MYz8"></div> <!-- Replace with your site key -->
                <input type="submit" value="Login">
            </form>
            <p>New User? <a href="register.php">Register here</a></p>
            <?php if (isset($error)) { echo "<p>$error</p>"; } ?>
        </div>
    </div>
</body>
</html>

