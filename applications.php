<?php
include 'db.php'; // Ensure this includes your database connection
session_start();

// Check if the user is logged in and has the correct role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'faculty') {
    header("Location: login.php");
    exit();
}

// Get the logged-in user ID
$user_id = $_SESSION['user_id'];

// Fetch the job applications of the logged-in faculty, including the institution name
$query = "SELECT jobs.title, jobs.institution_id, users.name AS institution_name, 
                 applications.status, applications.application_date 
          FROM applications 
          JOIN jobs ON applications.job_id = jobs.id 
          JOIN users ON jobs.institution_id = users.id 
          WHERE applications.faculty_id = '$user_id'";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Job Applications - EduConnect</title>
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
        <h2>Your Job Applications</h2>

        <?php if (mysqli_num_rows($result) > 0): ?>
            <table>
                <tr>
                    <th>Job Title</th>
                    <th>Institution</th>
                    <th>Application Date</th>
                    <th>Status</th>
                </tr>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                        <td><?php echo htmlspecialchars($row['institution_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['application_date']); ?></td>
                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>You have not applied to any jobs yet.</p>
        <?php endif; ?>
    </div>
</body>
</html>
