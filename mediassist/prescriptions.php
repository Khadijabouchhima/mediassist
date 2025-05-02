<?php
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    $redirect_url = urlencode($_SERVER['REQUEST_URI']);
    header("Location: signup.php?redirect=$redirect_url");
    exit();
}

$user_id = $_SESSION['user_id'];
include('pdo_connection.php'); // Make sure this contains your PDO connection as $pdo

// Handle form submission for adding new prescriptions
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_prescription'])) {
    // Sanitizing user input
    $doctor_name = htmlspecialchars($_POST['doctor_name']);
    $issue_date = htmlspecialchars($_POST['issue_date']);
    $expiry_date = htmlspecialchars($_POST['expiry_date']);
    $notes = htmlspecialchars($_POST['notes']);
    $prescription_file = null;

if (isset($_FILES['prescription_file']) && $_FILES['prescription_file']['error'] == 0) {
    $target_dir = "uploads/";
    $fileName = basename($_FILES['prescription_file']['name']);
    $target_file = $target_dir . time() . '_' . $fileName;

    if (move_uploaded_file($_FILES["prescription_file"]["tmp_name"], $target_file)) {
        $prescription_file = $target_file;
    } else {
        echo "Désolé, une erreur s'est produite lors du téléversement du fichier.";
    }
}


    // Insert prescription data into the database
    $query = "INSERT INTO prescriptions (user_id, doctor_name, issue_date, expiry_date,prescription_file, notes, created_at, updated_at) 
    VALUES (:user_id, :doctor_name, :issue_date, :expiry_date, :prescription_file ,:notes, NOW(), NOW())";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':doctor_name', $doctor_name, PDO::PARAM_STR);
    $stmt->bindParam(':issue_date', $issue_date, PDO::PARAM_STR);
    $stmt->bindParam(':expiry_date', $expiry_date, PDO::PARAM_STR);
    $stmt->bindParam(':prescription_file', $prescription_file, PDO::PARAM_STR);
    $stmt->bindParam(':notes', $notes, PDO::PARAM_STR);

    try {
        $stmt->execute();
        echo "<script>alert('Prescription added successfully.');</script>";
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}


// Fetch prescriptions from the database
$query = "SELECT * FROM prescriptions WHERE user_id = :user_id ORDER BY issue_date DESC";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
$stmt->execute();
$prescriptions = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Prescriptions</title>
    <link rel="stylesheet" href="prescrip.css">
</head>
<body>

<?php include('header.php'); ?>

<main>
    <h2>Mes Prescriptions</h2>


    <!-- Formulaire d'ajout de prescription -->
    <form method="POST" enctype="multipart/form-data">
        <h3>Ajouter une nouvelle prescription</h3>
        <label for="doctor_name">Nom du Médecin :</label>
        <input type="text" name="doctor_name" id="doctor_name" required>

        <label for="issue_date">Date d'émission :</label>
        <input type="date" name="issue_date" id="issue_date" required>

        <label for="expiry_date">Date d'expiration :</label>
        <input type="date" name="expiry_date" id="expiry_date" required>

        <label for="notes">Notes :</label>
        <textarea name="notes" id="notes" rows="4" required></textarea>

        <label for="prescription_file">Image de la Prescription :</label>
        <input type="file" name="prescription_file" id="prescription_file" accept="image/*">

        <button type="submit" name="add_prescription">Ajouter la prescription</button>
    </form>

    <div class="prescriptions-list">
        <?php if (count($prescriptions) > 0): ?>
            <ul>
                <?php foreach ($prescriptions as $prescription): ?>
                    <li>
                        <h3>Médecin : <?php echo htmlspecialchars($prescription['doctor_name']); ?></h3>
                        <p><strong>Émis le :</strong> <?php echo htmlspecialchars($prescription['issue_date']); ?></p>
                        <p><strong>Date d'expiration :</strong> <?php echo htmlspecialchars($prescription['expiry_date']); ?></p>
                        <p><strong>Notes :</strong> <?php echo nl2br(htmlspecialchars($prescription['notes'])); ?></p>
                        <?php if ($prescription['prescription_file']): ?>
                            <p><a href="<?php echo htmlspecialchars($prescription['prescription_file']); ?>" target="_blank">Voir l'image de la prescription</a></p>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Aucune prescription trouvée.</p>
        <?php endif; ?>
    </div>
</main>

<footer>
    <p>&copy; 2025 MediAssist. Tous droits réservés.</p>
</footer>

</body>
</html>
