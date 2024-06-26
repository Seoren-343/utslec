<?php
include("session_functions.php");
try {
    if (!isset($_SESSION["id"]) || $_SESSION["role"] !== "admin") {
        header("Location: login.php");
        exit();
    }
    include("db_config.php");
    $query = "SELECT * FROM users";
    if (!($result = mysqli_query($conn, $query))) {
        throw new Exception("Failed to execute the SQL statement: " . mysqli_error($conn));
    }
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
    <link rel="stylesheet" href="users_list_admin.css">

    <title>Admin Users List</title>
</head>
<body>
    <div class="container">
        <h2>Admins List</h2>
        <table>
            <tr>
                <th>User ID</th>
                <th>Email</th>
                <th>Name</th>
                <th>Address</th>
                <th>Gender</th>
                <th>Birthdate</th>
                <th>Roles</th>
                <th>Action</th>
            </tr>
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
                if ($row['roles'] === 'admin') {
                    echo "<tr>";
                    echo "<td>{$row['id']}</td>";
                    echo "<td>{$row['email']}</td>";
                    echo "<td>{$row['name']}</td>";
                    echo "<td>{$row['address']}</td>";
                    echo "<td>{$row['gender']}</td>";
                    echo "<td>{$row['birthdate']}</td>";
                    echo "<td>{$row['roles']}</td>";
                    echo "<td><a href='delete_user.php?id={$row['id']}'>Delete</a></td>";
                    echo "</tr>";
                }
            }
            ?>
        </table>

        <h2>Users List</h2>
        <table>
            <tr>
                <th>User ID</th>
                <th>Email</th>
                <th>Name</th>
                <th>Address</th>
                <th>Gender</th>
                <th>Birthdate</th>
                <th>Roles</th>
                <th>Action</th>
            </tr>
            <?php
            mysqli_data_seek($result, 0);
            while ($row = mysqli_fetch_assoc($result)) {
                if ($row['roles'] !== 'admin') {
                    echo "<tr>";
                    echo "<td>{$row['id']}</td>";
                    echo "<td>{$row['email']}</td>";
                    echo "<td>{$row['name']}</td>";
                    echo "<td>{$row['address']}</td>";
                    echo "<td>{$row['gender']}</td>";
                    echo "<td>{$row['birthdate']}</td>";
                    echo "<td>{$row['roles']}</td>";
                    echo "<td><a href='delete_user.php?id={$row['id']}'>Delete</a></td>";
                    echo "</tr>";
                }
            }
            ?>
        </table>
        <a href="home_admin.php"><button>Back</button></a>
    </div>
</body>
</html>
