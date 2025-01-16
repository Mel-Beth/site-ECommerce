<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: /index.php');
    exit();
}

include '../php/db.php'; // Connexion à la BDD

// Suppression ou mise à jour d'un utilisateur
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $userId = $_POST['user_id'] ?? 0;

    if ($action === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM utilisateurs WHERE id = :id");
        $stmt->execute(['id' => $userId]);
    } elseif ($action === 'update') {
        $newRole = $_POST['role'] ?? 'client';
        $newState = isset($_POST['etat']) ? true : false;

        $stmt = $pdo->prepare("UPDATE utilisateurs SET role = :role, etat = :etat WHERE id = :id");
        $stmt->execute(['role' => $newRole, 'etat' => $newState, 'id' => $userId]);
    }
}

// Récupération des utilisateurs
$users = [];
try {
    $stmt = $pdo->query("SELECT * FROM utilisateurs ORDER BY created_at DESC");
    $users = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Erreur lors de la récupération des utilisateurs : " . $e->getMessage());
}
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<main class="ml-60 mt-16 p-6">
    <h1 class="text-2xl font-bold mb-6">Gestion des Utilisateurs</h1>
    <table class="w-full bg-white shadow-lg rounded-lg">
        <thead class="bg-yellow-500 text-white">
            <tr>
                <th class="p-4">ID</th>
                <th class="p-4">Nom</th>
                <th class="p-4">Email</th>
                <th class="p-4">Rôle</th>
                <th class="p-4">État</th>
                <th class="p-4">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
            <tr class="border-b">
                <td class="p-4"><?= htmlspecialchars($user['id']) ?></td>
                <td class="p-4"><?= htmlspecialchars($user['nom']) ?></td>
                <td class="p-4"><?= htmlspecialchars($user['email']) ?></td>
                <td class="p-4"><?= htmlspecialchars($user['role']) ?></td>
                <td class="p-4"><?= $user['etat'] ? 'Actif' : 'Inactif' ?></td>
                <td class="p-4 flex space-x-2">
                    <!-- Formulaire de mise à jour -->
                    <form method="post" class="flex items-center space-x-2">
                        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                        <select name="role" class="p-1 border rounded">
                            <option value="client" <?= $user['role'] === 'client' ? 'selected' : '' ?>>Client</option>
                            <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                        </select>
                        <label>
                            <input type="checkbox" name="etat" <?= $user['etat'] ? 'checked' : '' ?>> Actif
                        </label>
                        <button type="submit" name="action" value="update" class="bg-blue-500 text-white px-3 py-1 rounded">
                            Modifier
                        </button>
                    </form>
                    <!-- Formulaire de suppression -->
                    <form method="post">
                        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                        <button type="submit" name="action" value="delete" class="bg-red-500 text-white px-3 py-1 rounded">
                            Supprimer
                        </button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>

<?php include '../includes/footer.php'; ?>
