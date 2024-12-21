<?php
include 'db.php';  // Ensure this includes your database connection
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get the logged-in user ID and role
$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Fetch user info based on the role (Faculty or Institution)
$query = "SELECT name, email FROM users WHERE id = '$user_id'";
$result = mysqli_query($conn, $query);

// Check if the query executed correctly
if (!$result) {
    die("Database query failed: " . mysqli_error($conn));
}

$user_info = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - EduConnect</title>
    <link rel="stylesheet" href="style.css">  <!-- Ensure style.css is correctly linked -->
</head>
<body>
    <div class="navbar">
        <h1>EduConnect</h1>
        <div>
            <a href="logout.php">Logout</a> <!-- Logout functionality -->
        </div>
    </div>

    <div class="container">
        <h2>Welcome, <?php echo htmlspecialchars($user_info['name']); ?>!</h2>
        <p>Your Email: <?php echo htmlspecialchars($user_info['email']); ?></p>

        <?php if ($role == 'faculty'): ?>
            <!-- Faculty Dashboard -->
            <h3>Quick Links</h3>
            <ul>
                <li><a href="view_jobs.php">Browse Available Jobs</a></li>
                <li><a href="profile.php">Update Profile</a></li>
                <li><a href="applications.php">View My Applications</a></li>
            </ul>

            <h3>Your Job Applications</h3>
            <?php
            // Fetch faculty's job applications
            $applications_query = "SELECT jobs.title, applications.status 
                                   FROM applications
                                   JOIN jobs ON applications.job_id = jobs.id
                                   WHERE applications.faculty_id = '$user_id'";
            $applications_result = mysqli_query($conn, $applications_query);
            
            if (!$applications_result) {
                die("Query failed: " . mysqli_error($conn));
            }

            if (mysqli_num_rows($applications_result) > 0): ?>
                <table>
                    <tr>
                        <th>Job Title</th>
                        <th>Status</th>
                    </tr>
                    <?php while ($row = mysqli_fetch_assoc($applications_result)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['title']); ?></td>
                            <td><?php echo htmlspecialchars($row['status']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            <?php else: ?>
                <p>You have not applied to any jobs yet.</p>
            <?php endif; ?>

        <?php elseif ($role == 'institution'): ?>
            <!-- Institution Dashboard -->
            <h3>Quick Links</h3>
            <ul>
                <li><a href="post_job.php">Post a New Job</a></li>
                <li><a href="profile.php">Update Profile</a></li>
            </ul>

            <h3>Your Job Postings</h3>
            <?php
            // Fetch institution's job postings
            $jobs_query = "SELECT id, title, created_at FROM jobs WHERE institution_id = '$user_id'";
            $jobs_result = mysqli_query($conn, $jobs_query);

            if (!$jobs_result) {
                die("Query failed: " . mysqli_error($conn));
            }

            if (mysqli_num_rows($jobs_result) > 0): ?>
                <table>
                    <tr>
                        <th>Job Title</th>
                        <th>Posted On</th>
                        <th>Actions</th>
                    </tr>
                    <?php while ($row = mysqli_fetch_assoc($jobs_result)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['title']); ?></td>
                            <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                            <td>
                                <!-- Link to view applicants for this job -->
                                <a href="view_applicants.php?job_id=<?php echo $row['id']; ?>" class="button">View Applicants</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            <?php else: ?>
                <p>You have not posted any jobs yet.</p>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</body>
</html>
