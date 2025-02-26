

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        header {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 1em 0;
        }
        header h2 {
            margin: 0;
        }
        .container {
            width: 50%;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            font-size: 16px;
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .form-group input[type="number"] {
            width: 100%;
        }
        button {
            background-color: #333;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #555;
        }
        .message {
            text-align: center;
            font-size: 16px;
            margin-top: 20px;
        }
        a {
            text-decoration: none;
            color: #333;
            font-size: 14px;
            display: inline-block;
            margin-top: 10px;
            text-align: center;
        }
        a:hover {
            color: #555;
        }
    </style>
</head>
<body>
    <header>
        <h2>Employee Profile</h2>
    </header>

    <div class="container">
        <form method="GET" action="Employee Profile">
            <div class="form-group">
                <label for="employee_id">Search Employee by ID:</label>
                <input type="number" name="id" id="employee_id" placeholder="Enter Employee ID" required>
            </div>
            <div class="form-group">
                <button type="submit">Search</button>
            </div>
        </form>
        <?php
session_start();
include 'db_connect.php'; // Database connection

// Check if 'id' is passed and is valid
$employee_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($employee_id <= 0) {
    echo "<p>Please provide a valid Employee ID to search.</p>";
    exit();
}

// Fetch employee details
$sql = "SELECT u.user_id, u.full_name, u.email, e.department, e.job_title, e.salary 
        FROM Employees e
        JOIN Users u ON e.user_id = u.user_id
        WHERE e.employee_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$result = $stmt->get_result();
$employee = $result->fetch_assoc();
$stmt->close();

if (!$employee) {
    die("Employee not found.");
}

// Update employee details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $department = trim($_POST['department']);
    $job_title = trim($_POST['job_title']);
    $salary = floatval($_POST['salary']);

    if ($full_name && $email && $department && $job_title && $salary > 0) {
        // Update Users table
        $stmt = $conn->prepare("UPDATE Users SET full_name = ?, email = ? WHERE user_id = ?");
        $stmt->bind_param("ssi", $full_name, $email, $employee['user_id']);
        $stmt->execute();
        $stmt->close();

        // Update Employees table
        $stmt = $conn->prepare("UPDATE Employees SET department = ?, job_title = ?, salary = ? WHERE employee_id = ?");
        $stmt->bind_param("ssdi", $department, $job_title, $salary, $employee_id);
        $stmt->execute();
        $stmt->close();

        echo "<p style='color: green;'>Employee details updated successfully.</p>";
    } else {
        echo "<p style='color: red;'>Please fill in all fields correctly.</p>";
    }
}
?>
        <?php if ($employee): ?>
            <!-- Employee Details Form -->
            <form method="POST">
                <div class="form-group">
                    <label>Full Name:</label>
                    <input type="text" name="full_name" value="<?= htmlspecialchars($employee['full_name']) ?>" required>
                </div>

                <div class="form-group">
                    <label>Email:</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($employee['email']) ?>" required>
                </div>

                <div class="form-group">
                    <label>Department:</label>
                    <input type="text" name="department" value="<?= htmlspecialchars($employee['department']) ?>" required>
                </div>

                <div class="form-group">
                    <label>Job Title:</label>
                    <input type="text" name="job_title" value="<?= htmlspecialchars($employee['job_title']) ?>" required>
                </div>

                <div class="form-group">
                    <label>Salary:</label>
                    <input type="number" name="salary" step="0.01" value="<?= htmlspecialchars($employee['salary']) ?>" required>
                </div>

                <div class="form-group">
                    <button type="submit">Update</button>
                </div>
            </form>
        <?php endif; ?>

        <div class="message">
            <a href="Employee List.php">Back to Employee List</a>
        </div>
    </div>
</body>
</html>