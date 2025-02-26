<?php
session_start();
include 'db_connect.php'; // Database connection

if ($_SESSION['role'] != 'IT Admin') {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IT Admin Dashboard</title>
</head>
<body>

<header>
    <h1>IT Admin Dashboard</h1>
    <nav>
        <ul>
            <li><a href="Onboarding.php">User Management</a></li>
            <li><a href="Access Management.php">Access Management</a></li>
            <li><a href="Audit Logs.php">Audit Logs</a></li>
            <li><a href="login.php">Logout</a></li>
        </ul>
    </nav>
</header>

<main>
    <h2>Welcome to the IT Admin Dashboard</h2>
</main>

</body>
</html>