<?php
session_start();
include 'db_connect.php'; // Database connection

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $department = trim($_POST['department']);
    $job_title = trim($_POST['job_title']);
    $salary = floatval($_POST['salary']);
    $hire_date = $_POST['hire_date'];
    
    if (!empty($full_name) && !empty($email) && !empty($department) && !empty($job_title) && $salary > 0 && !empty($hire_date)) {
        // Insert into Users table
        $stmt = $conn->prepare("INSERT INTO Users (full_name, email) VALUES (?, ?)");
        $stmt->bind_param("ss", $full_name, $email);
        if ($stmt->execute()) {
            $user_id = $stmt->insert_id;
            $stmt->close();
            
            // Insert into Employees table
            $stmt = $conn->prepare("INSERT INTO Employees (user_id, department, job_title, salary, hire_date) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("issds", $user_id, $department, $job_title, $salary, $hire_date);
            if ($stmt->execute()) {
                $success = "Employee onboarded successfully.";
            } else {
                $error = "Error onboarding employee.";
            }
            $stmt->close();
        } else {
            $error = "Error adding user.";
        }
    } else {
        $error = "Please fill in all required fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Onboarding</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #2980b9, #6dd5fa);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            text-align: center;
        }
        h2 {
            color: #333;
        }
        input, select, button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
        .message {
            padding: 10px;
            margin-top: 10px;
            border-radius: 5px;
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
        <h2>Employee Onboarding</h2>
        <form action="onboarding.php" method="POST">
            <input type="text" name="full_name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="department" placeholder="Department" required>
            <input type="text" name="job_title" placeholder="Job Title" required>
            <input type="number" name="salary" placeholder="Salary" required>
            <input type="date" name="hire_date" required>
            <button type="submit">Onboard Employee</button>
        </form>
        <?php 
        if (!empty($error)) echo "<p class='message error'>$error</p>"; 
        if (!empty($success)) echo "<p class='message success'>$success</p>"; 
        ?>
    </div>
</body>
</html>
