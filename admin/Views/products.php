<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Produits</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
</head>

<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Gestion des Produits</h1>

        <?php if (!empty($products)): ?>
            <table class="w-full bg-white shadow-lg rounded-lg">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="p-2">ID</th>
                        <th class="p-2">Nom</th>
                        <th class="p-2">Prix</th>
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
                            <td class="p-2"><?= htmlspecialchars($product['lib_categorie']) ?></td>
                            <td class="p-2">
                                <a href="/admin/products/edit/<?= $product['id_article'] ?>" class="bg-blue-500 text-white px-4 py-2 rounded">Éditer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-gray-500">Aucun produit à afficher.</p>
        <?php endif; ?>
    </div>
</body>

</html>