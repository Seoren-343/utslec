<?php
session_start();

// Unset the error variable
if (isset($_SESSION['error'])) {
    unset($_SESSION['error']);
}

function authenticate($username, $password) {
    // Replace with actual database credentials
    $servername = "localhost";
    $dbusername = "username";
    $dbpassword = "password";
    $dbname = "database";

    // Create connection
    $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare statement
    $stmt = $conn->prepare("SELECT role FROM users WHERE username = ? AND password = ?");
    if ($stmt === false) {
        die("Failed to prepare statement: " . $conn->error);
    }

    // Bind parameters
    if (!$stmt->bind_param("ss", $username, $password)) {
        die("Failed to bind parameters: " . $stmt->error);
    }

    // Execute query
    if (!$stmt->execute()) {
        die("Failed to execute query: " . $stmt->error);
    }

    // Bind result
    $stmt->bind_result($role);

    // Fetch result
    $stmt->fetch();

    // Close statement and connection
    $stmt->close();
    $conn->close();

    // Return role if exists, false otherwise
    return $role ? $role : false;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $captcha = $_POST['g-recaptcha-response'];

    // Verify the reCAPTCHA response
    $secretKey = "6LeydpQpAAAAAASkyMhjPC8arFvGeZLyv9IivPRt"; // Replace with your secret key
    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$captcha");
    $responseKeys = json_decode($response, true);

    if (empty($username) || empty($password)) {
        $error = 'Username and password are required.';
    } elseif (!$responseKeys["success"]) {
        $error = 'Invalid reCAPTCHA. Please try again.';
    } else {
        $role = authenticate($username, $password);
        if (!$role) {
            $error = 'Invalid username or password.';
        } else {
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $role;
            if ($role == 'admin') {
                header('Location: admin/admin.php');
            } else {
                header('Location: user/home.php');
            }
            exit();
        }
    }
    // Store the error message in the session
    $_SESSION['error'] = $error;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script>
    function validateForm() {
        var username = document.getElementById('username').value;
        var password = document.getElementById('password').value;
        if (username == "" || password == "") {
            alert("Username and password are required.");
            return false;
        }
    }
    </script>
</head>
<body>
    <form method="post" action="" onsubmit="return validateForm()">
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username"><br>
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password"><br>
        <div class="g-recaptcha" data-sitekey="6LeydpQpAAAAABDQiYoztJxiWhJZurUr9fJ8MYz8"></div> <!-- Replace with your site key -->
        <input type="submit" value="Login">
    </form>
    <?php if (!empty($_SESSION['error'])): ?>
        <p><?php echo $_SESSION['error']; ?></p>
    <?php endif; ?>
</body>
</html>
