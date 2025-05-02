<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contactez-nous | Entreprise</title>
    <link rel="stylesheet" href="contact.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<?php include('header.php'); ?>
    <div class="contact-container">
        <div class="contact-content">
            <div class="contact-info">
                <div class="info-card">
                    <div class="icon-circle">
                        <i class="fas fa-phone-alt"></i>
                    </div>
                    <h3>Téléphone</h3>
                    <p>+1 (555) 123-4567</p>
                </div>

                <div class="info-card">
                    <div class="icon-circle">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <h3>Email</h3>
                    <p>contact@entreprise.com</p>
                </div>

                <div class="info-card">
                    <div class="icon-circle">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <h3>Adresse</h3>
                    <p>123 Rue Principale<br>Montréal, QC H3B 2Y5</p>
                </div>
            </div>

            <form class="contact-form">
                <div class="form-group">
                    <input type="text" id="name" required>
                    <label for="name">Nom complet</label>
                </div>

                <div class="form-group">
                    <input type="email" id="email" required>
                    <label for="email">Adresse email</label>
                </div>

                <div class="form-group">
                    <input type="text" id="subject" required>
                    <label for="subject">Sujet</label>
                </div>

                <div class="form-group">
                    <textarea id="message" rows="5" required></textarea>
                    <label for="message">Message</label>
                </div>

                <button type="submit" class="submit-btn">
                    Envoyer le message <i class="fas fa-paper-plane"></i>
                </button>
            </form>
        </div>

        <div class="social-links">
            <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
            <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
            <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
            <a href="#" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
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