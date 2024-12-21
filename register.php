<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $query = "INSERT INTO users (name, email, password, role) VALUES ('$name', '$email', '$password', '$role')";
    if (mysqli_query($conn, $query)) {
        echo "Registration successful! <a href='login.php'>Login here</a>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register - EduConnect</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="navbar">
        <h1>EduConnect</h1>
</div>
<div class="container">
    <h2>Register</h2>
    <form method="post">
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <select name="role">
            <option value="faculty">Faculty</option>
            <option value="institution">Institution</option>
        </select>
        <button type="submit">Register</button>
    </form>
    <p>Already registered? <a href="login.php">login</a></p>
</div>
</body>
</html>
