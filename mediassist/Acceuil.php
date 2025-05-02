<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="MediAssist - Votre partenaire pour la gestion des médicaments et le suivi de santé">
    <title>MediAssist - Accueil</title>
    <link rel="stylesheet" href="Acceuil.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

<?php include('header.php'); ?>

<main>
    <section class="hero">
        <div class="hero-content">
            <h1>BIENVENUE À MEDIASSIST</h1>
            <p>Simplifiez la gestion de vos médicaments.</p>
            <div class="hero-buttons">
                <a href="signup.php" class="btn">Commencer</a>
                <a href="#features" class="btn btn-outline">Découvrir</a>
            </div>
        </div>
    </section>
    
    <section id="about" class="about">
        <div class="about-container">
            <div class="about-content">
                <h2>À propos de MediAssist</h2>
                <p>
                    MediAssist est plus qu'une simple application — c'est votre partenaire numérique dans la gestion de votre santé.
                    Suivez vos médicaments, planifiez vos rendez-vous médicaux et gérez vos prescriptions avec une simplicité déconcertante.
                </p>
                <p>
                    Notre mission est de vous offrir un accès immédiat à vos informations de santé, vous permettant de prendre le contrôle
                    tout en restant concentré sur votre bien-être. MediAssist est l'outil de santé que vous attendiez.
                </p>
                <div class="stats-container">
                    <div class="stat-item">
                        <span class="stat-number">10,000+</span>
                        <span class="stat-label">Utilisateurs satisfaits</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">24/7</span>
                        <span class="stat-label">Support disponible</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">100%</span>
                        <span class="stat-label">Confidentialité</span>
                    </div>
                </div>
                <a href="bienvenue.php" class="btn">Explorer l'application</a>
            </div>
        </div>
    </section>

    <section id="features" class="features-section">
    <h2>Nos Services</h2>
    <p class="section-subtitle">Gérez votre santé en toute simplicité</p>
    
    <div class="features-container">
        <!-- Feature 1 - Profil -->
        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-user-md"></i>
            </div>
            <h3>Profil Santé</h3>
            <p>Créez et gérez votre profil médical complet avec toutes vos informations importantes.</p>
            <a href="Profil.php" class="feature-link">Accéder <i class="fas fa-arrow-right"></i></a>
        </div>
        
        <!-- Feature 2 - Médicaments -->
        <div class="feature-card highlight">
            <div class="feature-icon">
                <i class="fas fa-pills"></i>
            </div>
            <h3>Médicaments</h3>
            <p>Suivez vos traitements avec des rappels personnalisés et des alertes de renouvellement.</p>
            <a href="medicaments.php" class="feature-link">Accéder <i class="fas fa-arrow-right"></i></a>
        </div>
        
        <!-- Feature 3 - Rendez-vous -->
        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-calendar-check"></i>
            </div>
            <h3>Rendez-vous</h3>
            <p>Organisez et suivez tous vos rendez-vous médicaux avec des notifications de rappel.</p>
            <a href="rendezvous.php" class="feature-link">Accéder <i class="fas fa-arrow-right"></i></a>
        </div>
        
        <!-- Feature 4 - Planning -->
        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <h3>Planning</h3>
            <p>Visualisez votre agenda santé en un coup d'œil avec une vue hebdomadaire ou mensuelle.</p>
            <a href="planning.php" class="feature-link">Accéder <i class="fas fa-arrow-right"></i></a>
        </div>
        
        <!-- Feature 5 - Prescriptions -->
        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-file-prescription"></i>
            </div>
            <h3>Prescriptions</h3>
            <p>Conservez et accédez à toutes vos ordonnances numérisées en toute sécurité.</p>
            <a href="Prescriptions.php" class="feature-link">Accéder <i class="fas fa-arrow-right"></i></a>
        </div>
        
        <!-- Feature 6 - Urgence -->
        <div class="feature-card emergency">
            <div class="feature-icon">
                <i class="fas fa-bell"></i>
            </div>
            <h3>Urgence</h3>
            <p>Accès rapide à vos informations vitales en cas d'urgence médicale.</p>
            <a href="urgence.php" class="feature-link">Accéder <i class="fas fa-arrow-right"></i></a>
        </div>
    </div>
</section>

    <section class="testimonials">
        <h2>Témoignages</h2>
        <p class="section-subtitle">Ce que nos utilisateurs disent de nous</p>
        <div class="testimonial-container">
            <div class="testimonial-card">
                <div class="testimonial-content">
                    <p>"MediAssist a révolutionné ma gestion des médicaments. Plus jamais je n'oublie de prendre mes traitements!"</p>
                </div>
                <div class="testimonial-author">
                    <span class="author-name">Marie D.</span>
                    <span class="author-details">Utilisatrice depuis 2023</span>
                </div>
            </div>
            <div class="testimonial-card">
                <div class="testimonial-content">
                    <p>"En tant que personne âgée prenant plusieurs médicaments, cette application est une bénédiction."</p>
                </div>
                <div class="testimonial-author">
                    <span class="author-name">Jean P.</span>
                    <span class="author-details">Utilisateur depuis 2024</span>
                </div>
            </div>
            <div class="testimonial-card">
                <div class="testimonial-content">
                    <p>"L'interface est si intuitive que même mes parents de 80 ans l'utilisent sans problème."</p>
                </div>
                <div class="testimonial-author">
                    <span class="author-name">Sophie L.</span>
                    <span class="author-details">Utilisatrice depuis 2022</span>
                </div>
            </div>
        </div>
    </section>

    <section class="faq-section">
        <h2>Questions Fréquentes</h2>
        <p class="section-subtitle">Trouvez des réponses à vos questions</p>
        <div class="faq-container">
            <div class="faq-item">
                <button class="faq-question">Comment ajouter un nouveau médicament?<i class="fas fa-chevron-down"></i></button>
                <div class="faq-answer">
                    <p>Allez dans la section "Médicaments", cliquez sur "+ Ajouter" et renseignez les informations demandées (nom, dosage, fréquence, etc.). Vous pouvez même scanner le code-barres de votre boîte de médicaments pour un ajout automatique.</p>
                </div>
            </div>
            <div class="faq-item">
                <button class="faq-question">Mes données sont-elles sécurisées?<i class="fas fa-chevron-down"></i></button>
                <div class="faq-answer">
                    <p>Absolument. Nous utilisons un chiffrement de niveau bancaire pour protéger toutes vos informations de santé. Vos données ne sont jamais partagées sans votre consentement explicite.</p>
                </div>
            </div>
            <div class="faq-item">
                <button class="faq-question">Puis-je partager mes informations avec mon médecin?<i class="fas fa-chevron-down"></i></button>
                <div class="faq-answer">
                    <p>Oui, vous pouvez générer un rapport complet ou partager des informations spécifiques avec vos professionnels de santé via email ou impression directement depuis l'application.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="cta-section">
        <div class="cta-content">
            <h2>Prêt à prendre le contrôle de votre santé?</h2>
            <p>Rejoignez des milliers d'utilisateurs satisfaits dès aujourd'hui</p>
            <div class="cta-buttons">
                <a href="signup.php" class="btn">S'inscrire maintenant</a>
                <a href="contact.php" class="btn btn-outline">Nous contacter</a>
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

<script src="questions.js"></script>
</body>
</html>