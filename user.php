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

// Récupération des informations utilisateur depuis la session
if (isset($_SESSION['user_name']) && is_array($_SESSION['user_name'])) {
    $nom = $_SESSION['user_name']['nom'];
    $prenom = $_SESSION['user_name']['prenom'];  // Ajouter le prénom à la session
    $email = $_SESSION['user_name']['email'];
} else {
    // Si la session ne contient pas les données, on les récupère depuis la base de données
    try {
        // Assurez-vous d'utiliser la colonne 'user_id' et pas 'id' si c'est la colonne correcte
        $stmt = $pdo->prepare("SELECT nom, prenom, email FROM utilisateurs WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $user_id]);  // Remplacer 'user_id' par le bon identifiant
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            header('Location: logout.php');
            exit();
        }

        $nom = $user['nom'];
        $prenom = $user['prenom'];  // Récupérer le prénom de la base de données
        $email = $user['email'];
    } catch (PDOException $e) {
        $error = "Erreur lors de la récupération des données utilisateur. Détails: " . $e->getMessage();
    }
}


// Mise à jour des informations utilisateur
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'] ?? '';
    $prenom = $_POST['prenom'] ?? '';  // Prendre en compte le prénom
    $email = $_POST['email'] ?? '';

    if (!empty($nom) && !empty($prenom) && !empty($email)) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Format de l'email invalide.";
        } else {
            try {
                $stmt = $pdo->prepare("
                    UPDATE utilisateurs 
                    SET nom = :nom, prenom = :prenom, email = :email 
                    WHERE id = :user_id  // Assurez-vous d'utiliser le bon identifiant
                ");
                $stmt->execute([
                    'nom' => $nom,
                    'prenom' => $prenom,  // Mettre à jour le prénom
                    'email' => $email,
                    'user_id' => $user_id,
                ]);

                $success = "Informations mises à jour avec succès.";

                // Mettre à jour la session avec les nouvelles informations
                $_SESSION['user_name'] = [
                    'nom' => $nom,
                    'prenom' => $prenom,  // Ajouter le prénom à la session
                    'email' => $email
                ];
            } catch (PDOException $e) {
                $error = "Erreur lors de la mise à jour des informations.";
            }
        }
    } else {
        $error = "Tous les champs doivent être remplis.";
    }
}

// Récupérer les commandes de l'utilisateur connecté
try {
    $stmt = $pdo->prepare("
        SELECT c.id, c.created_at, c.total_price, c.status 
        FROM commandes c
        WHERE c.user_id = :user_id
        ORDER BY c.created_at DESC
    ");
    $stmt->execute(['user_id' => $user_id]);  // Remplacez $user_id par l'ID de l'utilisateur connecté
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Erreur lors de la récupération des commandes : " . $e->getMessage();
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
            <input type="text" id="nom" name="nom" class="w-full p-2 border rounded mb-4" value="<?= htmlspecialchars($nom ?? '') ?>" required>

            <label for="prenom" class="block text-gray-700">Prénom :</label>
            <input type="text" id="prenom" name="prenom" class="w-full p-2 border rounded mb-4" value="<?= htmlspecialchars($prenom ?? '') ?>" required>

            <label for="email" class="block text-gray-700">Email :</label>
            <input type="email" id="email" name="email" class="w-full p-2 border rounded mb-4" value="<?= htmlspecialchars($email ?? '') ?>" required>

            <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Mettre à jour</button>
        </form>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-bold mb-4">Mes commandes</h2>
        <?php if (isset($orders) && is_array($orders) && count($orders) > 0): ?>
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
                            <td class="border border-gray-200 p-2 text-center"><?= htmlspecialchars($order['id']) ?></td>
                            <td class="border border-gray-200 p-2 text-center"><?= isset($order['created_at']) ? htmlspecialchars($order['created_at']) : 'Non spécifiée' ?></td>
                            <td class="border border-gray-200 p-2 text-center"><?= isset($order['nb_articles']) ? htmlspecialchars($order['nb_articles']) : '0' ?></td>
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
