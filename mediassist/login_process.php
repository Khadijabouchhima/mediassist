<?php
session_start();
$servername = "localhost";
$username = "root"; // Your MySQL username
$password = "12345678"; // Your MySQL password
$dbname = "users";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query to check if the email exists
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Check password (Assuming passwords are hashed)
        if (password_verify($password, $user['password'])) {
            // Store session info
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];

            // Check if there's a 'redirect' parameter in the URL
            if (isset($_GET['redirect']) && !empty($_GET['redirect'])) {
                $redirect_url = urldecode($_GET['redirect']);
                header("Location: $redirect_url");
                exit();
            } else {
                // Default redirect if no 'redirect' parameter is present
                header("Location: Acceuil.php");
                exit();
            }
        } else {
            echo "Mot de passe incorrect!";
        }
    } else {
        echo "Aucun utilisateur trouvé avec cet email!";
    }

    $stmt->close();
}
?>
