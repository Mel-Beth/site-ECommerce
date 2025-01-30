<?php
$modeleParent = new \Models\ModeleParent();
$pdo = $modeleParent->getPdo();
?>

<header class="bg-gray-800 text-white shadow-md fixed top-0 left-60 right-0 h-16 flex items-center px-6 z-10">
    <div class="flex items-center space-x-4 flex-1">
        <h1 class="text-xl font-bold text-yellow-500">
            <?php if (isset($_SESSION['user']) && $_SESSION['user']['id_role'] === 1): ?>
                Dashboard Admin
            <?php else: ?>
                Bienvenue sur Vide Ton Porte-Monnaie
            <?php endif; ?>
        </h1>
    </div>

    <!-- Barre de recherche uniquement pour les utilisateurs non admins -->
    <?php if (!isset($_SESSION['user']) || $_SESSION['user']['id_role'] !== 1): ?>
        <div class="flex items-center justify-center flex-1">
            <form action="search" method="GET" class="flex items-center bg-gray-200 rounded-full px-4 py-2 w-80">
                <input type="text" name="q" placeholder="Rechercher un produit..." class="flex-1 bg-transparent outline-none text-gray-700">
                <button type="submit" class="text-yellow-500">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
    <?php endif; ?>

    <nav class="flex items-center space-x-6">
        <!-- Utilisateur connecté -->
        <?php if (isset($_SESSION['user'])): ?>
            <div class="flex items-center space-x-2">
                <a href="user" class="text-yellow-500 hover:underline">
                    <span>Bonjour, <?= htmlspecialchars($_SESSION['user']['pseudo_membre'] ?? 'Utilisateur') ?> !</span>
                </a>
            </div>

            <!-- Déconnexion pour Admin -->
            <?php if ($_SESSION['user']['id_role'] === 1): ?>
                <a href="../logout" class="text-yellow-500 hover:underline">Déconnexion</a>
            <?php else: ?>
                <a href="logout" class="text-yellow-500 hover:underline">Déconnexion</a>
            <?php endif; ?>

        <?php else: ?>
            <!-- Connexion/Inscription -->
            <div class="relative group">
                <a href="login" class="flex items-center space-x-1 hover:underline">
                    <span>Se connecter</span>
                    <i class="fas fa-caret-down"></i>
                </a>
                <div class="dropdown-menu hidden group-hover:block">
                    <a href="login" class="block px-4 py-2 hover:bg-gray-100">Connexion</a>
                    <a href="register" class="block px-4 py-2 hover:bg-gray-100">Inscription</a>
                </div>
            </div>
        <?php endif; ?>

        <!-- Panier (uniquement pour les utilisateurs non admins) -->
        <?php if (!isset($_SESSION['user']) || (isset($_SESSION['user']) && $_SESSION['user']['id_role'] !== 1)): ?>
            <div class="relative group">
                <a href="cart" class="flex items-center space-x-2">
                    <i class="fas fa-shopping-cart text-yellow-500"></i>
                    <span>Panier</span>
                    <span id="cart-count-header" class="bg-orange-500 text-white text-xs font-bold px-2 py-0.5 rounded-full ml-1">
                        0
                    </span>

                </a>
                <div class="dropdown-menu hidden group-hover:block text-black">
                    <p id="header-cart-empty" class="text-gray-500">Votre panier est vide.</p>
                    <ul id="header-cart-items" class="space-y-2"></ul>
                    <a href="cart" class="block text-center mt-4 text-yellow-500 hover:underline">Voir le panier</a>
                </div>
            </div>
        <?php endif; ?>
    </nav>
</header>