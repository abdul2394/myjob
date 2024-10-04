<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // If the user is not logged in, redirect them to the login page
    header("Location: login.html");
    exit();
}

// Include the database connection
include 'db_connection.php';

// Fetch user information from the session
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$role = $_SESSION['role'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Job Portal</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-5">
        <h2>Welcome, <?php echo htmlspecialchars($username); ?>!</h2>
        <p>Your role: <strong><?php echo htmlspecialchars($role); ?></strong></p>

        <?php if ($role == 'employer'): ?>
            <h3>Your Job Listings</h3>
            <a href="post_job.php" class="btn btn-primary mb-3">Post a New Job</a>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Job Title</th>
                        <th>Description</th>
                        <th>Location</th>
                        <th>Date Posted</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch job listings posted by the employer
                    $sql = "SELECT * FROM jobs WHERE employer_id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $user_id);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['title']); ?></td>
                            <td><?php echo htmlspecialchars($row['description']); ?></td>
                            <td><?php echo htmlspecialchars($row['location']); ?></td>
                            <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <h3>Jobs You've Applied For</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Job Title</th>
                        <th>Company</th>
                        <th>Status</th>
                        <th>Application Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch job applications for the candidate
                    $sql = "SELECT applications.*, jobs.title, jobs.employer_id, users.username AS company
                            FROM applications
                            JOIN jobs ON applications.job_id = jobs.id
                            JOIN users ON jobs.employer_id = users.id
                            WHERE applications.candidate_id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $user_id);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['title']); ?></td>
                            <td><?php echo htmlspecialchars($row['company']); ?></td>
                            <td><?php echo htmlspecialchars($row['status']); ?></td>
                            <td><?php echo htmlspecialchars($row['applied_at']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
