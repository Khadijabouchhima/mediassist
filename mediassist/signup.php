<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Connexion / Inscription</title>
  <link rel="stylesheet" href="login.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<?php include 'header.php'; ?>

<div class="auth-container">
  <!-- Login Card -->
  <div class="auth-card active" id="login-card">
    <h2>Se connecter</h2>
    <form method="post" action="login_process.php<?php if (isset($_GET['redirect'])) echo '?redirect=' . urlencode($_GET['redirect']); ?>">
      <label for="email">Adresse e-mail</label>
      <input type="email" id="email" name="email" placeholder="Entrez votre e-mail" required>

      <label for="password">Mot de passe</label>
      <input type="password" id="password" name="password" placeholder="Entrez votre mot de passe" required>

      <button type="submit">Connexion</button>
      <p class="switch">Pas encore de compte ? <a href="#" id="show-signup">Inscrivez-vous</a></p>
    </form>
  </div>

  <!-- Signup Card -->
  <div class="auth-card" id="signup-card">
    <h2>Inscription</h2>
    <form method="POST" action="signup_process.php">
      <label for="signup-firstname">Prénom</label>
      <input type="text" name="signup_firstname" id="signup-firstname" required>

      <label for="signup-lastname">Nom</label>
      <input type="text" name="signup_lastname" id="signup-lastname" required>

      <label for="signup-email">Adresse e-mail</label>
      <input type="email" name="signup_email" id="signup-email" required>

      <label for="signup-password">Mot de passe</label>
      <input type="password" name="signup_password" id="signup-password" required>

      <label for="signup-confirm">Confirmez le mot de passe</label>
      <input type="password" name="signup_confirm" id="signup-confirm" required>

      <button type="submit">Créer un compte</button>
      <p class="switch">Déjà un compte ? <a href="#" id="show-login">Se connecter</a></p>
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
<script src="login.js"></script>
</body>
</html>
