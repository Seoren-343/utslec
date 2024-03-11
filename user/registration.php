<?php
session_start();

//Uncomment this code below when database is ready
//// Check if the user is not logged in 
//if (!isset($_SESSION['username'])) {
//    // Redirect to the login page
//    header('Location: ../login.php');
//    exit();
//} 

// Unset the error variable
if (isset($_SESSION['error'])) {
    unset($_SESSION['error']);
}

function register($email, $password, $name, $address, $gender, $birthdate, $payment_proof) {
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

    // TODO: Add your code to store the user data in a temporary storage,
    // and send a verification request to the admin

    // Close connection
    $conn->close();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $name = $_POST['name'];
    $address = $_POST['address'];
    $gender = $_POST['gender'];
    $birthdate = $_POST['birthdate'];
    $payment_proof = $_FILES['payment_proof'];
    $captcha = $_POST['g-recaptcha-response'];

    // Verify the reCAPTCHA response
    $secretKey = "6LeydpQpAAAAAASkyMhjPC8arFvGeZLyv9IivPRt"; // Replace with your secret key
    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$captcha");
    $responseKeys = json_decode($response, true);

    if (empty($email) || empty($password) || empty($confirm_password) || empty($name) || empty($address) || empty($gender) || empty($birthdate) || empty($payment_proof)) {
        $error = 'All fields are required.';
    } elseif ($password !== $confirm_password) {
        $error = 'Password and confirm password do not match.';
    } elseif (!$responseKeys["success"]) {
        $error = 'Invalid reCAPTCHA. Please try again.';
    } else {
        register($email, $password, $name, $address, $gender, $birthdate, $payment_proof);
        $error = 'Registration successful. Waiting for admin verification.';
    }

    // Store the error message in the session
    $_SESSION['error'] = $error;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registration</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script>
    $(document).ready(function() {
        $("form").on("submit", function(event) {
            event.preventDefault();

            $.ajax({
                url: 'register.php', // TODO: Replace with the URL of your PHP script
                type: 'post',
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function(response) {
                    // TODO: Add your code to handle the server response
                    console.log(response);
                    // Hide the submit button
                    $('input[type="submit"]').hide();
                    // Show the "Waiting for verification..." message
                    $('#verification').show();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                }
            });
        });
    });
    </script>
</head>
<body>
    <form method="post" action="" enctype="multipart/form-data">
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br>
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br>
        <label for="confirm_password">Confirm Password:</label><br>
        <input type="password" id="confirm_password" name="confirm_password" required><br>
        <label for="name">Nama:</label><br>
        <input type="text" id="name" name="name" required><br>
        <label for="address">Alamat:</label><br>
        <textarea id="address" name="address" required></textarea><br>
        <label for="gender">Jenis Kelamin:</label><br>
        <input type="radio" id="male" name="gender" value="male" required>
        <label for="male">Male</label><br>
        <input type="radio" id="female" name="gender" value="female" required>
        <label for="female">Female</label><br>
        <label for="birthdate">Tanggal Lahir:</label><br>
        <input type="date" id="birthdate" name="birthdate" required><br>
        <label for="payment_proof">Upload File Bukti Pembayaran Simpanan Pokok:</label><br>
        <input type="file" id="payment_proof" name="payment_proof" required><br>
        <div class="g-recaptcha" data-sitekey="your_site_key"></div> <!-- Replace with your site key -->
        <input type="submit" value="Register">
    </form>
    <p id="verification" style="display: none;">Waiting for verification...</p>
</body>
</html>