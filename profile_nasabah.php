<?php
include("session_functions.php");
try {
    if (!isset($_SESSION["id"])) {
        header("Location: login.php");
        exit();
    }

    include("db_config.php");

    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    $user_id = $_SESSION["id"];
    $query = "SELECT * FROM users WHERE id = $user_id";
    if (!($result = $conn->query($query))) {
        throw new Exception("Failed to execute the SQL statement: " . $conn->error);
    }

    $user = $result->fetch_assoc();
    if(isset($_POST["submit"])) {
        $profile_picture = $_FILES["newPicture"];
        if ($profile_picture["tmp_name"]) {
            $profile_picture_data = file_get_contents($profile_picture["tmp_name"]);
            $profile_picture_data = mysqli_real_escape_string($conn, $profile_picture_data);
            $query = "UPDATE users SET profile_picture = '$profile_picture_data' WHERE id = $user_id";
            if (!($result = $conn->query($query))) {
                throw new Exception("Failed to execute the SQL statement: " . $conn->error);
            }
        }
    }

    $conn->close();
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
    <link rel="stylesheet" href="profile_nasabah.css">
    <title>Profile - Nasabah</title>
</head>
<body>
    <div class="container">
        <h1 class="main-header">User Profile</h1>
        <div class="buttons">
            <a href="profile_settings_nasabah.php"><button>Profile Settings</button></a>
            <a href="home_nasabah.php"><button>Back</button></a>
        </div>
        <div class="profile-container">
            <div class="profile-pic-container">
                <?php if ($user['profile_picture']): ?>
                    <img src="data:image/jpeg;base64,<?php echo base64_encode($user['profile_picture']); ?>" alt="Profile Picture">
                <?php endif; ?>
            </div>
            <div class="user-details-container">
                <p><strong>Email:</strong> <?php echo $user["email"]; ?></p>
                <p><strong>Name:</strong> <?php echo $user["name"]; ?></p>
                <p><strong>Address:</strong> <?php echo $user["address"]; ?></p>
                <p><strong>Gender:</strong> <?php echo $user["gender"]; ?></p>
                <p><strong>Date of Birth:</strong> <?php echo $user["birthdate"]; ?></p>
            </div>
        </div>
    </div>
</body>
</html>

