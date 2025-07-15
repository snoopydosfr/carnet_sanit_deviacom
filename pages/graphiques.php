<?php
session_start();
include '../includes/header.php';
include '../includes/db.php';

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header('Location: ../login.php');
    exit();
}
?>

<div class="container mt-4">
    <h3>Visualisation des Données</h3>
    <p class="text-muted">Graphiques interactifs des mesures de température et analyses légionelles.</p>

    <!-- Onglets -->
    <ul class="nav nav-tabs" id="graphTabs" role="tablist">
        <li class="nav-item">
            <button class="nav-link active" id="temperature-tab" data-bs-toggle="tab" data-bs-target="#temperature-chart" type="button" role="tab">Températures ECS</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="legionelle-tab" data-bs-toggle="tab" data-bs-target="#legionelle-chart" type="button" role="tab">Analyses Légionelles</button>
        </li>
    </ul>

    <div class="tab-content mt-4">

        <!-- Graphique Températures -->
        <div class="tab-pane fade show active" id="temperature-chart" role="tabpanel">
            <h4 class="mt-4">Évolution Mensuelle des Températures</h4>
            <canvas id="temperatureChart" height="100"></canvas>
        </div>

        <!-- Graphique Légionelles -->
        <div class="tab-pane fade" id="legionelle-chart" role="tabpanel">
            <h4 class="mt-4">Résultats des Analyses Légionelles</h4>
            <canvas id="legionelleChart" height="100"></canvas>
        </div>

    </div>
</div>

<!-- ✅ Chart.js version 2.9.4 (compatible PHP 5.4) -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4"></script>

