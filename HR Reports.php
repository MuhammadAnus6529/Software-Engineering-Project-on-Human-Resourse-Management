<?php
session_start();
include 'db_connect.php'; // Database connection

// Fetch Employees
$employees_sql = "SELECT e.employee_id, u.full_name, e.department, e.job_title, e.status 
                  FROM Employees e 
                  JOIN Users u ON e.user_id = u.user_id";
$employees_result = $conn->query($employees_sql);

// Fetch Payroll Data
$payroll_sql = "SELECT p.employee_id, u.full_name, p.month_year, p.base_salary, p.overtime, p.deductions, p.net_salary
                FROM Payroll p
                JOIN Employees e ON p.employee_id = e.employee_id
                JOIN Users u ON e.user_id = u.user_id
                ORDER BY p.month_year DESC";
$payroll_result = $conn->query($payroll_sql);

// Fetch Attendance Data
$attendance_sql = "SELECT a.employee_id, u.full_name, a.date, a.status 
                    FROM Attendance a 
                    JOIN Employees e ON a.employee_id = e.employee_id 
                    JOIN Users u ON e.user_id = u.user_id
                    ORDER BY a.date DESC";
$attendance_result = $conn->query($attendance_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR Reports</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #2980b9, #6dd5fa);
            margin: 0;
            padding: 20px;
            text-align: center;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            width: 90%;
            max-width: 1000px;
            margin: auto;
        }
        h2 { color: #333; margin-bottom: 20px; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th { background: #2980b9; color: white; }
    </style>
</head>
<body>

    <div class="container">
        <h2>Employee Data</h2>
        <table>
            <tr>
                <th>Name</th><th>Department</th><th>Job Title</th><th>Status</th>
            </tr>
            <?php while ($row = $employees_result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['full_name']) ?></td>
                    <td><?= htmlspecialchars($row['department']) ?></td>
                    <td><?= htmlspecialchars($row['job_title']) ?></td>
                    <td><?= htmlspecialchars($row['status']) ?></td>
                </tr>
            <?php endwhile; ?>
        </table>

        <h2>Payroll Report</h2>
        <table>
            <tr>
                <th>Name</th><th>Month</th><th>Base Salary</th><th>Overtime</th><th>Deductions</th><th>Net Salary</th>
            </tr>
            <?php while ($row = $payroll_result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['full_name']) ?></td>
                    <td><?= htmlspecialchars($row['month_year']) ?></td>
                    <td>$<?= number_format($row['base_salary'], 2) ?></td>
                    <td>$<?= number_format($row['overtime'], 2) ?></td>
                    <td>$<?= number_format($row['deductions'], 2) ?></td>
                    <td><strong>$<?= number_format($row['net_salary'], 2) ?></strong></td>
                </tr>
            <?php endwhile; ?>
        </table>

        <h2>Attendance Report</h2>
        <table>
            <tr>
                <th>Name</th><th>Date</th><th>Status</th>
            </tr>
            <?php while ($row = $attendance_result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['full_name']) ?></td>
                    <td><?= htmlspecialchars($row['date']) ?></td>
                    <td><?= htmlspecialchars($row['status']) ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>

</body>
</html>
