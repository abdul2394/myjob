<?php
// Database connection settings
$servername = "localhost";
$username = "root"; // Default XAMPP user
$password = ""; // Default XAMPP password is empty
$dbname = "job_portal_db"; // The database name you created in phpMyAdmin

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
