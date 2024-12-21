<?php
include 'db.php';
session_start();

// Check if the user is logged in and has the correct role
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get the logged-in user ID and role
$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Fetch user info based on the role (Faculty or Institution)
$query = "SELECT name, email, qualifications, institution_name FROM users WHERE id = '$user_id'";
$result = mysqli_query($conn, $query);
$user_info = mysqli_fetch_assoc($result);

// Handle form submission to update profile
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    // For faculty, also update qualifications
    $qualifications = isset($_POST['qualifications']) ? mysqli_real_escape_string($conn, $_POST['qualifications']) : null;

    // For institution, update institution_name
    $institution_name = isset($_POST['institution_name']) ? mysqli_real_escape_string($conn, $_POST['institution_name']) : null;

    // Update the user profile in the database
    if ($role == 'faculty') {
        $update_query = "UPDATE users SET name='$name', email='$email', qualifications='$qualifications' WHERE id='$user_id'";
    } elseif ($role == 'institution') {
        $update_query = "UPDATE users SET name='$name', email='$email', institution_name='$institution_name' WHERE id='$user_id'";
    }

    if (mysqli_query($conn, $update_query)) {
        echo "<p>Profile updated successfully!</p>";
    } else {
        echo "<p>Error updating profile: " . mysqli_error($conn) . "</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile - EduConnect</title>
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
        <h2>Update Your Profile</h2>

        <form method="POST">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user_info['name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user_info['email']); ?>" required>
            </div>

            <?php if ($role == 'faculty'): ?>
                <div class="form-group">
                    <label for="qualifications">Qualifications</label>
                    <textarea id="qualifications" name="qualifications" required><?php echo htmlspecialchars($user_info['qualifications']); ?></textarea>
                </div>
            <?php elseif ($role == 'institution'): ?>
                <div class="form-group">
                    <label for="institution_name">Institution Name</label>
                    <input type="text" id="institution_name" name="institution_name" value="<?php echo htmlspecialchars($user_info['institution_name']); ?>" required>
                </div>
            <?php endif; ?>

            <div class="form-group">
                <button type="submit">Update Profile</button>
            </div>
        </form>
    </div>
</body>
</html>
