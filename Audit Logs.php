<?php
session_start();
include 'db_connect.php'; // Database connection

// Fetch audit logs
$sql = "SELECT a.log_id, u.full_name AS user_name, a.action, a.timestamp 
        FROM AuditLogs a 
        LEFT JOIN Users u ON a.user_id = u.user_id 
        ORDER BY a.timestamp DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audit Logs</title>
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
            max-width: 800px;
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
        .timestamp {
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>System Audit Logs</h2>
        <table>
            <tr>
                <th>Log ID</th>
                <th>User</th>
                <th>Action</th>
                <th>Timestamp</th>
            </tr>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($log = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($log['log_id']) ?></td>
                        <td><?= htmlspecialchars($log['user_name'] ?? 'Unknown') ?></td>
                        <td><?= htmlspecialchars($log['action']) ?></td>
                        <td class="timestamp"><?= date('F j, Y, g:i A', strtotime($log['timestamp'])) ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No logs available.</td>
                </tr>
            <?php endif; ?>
        </table>
    </div>

</body>
</html>