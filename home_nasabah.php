<?php
include("session_functions.php");
try {
    if (!isset($_SESSION["id"])) {
        header("Location: login.php");
        exit();
    }

    // Koneksi ke database
    $conn = new mysqli("localhost", "root", "", "uts_webprog_lec");

    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Get the user's id
    $user_id = $_SESSION["id"];

    // Initialize savings to 0
    $pokok = $wajib = $sukarela = 0;

    // Query to get the savings data
    $query = "SELECT SUM(pokok) as total_pokok, SUM(wajib) as total_wajib, SUM(sukarela) as total_sukarela FROM savings WHERE user_id = $user_id AND status = 'verified'";
    if (!($result = $conn->query($query))) {
        throw new Exception("Failed to execute the SQL statement: " . $conn->error);
    }

    if ($result->num_rows > 0) {
        // Fetch the savings data
        $row = $result->fetch_assoc();
        $pokok = $row["total_pokok"];
        $wajib = $row["total_wajib"];
        $sukarela = $row["total_sukarela"];
        $total_savings = $pokok + $wajib + $sukarela;
    }

    // Query to get the user data
    $query = "SELECT * FROM users WHERE id = $user_id";
    if (!($result = $conn->query($query))) {
        throw new Exception("Failed to execute the SQL statement: " . $conn->error);
    }

    $user = $result->fetch_assoc();

    $conn->close();
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
    <link rel="stylesheet" href="style.css">
    <title>Home - Nasabah</title>
</head>
<body>
    <div class="container">
        <h2>Welcome, <?php echo $user["name"]; ?>!</h2>

        <!-- Display total savings and savings in each category -->
        <p>Total Savings: Rp <?php echo number_format($total_savings, 0, ',', '.'); ?></p>
        <p>Savings Details:</p>
        <ul>
            <li>Pokok: Rp <?php echo number_format($pokok, 0, ',', '.'); ?></li>
            <li>Wajib: Rp <?php echo number_format($wajib, 0, ',', '.'); ?></li>
            <li>Sukarela: Rp <?php echo number_format($sukarela, 0, ',', '.'); ?></li>
        </ul>

        <a href="history_nasabah.php"><button>View History</button></a>
        <a href="profile_nasabah.php"><button>View Profile</button></a>
        <a href="pembayaran_nasabah.php"><button>Transaction</button></a>
        <a href="login.php"><button>Sign out</button></a>

        <!-- Add other content specific to the home page for Nasabah -->
    </div>
</body>
</html>
