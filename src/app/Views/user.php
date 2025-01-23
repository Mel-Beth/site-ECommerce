<?php
// Inclure les fichiers nécessaires
require_once __DIR__ . '/includes/head.php';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';
?>

<main class="ml-60 mt-16 p-6">
    <h1 class="text-2xl font-bold mb-6">Mon Profil</h1>

    <?php if (isset($error)): ?>
        <p class="text-red-500 mb-4"><?= htmlspecialchars($error) ?></p>
    <?php elseif (isset($success)): ?>
        <p class="text-green-500 mb-4"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>

    <div class="bg-white p-6 rounded-lg shadow-md mb-6">
        <h2 class="text-xl font-bold mb-4">Mes informations</h2>
        <form method="post" action="">
            <label for="pseudo_membre" class="block text-gray-700">Pseudo :</label>
            <input type="text" id="pseudo_membre" name="pseudo_membre" class="w-full p-2 border rounded mb-4" value="<?= htmlspecialchars($user['pseudo_membre'] ?? '') ?>" required>

            <label for="email" class="block text-gray-700">Email :</label>
            <input type="email" id="email" name="email" class="w-full p-2 border rounded mb-4" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>

            <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Mettre à jour</button>
        </form>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-bold mb-4">Mes commandes</h2>
        <?php if (!empty($orders)): ?>
            <table class="w-full border-collapse border border-gray-200">
                <thead>
                    <tr>
                        <th class="border border-gray-200 p-2">ID Commande</th>
                        <th class="border border-gray-200 p-2">Date</th>
                        <th class="border border-gray-200 p-2">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td class="border border-gray-200 p-2 text-center"><?= htmlspecialchars($order['id_commande']) ?></td>
                            <td class="border border-gray-200 p-2 text-center"><?= htmlspecialchars($order['date_commande']) ?></td>
                            <td class="border border-gray-200 p-2 text-center"><?= number_format($order['montant_ttc'], 2) ?> €</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-gray-500">Aucune commande trouvée.</p>
        <?php endif; ?>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>