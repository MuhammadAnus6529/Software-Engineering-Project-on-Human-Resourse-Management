<?php
session_start();
include 'db_connect.php'; // Database connection

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $employee_id = intval($_POST['employee_id']);
    $salary = floatval($_POST['salary']);
    $deductions = floatval($_POST['deductions']);
    $bonus = floatval($_POST['bonus']);

    // Validate input
    if ($employee_id > 0 && $salary > 0) {
        // Calculate net salary
        $net_salary = $salary - $deductions + $bonus;

        // Insert payroll data into Payroll table
        $stmt = $conn->prepare("INSERT INTO Payroll (employee_id, base_salary, deductions, overtime, net_salary, processed_at) VALUES (?, ?, ?, ?, ?, NOW())");
        if ($stmt) {
            $stmt->bind_param("idddd", $employee_id, $salary, $deductions, $bonus, $net_salary);
            if ($stmt->execute()) {
                $success = "Payroll processed successfully. Net Salary: $" . number_format($net_salary, 2);
            } else {
                $error = "Error processing payroll. Please try again.";
            }
            $stmt->close();
        } else {
            $error = "Database error. Please try again.";
        }
    } else {
        $error = "Invalid input. Please enter valid salary details.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payroll Processing</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.2);
            width: 350px;
            text-align: center;
        }
        h2 {
            margin-bottom: 20px;
            color: #333;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            transition: background 0.3s;
        }
        button:hover {
            background-color: #218838;
        }
        .message {
            padding: 10px;
            margin-top: 15px;
            border-radius: 5px;
            text-align: center;
            font-size: 14px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Payroll Processing</h2>
        <form action="process_payroll.php" method="POST">
            <input type="text" id="employee_id" name="employee_id" placeholder="Employee ID" required>
            <input type="number" id="salary" name="salary" placeholder="Base Salary" required>
            <input type="number" id="deductions" name="deductions" placeholder="Deductions" required>
            <input type="number" id="bonus" name="bonus" placeholder="Bonus">
            <button type="submit">Process Payroll</button>
        </form>

        <?php 
        if (!empty($error)) echo "<p class='message error'>$error</p>"; 
        if (!empty($success)) echo "<p class='message success'>$success</p>"; 
        ?>
    </div>
</body>
</html>