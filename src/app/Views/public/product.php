<?php include('src/app/Views/includes/head.php'); ?>
<?php include('src/app/Views/includes/header.php'); ?>
<?php include('src/app/Views/includes/sidebar.php'); ?>

<main class="flex-1 p-6 ml-60 min-h-screen">
    <?php if (isset($product)): ?>
        <div class="bg-white shadow-lg rounded-lg p-4">
            <!-- Affichage des erreurs de panier -->
            <?php if (!empty($_SESSION['cart_error'])): ?>
                <p class="text-red-500 text-sm mb-4"><?= htmlspecialchars($_SESSION['cart_error']) ?></p>
                <?php unset($_SESSION['cart_error']); ?>
            <?php endif; ?>

            <!-- Image du produit -->
            <img src="<?= htmlspecialchars('assets/images/' . ($product['image'] ?? 'default.png')) ?>" alt="<?= htmlspecialchars($product['lib_article']) ?>" class="w-full h-48 object-cover rounded-md mb-4">
            <!-- Nom du produit -->
            <h3 class="text-lg font-bold"><?= htmlspecialchars($product['lib_article']) ?></h3>
            <!-- Description du produit -->
            <?php if (isset($product['description'])): ?>
                <p class="text-gray-700"><?= htmlspecialchars($product['description']) ?></p>
            <?php else: ?>
                <p class="text-gray-700">Aucune description disponible.</p>
            <?php endif; ?>
            <!-- Prix du produit -->
            <p class="text-gray-700 font-bold text-xl"><?= number_format($product['prix'], 2) ?> €</p>
            <!-- Bouton pour ajouter au panier -->
            <form method="POST" action="cart">
                <input type="hidden" name="product_id" value="<?= $product['id_article'] ?>">

                <!-- Champ pour la quantité -->
                <div class="mb-4">
                    <label for="quantity" class="block text-gray-700 text-sm font-bold mb-2">Quantité :</label>
                    <input type="number" id="quantity" name="quantity" value="1" min="1" max="<?= isset($product['quantite_stock']) ? $product['quantite_stock'] : 1 ?>" class="shadow appearance-none border rounded w-20 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>

                <!-- Bouton pour ajouter au panier -->
                <button class="add-to-cart bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600"
                    data-id="<?= $product['id_article']; ?>"
                    data-name="<?= htmlspecialchars($product['lib_article']); ?>"
                    data-price="<?= $product['prix']; ?>"
                    data-discount="<?= $product['remise'] ?? 0; ?>">
                    Ajouter au panier 🛒
                </button>

            </form>

            <!-- Avis des clients -->
            <div class="mt-6">
                <h4 class="text-xl font-bold mb-4">Avis des clients</h4>
                <?php if (!empty($reviews)): ?>
                    <?php foreach ($reviews as $review): ?>
                        <div class="border-b border-gray-200 pb-4 mb-4">
                            <p class="text-gray-700"><?= htmlspecialchars($review['commentaire']) ?></p>
                            <p class="text-gray-500 text-sm">Par <?= htmlspecialchars($review['pseudo_membre']) ?> le <?= htmlspecialchars($review['date_creation']) ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-gray-500">Aucun avis pour ce produit.</p>
                <?php endif; ?>
            </div>

            <!-- Produits similaires -->
            <div class="mt-6">
                <h4 class="text-xl font-bold mb-4">Produits similaires</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    <!-- ✅ Vérification avant la boucle -->
                    <?php if (!empty($similarProducts) && is_array($similarProducts)) : ?>
                        <h2>Produits similaires</h2>
                        <ul>
                            <?php foreach ($similarProducts as $product) : ?>
                                <li>
                                    <a href="/product?id=<?= htmlspecialchars($product['id_article']) ?>">
                                        <?= htmlspecialchars($product['lib_article']) ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else : ?>
                        <p>Aucun produit similaire trouvé.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php else: ?>
        <p>Produit non trouvé.</p>
    <?php endif; ?>
</main>

<?php include('src/app/Views/includes/footer.php'); ?>
<script src="cart.js"></script>