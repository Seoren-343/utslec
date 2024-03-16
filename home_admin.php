<?php
// Implement admin home logic here
session_start();

try {
    if (!isset($_SESSION["id"]) || $_SESSION["role"] !== "admin") {
        header("Location: login.php");
        exit();
    }

    // Include the database connection file
    include("db_config.php");

    // Query to get the total savings
    $query_savings = "SELECT SUM(pokok) as total_pokok, SUM(wajib) as total_wajib, SUM(sukarela) as total_sukarela FROM savings WHERE status = 'verified'";
    if (!($result_savings = mysqli_query($conn, $query_savings))) {
        throw new Exception("Failed to execute the SQL statement: " . mysqli_error($conn));
    }

    $savings = mysqli_fetch_assoc($result_savings);
    $total_savings = $savings['total_pokok'] + $savings['total_wajib'] + $savings['total_sukarela'];
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
    <title>Admin Home</title>
</head>
<body>
    <div class="container">
        <h2>Welcome, Admin <?php echo $_SESSION["username"]; ?>!</h2>

        <!-- Display total savings and savings in each category -->
        <p>Total Savings: Rp<?php echo number_format($total_savings, 2); ?></p>
        <p>Savings Details:</p>
        <ul>
            <li>Pokok: Rp<?php echo number_format($savings['total_pokok'], 2); ?></li>
            <li>Wajib: Rp<?php echo number_format($savings['total_wajib'], 2); ?></li>
            <li>Sukarela: Rp<?php echo number_format($savings['total_sukarela'], 2); ?></li>
        </ul>

        <a href="verifikasi_admin.php"><button>Verification</button></a>
        <a href="history_admin.php"><button>History</button></a>
        <a href="users_list_admin.php"><button>Users List</button></a>
        <a href="login.php"><button>Sign out</button></a>

        <!-- Add other content specific to the home page for Admin -->
    </div>
</body>
</html>
