<?php
session_start(); // Start session at the beginning of the file

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to the login page
header("Location: Login.php");
exit();
?>
