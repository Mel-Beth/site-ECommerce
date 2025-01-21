<?php
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
    $stmt = $pdo->query("SELECT * FROM utilisateurs ORDER BY created_at ASC");
    $users = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Erreur lors de la récupération des utilisateurs : " . $e->getMessage());
}

// Charger les traductions
$translations = include '../includes/translations.php';

// Définir la langue actuelle
$lang = $_SESSION['lang'] ?? 'fr';

// Charger les traductions pour la langue actuelle
$t = $translations[$lang];

?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard E-commerce</title>
    <link rel="stylesheet" href="../assets/css/style.css">
  <!-- Intégration de Tailwind CSS pour le style et FontAwesome pour les icônes -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css"/>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
  <!-- Chart.js pour les graphiques -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100 h-screen">
  <!-- Fond gris clair, hauteur plein écran et désactivation du défilement horizontal -->

  <!-- Barre latérale -->
  <aside class="fixed inset-y-0 left-0 w-60 bg-gradient-to-b from-yellow-300 to-yellow-500 shadow-lg flex flex-col z-10">
    <!-- Position fixe, hauteur étendue sur l'écran vertical, largeur de 60 unités -->
    <!-- Fond en dégradé du jaune clair au jaune foncé, ombre légère, disposition verticale -->
    <div class="p-6 text-center text-white font-bold text-xl border-b">
      <!-- Remplissage intérieur, centrage du texte, blanc, gras et taille XL -->
      <i class="fas fa-store mr-2"></i>E-commerce
      <!-- Icône avec espace à droite -->
    </div>

    <!-- Menu -->
    <nav class="flex-1 p-4 space-y-4">
      <!-- Flex vertical prenant tout l'espace, rembourrage intérieur et espace vertical entre éléments -->
      <a href="dashboard.php" class="flex items-center space-x-3 text-white hover:text-yellow-200 p-3 rounded-lg">
        <!-- Icône et texte alignés horizontalement, espace entre eux -->
        <!-- Texte blanc avec effet au survol, rembourrage et coins arrondis -->
        <i class="fas fa-chart-line"></i> <span>Tableau de Bord</span>
      </a>
      <a href="products.php" class="flex items-center space-x-3 text-white hover:text-yellow-200 p-3 rounded-lg">
        <i class="fas fa-box"></i> <span>Articles</span>
      </a>
      <a href="orders.php" class="flex items-center space-x-3 text-white hover:text-yellow-200 p-3 rounded-lg">
        <i class="fas fa-truck"></i> <span>Commandes</span>
      </a>
      <a href="users.php" class="flex items-center space-x-3 text-white hover:text-yellow-200 p-3 rounded-lg">
        <i class="fas fa-user"></i> <span>Utilisateurs</span>
      </a>
    </nav>
  </aside>

  <!-- En-tête fixée en haut -->
  <header class="fixed top-0 left-60 right-0 bg-white shadow-md h-16 flex items-center px-6 z-10">
    <!-- Position fixe, largeur définie, fond blanc, ombre légère -->
    <!-- Hauteur définie à 16 unités, alignement horizontal des éléments -->
    <h1 class="text-xl font-bold text-yellow-700 flex-1">Dashboard E-commerce</h1>
    <!-- Texte jaune foncé, taille XL, gras, prend tout l'espace restant -->
    <a href="../logout.php" class="text-yellow-500 hover:underline"><?= $t['logout'] ?></a>
  </header>

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

</body>
</html>