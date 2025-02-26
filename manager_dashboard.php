<?php
session_start();
include 'db_connect.php'; // Database connection

if ($_SESSION['role'] != 'Manager') {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Dashboard</title>
</head>
<body>

<header>
    <h1>Manager Dashboard</h1>
    <nav>
        <ul>
            <li><a href="Performance Review.php">Performance Review</a></li>
            <li><a href="Attendance Tracking.php">Attendance Tracking</a></li>
            <li><a href="Leave Managment">Leave Requests</a></li>
            <li><a href="Audit Logs.php">Audit Logs</a></li>
            <li><a href="Job Listing.php">Job Listing</a></li>
            <li><a href="login.php">Logout</a></li>
        </ul>
    </nav>
</header>

<main>
    <h2>Welcome to the Manager Dashboard</h2>
</main>

</body>
</html>