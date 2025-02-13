<?php include('src/app/Views/includes/head.php'); ?>
<?php include('src/app/Views/includes/header.php'); ?>
<?php include('src/app/Views/includes/sidebar.php'); ?>

<main class="flex-1 p-6 ml-60 min-h-screen">
    <div class="max-w-sm mx-auto bg-white shadow-lg rounded-lg p-6">
        <h2 class="text-2xl font-bold mb-6 text-center">Connexion</h2>
        
        <!-- Affichage des erreurs de connexion -->
        <?php if (!empty($_SESSION['login_error'])): ?>
            <p class="text-red-500 text-sm mb-4"><?= htmlspecialchars($_SESSION['login_error']) ?></p>
            <?php unset($_SESSION['login_error']); ?>
        <?php endif; ?>
        
        <!-- Affichage des erreurs de connexion admin -->
        <?php if (!empty($_SESSION['error'])): ?>
            <p class="text-red-500 text-sm mb-4"><?= htmlspecialchars($_SESSION['error']) ?></p>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
        
        <!-- Formulaire de connexion -->
        <form method="POST" action="login">
            <!-- Champ email -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="email">Email</label>
                <input type="email" id="email" name="email" required class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-yellow-500">
            </div>
            
            <!-- Champ mot de passe -->
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-yellow-500">
            </div>
            
            <!-- Bouton de connexion -->
            <div class="flex items-center justify-between">
                <button type="submit" class="w-full bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                    Se connecter
                </button>
            </div>
        </form>

        <!-- Lien pour réinitialiser le mot de passe -->
        <div class="mt-4 text-center">
            <a href="forgot-password" class="text-yellow-500 hover:underline">Mot de passe oublié ?</a>
        </div>

        <!-- Connexion sociale -->
        <div class="mt-6">
            <p class="text-center text-gray-700 mb-4">Ou connectez-vous avec</p>
            <div class="flex justify-center space-x-4">
                <a href="login/google" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                    <i class="fab fa-google"></i> Google
                </a>
                <a href="login/facebook" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    <i class="fab fa-facebook"></i> Facebook
                </a>
            </div>
        </div>
    </div>
</main>

<?php include('src/app/Views/includes/footer.php'); ?>
<script src="cart.js"></script>