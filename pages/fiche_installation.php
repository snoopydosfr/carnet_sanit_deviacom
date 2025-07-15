<?php
session_start();
include '../includes/header.php';
include '../includes/db.php';

// Traitement du formulaire d'ajout
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $origine_eau = $_POST['origine_eau'];
    $diagnostic_date = $_POST['diagnostic_date'];
    $reseau_eau_froide = $_POST['reseau_eau_froide'];
    $reseau_eau_chaude = $_POST['reseau_eau_chaude'];
    $traitement_eau = $_POST['traitement_eau'];

    // Vérifier si une fiche existe déjà
    $stmt = $pdo->query("SELECT * FROM fiche_installation LIMIT 1");
    if ($stmt->rowCount() > 0) {
        // Mise à jour
        $stmt = $pdo->prepare("UPDATE fiche_installation SET 
            origine_eau = ?, diagnostic_date = ?, reseau_eau_froide = ?, reseau_eau_chaude = ?, traitement_eau = ?
            WHERE id = 1");
    } else {
        // Insertion initiale
        $stmt = $pdo->prepare("INSERT INTO fiche_installation (
            origine_eau, diagnostic_date, reseau_eau_froide, reseau_eau_chaude, traitement_eau)
            VALUES (?, ?, ?, ?, ?)");
    }

    $stmt->execute([
        $origine_eau,
        $diagnostic_date,
        $reseau_eau_froide,
        $reseau_eau_chaude,
        $traitement_eau
    ]);

    header("Location: fiche_installation.php?success=1");
    exit();
}

// Récupérer les données existantes (une seule fiche possible)
$stmt = $pdo->query("SELECT * FROM fiche_installation LIMIT 1");
$data = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<div class="container mt-4">
    <h3>Fiche Installation</h3>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">Données enregistrées avec succès.</div>
    <?php endif; ?>

    <form method="post">
        <div class="mb-3">
            <label class="form-label"><strong>Origine de l’eau</strong></label><br>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="origine_eau" value="Distribution publique" <?= ($data['origine_eau'] ?? '') == 'Distribution publique' ? 'checked' : '' ?>>
                <label class="form-check-label">Distribution publique</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="origine_eau" value="Ressource privée - Puits" <?= ($data['origine_eau'] ?? '') == 'Ressource privée - Puits' ? 'checked' : '' ?>>
                <label class="form-check-label">Ressource privée - Puits</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="origine_eau" value="Ressource privée - Forage" <?= ($data['origine_eau'] ?? '') == 'Ressource privée - Forage' ? 'checked' : '' ?>>
                <label class="form-check-label">Ressource privée - Forage</label>
            </div>
        </div>

        <div class="mb-3">
            <label for="diagnostic_date" class="form-label"><strong>Date du diagnostic des réseaux d'eau</strong></label>
            <input type="date" name="diagnostic_date" id="diagnostic_date" class="form-control" value="<?= htmlspecialchars($data['diagnostic_date'] ?? '') ?>">
        </div>

        <div class="mb-3">
            <label class="form-label"><strong>Réseau Eau Froide (EF)</strong></label>
            <textarea name="reseau_eau_froide" class="form-control" rows="5"><?= htmlspecialchars($data['reseau_eau_froide'] ?? '') ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label"><strong>Réseau Eau Chaude Sanitaire (ECS)</strong></label>
            <textarea name="reseau_eau_chaude" class="form-control" rows="5"><?= htmlspecialchars($data['reseau_eau_chaude'] ?? '') ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label"><strong>Type de traitement installé (s’il y a lieu)</strong></label>
            <textarea name="traitement_eau" class="form-control" rows="5"><?= htmlspecialchars($data['traitement_eau'] ?? '') ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Enregistrer</button>
    </form>

    <hr>

    <h4>Historique de la fiche installation</h4>
    <table class="table table-bordered table-striped">
        <thead class="table-light">
            <tr>
                <th>Date du diagnostic</th>
                <th>Réseau EF</th>
                <th>Réseau ECS</th>
                <th>Traitement eau</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $stmt = $pdo->query("SELECT * FROM fiche_installation ORDER BY id DESC LIMIT 10");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>
                        <td>" . date('d/m/Y', strtotime($row['diagnostic_date'])) . "</td>
                        <td>" . nl2br(htmlspecialchars($row['reseau_eau_froide'])) . "</td>
                        <td>" . nl2br(htmlspecialchars($row['reseau_eau_chaude'])) . "</td>
                        <td>" . nl2br(htmlspecialchars($row['traitement_eau'])) . "</td>
                      </tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include '../includes/footer.php'; ?>