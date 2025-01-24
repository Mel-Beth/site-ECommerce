<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Commandes</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Gestion des Commandes</h1>

        <?php if (!empty($orders)): ?>
            <?php foreach ($orders as $order): ?>
                <div class="bg-white shadow-lg rounded-lg mb-6 p-4">
                    <h2 class="text-xl font-bold">Commande #<?= htmlspecialchars($order['id_commande']) ?></h2>
                    <p>Client : <?= htmlspecialchars($order['pseudo_membre']) ?></p>
                    <p>Statut : <?= $order['statut_preparation'] ? 'En préparation' : 'En attente' ?></p>
                    <p>Total : <?= number_format($order['montant_ttc'], 2) ?> €</p>
                    <p>Date : <?= htmlspecialchars($order['date_commande']) ?></p>

                    <div class="mt-4">
                        <form method="post" action="admin/orders/updateStatus">
                            <input type="hidden" name="orderId" value="<?= $order['id_commande'] ?>">
                            <select name="status" class="p-2 border rounded">
                                <option value="0" <?= $order['statut_preparation'] === 0 ? 'selected' : '' ?>>En attente</option>
                                <option value="1" <?= $order['statut_preparation'] === 1 ? 'selected' : '' ?>>En préparation</option>
                            </select>
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Mettre à jour</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-gray-500">Aucune commande à afficher.</p>
        <?php endif; ?>
    </div>
</body>
</html>