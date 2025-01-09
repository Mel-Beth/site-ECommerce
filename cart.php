<?php
include 'includes/header.php';
session_start();

// Initialiser le panier si nécessaire
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Gestion des ajouts au panier
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'], $_POST['quantity'])) {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);

    // Vérifier si le produit est déjà dans le panier
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }
}

// Récupérer les articles dans le panier
include 'php/db.php';
$cart_items = [];
$total_price = 0;

if (!empty($_SESSION['cart'])) {
    $ids = implode(',', array_keys($_SESSION['cart']));
    $stmt = $pdo->query("SELECT * FROM articles WHERE id IN ($ids)");
    $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($cart_items as &$item) {
        $item['quantity'] = $_SESSION['cart'][$item['id']];
        $total_price += $item['price'] * $item['quantity'];
    }
}
?>

<main class="p-6">
    <h1 class="text-3xl font-bold mb-6">Mon Panier</h1>

    <?php if (empty($cart_items)): ?>
        <p class="text-gray-500">Votre panier est vide.</p>
    <?php else: ?>
        <div class="space-y-4">
            <?php foreach ($cart_items as $item): ?>
                <div class="flex items-center justify-between border p-4 rounded-lg">
                    <div>
                        <h2 class="text-lg font-bold"><?= htmlspecialchars($item['name']) ?></h2>
                        <p class="text-gray-700"><?= number_format($item['price'], 2) ?> € x <?= $item['quantity'] ?></p>
                    </div>
                    <div>
                        <p class="text-gray-800 font-bold"><?= number_format($item['price'] * $item['quantity'], 2) ?> €</p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="mt-6">
            <h2 class="text-xl font-bold">Total : <?= number_format($total_price, 2) ?> €</h2>
            <form action="checkout.php" method="post" class="mt-4">
                <button type="submit" class="bg-green-500 text-white px-6 py-2 rounded hover:bg-green-600">
                    Valider la commande
                </button>
            </form>
        </div>
    <?php endif; ?>
</main>

<?php include 'includes/footer.php'; ?>
