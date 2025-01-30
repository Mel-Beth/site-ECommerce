<?php include('src/app/Views/includes/head.php'); ?>
<?php include('src/app/Views/includes/header.php'); ?>
<?php include('src/app/Views/includes/sidebar.php'); ?>

<body class="bg-gray-100 h-screen overflow-hidden">
    <main class="ml-60 pt-16 p-6 h-screen overflow-auto">
        <h2 class="text-2xl font-bold mb-6">📦 Gestion des Produits</h2>

        <!-- 🔍 Barre de recherche et filtres -->
        <div class="flex justify-between mb-4">
            <input type="text" id="searchProduct" placeholder="Rechercher un produit..." class="p-2 border rounded w-1/3">

            <!-- 🔍 Filtre Catégories -->
            <select id="filterCategory" class="p-2 border rounded w-1/4">
                <option value="">Toutes les catégories</option>
                <?php
                $categoriesUnique = []; // Tableau temporaire pour éviter les doublons
                foreach ($categories as $category):
                    if (!in_array($category['lib_categorie'], $categoriesUnique)) {
                        $categoriesUnique[] = $category['lib_categorie']; ?>
                        <option value="<?= htmlspecialchars($category['lib_categorie']) ?>">
                            <?= htmlspecialchars($category['lib_categorie']) ?>
                        </option>
                <?php }
                endforeach; ?>
            </select>

            <!-- 🔍 Filtre Sous-Catégories -->
            <select id="filterSubCategory" class="p-2 border rounded w-1/4">
                <option value="">Toutes les sous-catégories</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= htmlspecialchars($category['lib_sous_categorie'] ?? '') ?>">
                        <?= htmlspecialchars($category['lib_sous_categorie'] ?? 'Sans sous-catégorie') ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button id="exportProducts" class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow-lg hover:bg-blue-700">
                📂 Exporter Produits
            </button>
        </div>

        <!-- 🆕 Bouton Ajouter Produit -->
        <div class="mb-4">
            <a href="add" class="bg-green-600 text-white px-4 py-2 rounded-lg shadow-lg hover:bg-green-700">
                ➕ Ajouter un produit
            </a>
        </div>
        
        <!-- 🏷️ Liste des produits -->
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="p-3 text-left">Nom</th>
                        <th class="p-3 text-left">Prix</th>
                        <th class="p-3 text-left">Stock</th>
                        <th class="p-3 text-left">Catégorie</th>
                        <th class="p-3 text-left">Sous-catégorie</th> <!-- 🆕 Ajouté -->
                        <th class="p-3 text-left">Actions</th>
                    </tr>
                </thead>

                <tbody id="productTable">
                    <?php foreach ($products as $product): ?>
                        <tr class="border-b hover:bg-gray-100">
                            <td class="p-3"><?= htmlspecialchars($product['lib_article'] ?? 'Nom inconnu') ?></td>
                            <td class="p-3"><?= number_format($product['prix'] ?? 0, 2) ?> €</td>
                            <td class="p-3"><?= $product['quantite_stock'] ?? 'Indisponible' ?></td>
                            <td class="p-3"><?= htmlspecialchars($product['lib_categorie'] ?? 'Sans catégorie') ?></td>
                            <td class="p-3"><?= htmlspecialchars($product['lib_sous_categorie'] ?? 'Sans sous-catégorie') ?></td> <!-- 🆕 Ajouté -->
                            <td class="p-3 flex space-x-2">
                                <a href="products/edit/<?= $product['id_article'] ?>" class="text-yellow-500 hover:underline">✏️ Modifier</a>
                                <a href="products/delete/<?= $product['id_article'] ?>" class="text-red-500 hover:underline" onclick="return confirm('Supprimer ce produit ?')">🗑 Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>

            </table>
        </div>
    </main>

    <script>
        // 🔍 Recherche Produits
        document.getElementById('searchProduct').addEventListener('input', function() {
            let search = this.value.toLowerCase();
            document.querySelectorAll("#productTable tr").forEach(row => {
                row.style.display = row.innerText.toLowerCase().includes(search) ? "" : "none";
            });
        });

        // 📂 Export Produits en CSV
        document.getElementById('exportProducts').addEventListener('click', function() {
            let csvContent = "data:text/csv;charset=utf-8,Nom,Prix,Stock,Catégorie\n";
            document.querySelectorAll("#productTable tr").forEach(row => {
                let cols = row.querySelectorAll("td");
                if (cols.length > 0) {
                    csvContent += Array.from(cols).map(td => td.innerText).join(",") + "\n";
                }
            });
            let link = document.createElement("a");
            link.setAttribute("href", encodeURI(csvContent));
            link.setAttribute("download", "produits.csv");
            document.body.appendChild(link);
            link.click();
        });

        // 🎯 Filtrage par Catégorie
        document.getElementById('filterCategory').addEventListener('change', function() {
            let category = this.value.toLowerCase();
            let subCategory = document.getElementById('filterSubCategory').value.toLowerCase();

            document.querySelectorAll("#productTable tr").forEach(row => {
                let cat = row.cells[3]?.innerText.toLowerCase(); // Catégorie
                let subCat = row.cells[4]?.innerText.toLowerCase(); // Sous-Catégorie

                let matchCategory = (!category || cat.includes(category));
                let matchSubCategory = (!subCategory || subCat.includes(subCategory));

                row.style.display = (matchCategory && matchSubCategory) ? "" : "none";
            });
        });

        document.getElementById('filterSubCategory').addEventListener('change', function() {
            let category = document.getElementById('filterCategory').value.toLowerCase();
            let subCategory = this.value.toLowerCase();

            document.querySelectorAll("#productTable tr").forEach(row => {
                let cat = row.cells[3]?.innerText.toLowerCase(); // Catégorie
                let subCat = row.cells[4]?.innerText.toLowerCase(); // Sous-Catégorie

                let matchCategory = (!category || cat.includes(category));
                let matchSubCategory = (!subCategory || subCat.includes(subCategory));

                row.style.display = (matchCategory && matchSubCategory) ? "" : "none";
            });
        });
    </script>

    <?php include('src/app/Views/includes/footer.php'); ?>
