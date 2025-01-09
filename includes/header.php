<?php
session_start();
?>
<header class="bg-white shadow-md fixed top-0 left-60 right-0 h-16 flex items-center px-6 z-10">
    <h1 class="text-xl font-bold text-yellow-700 flex-1">Galerie des Articles</h1>
    <nav class="flex items-center space-x-4">
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="/user.php" class="text-yellow-700">Mon Compte</a>
            <a href="/logout.php" class="text-red-500">Déconnexion</a>
        <?php else: ?>
            <a href="/login.php" class="bg-yellow-500 text-white px-3 py-1 rounded">Connexion / Inscription</a>
        <?php endif; ?>
    </nav>
</header>
