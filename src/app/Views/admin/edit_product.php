<?php include('src/app/Views/includes/head.php'); ?>
<?php include('src/app/Views/includes/header.php'); ?>
<?php include('src/app/Views/includes/sidebar.php'); ?>

<body class="bg-gray-100 h-screen overflow-hidden">

    <!-- Contenu principal -->
    <main class="ml-60 pt-16 p-6 h-screen overflow-auto">
        <div class="container mx-auto p-6">
            <h2 class="text-2xl font-bold mb-4">Modifier le produit</h2>

            <form action="admin/products/edit/<?= $product['id_article'] ?>" method="POST" enctype="multipart/form-data">
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="mb-4">
                        <label for="lib_article" class="block text-sm font-medium text-gray-700">Nom du produit</label>
                        <input type="text" id="lib_article" name="lib_article" value="<?= htmlspecialchars($product['lib_article']) ?>" class="mt-1 block w-full p-2 border rounded" required>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea id="description" name="description" class="mt-1 block w-full p-2 border rounded" required><?= htmlspecialchars($product['description']) ?></textarea>
                    </div>

                    <div class="mb-4">
                        <label for="prix" class="block text-sm font-medium text-gray-700">Prix</label>
                        <input type="number" id="prix" name="prix" value="<?= $product['prix'] ?>" class="mt-1 block w-full p-2 border rounded" required>
                    </div>

                    <div class="mb-4">
                        <label for="quantite_stock" class="block text-sm font-medium text-gray-700">Quantité en stock</label>
                        <input type="number" id="quantite_stock" name="quantite_stock" value="<?= $product['quantite_stock'] ?>" class="mt-1 block w-full p-2 border rounded" required>
                    </div>

                    <div class="mb-4">
                        <label for="id_sous_categorie" class="block text-sm font-medium text-gray-700">Sous-catégorie</label>
                        <select id="id_sous_categorie" name="id_sous_categorie" class="mt-1 block w-full p-2 border rounded" required>
                            <!-- Vérifiez si des sous-catégories existent pour chaque catégorie -->
                            <?php foreach ($categories as $category): ?>
                                <?php if (!empty($category['sous_categories'])): ?>
                                    <optgroup label="<?= htmlspecialchars($category['lib_categorie']) ?>">
                                        <?php foreach ($category['sous_categories'] as $subcategory): ?>
                                            <option value="<?= $subcategory['id_sous_categorie'] ?>"><?= htmlspecialchars($subcategory['lib_sous_categorie']) ?></option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="image" class="block text-sm font-medium text-gray-700">Image du produit</label>
                        <input type="file" id="image" name="image" class="mt-1 block w-full p-2 border rounded">
                        <p class="mt-2 text-sm text-gray-500">Laissez vide si vous ne voulez pas changer l'image.</p>
                    </div>

                    <div class="flex justify-between">
                        <button type="submit" class="bg-blue-500 text-white p-2 rounded">Enregistrer les modifications</button>
                        <a href="admin/products" class="text-red-500 p-2">Annuler</a>
                    </div>
                </div>
            </form>
        </div>
    </main>

<?php include('src/app/Views/includes/footer.php'); ?>