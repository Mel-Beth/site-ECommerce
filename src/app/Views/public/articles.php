<?php include('src/app/Views/includes/head.php'); ?>
<?php include('src/app/Views/includes/header.php'); ?>
<?php include('src/app/Views/includes/sidebar.php'); ?>

<main class="flex-1 p-6">
    <h2 class="text-2xl font-bold mb-6">Tous les articles</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <?php foreach ($articles as $article): ?>
            <div class="bg-white shadow-lg rounded-lg p-4">
                <img src="<?= htmlspecialchars('assets/images/' . ($article['image'] ?? 'default.png')) ?>" alt="<?= htmlspecialchars($article['lib_article']) ?>" class="w-full h-48 object-cover rounded-md mb-4">
                <h3 class="text-lg font-bold"><?= htmlspecialchars($article['lib_article']) ?></h3> <!-- Correction ici -->
                <p class="text-gray-700"><?= number_format($article['prix'], 2) ?> €</p> <!-- Correction ici -->
                <a href="product?id=<?= $article['id_article'] ?>" class="mt-4 inline-block bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600"> <!-- Correction ici -->
                    Voir les détails
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</main>

<?php include('src/app/Views/includes/footer.php'); ?>