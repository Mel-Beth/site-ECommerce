<?php include('src/app/Views/includes/head.php'); ?>
<?php include('src/app/Views/includes/header.php'); ?>
<?php include('src/app/Views/includes/sidebar.php'); ?>

<main class="flex-1 p-6 ml-60 min-h-screen">
    <h2 class="text-2xl font-bold mb-6">Votre panier</h2>
    <?php if (empty($products)): ?>
        <p>Votre panier est vide.</p>
    <?php else: ?>
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Section des produits -->
            <div class="lg:w-2/3">
                <div class="bg-white shadow-md rounded-lg p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold">Produits</h3>
                        <button class="text-blue-500 hover:underline">Désélectionner tous les éléments</button>
                    </div>

                    <?php foreach ($products as $index => $product): ?>
                        <div class="border-b border-gray-200 pb-4 mb-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <img src="assets/images/default.png" alt="<?= htmlspecialchars($product['lib_article'] ?? '') ?>" class="w-20 h-20 object-cover rounded-lg mr-4">
                                    <div>
                                        <h4 class="text-lg font-semibold"><?= htmlspecialchars($product['lib_article'] ?? '') ?></h4>
                                        <p class="text-gray-600">En stock</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-bold"><?= number_format($product['prix'] ?? 0, 2) ?> €</p>
                                    <div class="flex items-center space-x-2 mt-2">
                                        <input
                                            type="number"
                                            id="quantity-<?= $index ?>"
                                            value="<?= htmlspecialchars($product['quantity'] ?? 1) ?>"
                                            min="1"
                                            class="w-16 p-2 border rounded quantity-input"
                                            data-product-id="<?= $product['id_article'] ?>">

                                        <form method="POST" action="cart/removeFromCart" class="remove-form" data-product-id="<?= $product['id_article'] ?>">
                                            <input type="hidden" name="remove_product_id" value="<?= $product['id_article'] ?? '' ?>">
                                            <button type="submit" class="text-red-500 hover:underline">Supprimer</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Section du résumé de la commande -->
            <div class="lg:w-1/3">
                <div class="bg-white shadow-md rounded-lg p-6">
                    <h3 class="text-lg font-bold mb-4">Résumé de la commande</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between">
                            <p>Sous-total (<?= count($products) ?> article<?= count($products) > 1 ? 's' : '' ?>)</p>
                            <p class="font-bold"><?= number_format(array_reduce($products, function ($total, $product) {
                                                        return $total + ($product['prix'] * ($product['quantity'] ?? 1));
                                                    }, 0), 2) ?> €</p>
                        </div>
                        <div class="flex justify-between">
                            <p>Livraison</p>
                            <p class="font-bold"><?= number_format($shippingCost, 2) ?> €</p>
                        </div>
                        <div class="flex justify-between">
                            <p>Coupon de réduction</p>
                            <p class="font-bold">-<?= number_format($discount, 2) ?> €</p>
                        </div>
                        <div class="flex justify-between">
                            <p>Total</p>
                            <p class="font-bold"><?= number_format(array_reduce($products, function ($total, $product) {
                                                        return $total + ($product['prix'] * ($product['quantity'] ?? 1));
                                                    }, 0) + $shippingCost - $discount, 2) ?> €</p>
                        </div>
                    </div>
                    <form method="POST" action="cart/applyCoupon" class="mt-4">
                        <input type="text" name="coupon" placeholder="Code promo" class="w-full p-2 border rounded">
                        <button type="submit" class="w-full bg-yellow-500 text-white py-2 rounded-lg mt-2 hover:bg-yellow-600">Appliquer le coupon</button>
                    </form>
                    <button class="w-full bg-yellow-500 text-white py-2 rounded-lg mt-6 hover:bg-yellow-600">Passer la commande</button>
                </div>
            </div>
        </div>
    <?php endif; ?>
</main>

<?php include('src/app/Views/includes/footer.php'); ?>