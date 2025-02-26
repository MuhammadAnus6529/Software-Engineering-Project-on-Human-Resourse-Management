<!-- employee_dashboard.php -->
<?php
session_start();
include 'db_connect.php'; // Database connection

if ($_SESSION['role'] != 'Employee') {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard</title>
</head>
<body>

<header>
    <h1>Employee Dashboard</h1>
    <nav>
        <ul>
            <li><a href="Employee Profile.php">Profile</a></li>
            <li><a href="Attendance Tracking.php">Attendance</a></li>
            <li><a href="Performance Review.php">Performance Review</a></li>
            <li><a href="Leave Management.php">Leave Management</a></li>
            <li><a href="login.php">Logout</a></li>
        </ul>
    </nav>
</header>

<main>
    <h2>Welcome to the Employee Dashboard</h2>
</main>

</body>
</html>