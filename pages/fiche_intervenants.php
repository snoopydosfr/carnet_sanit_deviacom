<?php
session_start();
include '../includes/header.php';
include '../includes/db.php';

// Ajout d’un intervenant interne
if (isset($_POST['add_interne'])) {
    $nom = $_POST['nom'];
    $fonction = $_POST['fonction'];
    $telephone = $_POST['telephone'];

    $stmt = $pdo->prepare("INSERT INTO intervenants_internes (nom, fonction, telephone) VALUES (?, ?, ?)");
    $stmt->execute([$nom, $fonction, $telephone]);
    header("Location: fiche_intervenants.php");
    exit();
}

// Ajout d’un intervenant externe
if (isset($_POST['add_externe'])) {
    $societe = $_POST['societe'];
    $domaine = $_POST['domaine'];
    $telephone = $_POST['telephone'];
    $contrat_expiration = $_POST['contrat_expiration'];

    $stmt = $pdo->prepare("INSERT INTO intervenants_externes (societe, domaine, telephone, contrat_expiration) VALUES (?, ?, ?, ?)");
    $stmt->execute([$societe, $domaine, $telephone, $contrat_expiration]);
    header("Location: fiche_intervenants.php");
    exit();
}
?>

<div class="container mt-4">
    <h3>Fiche Intervenants</h3>

    <!-- Intervenants Internes -->
    <div class="mb-5">
        <h4>Intervenants Internes</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nom</th><th>Fonction</th><th>Téléphone</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $pdo->query("SELECT * FROM intervenants_internes");
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>
                            <td>{$row['nom']}</td>
                            <td>{$row['fonction']}</td>
                            <td>{$row['telephone']}</td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Formulaire d'ajout -->
        <form method="post">
            <div class="row g-2">
                <div class="col-md-4">
                    <input type="text" name="nom" class="form-control" placeholder="Nom" required>
                </div>
                <div class="col-md-4">
                    <input type="text" name="fonction" class="form-control" placeholder="Fonction / Qualification" required>
                </div>
                <div class="col-md-3">
                    <input type="text" name="telephone" class="form-control" placeholder="Tél / Fax" required>
                </div>
                <div class="col-md-1">
                    <button type="submit" name="add_interne" class="btn btn-success">+</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Intervenants Externes -->
    <div class="mb-5">
        <h4>Intervenants Externes</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Société</th><th>Domaine</th><th>Téléphone</th><th>Fin du contrat</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $pdo->query("SELECT * FROM intervenants_externes");
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>
                            <td>{$row['societe']}</td>
                            <td>{$row['domaine']}</td>
                            <td>{$row['telephone']}</td>
                            <td>" . date('d/m/Y', strtotime($row['contrat_expiration'])) . "</td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Formulaire d'ajout -->
        <form method="post">
            <div class="row g-2">
                <div class="col-md-3">
                    <input type="text" name="societe" class="form-control" placeholder="Société" required>
                </div>
                <div class="col-md-3">
                    <input type="text" name="domaine" class="form-control" placeholder="Domaine d'intervention" required>
                </div>
                <div class="col-md-3">
                    <input type="text" name="telephone" class="form-control" placeholder="Tél / Fax" required>
                </div>
                <div class="col-md-2">
                    <input type="date" name="contrat_expiration" class="form-control" required>
                </div>
                <div class="col-md-1">
                    <button type="submit" name="add_externe" class="btn btn-success">+</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>