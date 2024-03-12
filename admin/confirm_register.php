<?php
session_start();

// Check if the admin is logged in, else redirect to login page
if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

// Check if registration data is present
if (!isset($_SESSION['registration_data'])) {
    header('Location: ../login.php');
    exit();
}

// Display registration data for confirmation
$registrationData = $_SESSION['registration_data'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Confirm Registration</title>
</head>
<body>
    <h2>Confirm Registration</h2>
    <p>Email: <?php echo $registrationData['email']; ?></p>
    <!-- Display other registration data for confirmation -->
    
    <p>Proof of Payment: <img src="<?php echo $registrationData['proof_of_payment']; ?>" alt="Proof of Payment"></p>

    <form method="post" action="process_registration.php">
        <input type="submit" name="confirm" value="Confirm">
        <input type="submit" name="reject" value="Reject">
    </form>
</body>
</html>
