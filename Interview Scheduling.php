<?php
session_start();
include 'db_connect.php'; // Database connection

// Fetch job applications
$sql = "SELECT application_id, job_id, applicant_name, email, resume_link, status, applied_on FROM Applications ORDER BY applied_on DESC";
$result = $conn->query($sql);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_status'])) {
    $application_id = intval($_POST['application_id']);
    $new_status = $_POST['status'];
    $interview_date = !empty($_POST['applied_on']) ? $_POST['applied_on'] : null;

    $stmt = $conn->prepare("UPDATE Applications SET status = ?, applied_on = ? WHERE application_id = ?");
    $stmt->bind_param("ssi", $new_status, $interview_date, $application_id);
    $stmt->execute();
    $stmt->close();

    header("Location: Interview Scheduling.php"); // Refresh page
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interview Scheduling</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #2980b9, #6dd5fa);
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
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
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #2980b9;
            color: white;
        }
        select, input, button {
            padding: 8px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
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
    </style>
</head>
<body>

<div class="container">
    <h2>Interview Scheduling</h2>
    <table>
        <tr>
            <th>Applicant</th>
            <th>Email</th>
            <th>Resume</th>
            <th>Status</th>
            <th>Interview Date</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['applicant_name']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><a href="<?= htmlspecialchars($row['resume_link']) ?>" target="_blank">View Resume</a></td>
            <td>
                <form method="POST" style="display: flex; flex-direction: column;">
                    <input type="hidden" name="application_id" value="<?= $row['application_id'] ?>">
                    <select name="status">
                        <option value="Applied" <?= $row['status'] == 'Applied' ? 'selected' : '' ?>>Applied</option>
                        <option value="Interview Scheduled" <?= $row['status'] == 'Interview Scheduled' ? 'selected' : '' ?>>Interview Scheduled</option>
                        <option value="Rejected" <?= $row['status'] == 'Rejected' ? 'selected' : '' ?>>Rejected</option>
                        <option value="Hired" <?= $row['status'] == 'Hired' ? 'selected' : '' ?>>Hired</option>
                    </select>
                    <input type="datetime-local" name="interview_date" value="<?= $row['status'] == 'Interview Scheduled' ? $row['applied_on'] : '' ?>">
                    <button type="submit" name="update_status">Update</button>
                </form>
            </td>
            <td><?= htmlspecialchars($row['applied_on']) ?></td>
            <td><?= htmlspecialchars($row['status']) ?></td>


        </tr>
        <?php endwhile; ?>
    </table>
</div>

</body>
</html>
