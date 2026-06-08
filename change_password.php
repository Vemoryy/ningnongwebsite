<?php
session_start();
require '../process/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$new_password = $_POST['new_password'] ?? '';

// Validasi sederhana
if (strlen($new_password) < 6) {
    header("Location: dashboard.php?update=fail");
    exit();
}

// Hash password baru
$hashed = password_hash($new_password, PASSWORD_DEFAULT);

// Update di database
$query = "UPDATE users SET password = ? WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "si", $hashed, $user_id);
mysqli_stmt_execute($stmt);

if (mysqli_stmt_affected_rows($stmt) > 0) {
    header("Location: dashboard.php?update=success");
} else {
    header("Location: dashboard.php?update=fail");
}
exit();
?>
