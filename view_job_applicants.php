<?php
include 'db.php';
session_start();

// Check if the user is logged in and has the correct role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'employer') {
    header("Location: login.php");
    exit();
}

// Get the logged-in employer's ID and the job ID from the query string
$employer_id = $_SESSION['user_id'];
$job_id = $_GET['job_id'];

// Fetch the applicants for the specific job
$query = "
    SELECT u.name, u.email, a.application_date, a.status 
    FROM applications a 
    JOIN users u ON a.faculty_id = u.id 
    WHERE a.job_id = '$job_id' AND a.status != 'rejected'
";
$result = mysqli_query($conn, $query);

// Fetch the job title for display
$job_query = "SELECT title FROM jobs WHERE id = '$job_id' AND employer_id = '$employer_id'";
$job_result = mysqli_query($conn, $job_query);
$job = mysqli_fetch_assoc($job_result);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applicants for <?php echo htmlspecialchars($job['title']); ?> - EduConnect</title>
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
        <h2>Applicants for <?php echo htmlspecialchars($job['title']); ?></h2>

        <?php if (mysqli_num_rows($result) > 0): ?>
            <table>
                <tr>
                    <th>Applicant Name</th>
                    <th>Email</th>
                    <th>Application Date</th>
                    <th>Status</th>
                </tr>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['application_date']); ?></td>
                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>No applicants for this job yet.</p>
        <?php endif; ?>
    </div>
</body>
</html>
