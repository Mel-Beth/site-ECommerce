<aside class="fixed inset-y-0 left-0 w-60 bg-gradient-to-b from-yellow-300 to-yellow-500 shadow-lg flex flex-col z-10">
    <div class="p-6 text-center text-white font-bold text-xl border-b">
        <i class="fas fa-store mr-2"></i>
        <?php if (isset($_SESSION['user']) && $_SESSION['user']['id_role'] == 1): ?>
            E-commerce Admin
        <?php else: ?>
            Vide Ton Porte-Monnaie
        <?php endif; ?>
    </div>

    <nav class="flex-1 p-4 space-y-4">
        <?php if (isset($_SESSION['user']) && $_SESSION['user']['id_role'] == 1): ?>
            <!-- Liens pour l'administration -->
            <a href="dashboard" class="flex items-center space-x-3 text-white hover:text-yellow-200 p-3 rounded-lg">
                <i class="fas fa-chart-line"></i> <span>Dashboard</span>
            </a>
            <a href="orders" class="flex items-center space-x-3 text-white hover:text-yellow-200 p-3 rounded-lg">
                <i class="fas fa-truck"></i> <span>Commandes</span>
            </a>
            <a href="products" class="flex items-center space-x-3 text-white hover:text-yellow-200 p-3 rounded-lg">
                <i class="fas fa-box"></i> <span>Produits</span>
            </a>
            <a href="reviews" class="flex items-center space-x-3 text-white hover:text-yellow-200 p-3 rounded-lg">
                <i class="fas fa-star"></i> <span>Avis Clients</span>
            </a>
            <a href="users" class="flex items-center space-x-3 text-white hover:text-yellow-200 p-3 rounded-lg">
                <i class="fas fa-users"></i> <span>Utilisateurs</span>
            </a>
            <a href="stats" class="flex items-center space-x-3 text-white hover:text-yellow-200 p-3 rounded-lg">
                <i class="fas fa-chart-bar"></i> <span>Statistiques</span>
            </a>
            <a href="settings" class="flex items-center space-x-3 text-white hover:text-yellow-200 p-3 rounded-lg">
                <i class="fas fa-cogs"></i> <span>Paramètres</span>
            </a>
        <?php else: ?>
            <!-- Liens pour les utilisateurs ou visiteurs -->
            <a href="accueil" class="flex items-center space-x-3 text-white hover:text-yellow-200 p-3 rounded-lg">
                <i class="fas fa-home"></i> <span>Accueil</span>
            </a>
            <a href="products" class="flex items-center space-x-3 text-white hover:text-yellow-200 p-3 rounded-lg">
                <i class="fas fa-box"></i> <span>Articles</span>
            </a>
            <a href="cart" class="flex items-center space-x-3 text-white hover:text-yellow-200 p-3 rounded-lg">
                <i class="fas fa-shopping-cart"></i> <span>Panier</span>
            </a>
            <a href="promotions" class="flex items-center space-x-3 text-white hover:text-yellow-200 p-3 rounded-lg">
                <i class="fas fa-tag"></i> <span>Promotions</span>
            </a>
            <?php if (isset($_SESSION['user'])): ?>
                <a href="user" class="flex items-center space-x-3 text-white hover:text-yellow-200 p-3 rounded-lg">
                    <i class="fas fa-user"></i> <span>Mon compte</span>
                </a>
            <?php endif; ?>
        <?php endif; ?>
    </nav>
</aside>
