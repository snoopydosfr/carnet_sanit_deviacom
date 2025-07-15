<?php
session_start();
include '../includes/header.php';
include '../includes/db.php';

// Traitement du formulaire d'ajout
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $semaine = $_POST['semaine'];
    $annee = $_POST['annee'];
    $point_usage = $_POST['point_usage'];
    $temperature = $_POST['temperature'];
    $chasse_ballon = isset($_POST['chasse_ballon']) ? 1 : 0;

    $stmt = $pdo->prepare("INSERT INTO maintenance_hebdo 
        (semaine, annee, point_usage, temperature, chasse_ballon, date_operation, utilisateur_id)
        VALUES (?, ?, ?, ?, ?, NOW(), ?)");

    $stmt->execute([$semaine, $annee, $point_usage, $temperature, $chasse_ballon, $_SESSION['user']['id']]);
    
    header("Location: maintenance_hebdo.php?success=1");
    exit();
}
?>

<div class="container mt-4">
    <h3>Maintenance Hebdomadaire</h3>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">Données enregistrées avec succès.</div>
    <?php endif; ?>

    <form method="post">
        <div class="row g-3 mb-3">
            <div class="col-md-3">
                <label for="semaine" class="form-label">Semaine</label>
                <input type="number" name="semaine" id="semaine" class="form-control" min="1" max="53" required>
            </div>
            <div class="col-md-3">
                <label for="annee" class="form-label">Année</label>
                <input type="number" name="annee" id="annee" class="form-control" value="<?= date('Y') ?>" readonly>
            </div>
            <div class="col-md-6">
                <label for="point_usage" class="form-label">Point d'usage</label>
                <input type="text" name="point_usage" id="point_usage" class="form-control" required>
            </div>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label for="temperature" class="form-label">Température (°C)</label>
                <input type="number" step="0.1" name="temperature" id="temperature" class="form-control" required>
            </div>
            <div class="col-md-6 align-self-end">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="chasse_ballon" id="chasse_ballon">
                    <label class="form-check-label" for="chasse_ballon">
                        Chasse du ballon effectuée
                    </label>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Enregistrer</button>
    </form>

    <hr>

    <h4>Historique des opérations hebdomadaires</h4>
    <table class="table table-bordered table-striped mt-3">
        <thead class="table-light">
            <tr>
                <th>Semaine</th>
                <th>Année</th>
                <th>Point d'usage</th>
                <th>Température (°C)</th>
                <th>Chasse ballon</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $stmt = $pdo->query("SELECT * FROM maintenance_hebdo ORDER BY annee DESC, semaine DESC");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>
                        <td>{$row['semaine']}</td>
                        <td>{$row['annee']}</td>
                        <td>{$row['point_usage']}</td>
                        <td>{$row['temperature']}</td>
                        <td>" . ($row['chasse_ballon'] ? '✅ Oui' : '❌ Non') . "</td>
                        <td>" . date('d/m/Y', strtotime($row['date_operation'])) . "</td>
                      </tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include '../includes/footer.php'; ?>