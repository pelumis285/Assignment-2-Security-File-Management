<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Destroy session
$_SESSION = array();
session_destroy();

// Redirect to login
header("Location: login.php");
exit();
?>