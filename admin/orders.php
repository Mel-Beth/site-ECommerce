<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: /index.php');
    exit();
}

include '../php/db.php'; // Connexion à la BDD

// Mise à jour du statut de commande
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $commandeId = $_POST['commande_id'] ?? 0;
    $action = $_POST['action'] ?? '';

    if (in_array($action, ['approved', 'rejected'])) {
        $stmt = $pdo->prepare("UPDATE commandes SET status = :status, updated_at = CURRENT_DATE WHERE id = :id");
        $stmt->execute(['status' => ucfirst($action), 'id' => $commandeId]);
    }
}

// Récupération des commandes
$commandes = [];
try {
    $stmt = $pdo->query("
        SELECT c.id, c.user_name, c.status, c.total_price, c.created_at, c.updated_at, cd.article_id, cd.quantity, a.name AS article_name
        FROM commandes c
        LEFT JOIN commande_details cd ON c.id = cd.commande_id
        LEFT JOIN articles a ON cd.article_id = a.id
        ORDER BY c.created_at DESC
    ");
    $commandes = $stmt->fetchAll(PDO::FETCH_GROUP | PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des commandes : " . $e->getMessage());
}
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<main class="ml-60 mt-16 p-6">
    <h1 class="text-2xl font-bold mb-6">Gestion des Commandes</h1>
    <?php if (!empty($commandes)): ?>
        <?php foreach ($commandes as $commandeId => $details): ?>
            <div class="bg-white shadow-lg rounded-lg mb-6">
                <div class="p-4 flex justify-between items-center bg-yellow-500 text-white rounded-t-lg">
                    <div>
                        <h2 class="font-bold">Commande #<?= htmlspecialchars($commandeId) ?></h2>
                        <p>Client : <?= htmlspecialchars($details[0]['user_name']) ?></p>
                        <p>Date : <?= htmlspecialchars($details[0]['created_at']) ?></p>
                    </div>
                    <div>
                        <p class="text-lg font-bold">Total : <?= number_format($details[0]['total_price'], 2) ?> €</p>
                        <p class="<?= $details[0]['status'] === 'Approved' ? 'text-green-500' : ($details[0]['status'] === 'Rejected' ? 'text-red-500' : 'text-yellow-200') ?>">
                            Statut : <?= htmlspecialchars($details[0]['status']) ?>
                        </p>
                    </div>
                </div>
                <div class="p-4">
                    <table class="w-full bg-white rounded-lg">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="p-2 text-left">Article</th>
                                <th class="p-2 text-left">Quantité</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($details as $item): ?>
                                <tr>
                                    <td class="p-2"><?= htmlspecialchars($item['article_name']) ?></td>
                                    <td class="p-2"><?= htmlspecialchars($item['quantity']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="p-4 flex space-x-4">
                    <form method="post">
                        <input type="hidden" name="commande_id" value="<?= $commandeId ?>">
                        <button type="submit" name="action" value="approved" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                            Approuver
                        </button>
                    </form>
                    <form method="post">
                        <input type="hidden" name="commande_id" value="<?= $commandeId ?>">
                        <button type="submit" name="action" value="rejected" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                            Rejeter
                        </button>
                    </form>
                    <!-- Bouton pour générer la facture -->
                    <a href="generate_invoice.php?id=<?= $commandeId ?>" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">
                        Générer la Facture
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="text-gray-500">Aucune commande à afficher.</p>
    <?php endif; ?>
</main>

<?php include '../includes/footer.php'; ?>
