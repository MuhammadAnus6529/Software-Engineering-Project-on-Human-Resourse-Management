<?php
session_start();
include 'db_connect.php'; // Database connection

// Fetch employees with their names from Users table
$sql = "SELECT e.employee_id, u.full_name, u.email, e.department, e.job_title 
        FROM Employees e
        JOIN Users u ON e.user_id = u.user_id";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee List - HRMS</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        body {
            background: #f4f4f4;
            padding: 20px;
        }

        .navbar {
            background-color: #333;
            padding: 10px;
            text-align: center;
        }

        .navbar a {
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            margin: 0 10px;
            font-size: 16px;
        }

        .navbar a:hover {
            background-color: #555;
            border-radius: 5px;
        }

        .container {
            max-width: 900px;
            margin: auto;
            background: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background: #007bff;
            color: white;
        }

        tr:hover {
            background: #f1f1f1;
        }

        .no-data {
            text-align: center;
            font-size: 16px;
            padding: 10px;
            color: #666;
        }
    </style>
</head>
<body>

<!-- Navigation Bar -->
<div class="navbar">
    <a href="Employee List.php">Employee List</a>
    <a href="hr_dashboard.php">DashBoard</a>
    <a href="Employee Profile.php">Employee Profile</a> <!-- Link to the Employee Profile page -->
</div>

<!-- Employee List -->
<div class="container">
    <h2>Employee List</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Department</th>
                <th>Job Title</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row["employee_id"]; ?></td>
                        <td><?php echo $row["full_name"]; ?></td>
                        <td><?php echo $row["email"]; ?></td>
                        <td><?php echo $row["department"]; ?></td>
                        <td><?php echo $row["job_title"]; ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="no-data">No employees found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>