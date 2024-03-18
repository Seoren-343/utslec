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

    // Query to get the transaction data
    $query = "SELECT *, 
              CASE 
                WHEN kategori = 'Pokok' THEN pokok
                WHEN kategori = 'Wajib' THEN wajib
                WHEN kategori = 'Sukarela' THEN sukarela
              END AS jumlah_transfer
              FROM savings WHERE user_id = $user_id ORDER BY tanggal_transfer ASC";
    if (!($result = $conn->query($query))) {
        throw new Exception("Failed to execute the SQL statement: " . $conn->error);
    }

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
    <link rel="stylesheet" href="history_admin.css">
    <title>History - Nasabah</title>
</head>
<body>
    <div class="container">
        <h2>Transaction History</h2>

        <!-- Display transaction history in a table -->
        <table border="1">
            <tr>
                <th>No.</th>
                <th>Tanggal Transfer</th>
                <th>Jumlah Transfer</th>
                <th>Kategori Simpanan</th>
                <th>Status</th>
            </tr>
            <!-- Fetch and display transaction data from the database -->
            <?php
            $no = 1;
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $no++ . "</td>";
                echo "<td>" . $row["tanggal_transfer"] . "</td>";
                echo "<td>" . $row["jumlah_transfer"] . "</td>";
                echo "<td>" . $row["kategori"] . "</td>";
                echo "<td>" . $row["status"] . "</td>";
                echo "</tr>";
            }
            ?>
        </table>

        <a href="home_nasabah.php" class="back-button">Back</a>

    </div>
</body>
</html>
