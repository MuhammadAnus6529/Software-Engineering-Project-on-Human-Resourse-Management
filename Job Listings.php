<?php
session_start();
include 'db_connect.php'; // Database connection

// Fetch job postings
$sql = "SELECT title, department, description, posted_on FROM JobPostings ORDER BY posted_on DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Listings</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #2c3e50, #4ca1af);
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
        .job-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .job-item {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
            text-align: left;
        }
        .job-item h3 {
            margin: 0;
            color: #007bff;
        }
        .job-item p {
            margin: 5px 0;
            color: #555;
        }
        .posted-date {
            font-size: 12px;
            color: #888;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Job Listings</h2>
        <ul class="job-list">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($job = $result->fetch_assoc()): ?>
                    <li class="job-item">
                        <h3><?= htmlspecialchars($job['title']) ?></h3>
                        <p><strong>Department:</strong> <?= htmlspecialchars($job['department']) ?></p>
                        <p><?= nl2br(htmlspecialchars($job['description'])) ?></p>
                        <p class="posted-date"><strong>Posted on:</strong> <?= date('F j, Y', strtotime($job['posted_on'])) ?></p>
                    </li>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No job postings available.</p>
            <?php endif; ?>
        </ul>
    </div>
</body>
</html>
