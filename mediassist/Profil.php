<?php
// Include database connection
require_once 'db_connection.php';

// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: signup.php");
    exit();
}

// Get user data
$user_id = $_SESSION['user_id'];

// Get user info
$query = "SELECT * FROM users WHERE id = ?";
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    die("User not found");
}

// Get allergies
$allergies = [];
$query = "SELECT allergy_name FROM allergies WHERE user_id = ?";
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
while ($row = mysqli_fetch_assoc($result)) {
    $allergies[] = $row['allergy_name'];
}

// Get chronic conditions
$chronic_conditions = [];
$query = "SELECT condition_name FROM chronic_conditions WHERE user_id = ?";
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
while ($row = mysqli_fetch_assoc($result)) {
    $chronic_conditions[] = $row['condition_name'];
}

// Add arrays to user data
$user['allergies'] = $allergies ?: ['Aucune allergie enregistrée'];
$user['chronic_conditions'] = $chronic_conditions ?: ['Aucune maladie chronique enregistrée'];

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    // Start transaction
    mysqli_begin_transaction($connection);
    
    try {
        // Prepare update data (excluding firstname/lastname)
        $date_of_birth = mysqli_real_escape_string($connection, $_POST['date_of_birth'] ?? $user['date_of_birth']);
        $weight = floatval($_POST['weight'] ?? $user['weight']);
        $height = intval($_POST['height'] ?? $user['height']);
        $gender = mysqli_real_escape_string($connection, $_POST['gender'] ?? $user['gender']);
        $blood_type = mysqli_real_escape_string($connection, $_POST['blood_type'] ?? $user['blood_type']);
        $phone_number = mysqli_real_escape_string($connection, $_POST['phone_number'] ?? $user['phone_number']);
        $address = mysqli_real_escape_string($connection, $_POST['address'] ?? $user['address']);

        // Update user info (excluding firstname/lastname)
        $query = "UPDATE users SET date_of_birth = ?, weight = ?, height = ?, gender = ?, blood_type = ?, phone_number = ?, address = ? WHERE id = ?";
        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, "sdsssssi", $date_of_birth, $weight, $height, $gender, $blood_type, $phone_number, $address, $user_id);
        $update_success = mysqli_stmt_execute($stmt);

        if (!$update_success) {
            throw new Exception("User update failed: " . mysqli_error($connection));
        }

        // Handle allergies update
        if (isset($_POST['allergies'])) {
            // Delete existing allergies
            $query = "DELETE FROM allergies WHERE user_id = ?";
            $stmt = mysqli_prepare($connection, $query);
            mysqli_stmt_bind_param($stmt, "i", $user_id);
            mysqli_stmt_execute($stmt);

            // Process allergies from textarea
            $allergies_input = trim($_POST['allergies']);
            if ($allergies_input !== '') {
                $allergies_list = explode("\n", $allergies_input);
                $allergies_list = array_map('trim', $allergies_list);
                $allergies_list = array_filter($allergies_list);
                
                if (!empty($allergies_list)) {
                    $query = "INSERT INTO allergies (user_id, allergy_name) VALUES (?, ?)";
                    $stmt = mysqli_prepare($connection, $query);
                    
                    foreach ($allergies_list as $allergy) {
                        $allergy = mysqli_real_escape_string($connection, $allergy);
                        mysqli_stmt_bind_param($stmt, "is", $user_id, $allergy);
                        mysqli_stmt_execute($stmt);
                    }
                }
            }
        }

        // Handle chronic conditions update
        if (isset($_POST['chronic_conditions'])) {
            // Delete existing conditions
            $query = "DELETE FROM chronic_conditions WHERE user_id = ?";
            $stmt = mysqli_prepare($connection, $query);
            mysqli_stmt_bind_param($stmt, "i", $user_id);
            mysqli_stmt_execute($stmt);

            // Process conditions from textarea
            $conditions_input = trim($_POST['chronic_conditions']);
            if ($conditions_input !== '') {
                $conditions_list = explode("\n", $conditions_input);
                $conditions_list = array_map('trim', $conditions_list);
                $conditions_list = array_filter($conditions_list);
                
                if (!empty($conditions_list)) {
                    $query = "INSERT INTO chronic_conditions (user_id, condition_name) VALUES (?, ?)";
                    $stmt = mysqli_prepare($connection, $query);
                    
                    foreach ($conditions_list as $condition) {
                        $condition = mysqli_real_escape_string($connection, $condition);
                        mysqli_stmt_bind_param($stmt, "is", $user_id, $condition);
                        mysqli_stmt_execute($stmt);
                    }
                }
            }
        }

        // Handle profile picture upload
        if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/profile_pics/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $fileExt = pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION);
            $fileName = 'user_' . $user_id . '_' . time() . '.' . $fileExt;
            $filePath = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $filePath)) {
                // Update profile pic in database
                $query = "UPDATE users SET profile_picture = ? WHERE id = ?";
                $stmt = mysqli_prepare($connection, $query);
                mysqli_stmt_bind_param($stmt, "si", $fileName, $user_id);
                mysqli_stmt_execute($stmt);

                // Update current user data
                $user['profile_picture'] = $fileName;
            }
        }

        // Commit the transaction
        mysqli_commit($connection);
        header("Location: Profil.php");
        exit();
        
    } catch (Exception $e) {
        mysqli_rollback($connection);
        $error = "Error updating profile: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediAssist - Mon Profil</title>
    <link rel="stylesheet" href="profile.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="profile.js" defer></script>
</head>
<body>
<?php include('header.php'); ?>
    <div class="profile-container">
        <header class="profile-header">
            <h1>Mon Profil MediAssist</h1>
            <button id="edit-btn" class="edit-btn">Modifier</button>
        </header>
        
        <?php if (isset($error)): ?>
            <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <main class="profile-content">
            <section class="profile-section profile-card">
                <div class="profile-pic-container">
                    <img src="uploads/profile_pics/<?php echo htmlspecialchars($user['profile_picture'] ?? 'default.jpg'); ?>" 
                         alt="Photo de profil" class="profile-pic">
                    <button class="change-pic-btn">Changer la photo</button>
                </div>
                <div class="profile-info">
                    <h2 class="user-name"><?php echo htmlspecialchars($user['firstname'] . ' ' . $user['lastname']); ?></h2>
                    <div class="user-basic-info">
                        <div class="info-item">
                            <span class="info-label">Date de naissance:</span>
                            <span class="info-value"><?php echo htmlspecialchars($user['date_of_birth'] ?? 'Non spécifié'); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Poids:</span>
                            <span class="info-value"><?php echo htmlspecialchars($user['weight'] ?? 'Non spécifié'); ?> kg</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Taille:</span>
                            <span class="info-value"><?php echo htmlspecialchars($user['height'] ?? 'Non spécifié'); ?> cm</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Genre:</span>
                            <span class="info-value"><?php echo htmlspecialchars($user['gender'] ?? 'Non spécifié'); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Groupe sanguin:</span>
                            <span class="info-value"><?php echo htmlspecialchars($user['blood_type'] ?? 'Non spécifié'); ?></span>
                        </div>
                    </div>
                </div>
            </section>
            
            <section class="medical-section profile-card">
                <h3>Informations Médicales</h3>
                <div class="medical-info-grid">
                    <div class="medical-info-col">
                        <h4>Allergies</h4>
                        <ul class="allergies-list">
                            <?php foreach($user['allergies'] as $allergy): ?>
                                <li><?php echo htmlspecialchars($allergy); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div class="medical-info-col">
                        <h4>Maladies Chroniques</h4>
                        <ul class="conditions-list">
                            <?php foreach($user['chronic_conditions'] as $condition): ?>
                                <li><?php echo htmlspecialchars($condition); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </section>
            
            <section class="contact-section profile-card">
                <h3>Coordonnées</h3>
                <div class="contact-info">
                    <div class="info-item">
                        <span class="info-label">Email:</span>
                        <span class="info-value"><?php echo htmlspecialchars($user['email']); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Téléphone:</span>
                        <span class="info-value"><?php echo htmlspecialchars($user['phone_number'] ?? 'Non spécifié'); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Adresse:</span>
                        <span class="info-value"><?php echo htmlspecialchars($user['address'] ?? 'Non spécifié'); ?></span>
                    </div>
                </div>
            </section>
        </main>
    </div>
    
    <!-- Edit Modal -->
    <div class="modal-overlay" id="edit-modal">
        <div class="modal-content">
            <h2>Modifier le Profil</h2>
            <form id="profile-form" class="edit-form" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="update_profile" value="1">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="date_of_birth">Date de naissance</label>
                        <input type="date" id="date_of_birth" name="date_of_birth" value="<?php echo htmlspecialchars($user['date_of_birth'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label for="weight">Poids (kg)</label>
                        <input type="number" step="0.1" id="weight" name="weight" value="<?php echo htmlspecialchars($user['weight'] ?? ''); ?>">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="height">Taille (cm)</label>
                        <input type="number" id="height" name="height" value="<?php echo htmlspecialchars($user['height'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label for="gender">Genre</label>
                        <select id="gender" name="gender">
                            <option value="Male" <?php echo ($user['gender'] ?? '') === 'Male' ? 'selected' : ''; ?>>Homme</option>
                            <option value="Female" <?php echo ($user['gender'] ?? '') === 'Female' ? 'selected' : ''; ?>>Femme</option>
                            <option value="Other" <?php echo ($user['gender'] ?? '') === 'Other' ? 'selected' : ''; ?>>Autre</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="blood_type">Groupe sanguin</label>
                    <input type="text" id="blood_type" name="blood_type" value="<?php echo htmlspecialchars($user['blood_type'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label for="profile_pic">Photo de profil</label>
                    <input type="file" id="profile_pic" name="profile_pic" accept="image/*">
                </div>
                
                <div class="form-group">
                    <label>Allergies (une par ligne)</label>
                    <textarea name="allergies" rows="3"><?php 
                        if ($user['allergies'][0] !== 'Aucune allergie enregistrée') {
                            echo htmlspecialchars(implode("\n", $user['allergies']));
                        }
                    ?></textarea>
                </div>
                
                <div class="form-group">
                    <label>Maladies chroniques (une par ligne)</label>
                    <textarea name="chronic_conditions" rows="3"><?php 
                        if ($user['chronic_conditions'][0] !== 'Aucune maladie chronique enregistrée') {
                            echo htmlspecialchars(implode("\n", $user['chronic_conditions']));
                        }
                    ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="phone_number">Téléphone</label>
                    <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label for="address">Adresse</label>
                    <textarea id="address" name="address" rows="2"><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="cancel-btn">Annuler</button>
                    <button type="submit" class="save-btn">Enregistrer</button>
                </div>
            </form>
        </div>
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