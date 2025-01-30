<?php include('src/app/Views/includes/head.php'); ?>
<?php include('src/app/Views/includes/header.php'); ?>
<?php include('src/app/Views/includes/sidebar.php'); ?>

<main class="ml-60 pt-16 p-6 h-screen overflow-auto">
    <h2 class="text-2xl font-bold mb-6">✏️ Modifier un Produit</h2>

    <!-- ✅ Message de confirmation -->
    <div id="successMessage" class="hidden bg-green-100 text-green-800 p-4 rounded-md mb-4"></div>

    <form id="editProductForm" enctype="multipart/form-data">
        <input type="hidden" name="id_article" value="<?= $product['id_article'] ?>">

        <!-- 📌 Nom du produit -->
        <label class="block text-sm font-bold">Nom du produit</label>
        <input type="text" name="lib_article" value="<?= htmlspecialchars($product['lib_article']) ?>" class="p-2 border rounded w-full mb-4">

        <!-- 🏷️ Catégorie -->
        <label class="block text-sm font-bold">Catégorie</label>
        <select name="id_categorie" class="p-2 border rounded w-full mb-4">
            <?php foreach ($categories as $category): ?>
                <option value="<?= $category['id_categorie'] ?>" <?= ($product['id_categorie'] == $category['id_categorie']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($category['lib_categorie']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <!-- 🏷️ Sous-catégorie -->
        <label class="block text-sm font-bold">Sous-catégorie</label>
        <select name="id_sous_categorie" class="p-2 border rounded w-full mb-4">
            <option value="">Aucune</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?= $category['id_sous_categorie'] ?>" <?= ($product['id_sous_categorie'] == $category['id_sous_categorie']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($category['lib_sous_categorie']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <!-- 💰 Prix -->
        <label class="block text-sm font-bold">Prix (€)</label>
        <input type="number" name="prix" step="0.01" value="<?= $product['prix'] ?>" class="p-2 border rounded w-full mb-4">

        <!-- 📦 Stock -->
        <label class="block text-sm font-bold">Stock</label>
        <input type="number" name="quantite_stock" value="<?= $product['quantite_stock'] ?>" class="p-2 border rounded w-full mb-4">

        <!-- 🖼 Images actuelles -->
        <div class="mb-4">
            <label class="block text-sm font-bold">Images actuelles</label>
            <div class="flex gap-2">
                <?php foreach ($images as $image): ?>
                    <div class="relative">
                        <img src="<?= $image['url_image'] ?>" class="w-24 h-24 object-cover border rounded">
                        <button type="button" class="absolute top-0 right-0 bg-red-500 text-white p-1 text-sm remove-image" data-image-id="<?= $image['id_image'] ?>">❌</button>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- 🆕 Ajout d’images -->
        <label class="block text-sm font-bold">Ajouter des images</label>
        <input type="file" name="images[]" multiple class="p-2 border rounded w-full mb-4">

        <!-- ✅ Bouton de mise à jour -->
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow-lg hover:bg-blue-700">
            ✅ Mettre à jour le produit
        </button>
    </form>
</main>

<script>
    document.getElementById("editProductForm").addEventListener("submit", function(e) {
        e.preventDefault();

        let formData = new FormData(this);
        let url = "http://localhost/projets/back/siteECommerce/admin/products/update"; // 🔍 Vérifie bien cette URL

        console.log("🔍 Envoi AJAX vers :", url);

        fetch(url, {
                method: "POST",
                body: formData
            })
            .then(response => response.text()) // 🆕 Change `.json()` en `.text()` pour voir la réponse brute
            .then(text => {
                console.log("🔍 Réponse brute :", text);
                return JSON.parse(text);
            })
            .then(data => {
                if (data.success) {
                    document.getElementById("successMessage").innerText = "✅ Produit mis à jour avec succès !";
                    document.getElementById("successMessage").classList.remove("hidden");
                } else {
                    console.error("Erreur serveur :", data.error);
                }
            })
            .catch(error => console.error("❌ Erreur AJAX :", error));
    });

    // ❌ Suppression d’une image existante
    document.querySelectorAll(".remove-image").forEach(button => {
        button.addEventListener("click", function() {
            let imageId = this.getAttribute("data-image-id");
            fetch("products/delete_image/" + imageId, {
                    method: "POST"
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.parentElement.remove();
                    }
                });
        });
    });
</script>

<?php include('src/app/Views/includes/footer.php'); ?>