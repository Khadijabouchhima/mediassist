<?php

session_start();
require_once 'pdo_connection.php'; 

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: signup.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$appointments = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = $_POST['title'];
    $appointment_type = $_POST['appointment_type'] ?? null;
    $doctor_name = $_POST['doctor_name'] ?? null;
    $appointment_date = $_POST['appointment_date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'] ?? null;
    $location = $_POST['location'] ?? null;
    $notes = $_POST['notes'] ?? null;
    $reminder_minutes_before = $_POST['reminder_minutes_before'] ?? null;
    $is_completed = 0;
    $now = date('Y-m-d H:i:s');

    try {
        $stmt = $pdo->prepare("
            INSERT INTO appointments (
                user_id, title, appointment_type, doctor_name, location,
                appointment_date, start_time, end_time, notes,
                reminder_minutes_before, is_completed, created_at, updated_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $user_id, $title, $appointment_type, $doctor_name, $location,
            $appointment_date, $start_time, $end_time, $notes,
            $reminder_minutes_before, $is_completed, $now, $now
        ]);

        $success = "Rendez-vous ajouté avec succès!";
    } catch (PDOException $e) {
        echo "<div style='color:red;'>Erreur SQL : " . $e->getMessage() . "</div>";
    }
}


$stmt = $pdo->prepare("
    SELECT * FROM appointments 
    WHERE user_id = ? AND appointment_date >= CURDATE() 
    ORDER BY appointment_date ASC, start_time ASC
");
$stmt->execute([$user_id]);
$appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediAssist - Rendez-vous</title>
    <link rel="stylesheet" href="r_updated.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container">
    <main class="main-content">
    <?php if (isset($success)): ?>
        <div class="alert success"><?= $success ?></div>
    <?php endif; ?>
    <div class="appointments-wrapper">
    <section class="appointment-form">

    <h2>Nouveau Rendez-vous</h2>
    <form method="POST">
        <div class="form-group">
            <label for="title">Titre*</label>
            <input type="text" id="title" name="title" required>
        </div>

        <div class="form-group">
            <label for="appointment_type">Type de rendez-vous</label>
            <select id="appointment_type" name="appointment_type">
                <option value="">-- Sélectionnez --</option>
                <option value="consultation">Consultation</option>
                <option value="examination">Examen</option>
                <option value="test">Test</option>
                <option value="procedure">Procédure</option>
                <option value="follow-up">Suivi</option>
                <option value="other">Autre</option>
            </select>
        </div>

        <div class="form-group">
            <label for="doctor_name">Nom du médecin</label>
            <input type="text" id="doctor_name" name="doctor_name">
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="appointment_date">Date*</label>
                <input type="date" id="appointment_date" name="appointment_date" required>
            </div>

            <div class="form-group">
                <label for="start_time">Heure de début*</label>
                <input type="time" id="start_time" name="start_time" required>
            </div>

            <div class="form-group">
                <label for="end_time">Heure de fin</label>
                <input type="time" id="end_time" name="end_time">
            </div>
        </div>

        <div class="form-group">
            <label for="location">Lieu</label>
            <input type="text" id="location" name="location">
        </div>

        <div class="form-group">
            <label for="notes">Notes</label>
            <textarea id="notes" name="notes" rows="3"></textarea>
        </div>

        <div class="form-group">
            <label for="reminder_minutes_before">Rappel (minutes avant)</label>
            <input type="number" id="reminder_minutes_before" name="reminder_minutes_before" min="0">
        </div>

        <button type="submit" class="btn">Enregistrer</button>
        </section> 
    </form>

    <section class="appointment-list">
        <h2>Rendez-vous à venir</h2>
        <?php if (count($appointments) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Type</th>
                        <th>Médecin</th>
                        <th>Date</th>
                        <th>Heure début</th>
                        <th>Heure fin</th>
                        <th>Lieu</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($appointments as $appointment): ?>
                        <tr>
                            <td><?= htmlspecialchars($appointment['title']) ?></td>
                            <td><?= htmlspecialchars($appointment['appointment_type']) ?></td>
                            <td><?= htmlspecialchars($appointment['doctor_name']) ?></td>
                            <td><?= htmlspecialchars($appointment['appointment_date']) ?></td>
                            <td><?= htmlspecialchars($appointment['start_time']) ?></td>
                            <td><?= htmlspecialchars($appointment['end_time']) ?></td>
                            <td><?= htmlspecialchars($appointment['location']) ?></td>
                            <td><?= htmlspecialchars($appointment['notes']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Aucun rendez-vous à venir.</p>
        <?php endif; ?>
    </section>
    </div> 
        </main>
    </div>
    <footer class="footer">
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
