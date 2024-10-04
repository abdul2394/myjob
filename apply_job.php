<?php
include 'db_connection.php';
session_start();

if ($_SESSION['role'] != 'candidate') {
    echo "Unauthorized access.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $job_id = $_POST['job_id'];
    $candidate_id = $_SESSION['user_id'];
    $resume = $_FILES['resume']['name'];
    $cover_letter = $_POST['cover_letter'];

    // Move uploaded file
    move_uploaded_file($_FILES['resume']['tmp_name'], "uploads/" . $resume);

    $sql = "INSERT INTO applications (job_id, candidate_id, resume, cover_letter) VALUES ('$job_id', '$candidate_id', '$resume', '$cover_letter')";

    if ($conn->query($sql) === TRUE) {
        echo "Application submitted successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
