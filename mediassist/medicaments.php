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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitizing user input
    $nom = htmlspecialchars($_POST['nom']);
    $posologie = htmlspecialchars($_POST['posologie']);
    $frequence = htmlspecialchars($_POST['frequence']);
    $heure = $_POST['heure']; // assuming time format is correctly sent
    $photo = null;

    // Handle file upload
    if (!empty($_FILES['photo']['tmp_name'])) {
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        if (!is_writable($uploadDir)) {
            echo "The uploads directory is not writable.";
            exit; // Stop further execution
        }

        $fileName = basename($_FILES['photo']['name']);
        $targetFilePath = $uploadDir . time() . '_' . $fileName;
        $imageFileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // Validate the file type
        if (in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            // Check the file size
            if ($_FILES['photo']['size'] <= 5 * 1024 * 1024) { // 5MB max size
                if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetFilePath)) {
                    $photo = $targetFilePath;
                    // Debugging message
                    echo "<script>alert('Upload successful: ".$photo."');</script>";
                } else {
                    echo "There was an error uploading the file.";
                }
            } else {
                echo "File size must be less than 5MB.";
            }
        } else {
            echo "Only image files are allowed.";
        }
    }

    // Insert into database
    $stmt = $pdo->prepare("INSERT INTO medicaments (user_id, nom, posologie, frequence, heure, photo) 
                           VALUES (:user_id, :nom, :posologie, :frequence, :heure, :photo)");

    // Bind parameters and execute
    try {
        $stmt->execute([
            ':user_id' => $user_id,
            ':nom' => $nom,
            ':posologie' => $posologie,
            ':frequence' => $frequence,
            ':heure' => $heure,
            ':photo' => $photo
        ]);
        echo "<script>alert('Medication added successfully.');</script>";
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Fetch user's medications
$stmt = $pdo->prepare("SELECT * FROM medicaments WHERE user_id = :user_id ORDER BY created_at DESC");
$stmt->execute([':user_id' => $user_id]);
$medications = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestion des Médicaments</title>
  <link rel="stylesheet" href="medi.css">
</head>
<body>

<?php include('header.php'); ?>

<main>
  <section id="medicaments">
    <h2>Gestion des Médicaments</h2>

    <!-- Add Medication Form -->
    <form id="add-medication-form" method="post" enctype="multipart/form-data">
      <label for="medication-name">Nom du Médicament</label>
      <input type="text" id="medication-name" name="nom" required><br>

      <label for="posologie">Posologie</label>
      <input type="text" id="posologie" name="posologie" required><br>

      <label for="frequence">Fréquence</label>
      <input type="text" id="frequence" name="frequence" required><br>

      <label for="heure">Heure de prise</label>
      <input type="time" id="heure" name="heure" required><br>

      <label for="photo">Photo du Médicament</label>
      <input type="file" id="photo" name="photo" accept="image/*"><br>

      <button type="submit">Ajouter un Médicament</button>
    </form>

    <!-- Medications List -->
    <div class="medicaments-list">
      <h3>Liste des Médicaments</h3>
      <ul>
        <?php foreach ($medications as $med): ?>
          <li>
            <strong><?= htmlspecialchars($med['nom']) ?></strong><br>
            Posologie: <?= htmlspecialchars($med['posologie']) ?><br>
            Fréquence: <?= htmlspecialchars($med['frequence']) ?><br>
            Heure: <?= htmlspecialchars($med['heure']) ?><br>
            <?php if ($med['photo']): ?>
              <img src="<?= htmlspecialchars($med['photo']) ?>" alt="Photo Médicament" style="max-width: 100px;">
            <?php endif; ?>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </section>
</main>

<footer>
  <p>&copy; 2025 MediAssist. Tous droits réservés.</p>
</footer>

<script src="medi.js"></script>
</body>
</html>
