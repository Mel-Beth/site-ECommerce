<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un produit</title>
    <!-- Tailwind CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" />
</head>
<body class="bg-gray-100">

    <?php include('src/app/Views/includes/header.php'); ?>
    <?php include('src/app/Views/includes/sidebar.php'); ?>

    <div class="container mx-auto p-6">
        <h2 class="text-2xl font-bold mb-4">Ajouter un nouveau produit</h2>

        <form action="admin/products/add" method="POST" enctype="multipart/form-data">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="mb-4">
                    <label for="lib_article" class="block text-sm font-medium text-gray-700">Nom du produit</label>
                    <input type="text" id="lib_article" name="lib_article" class="mt-1 block w-full p-2 border rounded" required>
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea id="description" name="description" class="mt-1 block w-full p-2 border rounded" required></textarea>
                </div>

                <div class="mb-4">
                    <label for="prix" class="block text-sm font-medium text-gray-700">Prix</label>
                    <input type="number" id="prix" name="prix" class="mt-1 block w-full p-2 border rounded" required>
                </div>

                <div class="mb-4">
                    <label for="quantite_stock" class="block text-sm font-medium text-gray-700">Quantité en stock</label>
                    <input type="number" id="quantite_stock" name="quantite_stock" class="mt-1 block w-full p-2 border rounded" required>
                </div>

                <div class="mb-4">
                    <label for="id_categorie" class="block text-sm font-medium text-gray-700">Catégorie</label>
                    <select id="id_categorie" name="id_categorie" class="mt-1 block w-full p-2 border rounded" required>
                        <!-- Parcourez les catégories récupérées -->
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['id_categorie'] ?>"><?= htmlspecialchars($category['lib_categorie']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="id_sous_categorie" class="block text-sm font-medium text-gray-700">Sous-catégorie</label>
                    <select id="id_sous_categorie" name="id_sous_categorie" class="mt-1 block w-full p-2 border rounded" required>
                        <!-- Parcourez les sous-catégories récupérées -->
                        <?php foreach ($subcategories as $subcategory): ?>
                            <option value="<?= $subcategory['id_sous_categorie'] ?>"><?= htmlspecialchars($subcategory['lib_sous_categorie']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="image" class="block text-sm font-medium text-gray-700">Image du produit</label>
                    <input type="file" id="image" name="image" class="mt-1 block w-full p-2 border rounded" required>
                </div>

                <div class="flex justify-between">
                    <button type="submit" class="bg-blue-500 text-white p-2 rounded">Ajouter le produit</button>
                    <a href="admin/products" class="text-red-500 p-2">Annuler</a>
                </div>
            </div>
        </form>
    </div>
</body>
</html>
