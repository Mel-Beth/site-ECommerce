<aside class="fixed inset-y-0 left-0 w-60 bg-gradient-to-b from-yellow-300 to-yellow-500 shadow-lg flex flex-col z-10">
    <div class="p-6 text-center text-white font-bold text-xl border-b">
        <i class="fas fa-store mr-2"></i> Vide Ton Porte-Monnaie
    </div>
    <nav class="flex-1 p-4 space-y-4">
        <!-- Lien vers l'accueil -->
        <a href="accueil" class="flex items-center space-x-3 text-white hover:text-yellow-200 p-3 rounded-lg">
            <i class="fas fa-home"></i> <span>Accueil</span>
        </a>
        <!-- Lien vers les articles -->
        <a href="articles" class="flex items-center space-x-3 text-white hover:text-yellow-200 p-3 rounded-lg">
            <i class="fas fa-box"></i> <span>Articles</span>
        </a>
        <!-- Lien vers le panier -->
        <a href="cart" class="flex items-center space-x-3 text-white hover:text-yellow-200 p-3 rounded-lg">
            <i class="fas fa-shopping-cart"></i> <span>Panier</span>
        </a>
        <!-- Lien vers les promotions -->
        <a href="promotions" class="flex items-center space-x-3 text-white hover:text-yellow-200 p-3 rounded-lg">
            <i class="fas fa-tag"></i> <span>Promotions</span>
        </a>
        <!-- Lien vers le profil utilisateur (si connecté) -->
        <?php if (isset($_SESSION['user'])): ?>
            <a href="user" class="flex items-center space-x-3 text-white hover:text-yellow-200 p-3 rounded-lg">
                <i class="fas fa-user"></i> <span>Mon compte</span>
            </a>
        <?php endif; ?>
    </nav>
</aside>