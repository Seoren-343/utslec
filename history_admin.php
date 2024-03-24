<?php
include("session_functions.php");
try {
    if (!isset($_SESSION["id"]) || $_SESSION["role"] !== "admin") {
        header("Location: login.php");
        exit();
    }
    include("db_config.php");

    $query = "SELECT users.id, users.name, savings.tanggal_transfer, 
              CASE 
                WHEN savings.kategori = 'Pokok' THEN savings.pokok
                WHEN savings.kategori = 'Wajib' THEN savings.wajib
                WHEN savings.kategori = 'Sukarela' THEN savings.sukarela
              END AS jumlah_transfer,
              savings.kategori, savings.status 
              FROM users JOIN savings ON users.id = savings.user_id";
    if (!($result = mysqli_query($conn, $query))) {
        throw new Exception("Failed to execute the SQL statement: " . mysqli_error($conn));
    }
} catch (Exception $e) {
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
    <title>Admin History</title>
</head>
<body>
    <div class="container">
        <h2>Payment History</h2>
        <table border="1">
            <tr>
                <th>No.</th>
                <th>User ID</th>
                <th>Name</th>
                <th>Tanggal Transfer</th>
                <th>Jumlah Transfer</th>
                <th>Kategori Simpanan</th>
                <th>Status</th>
            </tr>
            <?php
            $counter = 1;
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>{$counter}</td>";
                echo "<td>{$row['id']}</td>";
                echo "<td>{$row['name']}</td>";
                echo "<td>{$row['tanggal_transfer']}</td>";
                echo "<td>{$row['jumlah_transfer']}</td>";
                echo "<td>{$row['kategori']}</td>";
                echo "<td>{$row['status']}</td>";
                echo "</tr>";
                $counter++;
            }
            ?>
        </table>

        <a href="home_admin.php" class="back-button">Back</a>
    </div>
</body>
</html>
