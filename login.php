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
    $query = "SELECT * FROM users WHERE name='$username' AND password='$password'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION["id"] = $row["id"];
        $_SESSION["username"] = $row["username"];
        $_SESSION["role"] = $row["roles"];

        // Redirect ke halaman sesuai peran (admin/nasabah)
        if ($_SESSION["role"] == "admin") {
            header("Location: admin_home.php");
        } else {
            header("Location: home_nasabah.php");
        }
    } else {
        $error = "Invalid username or password";
    }

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

        <input type="submit" value="Login">
    </form>

    <?php if (isset($error)) { echo "<p>$error</p>"; } ?>
</body>
</html>
