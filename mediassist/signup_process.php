<?php
session_start();
include('db_connection.php'); // Include the DB connection

if ($_POST['signup_password'] === $_POST['signup_confirm']) {
    // Sanitize input data to prevent SQL injection
    $firstname = mysqli_real_escape_string($connection, $_POST['signup_firstname']);
    $lastname = mysqli_real_escape_string($connection, $_POST['signup_lastname']);
    $email = mysqli_real_escape_string($connection, $_POST['signup_email']);
    $password = mysqli_real_escape_string($connection, password_hash($_POST['signup_password'], PASSWORD_DEFAULT)); // Hash the password for security

    // SQL query to insert the new user into the users table
    $query = "INSERT INTO users (firstname, lastname, email, password) VALUES ('$firstname', '$lastname', '$email', '$password')";

    if (mysqli_query($connection, $query)) {
        $_SESSION['email'] = $email;
        header("Location: Acceuil.php");
        exit();
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($connection);
    }
} else {
    header("Location: signup.php?signup_error=1");
    exit();
}
if (mysqli_query($connection, $query)) {
    echo "Record added successfully.";
} else {
    echo "Error: " . mysqli_error($connection);
}

?>
