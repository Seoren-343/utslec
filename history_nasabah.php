<?php
// Implement history nasabah logic here
session_start();

if (!isset($_SESSION["user_id"])) {
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
    <title>History - Nasabah</title>
</head>
<body>
    <div class="container">
        <h2>Transaction History</h2>

        <!-- Display transaction history in a table -->
        <table border="1">
            <tr>
                <th>Tanggal Transfer</th>
                <th>Jumlah Transfer</th>
                <th>Kategori Simpanan</th>
                <th>Status</th>
            </tr>
            <!-- Fetch and display transaction data from the database -->
            <tr>
                <td>YYYY-MM-DD</td>
                <td>$XXXX</td>
                <td>Wajib/Sukarela</td>
                <td>Reviewed/Verified/Rejected</td>
            </tr>
            <!-- Repeat the above row for each transaction -->
        </table>

        <!-- Add other content specific to the history page for Nasabah -->
    </div>
</body>
</html>
