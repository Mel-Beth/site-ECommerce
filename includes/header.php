<?php
session_start();

// Charger les traductions
$translations = include 'translations.php';

// Définir la langue par défaut
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'fr';
}

// Changer la langue si une nouvelle est sélectionnée
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// Définir la langue actuelle
$lang = $_SESSION['lang'];

// Charger les traductions pour la langue actuelle
$t = $translations[$lang];

// Récupérer les articles du panier depuis la session
$cartItems = $_SESSION['cart'] ?? [];

// Assurez-vous que $cartItems est un tableau
if (!is_array($cartItems)) {
    $cartItems = [];
}
?>

<header class="bg-gray-800 text-white shadow-md fixed top-0 left-60 right-0 h-16 flex items-center px-6 z-10">
    <!-- Section gauche -->
    <div class="flex items-center space-x-4 flex-1">
        <h1 class="text-xl font-bold text-yellow-500"><?= $t['welcome'] ?></h1>
    </div>

    <!-- Barre de recherche au centre -->
    <div class="flex items-center justify-center flex-1">
        <form action="search.php" method="GET" class="flex items-center bg-gray-200 rounded-full px-4 py-2 w-80">
            <input type="text" name="q" placeholder="<?= $t['search_placeholder'] ?>" class="flex-1 bg-transparent outline-none text-gray-700">
            <button type="submit" class="text-yellow-500">
                <i class="fas fa-search"></i>
            </button>
        </form>
    </div>

    <!-- Section droite -->
    <nav class="flex items-center space-x-6">
        <!-- Langue -->
        <div class="relative group">
            <div class="flex items-center cursor-pointer">
                <span class="fi <?= $_SESSION['lang'] === 'fr' ? 'fi-fr' : 'fi-gb' ?>"></span>
                <span class="ml-2"><?= $_SESSION['lang'] === 'fr' ? 'FR' : 'EN' ?></span>
                <i class="fas fa-caret-down ml-1"></i>
            </div>
            <div class="dropdown-menu hidden group-hover:block">
                <a href="?lang=fr" class="block px-4 py-2 hover:bg-gray-100"><?= $t['french'] ?></a>
                <a href="?lang=en" class="block px-4 py-2 hover:bg-gray-100"><?= $t['english'] ?></a>
            </div>
        </div>

        <!-- Compte et listes -->
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

        <!-- Panier -->
        <div class="relative group">
            <a href="cart.php" class="flex items-center space-x-2">
                <i class="fas fa-shopping-cart text-yellow-500"></i>
                <span><?= $t['cart'] ?></span>
                <span class="bg-orange-500 text-white text-xs font-bold px-2 py-0.5 rounded-full ml-1">
                    <?= count($cartItems) ?>
                </span>
            </a>
            <div class="dropdown-menu hidden group-hover:block">
                <?php if (count($cartItems) > 0): ?>
                    <ul class="space-y-2">
                        <?php foreach ($cartItems as $item): ?>
                            <li class="flex items-center space-x-2">
                                <img src="assets/images/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="w-10 h-10 object-cover rounded">
                                <div>
                                    <p class="font-bold text-sm"><?= htmlspecialchars($item['name']) ?></p>
                                    <p class="text-sm text-gray-500"><?= htmlspecialchars($item['quantity']) ?> x <?= number_format($item['price'], 2) ?> €</p>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <div class="mt-4">
                        <a href="cart.php" class="block px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600 text-center"><?= $t['view_cart'] ?></a>
                    </div>
                <?php else: ?>
                    <p class="text-gray-500"><?= $t['empty_cart'] ?></p>
                <?php endif; ?>
            </div>
        </div>
    </nav>
</header>
