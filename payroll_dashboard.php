<!-- payroll_officer_dashboard.php -->
<?php
session_start();
if ($_SESSION['role'] != 'Payroll Officer') {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payroll Officer Dashboard</title>
</head>
<body>

<header>
    <h1>Payroll Officer Dashboard</h1>
    <nav>
        <ul>
            <li><a href="Payroll Processing.php">Payroll Processing</a></li>
            <li><a href="Payslip.php">Payslip</a></li>
            <li><a href="Audit Logs.php">Audit Logs</a></li>
            <li><a href="login.php">Logout</a></li>
        </ul>
    </nav>
</header>

<main>
    <h2>Welcome to the Payroll Officer Dashboard</h2>
</main>

</body>
</html>