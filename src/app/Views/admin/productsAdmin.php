<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard Admin</title>

    <!-- Tailwind CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" />
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<?php include('src/app/Views/includes/header.php'); ?>
<?php include('src/app/Views/includes/sidebar.php'); ?>

<body class="bg-gray-100 h-screen overflow-hidden">

    <!-- Contenu principal -->
    <main class="ml-60 pt-16 p-6 h-screen overflow-auto">
        <div class="container mx-auto p-4">
            <h1 class="text-2xl font-bold mb-4">Gestion des Produits</h1>

            <!-- Formulaire d'ajout de produit -->
            <div class="mb-6">
                <a href="productsAdmin/add" class="bg-green-500 text-white px-4 py-2 rounded">Ajouter un Produit</a>
            </div>

            <?php if (!empty($products)): ?>
                <table class="w-full bg-white shadow-lg rounded-lg">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="p-2">ID</th>
                            <th class="p-2">Nom</th>
                            <th class="p-2">Prix</th>
                            <th class="p-2">Promotion</th>
                            <th class="p-2">Catégorie</th>
                            <th class="p-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                            <tr class="border-b">
                                <td class="p-2"><?= htmlspecialchars($product['id_article']) ?></td>
                                <td class="p-2"><?= htmlspecialchars($product['lib_article']) ?></td>
                                <td class="p-2"><?= number_format($product['prix'], 2) ?> €</td>
                                <td class="p-2"><?= htmlspecialchars($product['taux_promotion'] ?? 0) ?> %</td>
                                <td class="p-2"><?= htmlspecialchars($product['lib_categorie'] ?? 'Non spécifiée') ?></td>
                                <td class="p-2">
                                    <a href="productsAdmin/edit/<?= $product['id_article'] ?>" class="bg-blue-500 text-white px-4 py-2 rounded">Éditer</a>
                                    <a href="productsAdmin/delete/<?= $product['id_article'] ?>" class="bg-red-500 text-white px-4 py-2 rounded" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')">Supprimer</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-gray-500">Aucun produit à afficher.</p>
            <?php endif; ?>

        </div>
    </main>

</body>

</html>
