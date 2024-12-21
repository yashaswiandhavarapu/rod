<?php
include 'db.php';
session_start();

// Check if the user is logged in and has the correct role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'faculty') {
    header("Location: login.php");
    exit();
}

// Get the logged-in user ID and the job ID from the form
$user_id = $_SESSION['user_id'];
$job_id = $_POST['job_id'];

// Insert the application into the database
$query = "INSERT INTO applications (faculty_id, job_id, application_date, status) 
          VALUES ('$user_id', '$job_id', NOW(), 'pending')";

if (mysqli_query($conn, $query)) {
    echo "<p>Application submitted successfully. Your application is now pending review.</p>";
    echo "<a href='view_jobs.php'>Go back to available jobs</a>";
} else {
    echo "<p>Error submitting application: " . mysqli_error($conn) . "</p>";
    echo "<a href='view_jobs.php'>Try again</a>";
}
?>
