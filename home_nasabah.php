<?php
// Implement home nasabah logic here
session_start();

if (!isset($_SESSION["id"])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Home - Nasabah</title>
</head>
<body>
    <div class="container">
        <h2>Welcome, <?php echo $_SESSION["username"]; ?>!</h2>

        <!-- Display total savings and savings in each category -->
        <p>Total Savings: $XXXX</p>
        <p>Savings Details:</p>
        <ul>
            <li>Pokok: $XXXX</li>
            <li>Wajib: $XXXX</li>
            <li>Sukarela: $XXXX</li>
        </ul>

        <a href="history_nasabah.php"><button>View History</button></a>
        <a href="profile_nasabah.php"><button>View Profile</button></a>
        <a href="pembayaran_nasabah.php"><button>Make Payment</button></a>

        <!-- Add other content specific to the home page for Nasabah -->
    </div>
</body>
</html>
