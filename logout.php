<?php
session_start();
session_unset(); // clear all session variables
session_destroy(); // destroy the session

$lang = $_GET['lang'] ?? 'id'; // keep language selection on redirect
header("Location: index.php?lang=$lang");
exit();
