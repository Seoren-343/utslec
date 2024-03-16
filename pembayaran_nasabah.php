<?php
// Implement pembayaran nasabah logic here
session_start();

try {
    if (!isset($_SESSION["id"])) {
        header("Location: login.php");
        exit();
    }

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $kategori = $_POST["kategori"];
        $tanggal = $_POST["tanggal"];
        $jumlah = $_POST["jumlah"];
        $bukti_transfer = $_FILES["bukti_transfer"]["name"];

        // Upload the proof of payment file
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($bukti_transfer);
        if (!move_uploaded_file($_FILES["bukti_transfer"]["tmp_name"], $target_file)) {
            throw new Exception("Failed to upload the file.");
        }

        // Koneksi ke database
        $conn = new mysqli("localhost", "root", "", "uts_webprog_lec");

        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }

        // Query to insert the payment data into the savings table
        if ($kategori == "wajib") {
            $query = "INSERT INTO savings (user_id, kategori, tanggal_transfer, wajib, bukti_transfer, status)
                      VALUES (?, ?, ?, ?, ?, 'reviewed')";
        } elseif ($kategori == "sukarela") {
            $query = "INSERT INTO savings (user_id, kategori, tanggal_transfer, sukarela, bukti_transfer, status)
                      VALUES (?, ?, ?, ?, ?, 'reviewed')";
        } else { 
            $query = "INSERT INTO savings (user_id, kategori, tanggal_transfer, pokok, bukti_transfer, status)
                      VALUES (?, ?, ?, ?, ?, 'reviewed')";
        }

        if (!($stmt = $conn->prepare($query))) {
            throw new Exception("Failed to prepare the SQL statement: " . $conn->error);
        }
        $stmt->bind_param("issds", $_SESSION["id"], $kategori, $tanggal, $jumlah, $target_file);
        if (!$stmt->execute()) {
            throw new Exception("Failed to execute the SQL statement: " . $stmt->error);
        }

        if ($stmt->affected_rows > 0) {
            echo "Payment submitted for review";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    }
} catch (Exception $e) {
    // Handle the exception
    echo "Error: " . $e->getMessage();
    exit();
}
?>

<!-- The rest of your HTML code goes here -->

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
        <form method="post" action="" enctype="multipart/form-data">
            <label for="kategori">Kategori Simpanan:</label>
            <select name="kategori" required>
                <option value="pokok">Pokok</option>
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
        
        <a href="home_nasabah.php"><button>Back</button></a>
        <!-- Add other content specific to the payment page for Nasabah -->
    </div>
</body>
</html>
