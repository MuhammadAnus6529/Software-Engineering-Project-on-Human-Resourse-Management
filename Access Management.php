<?php
session_start();
include 'db_connect.php'; // Database connection


if (!isset($_SESSION['username']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'HR Manager') {
    header('Location: login.php'); // Redirect if not logged in or not an HR Manager
    exit();
}


// Fetch all users and their roles
$query = "SELECT user_id, full_name, email, role FROM Users";
$result = $conn->query($query);

// Handle role and permission updates
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_role'])) {
    $user_id = $_POST['user_id'];
    $new_role = $_POST['role'];

    // Update user role in the database
    $update_query = "UPDATE Users SET role = ? WHERE user_id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("si", $new_role, $user_id);
    $stmt->execute();
    $stmt->close();

    // Redirect to reload the page with updated information
    header("Location: Access Management.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Management</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: linear-gradient(to right, #4A90E2, #50C878);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            padding: 20px;
        }

        .container {
            width: 100%;
            max-width: 700px;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h1 {
            font-size: 28px;
            color: #333;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        th, td {
            padding: 15px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #007BFF;
            color: white;
        }

        td {
            background-color: #f9f9f9;
        }

        select, button {
            padding: 10px;
            width: 150px;
            border-radius: 5px;
            border: 1px solid #ddd;
            margin-top: 10px;
            font-size: 16px;
        }

        button {
            background-color: #007BFF;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        button:active {
            background-color: #003f7f;
        }

        footer {
            margin-top: 20px;
            font-size: 14px;
            color: #666;
        }

        footer a {
            color: #007BFF;
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Access Management</h1>

    <table>
        <thead>
            <tr>
                <th>User ID</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['user_id']; ?></td>
                    <td><?php echo $row['full_name']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['role']; ?></td>
                    <td>
                        <form method="POST" action="Access Management.php">
                            <input type="hidden" name="user_id" value="<?php echo $row['user_id']; ?>">
                            <select name="role">
                                <option value="HR Manager" <?php echo ($row['role'] === 'HR Manager') ? 'selected' : ''; ?>>HR Manager</option>
                                <option value="Employee" <?php echo ($row['role'] === 'Employee') ? 'selected' : ''; ?>>Employee</option>
                                <option value="Manager" <?php echo ($row['role'] === 'Manager') ? 'selected' : ''; ?>>Manager</option>
                                <option value="IT Admin" <?php echo ($row['role'] === 'IT Admin') ? 'selected' : ''; ?>>IT Admin</option>
                                <option value="Payroll Officer" <?php echo ($row['role'] === 'Payroll Officer') ? 'selected' : ''; ?>>Payroll Officer</option>
                            </select>
                            <button type="submit" name="update_role">Update Role</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <footer>
        <p>Access Management System - <a href="login.php">Logout</a></p>
    </footer>
</div>

</body>
</html>