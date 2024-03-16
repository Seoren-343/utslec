<?php
// Implement verifikasi admin logic here
session_start();

try {
    if (!isset($_SESSION["id"]) || $_SESSION["role"] !== "admin") {
        header("Location: login.php");
        exit();
    }

    // Include the database connection file
    include("db_config.php");

    // Fetch all users from the database
    $query = "SELECT *, 
              CASE 
                WHEN kategori = 'Pokok' THEN pokok
                WHEN kategori = 'Wajib' THEN wajib
                WHEN kategori = 'Sukarela' THEN sukarela
              END AS jumlah_transfer
              FROM savings WHERE status = 'reviewed'";
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
    <title>Verifikasi Admin</title>
</head>
<body>
    <div class="container">
        <h2>Payments to Review</h2>

        <?php
        if (mysqli_num_rows($result) > 0) {
            echo "<table border='1'>";
            echo "<tr>";
            echo "<th>Payment ID</th>";
            echo "<th>User ID</th>";
            echo "<th>Kategori</th>";
            echo "<th>Tanggal Transfer</th>";
            echo "<th>Jumlah Transfer</th>";
            echo "<th>Bukti Transfer</th>";
            echo "<th>Action</th>";
            echo "</tr>";

            // Fetch the payments data
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row["id"] . "</td>";
                echo "<td>" . $row["user_id"] . "</td>";
                echo "<td>" . $row["kategori"] . "</td>";
                echo "<td>" . $row["tanggal_transfer"] . "</td>";
                echo "<td>" . $row["jumlah_transfer"] . "</td>";
                echo "<td><a href='" . $row["bukti_transfer"] . "'>View</a></td>";
                echo "<td><a href='accept_payment.php?id=" . $row["id"] . "'>Accept</a> | <a href='reject_payment.php?id=" . $row["id"] . "'>Reject</a></td>";
                echo "</tr>";
            }

            echo "</table>";
        } else {
            echo "No payments to review";
        }

        $conn->close();
        ?>

        <a href="home_admin.php"><button>Back</button></a>

    </div>
</body>
</html>
