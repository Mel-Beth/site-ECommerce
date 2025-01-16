<?php
include 'php/db.php'; // Connexion à la BDD

// Vérifier si une recherche a été effectuée
if (isset($_GET['q'])) {
    $search = '%' . $_GET['q'] . '%';

    try {
        $stmt = $pdo->prepare("SELECT * FROM articles WHERE name LIKE :search OR description LIKE :search");
        $stmt->execute(['search' => $search]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Erreur de recherche : " . $e->getMessage());
    }
} else {
    $results = [];
}

include 'includes/head.php'; 
include 'includes/header.php'; 
include 'includes/sidebar.php'; 

// Charger les traductions
$translations = include 'includes/translations.php';

// Définir la langue actuelle
$lang = $_SESSION['lang'] ?? 'fr';

// Charger les traductions pour la langue actuelle
$t = $translations[$lang];
?>

<main class="ml-60 pt-16 p-6">
    <h1 class="text-3xl font-bold mb-6"><?= $t['search_results'] ?></h1>
    <?php if (empty($results)): ?>
        <p class="text-gray-500"><?= $t['no_results'] ?></p>
    <?php else: ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php foreach ($results as $article): ?>
                <div class="bg-white shadow-lg rounded-lg p-4">
                    <img src="<?= htmlspecialchars('assets/images/' . ($article['image'] ?? 'default.png')) ?>" alt="<?= htmlspecialchars($article['name']) ?>" class="w-full h-48 object-cover rounded-md mb-4">
                    <h3 class="text-lg font-bold"><?= htmlspecialchars($article['name']) ?></h3>
                    <p class="text-gray-700"><?= number_format($article['price'], 2) ?> €</p>
                    <a href="product.php?id=<?= $article['id'] ?>" class="mt-4 inline-block bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">
                        <?= $t['view_details'] ?>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<?php include 'includes/footer.php'; ?>
