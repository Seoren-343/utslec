<?php
include("session_functions.php");
try {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST["email"];
        $password = $_POST["password"];
        $confirm_password = $_POST["confirm_password"];
        $name = $_POST["name"];
        $address = $_POST["address"];
        $gender = $_POST["gender"];
        $birthdate = $_POST["birthdate"];
        $proof_of_payment = $_POST["proof_of_payment"];
        include("db_config.php");

        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO savings (total_savings, pokok, wajib, sukarela) VALUES (0, 0, 0, 0)";

        if ($conn->query($query) !== TRUE) {
            throw new Exception("Failed to execute the SQL statement: " . $conn->error);
        }
        $savings_id = $conn->insert_id;
        $query = "INSERT INTO users (email, password, name, address, gender, birthdate, proof_of_payment, roles, savings_id)
                  VALUES ('$email', '$hashed_password', '$name', '$address', '$gender', '$birthdate', '$proof_of_payment', 'nasabah', $savings_id)";

        if ($conn->query($query) !== TRUE) {
            throw new Exception("Failed to execute the SQL statement: " . $conn->error);
        }

        echo "Registration successful";

        $conn->close();
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="register.css">
    <title>Registration</title>
<body>
<div class="container">
        <h2>Registration Form</h2>
        <form method="post" action="">
            <label for="email">Email:</label>
            <input type="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" name="password" required>

            <label for="confirm_password">Confirm Password:</label>
            <input type="password" name="confirm_password" required>

            <label for="name">Name:</label>
            <input type="text" name="name" required>

            <label for="address">Address:</label>
            <input type="text" name="address" required>

            <label for="gender">Gender:</label>
            <select name="gender" required>
                <option value="male">Male</option>
                <option value="female">Female</option>
            </select>

            <label for="birthdate">Date of Birth:</label>
            <input type="date" name="birthdate" required>

            <label for="proof_of_payment">Proof of Payment:</label>
            <input type="file" name="proof_of_payment" accept="image/*" required>

            <div class="g-recaptcha" data-sitekey="6LeydpQpAAAAABDQiYoztJxiWhJZurUr9fJ8MYz8"></div>

            <input type="submit" value="Register">
            <button onclick="window.location.href='login.php'">Login</button>
        </form>
    </div>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</body>
</html>