<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'faculty') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['job_id'])) {
    $job_id = intval($_GET['job_id']);

    $query = "SELECT jobs.title, jobs.description, jobs.requirements, users.name AS institution_name, users.email AS institution_email 
              FROM jobs 
              JOIN users ON jobs.institution_id = users.id 
              WHERE jobs.id = $job_id";

    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $job = mysqli_fetch_assoc($result);
    } else {
        echo "<div class='container'><p>Job not found. <a href='view_jobs.php'>Go back</a></p></div>";
        exit();
    }
} else {
    header("Location: view_jobs.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Job Details - EduConnect</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="navbar">
        <h1>EduConnect</h1>
        <div>
            <a href="dashboard.php">Dashboard</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <div class="container">
        <h2><?php echo $job['title']; ?></h2>
        <p><strong>Institution:</strong> <?php echo $job['institution_name']; ?></p>
        <p><strong>Description:</strong> <?php echo $job['description']; ?></p>
        <p><strong>Requirements:</strong> <?php echo $job['requirements']; ?></p>
        <p><strong>Contact Email:</strong> <a href="mailto:<?php echo $job['institution_email']; ?>"><?php echo $job['institution_email']; ?></a></p>
        <a href="view_jobs.php" class="button">Back to Job Listings</a>
    </div>
</body>
</html>
