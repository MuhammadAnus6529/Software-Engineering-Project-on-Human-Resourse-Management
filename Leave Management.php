<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 20px;
            background-color: #f4f4f4;
        }
        h2 {
            color: #333;
        }
        form {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            margin: 0 auto;
        }
        label, input, select, button {
            display: block;
            width: 100%;
            margin-bottom: 10px;
            padding: 8px;
        }
        button {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            padding: 10px;
            font-size: 16px;
        }
        button:hover {
            background-color: #0056b3;
        }
        .error-message {
            color: red;
            font-size: 14px;
            margin-top: 10px;
        }
    </style>
    <script>
        // Validate that end date is after start date
        function validateDates() {
            const startDate = document.getElementById("start_date").value;
            const endDate = document.getElementById("end_date").value;

            if (new Date(startDate) > new Date(endDate)) {
                alert("End date cannot be earlier than start date.");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <h2>Apply for Leave</h2>
    <form action="/apply_leave" method="POST" onsubmit="return validateDates()">
        <label for="employee_id">Employee ID:</label>
        <input type="text" id="employee_id" name="employee_id" required>

        <label for="leave_type">Leave Type:</label>
        <select id="leave_type" name="leave_type" required>
            <option value="sick">Sick Leave</option>
            <option value="casual">Casual Leave</option>
            <option value="annual">Annual Leave</option>
        </select>

        <label for="start_date">Start Date:</label>
        <input type="date" id="start_date" name="start_date" required>

        <label for="end_date">End Date:</label>
        <input type="date" id="end_date" name="end_date" required>

        <button type="submit">Apply Leave</button>

        <!-- Optional error message section -->
        <div class="error-message" id="error-message"></div>
    </form>
</body>
</html>