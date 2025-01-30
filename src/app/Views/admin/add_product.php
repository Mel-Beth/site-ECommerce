<?php include('src/app/Views/includes/head.php'); ?>
<?php include('src/app/Views/includes/header.php'); ?>
<?php include('src/app/Views/includes/sidebar.php'); ?>

<body class="bg-gray-100 h-screen overflow-hidden">

    <!-- Contenu principal -->
    <main class="ml-60 pt-16 p-6 h-screen overflow-auto">
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
                        <input type="number" id="prix" name="prix" step="0.01" class="mt-1 block w-full p-2 border rounded" required>
                    </div>

                    <div class="mb-4">
                        <label for="quantite_stock" class="block text-sm font-medium text-gray-700">Quantité en stock</label>
                        <input type="number" id="quantite_stock" name="quantite_stock" class="mt-1 block w-full p-2 border rounded" required>
                    </div>

                    <div class="mb-4">
                        <label for="id_sous_categorie" class="block text-sm font-medium text-gray-700">Sous-catégorie</label>
                        <select id="id_sous_categorie" name="id_sous_categorie" class="mt-1 block w-full p-2 border rounded" required>
                            <option value="">Sélectionner une sous-catégorie</option>
                            <?php foreach ($categories as $category): ?>
                                <?php if (!empty($category['sous_categories'])): ?>
                                    <optgroup label="<?= htmlspecialchars($category['lib_categorie']) ?>">
                                        <?php foreach ($category['sous_categories'] as $subcategory): ?>
                                            <option value="<?= htmlspecialchars($subcategory['id_sous_categorie']) ?>">
                                                <?= htmlspecialchars($subcategory['lib_sous_categorie']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                <?php else: ?>
                                    <option value="" disabled><?= htmlspecialchars($category['lib_categorie']) ?> (Pas de sous-catégorie)</option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="image" class="block text-sm font-medium text-gray-700">Image du produit</label>
                        <input type="file" id="image" name="image" class="mt-1 block w-full p-2 border rounded" accept="image/*" required>
                        <img id="imagePreview" src="#" alt="Aperçu de l'image" style="display:none; width:100px; margin-top:10px;">
                    </div>

                    <div class="flex justify-between">
                        <button type="submit" class="bg-blue-500 text-white p-2 rounded">Ajouter le produit</button>
                        <a href="../products" class="text-red-500 p-2">Annuler</a>
                    </div>
                </div>
            </form>
        </div>
    </main>

    <?php include('src/app/Views/includes/footer.php'); ?>

    <script>
        document.getElementById("image").addEventListener("change", function(event) {
            const [file] = event.target.files;
            if (file) {
                document.getElementById("imagePreview").style.display = "block";
                document.getElementById("imagePreview").src = URL.createObjectURL(file);
            }
        });
    </script>
