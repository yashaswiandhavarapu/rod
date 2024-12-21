<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'institution') {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $requirements = $_POST['requirements'];
    $institution_id = $_SESSION['user_id'];

    $query = "INSERT INTO jobs (institution_id, title, description, requirements) 
              VALUES ('$institution_id', '$title', '$description', '$requirements')";
    if (mysqli_query($conn, $query)) {
        echo "<div class='container'>Job posted successfully! <a href='dashboard.php'>Go back to dashboard</a></div>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Post a Job - EduConnect</title>
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
        <h2>Post a Job</h2>
        <form method="post">
            <input type="text" name="title" placeholder="Job Title" required>
            <textarea name="description" placeholder="Job Description" rows="4" required></textarea>
            <textarea name="requirements" placeholder="Job Requirements" rows="4" required></textarea>
            <button type="submit">Post Job</button>
        </form>
    </div>
</body>
</html>
