<?php
session_start();

// Vérification si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include 'php/db.php'; // Connexion à la base de données

$user_id = $_SESSION['user_id'];
$error = '';
$success = '';

// Récupération des informations utilisateur
try {
    $stmt = $pdo->prepare("SELECT nom, prenom, email FROM utilisateurs WHERE id = :id");
    $stmt->execute(['id' => $user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        header('Location: logout.php');
        exit();
    }
} catch (PDOException $e) {
    $error = "Erreur lors de la récupération des données utilisateur.";
}

// Mise à jour des informations utilisateur
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'] ?? '';
    $prenom = $_POST['prenom'] ?? '';
    $email = $_POST['email'] ?? '';

    if (!empty($nom) && !empty($prenom) && !empty($email)) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Format de l'email invalide.";
        } else {
            try {
                $stmt = $pdo->prepare("
                    UPDATE utilisateurs 
                    SET nom = :nom, prenom = :prenom, email = :email 
                    WHERE id = :id
                ");
                $stmt->execute([
                    'nom' => $nom,
                    'prenom' => $prenom,
                    'email' => $email,
                    'id' => $user_id,
                ]);

                $success = "Informations mises à jour avec succès.";
                $_SESSION['user_name'] = $nom;
            } catch (PDOException $e) {
                $error = "Erreur lors de la mise à jour des informations.";
            }
        }
    } else {
        $error = "Tous les champs doivent être remplis.";
    }
}

// Récupération des commandes de l'utilisateur
try {
    $stmt = $pdo->prepare("
        SELECT c.id, c.date_commande, c.total_price, COUNT(cd.id) as nb_articles 
        FROM commandes c
        LEFT JOIN commande_details cd ON c.id = cd.commande_id
        WHERE c.user_id = :user_id
        GROUP BY c.id
        ORDER BY c.date_commande DESC
    ");
    $stmt->execute(['user_id' => $user_id]);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Erreur lors de la récupération des commandes.";
}

include 'includes/head.php';
include 'includes/header.php';
include 'includes/sidebar.php';
?>

<main class="ml-60 mt-16 p-6">
    <h1 class="text-2xl font-bold mb-6">Mon Profil</h1>

    <?php if ($error): ?>
        <p class="text-red-500 mb-4"><?= htmlspecialchars($error) ?></p>
    <?php elseif ($success): ?>
        <p class="text-green-500 mb-4"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>

    <div class="bg-white p-6 rounded-lg shadow-md mb-6">
        <h2 class="text-xl font-bold mb-4">Mes informations</h2>
        <form method="post" action="">
            <label for="nom" class="block text-gray-700">Nom :</label>
            <input type="text" id="nom" name="nom" class="w-full p-2 border rounded mb-4" value="<?= htmlspecialchars($user['nom']) ?>" required>

            <label for="prenom" class="block text-gray-700">Prénom :</label>
            <input type="text" id="prenom" name="prenom" class="w-full p-2 border rounded mb-4" value="<?= htmlspecialchars($user['prenom']) ?>" required>

            <label for="email" class="block text-gray-700">Email :</label>
            <input type="email" id="email" name="email" class="w-full p-2 border rounded mb-4" value="<?= htmlspecialchars($user['email']) ?>" required>

            <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Mettre à jour</button>
        </form>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-bold mb-4">Mes commandes</h2>
        <?php if ($orders): ?>
            <table class="w-full border-collapse border border-gray-200">
                <thead>
                    <tr>
                        <th class="border border-gray-200 p-2">ID Commande</th>
                        <th class="border border-gray-200 p-2">Date</th>
                        <th class="border border-gray-200 p-2">Articles</th>
                        <th class="border border-gray-200 p-2">Total</th>
                        <th class="border border-gray-200 p-2">Détails</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td class="border border-gray-200 p-2 text-center"><?= $order['id'] ?></td>
                            <td class="border border-gray-200 p-2 text-center"><?= $order['date_commande'] ?></td>
                            <td class="border border-gray-200 p-2 text-center"><?= $order['nb_articles'] ?></td>
                            <td class="border border-gray-200 p-2 text-center"><?= number_format($order['total_price'], 2) ?> €</td>
                            <td class="border border-gray-200 p-2 text-center">
                                <a href="order_details.php?id=<?= $order['id'] ?>" class="text-blue-500 hover:underline">Voir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-gray-500">Aucune commande trouvée.</p>
        <?php endif; ?>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
