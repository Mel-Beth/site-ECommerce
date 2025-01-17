<?php
include 'includes/init.php'; // Inclure le fichier d'initialisation

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Produit non trouvé !");
}

try {
    $stmt = $pdo->prepare("SELECT * FROM articles WHERE id = :id");
    $stmt->execute(['id' => $_GET['id']]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        die("Produit non trouvé !");
    }
} catch (PDOException $e) {
    die("Erreur lors de la récupération du produit : " . $e->getMessage());
}

include 'includes/head.php';
include 'includes/header.php';
include 'includes/sidebar.php';
?>



<main class="ml-60 p-6">
    <div class="flex flex-wrap">
        <div class="w-full md:w-1/2 p-4">
            <img src="<?= htmlspecialchars($product['image'] ?? 'assets/images/default.png') ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="w-full rounded-lg shadow-md">
        </div>

        <div class="w-full md:w-1/2 p-4">
            <h1 class="text-3xl font-bold"><?= htmlspecialchars($product['name']) ?></h1>
            <p class="text-gray-700 mt-4"><?= nl2br(htmlspecialchars($product['description'])) ?></p>
            <p class="text-2xl font-bold text-yellow-500 mt-4"><?= number_format($product['price'], 2) ?> €</p>
            <p class="text-gray-600 mt-2"><?= $t['stock_available'] ?>: <?= $product['stock'] ?></p>

            <form method="post" action="php/add_to_cart.php" class="mt-6">
                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                <label for="quantity" class="block text-gray-700"><?= $t['quantity'] ?>:</label>
                <input type="number" id="quantity" name="quantity" value="1" min="1" max="<?= $product['stock'] ?>" class="w-20 p-2 border rounded">
                <button type="submit" class="mt-4 bg-yellow-500 text-white px-6 py-2 rounded hover:bg-yellow-600"><?= $t['add_to_cart'] ?></button>
            </form>

        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>