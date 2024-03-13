<?php
// Implement login logic here
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validasi dan sanitasi input

    // Koneksi ke database
    $conn = new mysqli("localhost", "root", "", "uts_webprog_lec");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Query untuk mengecek login
    $stmt = $conn->prepare("SELECT * FROM users WHERE name=? AND password=?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Verify reCAPTCHA response
        $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
        $recaptcha_secret = '6LeydpQpAAAAAASkyMhjPC8arFvGeZLyv9IivPRt'; // Replace with your secret key
        $recaptcha_response = $_POST['g-recaptcha-response'];

        // Make and decode POST request
        $recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
        $recaptcha = json_decode($recaptcha);

        // Check if reCAPTCHA is valid
        if ($recaptcha->success) {
            $row = $result->fetch_assoc();
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
            $error = "Invalid reCAPTCHA verification";
        }
    } else {
        // Redirect non-registered users to register.php
        header("Location: register.php");
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<!-- Login form HTML structure -->
<body>
    <form method="post" action="">
        <label for="username">Username:</label>
        <input type="text" name="username" required>

        <label for="password">Password:</label>
        <input type="password" name="password" required>

        <!-- Add reCAPTCHA widget -->
        <div class="g-recaptcha" data-sitekey="6LeydpQpAAAAABDQiYoztJxiWhJZurUr9fJ8MYz8"></div> <!-- Replace with your site key -->

        <input type="submit" value="Login">
    </form>

    <!-- Add "New User?" sign and "Register here" link -->
    <p>New User? <a href="register.php">Register here</a></p>

    <?php if (isset($error)) { echo "<p>$error</p>"; } ?>

    <!-- Include reCAPTCHA script -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</body>
</html>
