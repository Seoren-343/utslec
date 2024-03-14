<?php
// Implement profile nasabah logic here
session_start();

if (!isset($_SESSION["id"])) {
    header("Location: login.php");
    exit();
}

// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "uts_webprog_lec");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the user's id
$user_id = $_SESSION["id"];

// Query to get the user data
$query = "SELECT * FROM users WHERE id = $user_id";
$result = $conn->query($query);

if (!$result) {
    die("Query failed: " . $conn->connect_error);
}

$user = $result->fetch_assoc();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Profile - Nasabah</title>
</head>
<body>
    <div class="container">
        <h2>User Profile</h2>

        <!-- Profile picture placement -->
        <div>
            <?php if ($user['profile_picture']): ?>
                <img src="data:image/jpeg;base64,<?php echo base64_encode($user['profile_picture']); ?>" alt="Profile Picture">
            <?php endif; ?>
        </div>

        <!-- Display user information -->
        <p><strong>Email:</strong> <?php echo $user["email"]; ?></p>
        <p><strong>Name:</strong> <?php echo $user["name"]; ?></p>
        <p><strong>Address:</strong> <?php echo $user["address"]; ?></p>
        <p><strong>Gender:</strong> <?php echo $user["gender"]; ?></p>
        <p><strong>Date of Birth:</strong> <?php echo $user["birthdate"]; ?></p>

        <!-- Rest of the buttons -->
        <a href="profile_settings_nasabah.php"><button>Profile Settings</button></a>
        <a href="home_nasabah.php"><button>Back</button></a>
    </div>
</body>
</html>
