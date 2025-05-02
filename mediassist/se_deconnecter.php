<?php
session_start(); // Start the session

// Destroy the session to log out the user
session_unset(); // Remove all session variables
session_destroy(); // Destroy the session

// Redirect to the homepage or login page after logging out
header("Location: Acceuil.php");
exit(); // Make sure the script stops here after the redirect
?>
