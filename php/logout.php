<?php
// Start the session
session_start();

// Unset all session variables
session_unset();

// Destroy the session
session_destroy();
setcookie('userID', '', time() - 3600, '/');

// Redirect to home2.php
header("Location: ../home.php");
exit; // Make sure to add 'exit' after redirecting to prevent further script execution
?>