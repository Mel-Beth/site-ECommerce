<?php include('src/app/Views/includes/head.php'); ?>
<?php include('src/app/Views/includes/header.php'); ?>
<?php include('src/app/Views/includes/sidebar.php'); ?>

<main class="flex-1 p-6">
    <h2 class="text-2xl font-bold mb-6">Résultats de recherche</h2>
    <?php if (empty($searchResults)): ?>
        <p>Aucun résultat trouvé pour votre recherche.</p>
    <?php else: ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php foreach ($searchResults as $result): ?>
                <div class="bg-white shadow-lg rounded-lg p-4">
                    <!-- Image du produit -->
                    <img src="<?= htmlspecialchars('assets/images/' . ($result['image'] ?? 'default.png')) ?>" alt="<?= htmlspecialchars($result['lib_article']) ?>" class="w-full h-48 object-cover rounded-md mb-4"> <!-- Correction ici -->
                    <!-- Nom du produit -->
                    <h3 class="text-lg font-bold"><?= htmlspecialchars($result['lib_article']) ?></h3> <!-- Correction ici -->
                    <!-- Prix du produit -->
                    <p class="text-gray-700"><?= number_format($result['prix'], 2) ?> €</p> <!-- Correction ici -->
                    <!-- Lien vers les détails du produit -->
                    <a href="product.php?id=<?= $result['id_article'] ?>" class="mt-4 inline-block bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Voir les détails</a> <!-- Correction ici -->
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<?php include('src/app/Views/includes/footer.php'); ?>