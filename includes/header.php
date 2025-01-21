<?php
include 'init.php'; // Inclure le fichier d'initialisation

?>


<header class="bg-gray-800 text-white shadow-md fixed top-0 left-60 right-0 h-16 flex items-center px-6 z-10">
    <div class="flex items-center space-x-4 flex-1">
        <h1 class="text-xl font-bold text-yellow-500"><?= $t['welcome'] ?></h1>
    </div>

    <div class="flex items-center justify-center flex-1">
        <form action="search.php" method="GET" class="flex items-center bg-gray-200 rounded-full px-4 py-2 w-80">
            <input type="text" name="q" placeholder="<?= $t['search_placeholder'] ?>" class="flex-1 bg-transparent outline-none text-gray-700">
            <button type="submit" class="text-yellow-500">
                <i class="fas fa-search"></i>
            </button>
        </form>
    </div>

    <nav class="flex items-center space-x-6">
        <!-- Changer de langue -->
        <div class="relative group">
            <a href="#"><?= $t['language'] ?></a>
            <div class="dropdown-menu hidden group-hover:block">
                <a href="?lang=fr"><?= $t['french'] ?></a>
                <a href="?lang=en"><?= $t['english'] ?></a>
            </div>
        </div>

        <!-- Utilisateur connecté -->
        <?php if (isset($_SESSION['user'])): ?>
            <div class="flex items-center space-x-2">
                <a href="user.php" class="text-yellow-500 hover:underline">
                    <span>Bonjour, <?= htmlspecialchars($userPrenom) ?> !</span>
                </a>
            </div>
            <?php if ($_SESSION['user']['role'] == 'admin'): ?>
                <a href="admin/dashboard.php" class="text-yellow-500 hover:underline"><?= $t['admin_dashboard'] ?></a>
            <?php endif; ?>
            <a href="logout.php" class="text-yellow-500 hover:underline"><?= $t['logout'] ?></a>
        <?php else: ?>
            <!-- Connexion/Inscription -->
            <div class="relative group">
                <a href="#" class="flex items-center space-x-1 hover:underline">
                    <span><?= $t['identify'] ?></span>
                    <i class="fas fa-caret-down"></i>
                </a>
                <div class="dropdown-menu hidden group-hover:block">
                    <a href="login.php" class="block px-4 py-2 hover:bg-gray-100"><?= $t['connect'] ?></a>
                    <a href="register.php" class="block px-4 py-2 hover:bg-gray-100"><?= $t['register'] ?></a>
                </div>
            </div>
        <?php endif; ?>

        <!-- Panier -->
        <div class="relative group">
            <a href="#" class="flex items-center space-x-2">
                <i class="fas fa-shopping-cart text-yellow-500"></i>
                <span><?= $t['cart'] ?></span>
                <span class="bg-orange-500 text-white text-xs font-bold px-2 py-0.5 rounded-full ml-1">
                    <?= count($cartItems) ?>
                </span>
            </a>
            <div class="dropdown-menu hidden group-hover:block text-black">
                <?php if (empty($cartItems)): ?>
                    <p class="text-gray-500"><?= $t['empty_cart'] ?></p>
                <?php else: ?>
                    <ul class="space-y-2">
                        <?php
                        // Récupérer les détails des produits
                        include 'php/db.php';
                        $ids = implode(',', array_keys($cartItems));
                        $stmt = $pdo->query("SELECT id, name, price FROM articles WHERE id IN ($ids)");
                        $products = $stmt->fetchAll();

                        // Associer les quantités avec les produits
                        foreach ($products as $product):
                            $quantity = $cartItems[$product['id']];
                            $totalPrice = $product['price'] * $quantity;
                        ?>
                            <li class="flex justify-between items-center">
                                <span class="font-semibold"><?= htmlspecialchars($product['name']) ?></span>
                                <span><?= $quantity ?> x <?= number_format($product['price'], 2) ?> €</span>
                                <form action="cart.php" method="POST" class="ml-2">
                                    <input type="hidden" name="remove_product_id" value="<?= $product['id'] ?>">
                                    <button type="submit" class="text-red-500 hover:text-red-700">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <a href="cart.php" class="block text-center mt-4 text-yellow-500 hover:underline"><?= $t['view_cart'] ?></a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
</header>