<script>
    // === Graphique Températures ECS ===
    var temperatureCtx = document.getElementById('temperatureChart');

    <?php
    // Récupérer les températures depuis la base
    $temperatureLabels = array("Jan", "Fév", "Mar", "Avr", "Mai", "Jun", "Jul", "Aou", "Sep", "Oct", "Nov", "Déc");
    $temperatureDataSets = array();

    try {
        $stmt = $pdo->query("SELECT * FROM surveillance_temperature ORDER BY annee DESC LIMIT 3");

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $dataSet = array(
                'label' => isset($row['point_surveillance']) ? $row['point_surveillance'] : 'Point inconnu',
                'data' => array(),
                'borderColor' => '#' . substr(md5(rand()), 0, 6),
                'backgroundColor' => 'rgba(13, 110, 253, 0.1)',
                'fill' => false,
                'tension' => 0.1
            );

            // Ajouter les valeurs mensuelles
            $dataSet['data'][] = isset($row['jan']) && $row['jan'] !== null ? floatval($row['jan']) : null;
            $dataSet['data'][] = isset($row['fev']) && $row['fev'] !== null ? floatval($row['fev']) : null;
            $dataSet['data'][] = isset($row['mar']) && $row['mar'] !== null ? floatval($row['mar']) : null;
            $dataSet['data'][] = isset($row['avr']) && $row['avr'] !== null ? floatval($row['avr']) : null;
            $dataSet['data'][] = isset($row['mai']) && $row['mai'] !== null ? floatval($row['mai']) : null;
            $dataSet['data'][] = isset($row['jun']) && $row['jun'] !== null ? floatval($row['jun']) : null;
            $dataSet['data'][] = isset($row['jul']) && $row['jul'] !== null ? floatval($row['jul']) : null;
            $dataSet['data'][] = isset($row['aou']) && $row['aou'] !== null ? floatval($row['aou']) : null;
            $dataSet['data'][] = isset($row['sep']) && $row['sep'] !== null ? floatval($row['sep']) : null;
            $dataSet['data'][] = isset($row['oct']) && $row['oct'] !== null ? floatval($row['oct']) : null;
            $dataSet['data'][] = isset($row['nov']) && $row['nov'] !== null ? floatval($row['nov']) : null;
            $dataSet['data'][] = isset($row['dec']) && $row['dec'] !== null ? floatval($row['dec']) : null;

            $temperatureDataSets[] = $dataSet;
        }
    } catch (PDOException $e) {
        echo '// Erreur SQL : ' . addslashes($e->getMessage()) . "\n";
    }
    ?>

    if (temperatureCtx) {
        var temperatureData = {
             labels: ["Jan", "Fév", "Mar", "Avr", "Mai", "Jun", "Jul", "Aou", "Sep", "Oct", "Nov", "Déc"],
            datasets: [
                <?php foreach ($temperatureDataSets as $set): ?>
                {
                    label: "<?php echo addslashes($set['label']); ?>",
                     [<?php
                        $jsData = array();
                        foreach ($set['data'] as $value) {
                            $jsData[] = $value !== null ? $value : 'null';
                        }
                        echo implode(',', $jsData);
                    ?>],
                    borderColor: "<?php echo $set['borderColor']; ?>",
                    backgroundColor: "<?php echo $set['backgroundColor']; ?>",
                    fill: <?php echo $set['fill'] ? 'true' : 'false'; ?>,
                    tension: <?php echo floatval($set['tension']); ?>
                },
                <?php endforeach; ?>
            ]
        };

        new Chart(temperatureCtx.getContext('2d'), {
            type: 'line',
             temperatureData,
            options: {
                responsive: true,
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: false
                        },
                        scaleLabel: {
                            display: true,
                            labelString: 'Température (°C)'
                        }
                    }],
                    xAxes: [{
                        ticks: {
                            callback: function(value) {
                                return value + ' °C';
                            }
                        }
                    }]
                },
                plugins: {
                    legend: { position: 'top' },
                    title: {
                        display: true,
                        text: 'Évolution mensuelle des températures ECS'
                    }
                }
            }
        });
    }

    // === Graphique Analyses Légionelles ===
    var legionelleCtx = document.getElementById('legionelleChart');

    <?php
    // Récupérer les résultats légionelles
    $legionelleLabels = array();
    $legionelleResults = array();

    try {
        $stmt_leg = $pdo->query("SELECT point_prelevement, resultat FROM analyse_legionelle ORDER BY date DESC LIMIT 10");

        while ($row = $stmt_leg->fetch(PDO::FETCH_ASSOC)) {
            $legionelleLabels[] = substr($row['point_prelevement'], 0, 20);
            $legionelleResults[] = intval($row['resultat']);
        }
    } catch (PDOException $e) {
        echo '// Erreur SQL légionelle : ' . addslashes($e->getMessage()) . "\n";
    }
    ?>

    if (legionelleCtx) {
        var legionelleData = {
             labels: [
                 <?php
                 $jsLegionelleLabels = array();
                 foreach ($legionelleLabels as $label) {
                     $jsLegionelleLabels[] = "'" . addslashes($label) . "'";
                 }
                 echo implode(',', $jsLegionelleLabels);
                 ?>
             ],
            datasets: [{
                label: 'Résultats (UFC/L)',
                 [<?php
                    $jsLegionelleResults = array();
                    foreach ($legionelleResults as $res) {
                        $jsLegionelleResults[] = intval($res);
                    }
                    echo implode(',', $jsLegionelleResults);
                ?>],
                backgroundColor: 'rgba(220, 53, 69, 0.6)',
                borderColor: 'rgba(220, 53, 69, 1)',
                borderWidth: 1
            }]
        };

        new Chart(legionelleCtx.getContext('2d'), {
            type: 'bar',
             legionelleData,
            options: {
                indexAxis: 'y',
                responsive: true,
                plugins: {
                    legend: { display: false },
                    title: {
                        display: true,
                        text: 'Résultats légionelles – UFC/L'
                    }
                },
                scales: {
                    xAxes: [{
                        ticks: {
                            callback: function(value) {
                                return value + ' UFC/L';
                            }
                        }
                    }]
                }
            }
        });
    }
</script>

<?php include '../includes/footer.php'; ?>