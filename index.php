<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

include 'includes/header.php';
?>

<div class="container-fluid mt-4">
    <div class="row">
        <!-- Fiches numérisées -->
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white h-100">
                <div class="card-body">
                    <h5 class="card-title">Fiche Intervenants</h5>
                    <p class="card-text">Gestion des intervenants internes et externes.</p>
                    <a href="/edsa-chatqwen/pages/fiche_intervenants.php" class="btn btn-light btn-sm">Accéder</a>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white h-100">
                <div class="card-body">
                    <h5 class="card-title">Maintenance Hebdomadaire</h5>
                    <p class="card-text">Suivi des opérations hebdomadaires effectuées.</p>
                    <a href="/edsa-chatqwen/pages/maintenance_hebdo.php" class="btn btn-light btn-sm">Accéder</a>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-dark h-100">
                <div class="card-body">
                    <h5 class="card-title">Températures ECS</h5>
                    <p class="card-text">Mesures mensuelles des températures.</p>
                    <a href="/edsa-chatqwen/pages/surveillance_temperatures.php" class="btn btn-dark btn-sm">Accéder</a>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card bg-info text-dark h-100">
                <div class="card-body">
                    <h5 class="card-title">Analyses Légionelles</h5>
                    <p class="card-text">Résultats annuels des analyses.</p>
                    <a href="/edsa-chatqwen/pages/analyse_legionelle.php" class="btn btn-dark btn-sm">Accéder</a>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card bg-danger text-white h-100">
                <div class="card-body">
                    <h5 class="card-title">Graphiques</h5>
                    <p class="card-text">Visualisation interactive des mesures.</p>
                    <a href="/edsa-chatqwen/pages/graphiques.php" class="btn btn-light btn-sm">Voir</a>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card bg-secondary text-white h-100">
                <div class="card-body">
                    <h5 class="card-title">Rapports PDF</h5>
                    <p class="card-text">Génération de rapports complets (PDF).</p>
                    <a href="/edsa-chatqwen/rapports/generate_pdf.php" class="btn btn-light btn-sm">Exporter</a>
                </div>
            </div>
        </div>

        <?php if ($_SESSION['user']['role'] === 'admin'): ?>
        <div class="col-md-3 mb-3">
            <div class="card bg-dark text-white h-100">
                <div class="card-body">
                    <h5 class="card-title">Utilisateurs</h5>
                    <p class="card-text">Gestion des comptes utilisateurs.</p>
                    <a href="/edsa-chatqwen/pages/utilisateurs.php" class="btn btn-light btn-sm">Configurer</a>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <hr>

    <div class="row mt-4">
        <div class="col-md-12">
            <h4>Bienvenue, <?= htmlspecialchars($_SESSION['user']['username']); ?> 👋</h4>
            <p>Vous êtes connecté en tant que <strong><?= $_SESSION['user']['role']; ?></strong>.</p>
            <p>Utilisez les onglets ci-dessus pour naviguer dans l'application.</p>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>