<?php
include('pdo_connection.php');
session_start(); // Ensure the session is started

// Assuming user_id is stored in session upon login
$user_id = $_SESSION['user_id']; // Adjust this based on how your session is set

// Traitement de l'upload de fichier
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['photo'])) {
    $upload_dir = 'uploads/';
    $file_name = basename($_FILES['photo']['name']);
    $target_file = $upload_dir . $file_name;
    $upload_ok = 1;

    // Vérification si le fichier est une image
    $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    if (getimagesize($_FILES['photo']['tmp_name']) === false) {
        echo "Le fichier n'est pas une image.";
        $upload_ok = 0;
    }

    // Vérification de la taille du fichier (limité à 5 Mo par exemple)
    if ($_FILES['photo']['size'] > 5000000) {
        echo "Désolé, votre fichier est trop volumineux.";
        $upload_ok = 0;
    }

    // Autorisation de certains formats de fichier
    if ($image_file_type != 'jpg' && $image_file_type != 'png' && $image_file_type != 'jpeg') {
        echo "Désolé, seuls les fichiers JPG, JPEG et PNG sont autorisés.";
        $upload_ok = 0;
    }

    // Vérification si l'upload est autorisé
    if ($upload_ok == 0) {
        echo "Désolé, votre fichier n'a pas pu être téléchargé.";
    } else {
        // Essayer d'upload le fichier
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
            echo "Le fichier " . htmlspecialchars($file_name) . " a été téléchargé avec succès.";
        } else {
            echo "Désolé, une erreur est survenue lors de l'upload de votre fichier.";
        }
    }
}

// Insertion des données du formulaire dans la base de données
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['contact_name'])) {
    try {
        $stmt = $pdo->prepare("INSERT INTO emergency_contacts (user_id, contact_name, relationship, phone_number, photo, notes) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $user_id, // Assigning the logged-in user's ID
            $_POST['contact_name'],
            $_POST['relationship'],
            $_POST['phone_number'],
            $target_file, // Sauvegarde du chemin du fichier dans la base de données
            $_POST['notes']
        ]);
        echo "Nouveau contact ajouté avec succès !";
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contacts d'Urgence</title>
  <link rel="stylesheet" href="urgence.css">
</head>
<body>
<?php include('header.php'); ?>
  <div class="container">
    <h1>Contacts d'Urgence</h1>

    <!-- Formulaire pour ajouter un nouveau contact -->
    <form action="urgence.php" method="POST" enctype="multipart/form-data">
      <label for="contact_name">Nom du contact :</label>
      <input type="text" name="contact_name" required><br>

      <label for="relationship">Relation :</label>
      <input type="text" name="relationship" required><br>

      <label for="phone_number">Numéro de téléphone :</label>
      <input type="text" name="phone_number" required><br>

      <label for="photo">Photo :</label>
      <input type="file" name="photo" accept="image/*" required><br>

      <label for="notes">Notes :</label>
      <textarea name="notes"></textarea><br>

      <button type="submit">Ajouter le contact</button>
    </form>

    <!-- Affichage des contacts -->
    <table>
      <thead>
        <tr>
          <th>Nom du Contact</th>
          <th>Relation</th>
          <th>Numéro de Téléphone</th>
          <th>Photo</th>
          <th>Notes</th>
          <th>Créé le</th>
          <th>Mis à jour le</th>
        </tr>
      </thead>
      <tbody>
        <?php
        try {
          // Fetch only the user_id associated contacts
          $stmt = $pdo->prepare("SELECT * FROM emergency_contacts WHERE user_id = ?");
          $stmt->execute([$user_id]); // Only fetch the contacts of the logged-in user
          
          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['contact_name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['relationship']) . "</td>";
            echo "<td>" . htmlspecialchars($row['phone_number']) . "</td>";
            echo "<td><img src='" . htmlspecialchars($row['photo']) . "' alt='Photo' class='photo'></td>";
            echo "<td>" . htmlspecialchars($row['notes']) . "</td>";
            echo "<td>" . $row['created_at'] . "</td>";
            echo "<td>" . $row['updated_at'] . "</td>";
            echo "</tr>";
          }
        } catch (PDOException $e) {
          echo "Erreur : " . $e->getMessage();
        }
        ?>
      </tbody>
    </table>
  </div>
  <footer>
    <div class="footer-content">
        <div class="footer-section">
            <h3>MediAssist</h3>
            <p>Votre partenaire santé numérique pour une meilleure gestion des médicaments et un suivi médical simplifié.</p>
        </div>
        <div class="footer-section">
            <h3>Liens rapides</h3>
            <ul>
                <li><a href="acceuil.php">Accueil</a></li>
                <li><a href="bienvenue.php">Fonctionnalités</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
        </div>
        <div class="footer-section">
            <h3>Contactez-nous</h3>
            <p><i class="fas fa-envelope"></i> contact@mediassist.com</p>
            <p><i class="fas fa-phone"></i> +33 1 23 45 67 89</p>
            <div class="social-icons">
                <a href="#"><i class="fab fa-facebook"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; 2025 MediAssist. Tous droits réservés.</p>
    </div>
</footer>
</body>
</html>
