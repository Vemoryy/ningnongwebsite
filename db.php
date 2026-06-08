<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "ningnong_db"; 

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>

