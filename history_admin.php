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

    // Fetch all savings from the database
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
    <title>Admin History</title>
</head>
<body>
    <div class="container">
        <h2>Payment History</h2>

        <!-- Display all payments in a table -->
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
            // Initialize the counter
            $counter = 1;

            // Loop through each payment and display their information
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

                // Increment the counter
                $counter++;
            }
            ?>
        </table>

        <a href="home_admin.php"><button>Back</button></a>
    </div>
</body>
</html>
