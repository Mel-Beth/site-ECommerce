<aside class="fixed inset-y-0 left-0 w-60 bg-gradient-to-b from-yellow-300 to-yellow-500 shadow-lg flex flex-col z-10">
    <div class="p-6 text-center text-white font-bold text-xl border-b">
        <i class="fas fa-store mr-2"></i> <?= $t['site_name'] ?>
    </div>
    <nav class="flex-1 p-4 space-y-4">
        <a href="index.php" class="flex items-center space-x-3 text-white hover:text-yellow-200 p-3 rounded-lg">
            <i class="fas fa-home"></i> <span><?= $t['home'] ?></span>
        </a>
        <a href="articles.php" class="flex items-center space-x-3 text-white hover:text-yellow-200 p-3 rounded-lg">
            <i class="fas fa-box"></i> <span><?= $t['articles'] ?></span>
        </a>
        <a href="cart.php" class="flex items-center space-x-3 text-white hover:text-yellow-200 p-3 rounded-lg">
            <i class="fas fa-shopping-cart"></i> <span><?= $t['cart'] ?></span>
        </a>
        <?php if (isset($_SESSION['user'])): // Vérification de l'existence d'un utilisateur connecté ?>
            <a href="user.php" class="flex items-center space-x-3 text-white hover:text-yellow-200 p-3 rounded-lg">
                <i class="fas fa-user"></i> <span><?= $t['my_account'] ?></span>
            </a>
        <?php endif; ?>
    </nav>
</aside>
