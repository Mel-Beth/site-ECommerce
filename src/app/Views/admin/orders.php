<?php include('src/app/Views/includes/head.php'); ?>
<?php include('src/app/Views/includes/header.php'); ?>
<?php include('src/app/Views/includes/sidebar.php'); ?>

<body class="bg-gray-100 h-screen overflow-hidden">

    <!-- Contenu principal -->
    <main class="ml-60 pt-16 p-6 h-screen overflow-auto">
        <div class="container mx-auto p-4">
            <h1 class="text-2xl font-bold mb-4">Gestion des Commandes</h1>

            <!-- Filtres -->
            <div class="mb-4">
                <form method="GET" action="admin/orders">
                    <select name="statut" class="p-2 border rounded">
                        <option value="">Tous les statuts</option>
                        <option value="0" <?= isset($_GET['statut']) && $_GET['statut'] === '0' ? 'selected' : '' ?>>En attente</option>
                        <option value="1" <?= isset($_GET['statut']) && $_GET['statut'] === '1' ? 'selected' : '' ?>>En préparation</option>
                    </select>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Filtrer</button>
                </form>
            </div>

            <?php if (!empty($orders)): ?>
                <table class="w-full bg-white shadow-lg rounded-lg">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="p-2">ID Commande</th>
                            <th class="p-2">Date Commande</th>
                            <th class="p-2">Statut</th>
                            <th class="p-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr class="border-b">
                                <td class="p-2"><?= htmlspecialchars($order['id_commande']) ?></td>
                                <td class="p-2"><?= htmlspecialchars($order['date_commande']) ?></td>
                                <td class="p-2"><?= $order['statut_preparation'] ? 'Préparation' : 'En attente' ?></td>
                                <td class="p-2">
                                    <!-- Bouton pour générer la facture -->
                                    <a href="orders/generateInvoice/<?= $order['id_commande'] ?>" class="bg-green-500 text-white px-4 py-2 rounded">Générer Facture</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-gray-500">Aucune commande à afficher.</p>
            <?php endif; ?>
        </div>
    </main>

<?php include('src/app/Views/includes/footer.php'); ?>