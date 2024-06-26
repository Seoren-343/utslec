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
    if(isset($_POST["submit"])) {
        $profile_picture = $_FILES["profile_picture"];
        $profile_picture_data = file_get_contents($profile_picture["tmp_name"]);
        $profile_picture_data = mysqli_real_escape_string($conn, $profile_picture_data);
        $query = "UPDATE users SET profile_picture = '$profile_picture_data' WHERE id = $user_id";
        if (!($result = $conn->query($query))) {
            throw new Exception("Failed to execute the SQL statement: " . $conn->error);
        }
    }
    if(isset($_POST["remove"])) {
        $query = "UPDATE users SET profile_picture = NULL WHERE id = $user_id";
        if (!($result = $conn->query($query))) {
            throw new Exception("Failed to execute the SQL statement: " . $conn->error);
        }
        echo "<meta http-equiv='refresh' content='0'>";
    }
    $query = "SELECT * FROM users WHERE id = $user_id";
    if (!($result = $conn->query($query))) {
        throw new Exception("Failed to execute the SQL statement: " . $conn->error);
    }

    $user = $result->fetch_assoc();

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
    <link rel="stylesheet" href="profile_edit.css">
    <title>Profile - Nasabah</title>
</head>
<body>
    <div class="container">
        <h2>User Profile</h2>
        <div class="profile-pic-container">
            <?php if ($user['profile_picture']): ?>
                <img src="data:image/jpeg;base64,<?php echo base64_encode($user['profile_picture']); ?>" alt="Profile Picture">
                <form action="profile_nasabah.php" method="post" enctype="multipart/form-data">
                    <input type="file" name="newPicture">
                    <input type="submit" name="submit" value="Change Picture">
                </form>
            <?php else: ?>
                <form action="profile_nasabah.php" method="post" enctype="multipart/form-data">
                    <input type="file" name="newPicture">
                    <input type="submit" name="submit" value="Upload Picture">
                </form>
            <?php endif; ?>
        </div>
        <div class="user-details-container">
            <p><strong>Email:</strong> <?php echo $user["email"]; ?></p>
            <button onclick="document.getElementById('changeEmail').style.display='block'">Change Email</button>
            <form id="changeEmail" style="display:none" action="change_detail.php" method="post">
                <input type="text" name="newEmail" placeholder="New Email">
                <input type="submit" name="submit" value="Submit">
            </form>
                    
            <p><strong>Name:</strong> <?php echo $user["name"]; ?></p>
            <button onclick="document.getElementById('changeName').style.display='block'">Change Name</button>
            <form id="changeName" style="display:none" action="change_detail.php" method="post">
                <input type="text" name="newName" placeholder="New Name">
                <input type="submit" name="submit" value="Submit">
            </form>
                    
            <p><strong>Address:</strong> <?php echo $user["address"]; ?></p>
            <button onclick="document.getElementById('changeAddress').style.display='block'">Change Address</button>
            <form id="changeAddress" style="display:none" action="change_detail.php" method="post">
                <input type="text" name="newAddress" placeholder="New Address">
                <input type="submit" name="submit" value="Submit">
            </form>
                    
            <p><strong>Gender:</strong> <?php echo $user["gender"]; ?></p>
            <button onclick="document.getElementById('changeGender').style.display='block'">Change Gender</button>
            <form id="changeGender" style="display:none" action="change_detail.php" method="post">
                <select name="newGender">
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
                <input type="submit" name="submit" value="Submit">
            </form>
                    
            <p><strong>Date of Birth:</strong> <?php echo $user["birthdate"]; ?></p>
            <button onclick="document.getElementById('changeBirthdate').style.display='block'">Change Date of Birth</button>
            <form id="changeBirthdate" style="display:none" action="change_detail.php" method="post">
                <input type="date" name="newBirthdate">
                <input type="submit" name="submit" value="Submit">
            </form>
        </div>
 
        <div class="buttons">
            <a href="profile_nasabah.php"><button>Back</button></a>
        </div>
    </div>
</body>
</html>


