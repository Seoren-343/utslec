<?php
// Implement pembayaran nasabah logic here
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
    <title>Pembayaran - Nasabah</title>
</head>
<body>
    <div class="container">
        <h2>Pembayaran</h2>

        <!-- Payment form fields -->
        <form method="post" action="">
            <label for="kategori">Kategori Simpanan:</label>
            <select name="kategori" required>
                <option value="wajib">Wajib</option>
                <option value="sukarela">Sukarela</option>
            </select>

            <label for="tanggal">Tanggal Transfer:</label>
            <input type="date" name="tanggal" required>

            <label for="jumlah">Jumlah Transfer:</label>
            <input type="number" name="jumlah" required>

            <label for="bukti_transfer">File Upload Bukti Transfer:</label>
            <input type="file" name="bukti_transfer" accept="image/*" required>

            <input type="submit" value="Submit Payment">
        </form>

        <!-- Add other content specific to the payment page for Nasabah -->
    </div>
</body>
</html>
