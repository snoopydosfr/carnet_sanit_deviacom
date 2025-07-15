<?php
//session_start();

// Vérifier si l'utilisateur est connecté
if (isset($_SESSION['user'])) {
    $role = $_SESSION['user']['role'];
} else {
    $role = null;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Carnet Sanitaire Digitalisé</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css " rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js "></script>
    <style>
        body {
            padding-top: 70px;
        }
        .navbar {
            margin-bottom: 20px;
        }
        .alert-legionella {
            background-color: #fff3cd;
            color: #856404;
            border-left: 5px solid #ffc107;
            padding: 10px 15px;
            margin: 10px 0;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">Carnet Sanitaire</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="/edsa-chatqwen/index.php"><i class="fas fa-home"></i> Accueil</a></li>
                <li class="nav-item"><a class="nav-link" href="/edsa-chatqwen/pages/maintenance_hebdo.php"><i class="fas fa-tools"></i> Maintenance Hebdomadaire</a></li>
                <li class="nav-item"><a class="nav-link" href="/edsa-chatqwen/pages/surveillance_temperatures.php"><i class="fas fa-thermometer-half"></i> Températures ECS</a></li>
                <li class="nav-item"><a class="nav-link" href="/edsa-chatqwen/pages/analyse_legionelle.php"><i class="fas fa-virus"></i> Analyses Légionelles</a></li>
                <li class="nav-item"><a class="nav-link" href="/edsa-chatqwen/rapports/generate_pdf.php"><i class="fas fa-file-pdf"></i> Export PDF</a></li>
                <?php if ($role === 'admin'): ?>
                    <li class="nav-item"><a class="nav-link" href="/edsa-chatqwen/pages/utilisateurs.php"><i class="fas fa-users-cog"></i> Utilisateurs</a></li>
                <?php endif; ?>
            </ul>
            <ul class="navbar-nav ms-auto">
                <?php if (isset($_SESSION['user'])): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle"></i> <?= htmlspecialchars($_SESSION['user']['username']); ?> (<?= $_SESSION['user']['role']; ?>)
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="/edsa-chatqwen/logout.php"><i class="fas fa-sign-out-alt"></i> Déconnexion</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="/edsa-chatqwen/login.php"><i class="fas fa-sign-in-alt"></i> Connexion</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<div class="container-fluid">

<?php
// Afficher une alerte légionelle si nécessaire
include 'db.php';

$stmt = $pdo->query("SELECT * FROM analyse_legionelle WHERE resultat > 1000 ORDER BY date DESC LIMIT 1");
if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo '<div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
            <strong>ALERTE LÉGIONELLE</strong> : Un résultat supérieur à 1000 UFC/L a été détecté au point "'.$row['point_prelevement'].'" le '.$row['date'].'.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
          </div>';
}
?>