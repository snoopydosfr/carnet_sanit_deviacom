<?php
session_start();
include '../includes/header.php';
include '../includes/db.php';

// Traitement du formulaire d'ajout
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mois = $_POST['mois'];
    $annee = $_POST['annee'];
    $point_surveillance = $_POST['point_surveillance'];

    // Récupération des températures mensuelles
    $jan = !empty($_POST['jan']) ? $_POST['jan'] : null;
    $fev = !empty($_POST['fev']) ? $_POST['fev'] : null;
    $mar = !empty($_POST['mar']) ? $_POST['mar'] : null;
    $avr = !empty($_POST['avr']) ? $_POST['avr'] : null;
    $mai = !empty($_POST['mai']) ? $_POST['mai'] : null;
    $jun = !empty($_POST['jun']) ? $_POST['jun'] : null;
    $jul = !empty($_POST['jul']) ? $_POST['jul'] : null;
    $aou = !empty($_POST['aou']) ? $_POST['aou'] : null;
    $sep = !empty($_POST['sep']) ? $_POST['sep'] : null;
    $oct = !empty($_POST['octobre']) ? $_POST['octobre'] : null; // Renommé en octobre dans le form
    $nov = !empty($_POST['nov']) ? $_POST['nov'] : null;
    $dec = !empty($_POST['decembre']) ? $_POST['decembre'] : null; // Renommé en decembre pour éviter mot réservé

    try {
        // Requête SQL avec protection des mots réservés
        $stmt = $pdo->prepare("INSERT INTO surveillance_temperature 
            (mois, annee, point_surveillance, jan, fev, mar, avr, mai, jun, jul, aou, sep, `oct`, nov, `dec`)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->execute([
            $mois, $annee, $point_surveillance,
            $jan, $fev, $mar, $avr, $mai, $jun, $jul, $aou, $sep, $oct, $nov, $dec
        ]);

        header("Location: surveillance_temperatures.php?success=1");
        exit();
    } catch (PDOException $e) {
        echo "<div class='alert alert-danger'>Erreur lors de l'enregistrement : " . htmlspecialchars($e->getMessage()) . "</div>";
    }
}
?>

<div class="container mt-4">
    <h3>Surveillance des Températures</h3>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">Données enregistrées avec succès.</div>
    <?php endif; ?>

    <form method="post" class="mb-4">
        <div class="row g-3 mb-3">
            <div class="col-md-3">
                <label for="mois" class="form-label">Mois</label>
                <select name="mois" id="mois" class="form-select" required>
                    <option value="">Sélectionner un mois</option>
                    <?php
                    $months = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
                               'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
                    foreach ($months as $m) {
                        echo "<option value='$m'>$m</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-2">
                <label for="annee" class="form-label">Année</label>
                <input type="number" name="annee" id="annee" class="form-control"
                       value="<?= date('Y') ?>" readonly required>
            </div>
            <div class="col-md-7">
                <label for="point_surveillance" class="form-label">Point de surveillance</label>
                <input type="text" name="point_surveillance" id="point_surveillance" class="form-control"
                       placeholder="Exemple : Sortie Production ECS - Retour Boucle - Salle de bain 3" required>
            </div>
        </div>

        <div class="row text-center fw-bold mb-2">
            <div class="col">Jan</div>
            <div class="col">Fév</div>
            <div class="col">Mar</div>
            <div class="col">Avr</div>
            <div class="col">Mai</div>
            <div class="col">Jun</div>
            <div class="col">Jul</div>
            <div class="col">Aou</div>
            <div class="col">Sep</div>
            <div class="col">Oct</div>
            <div class="col">Nov</div>
            <div class="col">Déc</div>
        </div>

        <div class="row g-2 mb-3">
            <div class="col"><input type="number" step="0.1" name="jan" class="form-control" placeholder="°C"></div>
            <div class="col"><input type="number" step="0.1" name="fev" class="form-control" placeholder="°C"></div>
            <div class="col"><input type="number" step="0.1" name="mar" class="form-control" placeholder="°C"></div>
            <div class="col"><input type="number" step="0.1" name="avr" class="form-control" placeholder="°C"></div>
            <div class="col"><input type="number" step="0.1" name="mai" class="form-control" placeholder="°C"></div>
            <div class="col"><input type="number" step="0.1" name="jun" class="form-control" placeholder="°C"></div>
            <div class="col"><input type="number" step="0.1" name="jul" class="form-control" placeholder="°C"></div>
            <div class="col"><input type="number" step="0.1" name="aou" class="form-control" placeholder="°C"></div>
            <div class="col"><input type="number" step="0.1" name="sep" class="form-control" placeholder="°C"></div>
            <div class="col"><input type="number" step="0.1" name="octobre" class="form-control" placeholder="°C"></div> <!-- Renommé -->
            <div class="col"><input type="number" step="0.1" name="nov" class="form-control" placeholder="°C"></div>
            <div class="col"><input type="number" step="0.1" name="decembre" class="form-control" placeholder="°C"></div> <!-- Renommé -->
        </div>

        <button type="submit" class="btn btn-primary">Enregistrer</button>
    </form>

    <hr>

    <h4>Historique des mesures de température</h4>
    <table class="table table-bordered table-striped mt-3">
        <thead class="table-light">
            <tr>
                <th>Mois</th>
                <th>Année</th>
                <th>Point de surveillance</th>
                <th>Jan</th><th>Fév</th><th>Mar</th><th>Avr</th>
                <th>Mai</th><th>Jun</th><th>Jul</th><th>Aou</th>
                <th>Sep</th><th>Oct</th><th>Nov</th><th>Déc</th>
            </tr>
        </thead>
        <tbody>
          
                <?php
            $stmt = $pdo->query("SELECT * FROM surveillance_temperature ORDER BY annee DESC LIMIT 10");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>
                        <td>{$row['mois']}</td>
                        <td>{$row['annee']}</td>
                        <td>{$row['point_surveillance']}</td>
                        <td>" . (!empty($row['jan']) ? $row['jan'] : '-') . "</td>
                        <td>" . (!empty($row['fev']) ? $row['fev'] : '-') . "</td>
                        <td>" . (!empty($row['mar']) ? $row['mar'] : '-') . "</td>
                        <td>" . (!empty($row['avr']) ? $row['avr'] : '-') . "</td>
                        <td>" . (!empty($row['mai']) ? $row['mai'] : '-') . "</td>
                        <td>" . (!empty($row['jun']) ? $row['jun'] : '-') . "</td>
                        <td>" . (!empty($row['jul']) ? $row['jul'] : '-') . "</td>
                        <td>" . (!empty($row['aou']) ? $row['aou'] : '-') . "</td>
                        <td>" . (!empty($row['sep']) ? $row['sep'] : '-') . "</td>
                        <td>" . (!empty($row['oct']) ? $row['oct'] : '-') . "</td>
                        <td>" . (!empty($row['nov']) ? $row['nov'] : '-') . "</td>
                        <td>" . (!empty($row['dec']) ? $row['dec'] : '-') . "</td>
                      </tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include '../includes/footer.php'; ?>