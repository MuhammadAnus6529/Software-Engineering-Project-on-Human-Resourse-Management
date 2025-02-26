<?php
session_start();
include 'db_connect.php'; // Database connection

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format!";
    } elseif (!empty($email)) {
        // Check if email exists
        $stmt = $conn->prepare("SELECT user_id FROM Users WHERE email = ?");
        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $stmt->bind_result($user_id);
                $stmt->fetch();
                $stmt->close(); // Close first statement

                // Generate a reset token and send email (for better security)
                $reset_token = bin2hex(random_bytes(16)); // Generate a unique token
                $expiration_time = date('Y-m-d H:i:s', strtotime('+1 hour')); // Set expiration time (e.g., 1 hour)

                // Store reset token and expiration time in the database
                $stmt = $conn->prepare("UPDATE Users SET reset_token = ?, reset_token_expiry = ? WHERE email = ?");
                if ($stmt) {
                    $stmt->bind_param("sss", $reset_token, $expiration_time, $email);
                    $stmt->execute();
                    $stmt->close();

                    // Send the reset link to the user's email
                    $reset_link = "https://yourdomain.com/reset_password.php?token=$reset_token";
                    $subject = "Password Reset Request";
                    $message = "To reset your password, click the following link: $reset_link";
                    $headers = "From: no-reply@yourdomain.com";

                    if (mail($email, $subject, $message, $headers)) {
                        $success = "A password reset link has been sent to your email.";
                    } else {
                        $error = "Failed to send the reset link. Please try again.";
                    }
                } else {
                    $error = "Database error! Please try again.";
                }
            } else {
                $error = "Email not found!";
            }
        } else {
            $error = "Database error! Please try again.";
        }
    } else {
        $error = "Please enter your email!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - HRMS</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(to right, #007bff, #00c6ff);
        }

        .container {
            background: white;
            padding: 30px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            width: 350px;
            text-align: center;
        }

        h2 {
            margin-bottom: 15px;
            color: #333;
        }

        p {
            color: #666;
            font-size: 14px;
            margin-bottom: 15px;
        }

        input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        button {
            background: #007bff;
            color: white;
            border: none;
            padding: 12px;
            width: 100%;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #0056b3;
        }

        a {
            display: block;
            margin-top: 15px;
            color: #007bff;
            text-decoration: none;
            font-size: 14px;
        }

        .error, .success {
            margin-top: 10px;
            font-size: 14px;
            padding: 10px;
            border-radius: 5px;
        }

        .error {
            color: red;
            background: #f8d7da;
            border: 1px solid #f5c6cb;
        }

        .success {
            color: green;
            background: #d4edda;
            border: 1px solid #c3e6cb;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Forgot Password</h2>
        <p>Enter your email to reset your password.</p>
        
        <form action="forgot_password.php" method="POST">
            <input type="email" name="email" placeholder="Enter your email" required>
            <button type="submit">Reset Password</button>
        </form>

        <?php 
        if (!empty($error)) echo "<p class='error'>$error</p>"; 
        if (!empty($success)) echo "<p class='success'>$success</p>"; 
        ?>
        
        <a href="login.php">Back to Login</a>
    </div>
</body>
</html>