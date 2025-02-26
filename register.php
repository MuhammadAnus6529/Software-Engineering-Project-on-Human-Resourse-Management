<?php
session_start();
include 'db_connect.php'; // Database connection

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role = trim($_POST['role']);

    if (!empty($name) && !empty($email) && !empty($password) && !empty($role)) {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT email FROM Users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $error = "Email already registered!";
        } else {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO Users (full_name, email, password_hash, role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $email, $password_hash, $role);
            if ($stmt->execute()) {
                header("Location: login.php"); // Redirect to login
                exit();
            } else {
                $error = "Registration failed!";
            }
        }
        $stmt->close();
    } else {
        $error = "Please fill in all fields!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - HRMS</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 10px;
            width: 300px;
            text-align: center;
        }
        input, select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background: #007bff;
            color: white;
            border: none;
            padding: 10px;
            width: 100%;
            border-radius: 5px;
            cursor: pointer;
        }
        a {
            display: block;
            margin-top: 10px;
            color: #007bff;
            text-decoration: none;
        }
        .error {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Register</h2>
        <form action="register.php" method="POST">
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <select name="role" required>
                <option value="">Select Role</option>
                <option value="HR Manager">HR Manager</option>
                <option value="Employee">Employee</option>
                <option value="Manager">Manager</option>
                <option value="IT Administrator">IT Administrator</option>
                <option value="Payroll Officer">Payroll Officer</option>
            </select>
            <button type="submit">Register</button>
        </form>
        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
        <a href="login.php">Back to Login</a>
    </div>
</body>
</html>
