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

// Check if the form is submitted
if(isset($_POST["submit"])) {
    // Get the file
    $profile_picture = $_FILES["profile_picture"];

    // Get the file contents
    $profile_picture_data = file_get_contents($profile_picture["tmp_name"]);

    // Escape the data
    $profile_picture_data = mysqli_real_escape_string($conn, $profile_picture_data);

    // Update the user data
    $query = "UPDATE users SET profile_picture = '$profile_picture_data' WHERE id = $user_id";
    $result = $conn->query($query);

    if (!$result) {
        die("Query failed: " . $conn->connect_error);
    }
}

// Check if the remove button is clicked
if(isset($_POST["remove"])) {
    // Remove the profile picture from the database
    $query = "UPDATE users SET profile_picture = NULL WHERE id = $user_id";
    $result = $conn->query($query);

    if (!$result) {
        die("Query failed: " . $conn->connect_error);
    }
    // Refresh the page to reflect the changes
    echo "<meta http-equiv='refresh' content='0'>";
}

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

        <!-- Display user information -->
        <p><strong>Email:</strong> <?php echo $user["email"]; ?> <button onclick="document.getElementById('changeEmail').style.display='block'">Change Email</button></p>
        <form id="changeEmail" style="display:none" action="change_detail.php" method="post">
            <input type="text" name="newEmail" placeholder="New Email">
            <input type="submit" name="submit" value="Submit">
        </form>

        <p><strong>Name:</strong> <?php echo $user["name"]; ?> <button onclick="document.getElementById('changeName').style.display='block'">Change Name</button></p>
        <form id="changeName" style="display:none" action="change_detail.php" method="post">
            <input type="text" name="newName" placeholder="New Name">
            <input type="submit" name="submit" value="Submit">
        </form>

        <p><strong>Address:</strong> <?php echo $user["address"]; ?> <button onclick="document.getElementById('changeAddress').style.display='block'">Change Address</button></p>
        <form id="changeAddress" style="display:none" action="change_detail.php" method="post">
            <input type="text" name="newAddress" placeholder="New Address">
            <input type="submit" name="submit" value="Submit">
        </form>

        <p><strong>Gender:</strong> <?php echo $user["gender"]; ?> <button onclick="document.getElementById('changeGender').style.display='block'">Change Gender</button></p>
        <form id="changeGender" style="display:none" action="change_detail.php" method="post">
            <select name="newGender">
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>
            <input type="submit" name="submit" value="Submit">
        </form>

        <p><strong>Date of Birth:</strong> <?php echo $user["birthdate"]; ?> <button onclick="document.getElementById('changeBirthdate').style.display='block'">Change Date of Birth</button></p>
        <form id="changeBirthdate" style="display:none" action="change_detail.php" method="post">
            <input type="date" name="newBirthdate">
            <input type="submit" name="submit" value="Submit">
        </form>

        <!-- Rest of the buttons -->
        <a href="home_nasabah.php"><button>Back</button></a>
    </div>
</body>
</html>


