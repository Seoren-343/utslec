<?php
// Implement profile nasabah logic here
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
    <title>Profile - Nasabah</title>
</head>
<body>
    <div class="container">
        <h2>User Profile</h2>

        <!-- Display user information -->
        <p><strong>Email:</strong> <?php echo $_SESSION["username"]; ?></p>
        <p><strong>Name:</strong> Your Name</p>
        <p><strong>Address:</strong> Your Address</p>
        <p><strong>Gender:</strong> Your Gender</p>
        <p><strong>Date of Birth:</strong> Your Date of Birth</p>

        <!-- Form for updating profile information -->
        <form method="post" action="">
            <label for="name">Name:</label>
            <input type="text" name="name" value="Your Name" required>

            <label for="address">Address:</label>
            <input type="text" name="address" value="Your Address" required>

            <label for="gender">Gender:</label>
            <select name="gender" required>
                <option value="male">Male</option>
                <option value="female">Female</option>
            </select>

            <label for="birthdate">Date of Birth:</label>
            <input type="date" name="birthdate" value="Your Date of Birth" required>

            <input type="submit" value="Update Profile">
        </form>

        <!-- Form for changing password -->
        <form method="post" action="">
            <label for="current_password">Current Password:</label>
            <input type="password" name="current_password" required>

            <label for="new_password">New Password:</label>
            <input type="password" name="new_password" required>

            <label for="confirm_new_password">Confirm New Password:</label>
            <input type="password" name="confirm_new_password" required>

            <input type="submit" value="Change Password">
        </form>

        <!-- Add other content specific to the profile page for Nasabah -->
    </div>
</body>
</html>
