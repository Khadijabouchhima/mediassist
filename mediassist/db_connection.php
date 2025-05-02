<?php
$servername = "localhost";
$username = "root"; // or your MySQL username
$password = "12345678"; // or your MySQL password if you set one
$dbname = "users"; // your database name

// Create connection
$connection = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}
?>