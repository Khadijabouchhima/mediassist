<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Fonctionnalités | MediAssist</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="f.css">
</head>
<body>

  <?php include 'header.php'; ?>

  <main>
    <section class="features-hero">
      <h1>Découvrez nos fonctionnalités</h1>
      <p>Des outils innovants pour simplifier votre gestion médicale au quotidien</p>
    </section>

    <section class="carousel-section">
      <h2 class="section-title">Nos Solutions Complètes</h2>
      
      <div class="carousel-container">
        <div class="carousel" id="featuresCarousel">
          <div class="carousel-track" id="carouselTrack">
            <!-- Feature 1 -->
            <div class="carousel-item">
              <div class="feature-card">
                <img src="images/pills-6605909.jpg" alt="Gestion des médicaments" class="feature-image">
                <div class="feature-content">
                  <i class="fas fa-pills feature-icon"></i>
                  <h3>Gestion des Médicaments</h3>
                  <p>Ne manquez plus jamais une dose avec notre système de rappels intelligents et de suivi des prises.</p>
                  <ul class="feature-benefits">
                    <li>Rappels personnalisés pour chaque médicament</li>
                    <li>Historique complet des prises</li>
                    <li>Alertes de renouvellement</li>
                    <li>Scanner d'ordonnance intégré</li>
                  </ul>
                </div>
              </div>
            </div>
            
            <!-- Feature 2 -->
            <div class="carousel-item">
              <div class="feature-card">
                <img src="images/appointment.avif" alt="Rendez-vous médicaux" class="feature-image">
                <div class="feature-content">
                  <i class="fas fa-calendar-check feature-icon"></i>
                  <h3>Rendez-vous Médicaux</h3>
                  <p>Organisez et suivez tous vos rendez-vous médicaux en un seul endroit.</p>
                  <ul class="feature-benefits">
                    <li>Synchronisation avec votre calendrier</li>
                    <li>Rappels automatiques</li>
                    <li>Directions vers le cabinet</li>
                    <li>Documents pré-consultation</li>
                  </ul>
                </div>
              </div>
            </div>
            
            <!-- Feature 3 -->
            <div class="carousel-item">
              <div class="feature-card">
                <img src="images/hand.png" alt="Dossier médical" class="feature-image">
                <div class="feature-content">
                  <i class="fas fa-file-medical feature-icon"></i>
                  <h3>Dossier Médical</h3>
                  <p>Vos informations de santé sécurisées et accessibles en quelques clics.</p>
                  <ul class="feature-benefits">
                    <li>Stockage sécurisé des documents</li>
                    <li>Accès depuis n'importe quel appareil</li>
                    <li>Partage contrôlé avec les professionnels</li>
                    <li>Analyse des tendances de santé</li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="carousel-nav" id="carouselNav">
          <div class="carousel-dot active"></div>
          <div class="carousel-dot"></div>
          <div class="carousel-dot"></div>
        </div>
      </div>
    </section>

    <section class="cta-section">
      <div class="cta-container">
        <h2>Prêt à prendre le contrôle de votre santé ?</h2>
        <p>Rejoignez des milliers d'utilisateurs satisfaits et simplifiez votre gestion médicale dès aujourd'hui.</p>
        <div class="cta-buttons">
          <a href="signup.php" class="btn btn-primary">S'inscrire Maintenant</a>
        </div>
      </div>
    </section>
  </main>
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

<script src="f.js"></script>
</body>
</html>
  