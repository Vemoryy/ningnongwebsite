<?php session_start(); ?>


<?php
require '../db.php';

$username = mysqli_real_escape_string($conn, $_POST['username']);
$email    = mysqli_real_escape_string($conn, $_POST['email']);
$password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Securely hash

// Check if user/email exists
$check = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' OR email='$email'");
if (mysqli_num_rows($check) > 0) {
    echo "Username or Email already exists. <a href='../register.php'>Try again</a>";
    exit;
}

// Insert user
$query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
if (mysqli_query($conn, $query)) {
    header("Location: ../login.php?register=success");
} else {
    echo "Registration failed: " . mysqli_error($conn);
}
?>
