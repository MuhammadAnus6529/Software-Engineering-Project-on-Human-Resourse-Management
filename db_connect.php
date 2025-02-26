<?php
$servername = "localhost"; // Change if using a remote database
$username = "root"; // Your MySQL username
$password = ""; // Your MySQL password (leave empty if none)
$database = "HRMS_DB"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>