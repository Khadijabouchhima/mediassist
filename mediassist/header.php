<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); 
}
?>

<header>
    <div class="header">
        <div class="header-left">
            <a href="Acceuil.php">Accueil</a>
            <a href="Acceuil.php#about">A propos</a>
            <a href="contact.php">Contactez-nous</a>
            <?php if (isset($_SESSION['email'])): ?>
                <a href="se_deconnecter.php">Se déconnecter</a>
            <?php else: ?>
                <a href="signup.php">Se Connecter</a>
            <?php endif; ?>
        </div>
        <div class="header-logo">
            <img src="images/logo.png" alt="MediAssist Logo" class="logo-img">
        </div>
    </div>
</header>
