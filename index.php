<?php
include 'includes/head.php';
include 'includes/header.php'; 
include 'php/db.php'; // Connexion à la base de données

// Récupérer les produits depuis la base de données
try {
    $query = $pdo->query("SELECT * FROM articles");
    $articles = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des articles : " . $e->getMessage());
}

include 'includes/sidebar.php'; // Barre latérale
?>

<main class="ml-60 pt-16 p-6 h-screen overflow-auto">
    <h2 class="text-2xl font-bold mb-6">Articles Disponibles</h2>
    <div id="articles-container" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <?php foreach ($articles as $article): ?>
        <div class="bg-white shadow-lg rounded-lg p-4">
            <img src="<?= htmlspecialchars('/assets/images/' . ($article['image'] ?? 'default.png')) ?>" alt="<?= htmlspecialchars($article['name']) ?>" class="w-full h-48 object-cover rounded-md mb-4">
            <h3 class="text-lg font-bold"><?= htmlspecialchars($article['name']) ?></h3>
            <p class="text-gray-700"><?= number_format($article['price'], 2) ?> €</p>
            <a href="product.php?id=<?= $article['id'] ?>" class="mt-4 inline-block bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">
                Voir Détails
            </a>
        </div>
        <?php endforeach; ?>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
