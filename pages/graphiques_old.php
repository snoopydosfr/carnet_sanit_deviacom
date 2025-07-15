<?php
session_start();
include '../includes/header.php';
include '../includes/db.php';
?>

<div class="container mt-4">
    <h3>Visualisation des Données</h3>
    <p class="text-muted">Graphiques interactifs des mesures de température et analyses légionelles.</p>

    <ul class="nav nav-tabs" id="graphTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="temperature-tab" data-bs-toggle="tab" data-bs-target="#temperature-chart" type="button" role="tab">Températures ECS</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="legionelle-tab" data-bs-toggle="tab" data-bs-target="#legionelle-chart" type="button" role="tab">Analyses Légionelles</button>
        </li>
    </ul>

    <div class="tab-content mt-4">
        <!-- Graphique Températures -->
        <div class="tab-pane fade show active" id="temperature-chart" role="tabpanel">
            <h4>Évolution Mensuelle des Températures</h4>
            <canvas id="temperatureChart" width="800" height="250"></canvas>
        </div>

        <!-- Graphique Légionelles -->
        <div class="tab-pane fade" id="legionelle-chart" role="tabpanel">
            <h4>Résultats des Analyses Légionelles</h4>
            <canvas id="legionelleChart" width="800" height="250"></canvas>
        </div>
    </div>
</div>

<!-- Inclusion de Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js "></script>

<script>
// Récupération des données PHP pour les graphiques
const temperatureData = {
    labels: ["Jan", "Fév", "Mar", "Avr", "Mai", "Jun", "Jul", "Aou", "Sep", "Oct", "Nov", "Déc"],
    datasets: []
};

const legionelleData = {
    labels: [],
    datasets: [{
        label: 'Résultat (UFC/L)',
        data: [],
        borderColor: '#dc3545',
        backgroundColor: 'rgba(220, 53, 69, 0.2)',
        fill: false,
        tension: 0.1
    }]
};

// Chargement dynamique des données depuis la base
<?php
// Températures mensuelles
$stmt = $pdo->query("SELECT mois, jan, fev, mar, avr, mai, jun, jul, aou, sep, oct, nov, dec, point_surveillance FROM surveillance_temperature ORDER BY annee DESC LIMIT 3");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "
    temperatureData.datasets.push({
        label: '" . addslashes($row['point_surveillance']) . "',
        data: [" . $row['jan'] . ", " . $row['fev'] . ", " . $row['mar'] . ", " . $row['avr'] . ", " . $row['mai'] . ", " . $row['jun'] . ", " . $row['jul'] . ", " . $row['aou'] . ", " . $row['sep'] . ", " . $row['oct'] . ", " . $row['nov'] . ", " . $row['dec'] . "],
        borderColor: '#' + Math.floor(Math.random()*16777215).toString(16),
        fill: false,
        tension: 0.1
    });
    ";
}

// Analyses légionelles
$stmt_leg = $pdo->query("SELECT * FROM analyse_legionelle ORDER BY date DESC LIMIT 10");
$labels_leg = [];
$data_leg = [];
foreach ($stmt_leg as $row) {
    $labels_leg[] = date('d/m', strtotime($row['date'])) . " (" . $row['point_prelevement'] . ")";
    $data_leg[] = $row['resultat'];
}
if (!empty($labels_leg)) {
    echo "
    legionelleData.labels = [" . json_encode($labels_leg)[1];
    echo "legionelleData.datasets[0].data = [" . json_encode($data_leg)[1];
}
?>

// Création du graphique températures
const ctxTemp = document.getElementById('temperatureChart').getContext('2d');
new Chart(ctxTemp, {
    type: 'line',
    data: temperatureData,
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'top' },
            title: { display: true, text: 'Évolution des températures mensuelles' }
        },
        scales: {
            y: {
                beginAtZero: false,
                title: { display: true, text: 'Température (°C)' }
            }
        }
    }
});

// Création du graphique légionelles
const ctxLeg = document.getElementById('legionelleChart').getContext('2d');
new Chart(ctxLeg, {
    type: 'line',
    data: legionelleData,
    options: {
        responsive: true,
        plugins: {
            legend: { display: true },
            title: { display: true, text: 'Résultats des analyses légionelles' }
        },
        scales: {
            y: {
                beginAtZero: true,
                title: { display: true, text: 'UFC/L' }
            }
        }
    }
});
</script>

<?php include '../includes/footer.php'; ?>