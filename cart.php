<?php
include 'includes/head.php';
include 'includes/header.php';

// Charger les traductions
$translations = include 'includes/translations.php';

// Définir la langue actuelle
$lang = $_SESSION['lang'] ?? 'fr';

// Charger les traductions pour la langue actuelle
$t = $translations[$lang];

// Initialiser le panier si nécessaire
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Supprimer un article du panier via le formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_product_id'])) {
    $removeProductId = intval($_POST['remove_product_id']);
    unset($_SESSION['cart'][$removeProductId]); // Retirer le produit du panier
    header('Location: cart.php'); // Rediriger pour rafraîchir le panier
    exit();
}

// Mettre à jour la quantité d'un article dans le panier
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_product_id'], $_POST['new_quantity'])) {
    $updateProductId = intval($_POST['update_product_id']);
    $newQuantity = intval($_POST['new_quantity']);
    
    // Si la quantité est valide (positive), on met à jour la quantité dans le panier
    if ($newQuantity > 0) {
        $_SESSION['cart'][$updateProductId] = $newQuantity;
    } else {
        // Si la quantité est invalidée (négative ou zéro), on supprime l'article
        unset($_SESSION['cart'][$updateProductId]);
    }
    header('Location: cart.php'); // Rediriger pour rafraîchir le panier
    exit();
}

// Récupérer les articles dans le panier
include 'php/db.php';
$cart_items = [];
$total_price = 0;

if (!empty($_SESSION['cart'])) {
    $ids = implode(',', array_keys($_SESSION['cart']));
    $stmt = $pdo->query("SELECT * FROM articles WHERE id IN ($ids)");
    $cart_items = $stmt->fetchAll();

    foreach ($cart_items as &$item) {
        $item['quantity'] = $_SESSION['cart'][$item['id']];
        $total_price += $item['price'] * $item['quantity'];
    }
}

include 'includes/sidebar.php'; // Barre latérale
?>

<main class="flex-1 p-6">
    <h1 class="text-3xl font-bold mb-6"><?= $t['my_cart'] ?></h1>

    <?php if (empty($cart_items)): ?>
        <p class="text-gray-500"><?= $t['empty_cart'] ?></p>
    <?php else: ?>
        <div class="space-y-4">
            <?php foreach ($cart_items as $item): ?>
                <div class="flex items-center justify-between border p-4 rounded-lg shadow-lg hover:shadow-xl transition-all">
                    <div class="flex items-center space-x-4">
                        <!-- Utilisation de 'image' au lieu de 'image_url' -->
                        <img src="<?= isset($item['image']) ? $item['image'] : 'default_image_url.jpg' ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="w-20 h-20 object-cover rounded-lg">
                        <div>
                            <h2 class="text-lg font-semibold"><?= htmlspecialchars($item['name']) ?></h2>
                            <p class="text-gray-700"><?= number_format($item['price'], 2) ?> €</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4 text-right">
                        <form action="cart.php" method="POST">
                            <input type="hidden" name="update_product_id" value="<?= $item['id'] ?>">
                            <input type="number" name="new_quantity" value="<?= $item['quantity'] ?>" min="1" class="w-16 text-center border p-1 rounded-md" onchange="this.form.submit()">
                        </form>
                        <p class="text-gray-800 font-bold"><?= number_format($item['price'] * $item['quantity'], 2) ?> €</p>
                        <form action="cart.php" method="POST" class="mt-2">
                            <input type="hidden" name="remove_product_id" value="<?= $item['id'] ?>">
                            <button type="submit" class="text-red-500 hover:text-red-700">
                                <i class="fas fa-trash-alt"></i> <?= $t['remove'] ?>
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="mt-6 flex justify-between items-center">
            <h2 class="text-xl font-bold"><?= $t['total'] ?> : <?= number_format($total_price, 2) ?> €</h2>
            <form action="checkout.php" method="post">
                <button type="submit" class="bg-green-500 text-white px-6 py-2 rounded hover:bg-green-600 transition-all">
                    <?= $t['checkout'] ?>
                </button>
            </form>
        </div>
    <?php endif; ?>
</main>

<?php include 'includes/footer.php'; ?>