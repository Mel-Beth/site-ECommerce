<?php include('src/app/Views/includes/head.php'); ?>
<?php include('src/app/Views/includes/header.php'); ?>
<?php include('src/app/Views/includes/sidebar.php'); ?>

<main class="flex-1 p-6 ml-60 min-h-screen">
    <h2 class="text-2xl font-bold mb-6">Votre panier</h2>

    <!-- Affichage dynamique via JavaScript -->
    <div id="cart-empty" class="text-center text-gray-600 text-lg">
        <p>Votre panier est vide.</p>
    </div>
    
    <div id="cart-container" class="hidden">
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Section des produits -->
            <div class="lg:w-2/3">
                <div class="bg-white shadow-md rounded-lg p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold">Produits</h3>
                        <button id="clear-cart" class="text-blue-500 hover:underline">Vider le panier</button>
                    </div>
                    
                    <!-- Liste des produits du panier -->
                    <div id="cart-items"></div>
                </div>
            </div>

            <!-- Section du résumé de la commande -->
            <div class="lg:w-1/3">
                <div class="bg-white shadow-md rounded-lg p-6">
                    <h3 class="text-lg font-bold mb-4">Résumé de la commande</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between">
                            <p>Sous-total (<span id="cart-count">0</span> article<span id="cart-count-s">s</span>)</p>
                            <p class="font-bold" id="cart-subtotal">0.00 €</p>
                        </div>
                        <div class="flex justify-between">
                            <p>Livraison</p>
                            <p class="font-bold" id="shipping-cost">0.00 €</p>
                        </div>
                        <div class="flex justify-between">
                            <p>Coupon de réduction</p>
                            <p class="font-bold text-red-500" id="discount">-0.00 €</p>
                        </div>
                        <div class="flex justify-between text-xl font-bold">
                            <p>Total</p>
                            <p id="cart-total">0.00 €</p>
                        </div>
                    </div>

                    <form method="POST" action="cart/applyCoupon" class="mt-4">
                        <input type="text" name="coupon" placeholder="Code promo" class="w-full p-2 border rounded">
                        <button type="submit" class="w-full bg-yellow-500 text-white py-2 rounded-lg mt-2 hover:bg-yellow-600">
                            Appliquer le coupon
                        </button>
                    </form>

                    <form action="/payment" method="POST" id="checkout-form" class="hidden">
                        <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-lg mt-6 hover:bg-blue-600">
                            Payer avec Stripe 💳
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include('src/app/Views/includes/footer.php'); ?>

<script src="cart.js"></script>
