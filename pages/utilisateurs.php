<?php
session_start();
include '../includes/header.php';
include '../includes/db.php';

// Vérifiez si l'utilisateur est admin
if ($_SESSION['user']['role'] !== 'admin') {
    header('Location: ../index.php');
    exit();
}

// Ajout d’un utilisateur
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])) {
    $username = trim($_POST['username']);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $stmt->execute([$username, $password, $role]);
    header("Location: utilisateurs.php?success=1");
    exit();
}

// Suppression d’un utilisateur
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: utilisateurs.php");
    exit();
}
?>

<div class="container mt-4">
    <h3>Gestion des Utilisateurs</h3>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">Utilisateur ajouté avec succès.</div>
    <?php endif; ?>

    <!-- Formulaire d'ajout -->
    <form method="post" class="mb-4">
        <div class="row g-3">
            <div class="col-md-4">
                <input type="text" name="username" class="form-control" placeholder="Nom d'utilisateur" required>
            </div>
            <div class="col-md-4">
                <input type="password" name="password" class="form-control" placeholder="Mot de passe" required>
            </div>
            <div class="col-md-2">
                <select name="role" class="form-select" required>
                    <option value="">Rôle</option>
                    <option value="admin">Admin</option>
                    <option value="technicien">Technicien</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" name="add_user" class="btn btn-success w-100">Ajouter</button>
            </div>
        </div>
    </form>

    <hr>

    <!-- Liste des utilisateurs -->
    <h4>Liste des utilisateurs</h4>
    <table class="table table-bordered table-striped">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Nom d'utilisateur</th>
                <th>Rôle</th>
                <th>Date de création</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $stmt = $pdo->query("SELECT * FROM users ORDER BY role DESC");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['username']}</td>
                        <td>{$row['role']}</td>
                        <td>" . date('d/m/Y', strtotime($row['created_at'])) . "</td>
                        <td>
                            <a href='?delete={$row['id']}' class='btn btn-danger btn-sm' onclick=\"return confirm('Êtes-vous sûr ?');\">Supprimer</a>
                        </td>
                      </tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include '../includes/footer.php'; ?>