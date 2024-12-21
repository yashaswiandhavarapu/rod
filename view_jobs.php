<?php
include 'db.php';
session_start();

// Check if the user is logged in and has the correct role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'faculty') {
    header("Location: login.php");
    exit();
}

// Get the logged-in user ID
$user_id = $_SESSION['user_id'];

// Fetch the list of available jobs with 'open' status and the corresponding institution name
$query = "SELECT jobs.id, jobs.title, jobs.description, users.name as institution_name 
          FROM jobs 
          JOIN users ON jobs.institution_id = users.id 
          WHERE jobs.status = 'open'";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Jobs - EduConnect</title>
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
        <h2>Available Jobs</h2>

        <?php if (mysqli_num_rows($result) > 0): ?>
            <table>
                <tr>
                    <th>Job Title</th>
                    <th>Description</th>
                    <th>Institution</th>
                    <th>Action</th>
                </tr>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                        <td><?php echo htmlspecialchars($row['description']); ?></td>
                        <td><?php echo htmlspecialchars($row['institution_name']); ?></td>
                        <td>
                            <form action="apply_for_job.php" method="POST">
                                <input type="hidden" name="job_id" value="<?php echo $row['id']; ?>">
                                <button type="submit">Apply</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>No available jobs at the moment.</p>
        <?php endif; ?>
    </div>
</body>
</html>
