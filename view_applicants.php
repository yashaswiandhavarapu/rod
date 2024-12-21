<?php
include 'db.php';
session_start();

// Check if the user is logged in and is an institution (employer)
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'institution') {
    header("Location: login.php");
    exit();
}

// Get the logged-in user ID (institution)
$institution_id = $_SESSION['user_id'];

// Check if a job ID is provided via GET
if (!isset($_GET['job_id'])) {
    die("No job selected.");
}

$job_id = $_GET['job_id'];

// Fetch the job title and other details of the selected job
$job_query = "SELECT title FROM jobs WHERE id = '$job_id' AND institution_id = '$institution_id'";
$job_result = mysqli_query($conn, $job_query);

if (!$job_result || mysqli_num_rows($job_result) == 0) {
    die("Job not found or you do not have permission to view this job.");
}

$job = mysqli_fetch_assoc($job_result);
$job_title = $job['title'];

// Fetch the applicants for this job
$applicants_query = "SELECT a.id as application_id, u.name, u.email, a.status 
                     FROM applications a
                     JOIN users u ON a.faculty_id = u.id
                     WHERE a.job_id = '$job_id'";
$applicants_result = mysqli_query($conn, $applicants_query);

if (!$applicants_result) {
    die("Error fetching applicants: " . mysqli_error($conn));
}

// Handle Accepting an Applicant
if (isset($_POST['accept_application'])) {
    $application_id = $_POST['application_id'];
    // Update the application status to 'Accepted'
    $accept_query = "UPDATE applications SET status = 'Accepted' WHERE id = '$application_id' AND job_id = '$job_id' AND status != 'Accepted'";
    if (mysqli_query($conn, $accept_query)) {
        echo "<script>alert('Applicant accepted successfully.');</script>";
        header("Location: view_applicants.php?job_id=$job_id");  // Refresh the page
        exit();
    } else {
        echo "<script>alert('Error accepting the applicant.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applicants for Job - EduConnect</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="navbar">
        <h1>EduConnect</h1>
        <div>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <div class="container">
        <h2>Applicants for: <?php echo htmlspecialchars($job_title); ?></h2>

        <?php if (mysqli_num_rows($applicants_result) > 0): ?>
            <table>
                <tr>
                    <th>Applicant Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                <?php while ($applicant = mysqli_fetch_assoc($applicants_result)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($applicant['name']); ?></td>
                        <td><?php echo htmlspecialchars($applicant['email']); ?></td>
                        <td><?php echo htmlspecialchars($applicant['status']); ?></td>
                        <td>
                            <?php if ($applicant['status'] != 'Accepted'): ?>
                                <form action="view_applicants.php?job_id=<?php echo $job_id; ?>" method="POST">
                                    <input type="hidden" name="application_id" value="<?php echo $applicant['application_id']; ?>">
                                    <button type="submit" name="accept_application" class="button">Accept</button>
                                </form>
                            <?php else: ?>
                                <button disabled class="button">Accepted</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>No applicants have applied for this job yet.</p>
        <?php endif; ?>
        
        <a href="dashboard.php" class="button">Back to Dashboard</a>
    </div>
</body>
</html>
