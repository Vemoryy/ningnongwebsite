<?php
session_start();
require '../process/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_POST['username'] ?? '';
$email = $_POST['email'] ?? '';
$address = $_POST['address'] ?? '';
$new_password = $_POST['new_password'] ?? '';

// If password field is filled, hash and update it too
if (!empty($new_password)) {
    $hashed = password_hash($new_password, PASSWORD_DEFAULT);
    $query = "UPDATE users SET username = ?, email = ?, address = ?, password = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ssssi", $username, $email, $address, $hashed, $user_id);
} else {
    $query = "UPDATE users SET username = ?, email = ?, address = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sssi", $username, $email, $address, $user_id);
}

mysqli_stmt_execute($stmt);

// Update session if needed
$_SESSION['username'] = $username;

if (mysqli_stmt_affected_rows($stmt) > 0) {
    header("Location: dashboard.php?update=success");
} else {
    header("Location: dashboard.php?update=fail");
}
exit();
?>
