<?php require_once __DIR__ . '/includes/head.php'; ?>
<?php require_once __DIR__ . '/includes/header.php'; ?>
<?php require_once __DIR__ . '/includes/sidebar.php'; ?>

<main class="flex-1 p-6">
    <?php if (isset($product)): ?>
        <div class="bg-white shadow-lg rounded-lg p-4">
            <!-- Affichage des erreurs de panier -->
            <?php if (!empty($_SESSION['cart_error'])): ?>
                <p class="text-red-500 text-sm mb-4"><?= htmlspecialchars($_SESSION['cart_error']) ?></p>
                <?php unset($_SESSION['cart_error']); ?>
            <?php endif; ?>

            <!-- Image du produit -->
            <img src="<?= htmlspecialchars('assets/images/' . ($product['image'] ?? 'default.png')) ?>" alt="<?= htmlspecialchars($product['lib_article']) ?>" class="w-full h-48 object-cover rounded-md mb-4"> <!-- Correction ici -->
            <!-- Nom du produit -->
            <h3 class="text-lg font-bold"><?= htmlspecialchars($product['lib_article']) ?></h3> <!-- Correction ici -->
            <!-- Description du produit -->
            <?php if (isset($product['description'])): ?>
                <p class="text-gray-700"><?= htmlspecialchars($product['description']) ?></p> <!-- Correction ici -->
            <?php else: ?>
                <p class="text-gray-700">Aucune description disponible.</p>
            <?php endif; ?>
            <!-- Prix du produit -->
            <p class="text-gray-700 font-bold text-xl"><?= number_format($product['prix'], 2) ?> €</p> <!-- Correction ici -->
            <!-- Bouton pour ajouter au panier -->
            <form method="POST" action="cart">
                <input type="hidden" name="product_id" value="<?= $product['id_article'] ?>"> <!-- Correction ici -->

                <!-- Champ pour la quantité -->
                <div class="mb-4">
                    <label for="quantity" class="block text-gray-700 text-sm font-bold mb-2">Quantité :</label>
                    <input type="number" id="quantity" name="quantity" value="1" min="1" max="<?= isset($product['quantite_stock']) ? $product['quantite_stock'] : 1 ?>" class="shadow appearance-none border rounded w-20 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"> <!-- Correction ici -->
                </div>

                <!-- Bouton pour ajouter au panier -->
                <button type="submit" class="mt-4 inline-block bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Ajouter au panier</button>
            </form>
        </div>
    <?php else: ?>
        <p>Produit non trouvé.</p>
    <?php endif; ?>
</main>

<?php require_once 'includes/footer.php'; ?>