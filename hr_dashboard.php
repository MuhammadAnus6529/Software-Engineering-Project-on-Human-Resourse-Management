<!-- hr_dashboard.php -->
<?php
include 'db_connect.php'; // Database connection

session_start();
if ($_SESSION['role'] != 'HR Manager') {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR Dashboard</title>
</head>
<body>

<header>
    <h1>HR Dashboard</h1>
    <nav>
        <ul>
            <li><a href="Employee List.php">Employee List</a></li>
            <li><a href="Leave Management.php">Leave Management</a></li>
            <li><a href="Payroll Processing.php">Payroll Processing</a></li>
            <li><a href="HR Reports.php">HR Reports</a></li>
            <li><a href="Performance.php">Performance</a></li>

            <li><a href="Audit Logs.php">Audit Logs</a></li>
            <li><a href="login.php">Logout</a></li>
        </ul>
    </nav>
</header>

<main>
    <h2>Welcome to the HR Manager Dashboard</h2>
</main>

</body>
</html>