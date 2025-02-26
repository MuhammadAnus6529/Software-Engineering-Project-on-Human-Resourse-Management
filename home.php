<?php
session_start();
include 'db_connect.php'; // Database connection

// Check if the user is logged in
if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit();
}

// Fetch current user role (optional)
$username = $_SESSION['username'];
$role = $_SESSION['role'];

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: login.php'); // Redirect to login page after logout
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR Management System - Home</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #007bff;
            color: white;
            padding: 20px 0;
            text-align: center;
        }

        header h1 {
            margin: 0;
        }

        header p {
            font-size: 16px;
        }

        nav ul {
            list-style: none;
            padding: 0;
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 10px;
        }

        nav ul li {
            display: inline;
        }

        nav ul li a {
            text-decoration: none;
            color: white;
            padding: 10px 15px;
            background-color: #0056b3;
            border-radius: 5px;
        }

        nav ul li a:hover {
            background-color: #003366;
        }

        main {
            padding: 20px;
            max-width: 1000px;
            margin: 20px auto;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        footer {
            background-color: #007bff;
            color: white;
            padding: 10px 0;
            text-align: center;
            margin-top: 30px;
        }

        footer p {
            margin: 0;
        }
    </style>
</head>
<body>

<!-- Header Section -->
<header>
    <h1>Welcome to HR Management System</h1>
    <p>Hello, <?php echo htmlspecialchars($username); ?> (Role: <?php echo htmlspecialchars($role); ?>)</p>
    <nav>
        <ul>
            <li><a href="hr_dashboard.php">Dashboard</a></li>
            <li><a href="employee_dashboard.php">Employee Management</a></li>
            <li><a href="Attendance Tracking.php">Attendance Management</a></li>
            <li><a href="payroll_dashboard.php">Payroll Management</a></li>
            <li><a href="Interview Scheduling">Interview</a></li>
            <!-- Show Access Management only for certain roles -->
            <?php if ($role == 'HR Manager' || $role == 'Manager'): ?>
                <li><a href="Access Management.php">Access Management</a></li>
            <?php endif; ?>
            <li><a href="login.php?logout=true">Logout</a></li>
        </ul>
    </nav>
</header>

<!-- Main Content Section -->
<main>
    <h2>System Overview</h2>
    <p>Welcome to the HR Management System. Use the navigation menu above to access various sections of the system.</p>

    <!-- Dynamic content based on the user's role -->
    <?php if ($role == 'HR Manager'): ?>
        <p>As an HR Manager, you have full access to all features, including Employee Management and Access Management.</p>
    <?php elseif ($role == 'Manager'): ?>
        <p>As a Manager, you can manage employees and view attendance reports.</p>
    <?php elseif ($role == 'Payroll Officer'): ?>
        <p>As a Payroll Officer, you are responsible for managing payroll records and calculations.</p>
    <?php elseif ($role == 'Employee'): ?>
        <p>As an Employee, you can view your attendance and payroll details.</p>
    <?php endif; ?>
</main>

<!-- Footer Section -->
<footer>
    <p>&copy; 2025 HR Management System | All Rights Reserved</p>
</footer>

</body>
</html>