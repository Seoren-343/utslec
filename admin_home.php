<?php
// Implement admin home logic here
session_start();

if (!isset($_SESSION["id"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit();
}

// Include the database connection file
include("db_config.php");

// Fetch all users from the database
$query = "SELECT * FROM users";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Admin Home</title>
</head>
<body>
    <div class="container">
        <h2>Welcome, Admin <?php echo $_SESSION["username"]; ?>!</h2>

        <!-- Display total savings and savings in each category -->
        <p>Total Savings: $XXXX</p>
        <p>Savings Details:</p>
        <ul>
            <li>Pokok: $XXXX</li>
            <li>Wajib: $XXXX</li>
            <li>Sukarela: $XXXX</li>
        </ul>

        <!-- Display all users in a table -->
        <h3>All Users</h3>
        <table border="1">
            <tr>
                <th>User ID</th>
                <th>Email</th>
                <th>Name</th>
                <th>Address</th>
                <th>Gender</th>
                <th>Birthdate</th>
                <th>Roles</th>
            </tr>
            <?php
            // Loop through each user and display their information
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>{$row['id']}</td>";
                echo "<td>{$row['email']}</td>";
                echo "<td>{$row['name']}</td>";
                echo "<td>{$row['address']}</td>";
                echo "<td>{$row['gender']}</td>";
                echo "<td>{$row['birthdate']}</td>";
                echo "<td>{$row['roles']}</td>";
                echo "</tr>";
            }
            ?>
        </table>

        <!-- Add other content specific to the home page for Admin -->
    </div>
</body>
</html>

