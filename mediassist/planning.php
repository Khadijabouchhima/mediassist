<?php
session_start();
require_once 'pdo_connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: signup.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Get current month and year
$month = isset($_GET['month']) ? (int)$_GET['month'] : date('n');
$year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');

// Calculate previous and next months
$prev_month = $month - 1 < 1 ? 12 : $month - 1;
$prev_year = $month - 1 < 1 ? $year - 1 : $year;
$next_month = $month + 1 > 12 ? 1 : $month + 1;
$next_year = $month + 1 > 12 ? $year + 1 : $year;

// Fetch data
$start_date = "$year-$month-01";
$end_date = date('Y-m-t', strtotime($start_date));

// Get appointments
$stmt = $pdo->prepare("SELECT * FROM appointments 
                      WHERE user_id = ? 
                      AND appointment_date BETWEEN ? AND ?
                      ORDER BY appointment_date, start_time");
$stmt->execute([$user_id, $start_date, $end_date]);
$appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get medications
$stmt = $pdo->prepare("SELECT * FROM medicaments WHERE user_id = ?");
$stmt->execute([$user_id]);
$medicaments = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Prepare calendar data
$calendar_data = [
    'month' => $month,
    'year' => $year,
    'prev_month' => $prev_month,
    'prev_year' => $prev_year,
    'next_month' => $next_month,
    'next_year' => $next_year,
    'appointments' => $appointments,
    'medicaments' => $medicaments,
    'month_name' => strftime('%B %Y', strtotime("$year-$month-01"))
];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planning | MediAssist</title>
    <link rel="stylesheet" href="planning.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<?php include('header.php'); ?>
    <div class="container">
        <main class="main-content">
            <section class="calendar-section">
                <div class="calendar-header">
                    <div class="calendar-title">
                        <?= $calendar_data['month_name'] ?>
                    </div>
                    <div class="calendar-nav">
                        <a href="?month=<?= $calendar_data['prev_month'] ?>&year=<?= $calendar_data['prev_year'] ?>" class="btn small">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                        <a href="?month=<?= date('n') ?>&year=<?= date('Y') ?>" class="btn small">
                            Aujourd'hui
                        </a>
                        <a href="?month=<?= $calendar_data['next_month'] ?>&year=<?= $calendar_data['next_year'] ?>" class="btn small">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </div>
                </div>
                
                <div class="calendar-grid">
                    <!-- Day headers -->
                    <?php 
                    $days = ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'];
                    foreach ($days as $day): ?>
                        <div class="calendar-day-header"><?= $day ?></div>
                    <?php endforeach; ?>
                    
                    <!-- Calendar days -->
                    <?php
                    $first_day = date('N', strtotime("{$calendar_data['year']}-{$calendar_data['month']}-01"));
                    $days_in_month = date('t', strtotime("{$calendar_data['year']}-{$calendar_data['month']}-01"));
                    $current_day = 1;
                    
                    for ($i = 1; $i <= 42; $i++): // 6 weeks max
                        if ($i >= $first_day && $current_day <= $days_in_month):
                            $date = sprintf("%04d-%02d-%02d", $calendar_data['year'], $calendar_data['month'], $current_day);
                            $today = date('Y-m-d') == $date;
                    ?>
                        <div class="calendar-day <?= $today ? 'today' : '' ?>">
                            <span class="day-number"><?= $current_day ?></span>
                            
                            <?php foreach ($calendar_data['appointments'] as $appt):
                                if ($appt['appointment_date'] == $date):?>
                                <div class="event event-appointment" title="<?= htmlspecialchars($appt['title']) ?>">
                                    <i class="far fa-calendar-alt"></i> 
                                    <?= date('H:i', strtotime($appt['start_time'])) ?> 
                                    <?= htmlspecialchars(substr($appt['title'], 0, 10)) ?>...
                                </div>
                            <?php endif; endforeach; ?>
                            
                            <?php foreach ($calendar_data['medicaments'] as $med):
                                // Convert frequence into an array
                                $days_of_week = $med['frequence'] ? explode(',', $med['frequence']) : range(1, 7);
                                $day_of_week = date('N', strtotime($date));
                                
                                // Check if medication should appear on the current day
                                if (in_array($day_of_week, $days_of_week)):?>
                                <div class="event event-medication" title="Prendre <?= htmlspecialchars($med['nom']) ?>">
                                    <i class="fas fa-pills"></i> 
                                    <?= substr($med['heure'], 0, 5) ?> 
                                    <?= htmlspecialchars(substr($med['nom'], 0, 10)) ?>...
                                </div>
                            <?php endif; endforeach; ?>
                        </div>
                        <?php $current_day++; else: ?>
                        <div class="calendar-day empty-day"></div>
                    <?php endif; endfor; ?>
                </div>
            </section>
            
            <section class="legend">
                <h2>Légende</h2>
                <div class="legend-items">
                    <div class="legend-item">
                        <span class="event-medication legend-icon"></span>
                        <span>Médicaments</span>
                    </div>
                    <div class="legend-item">
                        <span class="event-appointment legend-icon"></span>
                        <span>Rendez-vous</span>
                    </div>
                </div>
            </section>
        </main>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const todayElement = document.querySelector('.today');
            if (todayElement) {
                todayElement.scrollIntoView({behavior: 'smooth', block: 'center'});
            }
        });
    </script>
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
