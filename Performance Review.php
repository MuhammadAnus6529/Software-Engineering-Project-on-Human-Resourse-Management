<?php
session_start();
include 'db_connect.php'; // Database connection

// Handle performance update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['evaluation_id'], $_POST['rating'], $_POST['feedback'])) {
    $evaluation_id = intval($_POST['evaluation_id']);
    $rating = intval($_POST['rating']);
    $feedback = trim($_POST['feedback']);

    $stmt = $conn->prepare("UPDATE PerformanceEvaluations SET rating = ?, feedback = ? WHERE evaluation_id = ?");
    $stmt->bind_param("isi", $rating, $feedback, $evaluation_id);
    if ($stmt->execute()) {
        $message = "Performance review updated successfully.";
    } else {
        $error = "Failed to update performance review.";
    }
    $stmt->close();
}

// Fetch performance evaluations with employee and reviewer names
$sql = "SELECT p.evaluation_id, e.full_name AS employee_name, r.full_name AS reviewer_name, 
               p.evaluation_date, p.rating, p.feedback
        FROM PerformanceEvaluations p
        JOIN Employees emp ON p.employee_id = emp.employee_id
        JOIN Users e ON emp.user_id = e.user_id
        LEFT JOIN Users r ON p.reviewer_id = r.user_id
        ORDER BY p.evaluation_date DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Performance Reviews</title>
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
        .rating {
            font-weight: bold;
            color: #27ae60;
        }
        .action-form {
            display: inline;
        }
        .action-form input, .action-form textarea, .action-form button {
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
        <h2>Performance Reviews</h2>

        <?php if (!empty($message)) echo "<p class='message success'>$message</p>"; ?>
        <?php if (!empty($error)) echo "<p class='message error'>$error</p>"; ?>

        <table>
            <tr>
                <th>Employee</th>
                <th>Reviewer</th>
                <th>Evaluation Date</th>
                <th>Rating</th>
                <th>Feedback</th>
                <th>Action</th>
            </tr>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($review = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($review['employee_name']) ?></td>
                        <td><?= htmlspecialchars($review['reviewer_name'] ?? 'N/A') ?></td>
                        <td><?= date('F j, Y', strtotime($review['evaluation_date'])) ?></td>
                        <td class="rating"><?= htmlspecialchars($review['rating']) ?>/5</td>
                        <td><?= nl2br(htmlspecialchars($review['feedback'])) ?></td>
                        <td>
                            <form method="POST" class="action-form">
                                <input type="hidden" name="evaluation_id" value="<?= $review['evaluation_id'] ?>">
                                <input type="number" name="rating" min="1" max="5" value="<?= htmlspecialchars($review['rating']) ?>" required>
                                <textarea name="feedback" rows="2" placeholder="Update feedback"><?= htmlspecialchars($review['feedback']) ?></textarea>
                                <button type="submit">Update</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">No performance reviews available.</td>
                </tr>
            <?php endif; ?>
        </table>
    </div>

</body>
</html>