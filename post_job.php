<?php
include 'db_connection.php';
session_start();

if ($_SESSION['role'] != 'employer') {
    echo "Unauthorized access.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $requirements = $_POST['requirements'];
    $location = $_POST['location'];
    $employer_id = $_SESSION['user_id'];

    $sql = "INSERT INTO jobs (employer_id, title, description, requirements, location) VALUES ('$employer_id', '$title', '$description', '$requirements', '$location')";

    if ($conn->query($sql) === TRUE) {
        echo "Job posted successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
