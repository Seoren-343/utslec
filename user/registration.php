<?php
session_start();

// Check if the user is already logged in, redirect to home page
if (isset($_SESSION['username'])) {
    header('Location: user/home.php');
    exit();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    $name = $_POST['nama'];
    $address = $_POST['alamat'];
    $gender = $_POST['jenis_kelamin'];
    $birthdate = $_POST['tanggal_lahir'];
    $proofOfPayment = $_FILES['bukti']['nama'];

    // Validate form data
    // Add your validation logic here

    // Check if passwords match
    if ($password !== $confirmPassword) {
        $error = 'Passwords do not match.';
    } else {
        // Save uploaded file to folder
        $uploadDir = 'uploads/';
        $proofOfPaymentPath = $uploadDir . basename($proofOfPayment);

        if (move_uploaded_file($_FILES['proof_of_payment']['tmp_name'], $proofOfPaymentPath)) {
            // Registration data is valid, proceed with admin verification
            $_SESSION['registration_data'] = array(
                'email' => $email,
                'password' => $password,
                'nama' => $nama,
                'alamat' => $alamat,
                'jenis_kelamin' => $jenis_kelamin,
                'tanggal_lahir' => $tanggal_lahir,
                'bukti' => $bukti
            );

            // Redirect to admin confirmation page
            header('Location: admin/confirm_registration.php');
            exit();
        } else {
            $error = 'Failed to upload proof of payment.';
        }
    }

    // Store the error message in the session
    $_SESSION['error'] = $error;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registration</title>
</head>
<body>
    <form method="post" action="" enctype="multipart/form-data">
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br>
        <label for="email">Password:</label><br>
        <input type="password" id="password" name="password" required><br>
        <label for="email">Nama:</label><br>
        <input type="nama" id="nama" name="nama" required><br>
        <label for="email">Alamat:</label><br>
        <input type="alamat" id="alamat" name="alamat" required><br>
        <label for="email">Jenis Kelamin:</label><br>
        <input type="jenis_kelamin" id="jenis_kelamin" name="jenis_kelamin" required><br>
        <label for="email">Tanggal Lahir:</label><br>
        <input type="tanggal_lahir" id="tanggal_lahir" name="tanggal_lahir" required><br>
        <label for="proof_of_payment">Bukti Pembayaran:</label><br>
        <input type="file" id="bukti" name="bukti" required><br>
        <input type="submit" value="Register">
    </form>
    <?php if (!empty($_SESSION['error'])): ?>
        <p><?php echo $_SESSION['error']; ?></p>
    <?php endif; ?>
</body>
</html>
