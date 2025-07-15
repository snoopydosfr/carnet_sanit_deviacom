<?php
session_start();
include '../includes/header.php';
include '../includes/db.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header('Location: ../login.php');
    exit();
}
?>

<div class="container mt-4">
    <h3>Visualisation des Données</h3>
    <p class="text-muted">Graphiques simplifiés pour PHP 5.4 – version statique testée.</p>

    <!-- Graphique Températures -->
    <div class="card mb-4">
        <div class="card-header">
            Évolution Mensuelle des Températures ECS
        </div>
        <div class="card-body">
            <?php
            // Récupérer les températures depuis la base
            $stmt = $pdo->query("SELECT * FROM surveillance_temperature ORDER BY annee DESC LIMIT 1");

            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '<table class="table table-bordered">';
                echo "<tr><th>Mois</th><th>Valeur (°C)</th></tr>";

                $data = array(
                    'jan' => $row['jan'],
                    'fev' => $row['fev'],
                    'mar' => $row['mar'],
                    'avr' => $row['avr'],
                    'mai' => $row['mai'],
                    'jun' => $row['jun'],
                    'jul' => $row['jul'],
                    'aou' => $row['aou'],
                    'sep' => $row['sep'],
                    'oct' => $row['oct'],
                    'nov' => $row['nov'],
                    'dec' => $row['dec']
                );

                foreach ($data as $mois => $value) {
                    echo "<tr><td>" . ucfirst($mois) . "</td><td>" . ($value !== null ? $value : '-') . "</td></tr>";
                }

                echo '</table>';
            } else {
                echo "<div class='alert alert-warning'>Aucune donnée trouvée dans la base de données.</div>";
            }
            ?>
        </div>
    </div>

    <!-- Graphique Légionelles -->
    <div class="card mb-4">
        <div class="card-header">
            Résultats des Analyses Légionelles
        </div>
        <div class="card-body">
            <?php
            // Récupérer les analyses légionelles
            $stmt_leg = $pdo->query("SELECT point_prelevement, resultat FROM analyse_legionelle ORDER BY date DESC LIMIT 5");

            if ($stmt_leg->rowCount() > 0) {
                echo '<ul class="list-group">';
                while ($row = $stmt_leg->fetch(PDO::FETCH_ASSOC)) {
                    $resultat = $row['resultat'];
                    $classe = $resultat > 1000 ? 'list-group-item-danger' : 'list-group-item-success';

                    echo "<li class='list-group-item " . $classe . "'>";
                    echo "<strong>" . htmlspecialchars($row['point_prelevement']) . "</strong> : ";
                    echo $resultat . " UFC/L";
                    echo "</li>";
                }
                echo '</ul>';
            } else {
                echo "<div class='alert alert-info'>Aucun résultat d'analyse légionelle trouvé.</div>";
            }
            ?>
        </div>
    </div>

    <a href="../index.php" class="btn btn-secondary">&larr; Retour à l'accueil</a>
</div>

<?php include '../includes/footer.php'; ?>