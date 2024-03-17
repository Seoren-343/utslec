<?php
include("session_functions.php");
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
    <link rel="stylesheet" href="home_admin.css">
    <title>Admin Home</title>
</head>
<body>
    <div class="container">
        <h2>Welcome, Admin <?php echo $_SESSION["username"]; ?>!</h2>

        <!-- Display total savings and savings in each category -->
        <div class="total-savings-box">
            <p>Total Savings</p>
            <h1>Rp<?php echo number_format($total_savings, 2); ?></h1>
        </div>
        <div class="savings-details">
            <div class="savings-box">
                <p>Pokok</p>
                <h3>Rp<?php echo number_format($savings['total_pokok'], 2); ?></h3>
            </div>
            <div class="savings-box">
                <p>Wajib</p>
                <h3>Rp<?php echo number_format($savings['total_wajib'], 2); ?></h3>
            </div>
            <div class="savings-box">
                <p>Sukarela</p>
                <h3>Rp<?php echo number_format($savings['total_sukarela'], 2); ?></h3>
            </div>
        </div>
        <!-- Buttons -->
        <div class="buttons">
            <div class="button-container">
                <a href="verifikasi_admin.php">
                    <span class="icon">🔍</span>
                    <span class="button-name">Verification</span>
                    <p>Approve users payment</p>
                </a>
            </div>
            <div class="button-container">
                <a href="history_admin.php">
                    <span class="icon">📜</span>
                    <span class="button-name">History</span>
                    <p>Sees your approval history</p>
                </a>
            </div>
            <div class="button-container">
                <a href="users_list_admin.php">
                    <span class="icon">👥</span>
                    <span class="button-name">Users List</span>
                    <p>Display all listed users</p>
                </a>
            </div>
            <div class="button-container">
                <a href="login.php">
                    <span class="icon">🚪</span>
                    <span class="button-name">Sign out</span>
                    <p>bye bye</p>
                </a>
            </div>
        </div>
    </div>
</body>
</html>
