<?php
include("session_functions.php");
try {
    if (!isset($_SESSION["id"])) {
        header("Location: login.php");
        exit();
    }

    // Include the database connection file
    include("db_config.php");

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
    <link rel="stylesheet" href="home_nasabah.css">
    <title>Home - Nasabah</title>
</head>
<body>
    <div class="container">
        <h2>Welcome, <?php echo $user["name"]; ?>!</h2>

        <!-- Profile picture and user details -->
        <div class="profile-container">
            <!-- Profile picture container -->
            <div class="profile-pic-container">
                <?php if ($user['profile_picture']): ?>
                    <img src="data:image/jpeg;base64,<?php echo base64_encode($user['profile_picture']); ?>" alt="Profile Picture">
                <?php endif; ?>
            </div>

            <!-- User details container -->
            <div class="user-details-container">
                <p><strong>Email:</strong> <?php echo $user["email"]; ?></p>
                <p><strong>Name:</strong> <?php echo $user["name"]; ?></p>
                <p><strong>Address:</strong> <?php echo $user["address"]; ?></p>
                <p><strong>Gender:</strong> <?php echo $user["gender"]; ?></p>
                <p><strong>Date of Birth:</strong> <?php echo $user["birthdate"]; ?></p>
            </div>
        </div>

        <div class="total-savings-box">
            <p>Total Savings: Rp <?php echo number_format($total_savings, 0, ',', '.'); ?></p>
        </div>
        <div class="savings-details">
            <div class="savings-box">
                <p>Pokok</p>
                <h3>Rp <?php echo number_format($pokok, 0, ',', '.'); ?></h3>
            </div>
            <div class="savings-box">
                <p>Wajib</p>
                <h3>Rp <?php echo number_format($wajib, 0, ',', '.'); ?></h3>
            </div>
            <div class="savings-box">
                <p>Sukarela</p>
                <h3>Rp <?php echo number_format($sukarela, 0, ',', '.'); ?><h3>
            </div>
        </div>

        <div class="buttons">
            <div class="button-container">
                <a href="history_nasabah.php">
                    <span class="icon">📜</span>
                    <span class="button-name">View History</span>
                    <p>View your transactions history</p>
                </a>
            </div>
            <div class="button-container">
                <a href="profile_nasabah.php">
                    <span class="icon">👤</span>
                    <span class="button-name">View Profile</span>
                    <p>View and edit your profile</p>
                </a>
            </div>
            <div class="button-container">
                <a href="pembayaran_nasabah.php">
                    <span class="icon">💵</span>
                    <span class="button-name">Transaction</span>
                    <p>Make your transactions here</p>
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
