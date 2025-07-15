<?php
session_start();
include '../includes/header.php';
include '../includes/db.php';

// Traitement du formulaire d'ajout
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $point_prelevement = $_POST['point_prelevement'];
    $date = $_POST['date'];
    $temperature = $_POST['temperature'];
    $mode_prelevement = $_POST['mode_prelevement'];
    $resultat = $_POST['resultat'];
    $action = $_POST['action'];

    $stmt = $pdo->prepare("INSERT INTO analyse_legionelle 
        (point_prelevement, date, temperature, mode_prelevement, resultat, action)
        VALUES (?, ?, ?, ?, ?, ?)");

    $stmt->execute([$point_prelevement, $date, $temperature, $mode_prelevement, $resultat, $action]);

    header("Location: analyse_legionelle.php?success=1");
    exit();
}
?>

<div class="container mt-4">
    <h3>Analyses Légionelles</h3>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">Données enregistrées avec succès.</div>
    <?php endif; ?>

    <form method="post" class="mb-4">
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label for="point_prelevement" class="form-label">Point de prélèvement</label>
                <input type="text" name="point_prelevement" id="point_prelevement" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label for="date" class="form-label">Date du prélèvement</label>
                <input type="date" name="date" id="date" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label for="temperature" class="form-label">Température (°C)</label>
                <input type="number" step="0.1" name="temperature" id="temperature" class="form-control" required>
            </div>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-3">
                <label for="mode_prelevement" class="form-label">Mode de prélèvement</label>
                <select name="mode_prelevement" id="mode_prelevement" class="form-select" required>
                    <option value="">Sélectionner</option>
                    <option value="1er jet">1er jet</option>
                    <option value="Après écoulement">Après écoulement</option>
                </select>
            </div>

            <div class="col-md-3">
                <label for="resultat" class="form-label">Résultat en Legionella pneumophila (UFC/L)</label>
                <input type="number" name="resultat" id="resultat" class="form-control" required>
            </div>

            <div class="col-md-6">
                <label for="action" class="form-label">Actions mises en place si >1000 UFC/L</label>
                <textarea name="action" id="action" class="form-control" rows="3"></textarea>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Enregistrer l'analyse</button>
    </form>

    <hr>

    <h4>Historique des analyses légionelles</h4>
    <table class="table table-bordered table-striped mt-3">
        <thead class="table-light">
            <tr>
                <th>Point de prélèvement</th>
                <th>Date</th>
                <th>Température (°C)</th>
                <th>Mode de prélèvement</th>
                <th>Résultat (UFC/L)</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $stmt = $pdo->query("SELECT * FROM analyse_legionelle ORDER BY date DESC");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>
                        <td>{$row['point_prelevement']}</td>
                        <td>" . date('d/m/Y', strtotime($row['date'])) . "</td>
                        <td>{$row['temperature']}</td>
                        <td>{$row['mode_prelevement']}</td>
                        <td class='" . ($row['resultat'] > 1000 ? 'text-danger fw-bold' : '') . "'>{$row['resultat']}</td>
                        <td>{$row['action']}</td>
                      </tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include '../includes/footer.php'; ?>