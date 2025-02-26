<?php
session_start();
include 'db_connect.php'; // Database connection

// Handle status update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['leave_id'], $_POST['status'])) {
    $leave_id = intval($_POST['leave_id']);
    $status = in_array($_POST['status'], ['Approved', 'Rejected']) ? $_POST['status'] : 'Pending';

    $stmt = $conn->prepare("UPDATE LeaveRequests SET status = ? WHERE leave_id = ?");
    $stmt->bind_param("si", $status, $leave_id);
    if ($stmt->execute()) {
        $message = "Leave request updated successfully.";
    } else {
        $error = "Failed to update leave request.";
    }
    $stmt->close();
}

// Fetch leave requests with user details
$sql = "SELECT l.leave_id, u.full_name, l.leave_type, l.start_date, l.end_date, l.status, l.applied_on 
        FROM LeaveRequests l
        JOIN Employees e ON l.employee_id = e.employee_id
        JOIN Users u ON e.user_id = u.user_id
        ORDER BY l.applied_on DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Management</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #2980b9, #6dd5fa);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            flex-direction: column;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            width: 90%;
            max-width: 900px;
            text-align: center;
        }
        h2 {
            color: #333;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: #f4f4f4;
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        th {
            background: #007bff;
            color: white;
        }
        tr:nth-child(even) {
            background: #f9f9f9;
        }
        .status {
            font-weight: bold;
            color: #fff;
            padding: 5px 10px;
            border-radius: 5px;
        }
        .status.Pending { background: #f39c12; }
        .status.Approved { background: #28a745; }
        .status.Rejected { background: #dc3545; }
        .action-form {
            display: inline;
        }
        .action-form select, .action-form button {
            padding: 5px;
            font-size: 14px;
            margin-right: 5px;
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
        <h2>Leave Management</h2>

        <?php if (!empty($message)) echo "<p class='message success'>$message</p>"; ?>
        <?php if (!empty($error)) echo "<p class='message error'>$error</p>"; ?>

        <table>
            <tr>
                <th>Employee</th>
                <th>Leave Type</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Status</th>
                <th>Applied On</th>
                <th>Action</th>
            </tr>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($leave = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($leave['full_name']) ?></td>
                        <td><?= htmlspecialchars($leave['leave_type']) ?></td>
                        <td><?= htmlspecialchars($leave['start_date']) ?></td>
                        <td><?= htmlspecialchars($leave['end_date']) ?></td>
                        <td><span class="status <?= htmlspecialchars($leave['status']) ?>"><?= htmlspecialchars($leave['status']) ?></span></td>
                        <td><?= date('F j, Y', strtotime($leave['applied_on'])) ?></td>
                        <td>
                            <form method="POST" class="action-form">
                                <input type="hidden" name="leave_id" value="<?= $leave['leave_id'] ?>">
                                <select name="status">
                                    <option value="Pending" <?= $leave['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                                    <option value="Approved" <?= $leave['status'] == 'Approved' ? 'selected' : '' ?>>Approved</option>
                                    <option value="Rejected" <?= $leave['status'] == 'Rejected' ? 'selected' : '' ?>>Rejected</option>
                                </select>
                                <button type="submit">Update</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">No leave requests available.</td>
                </tr>
            <?php endif; ?>
        </table>
    </div>

</body>
</html>