<?php
session_start();
include 'db_connect.php'; // Database connection

$payslip = null;
$error = '';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $employee_id = intval($_POST['employee_id']);

    if ($employee_id > 0) {
        // Fetch payslip details
        $sql = "SELECT u.full_name, u.email, p.base_salary, p.deductions, p.overtime, p.net_salary, p.processed_at 
                FROM Payroll p
                JOIN Employees e ON p.employee_id = e.employee_id
                JOIN Users u ON e.user_id = u.user_id
                WHERE p.employee_id = ?
                ORDER BY p.processed_at DESC LIMIT 1"; // Get latest payslip

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $employee_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $payslip = $result->fetch_assoc();
        } else {
            $error = "No payslip found for Employee ID: $employee_id.";
        }

        $stmt->close();
    } else {
        $error = "Invalid Employee ID. Please enter a valid number.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payslip</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #2980b9, #6dd5fa);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            width: 400px;
            text-align: center;
        }
        h2 {
            color: #333;
            margin-bottom: 20px;
        }
        form {
            margin-bottom: 15px;
        }
        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            text-align: left;
        }
        input {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            width: 100%;
            background: #3498db;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background: #217dbb;
        }
        .message {
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .payslip-box {
            background: #ecf0f1;
            padding: 15px;
            border-radius: 8px;
            margin-top: 10px;
            text-align: left;
        }
        .payslip-box p {
            font-size: 14px;
            margin: 5px 0;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Search Payslip</h2>
        <form method="POST" action="">
            <label for="employee_id">Enter Employee ID:</label>
            <input type="number" id="employee_id" name="employee_id" required>
            <button type="submit">Search</button>
        </form>

        <?php if (!empty($error)) echo "<p class='message error'>$error</p>"; ?>

        <?php if ($payslip): ?>
            <div class="payslip-box">
                <h3>Payslip Details</h3>
                <p><strong>Employee Name:</strong> <?= htmlspecialchars($payslip['full_name']) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($payslip['email']) ?></p>
                <p><strong>Base Salary:</strong> $<?= number_format($payslip['base_salary'], 2) ?></p>
                <p><strong>Deductions:</strong> $<?= number_format($payslip['deductions'], 2) ?></p>
                <p><strong>Bonus:</strong> $<?= number_format($payslip['overtime'], 2) ?></p>
                <p><strong>Net Salary:</strong> $<?= number_format($payslip['net_salary'], 2) ?></p>
                <p><strong>Processed Date:</strong> <?= date('F Y', strtotime($payslip['processed_at'])) ?></p>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>