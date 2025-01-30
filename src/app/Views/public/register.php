<?php include('src/app/Views/includes/head.php'); ?>
<?php include('src/app/Views/includes/header.php'); ?>
<?php include('src/app/Views/includes/sidebar.php'); ?>

<main class="flex-1 p-6 ml-60 min-h-screen">
    <div class="max-w-sm mx-auto bg-white shadow-lg rounded-lg p-6">
        <h2 class="text-2xl font-bold mb-6 text-center">Créer un compte</h2>

        <!-- Formulaire d'inscription -->
        <form method="POST" action="register">
            <!-- Champ pseudo -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="pseudo_membre">Pseudo</label>
                <input type="text" id="pseudo_membre" name="pseudo_membre" required class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-yellow-500">
            </div>

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

            <!-- Champ adresse (facultatif) -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="adresse">Adresse</label>
                <input type="text" id="adresse" name="adresse" class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-yellow-500">
            </div>

            <!-- Bouton d'inscription -->
            <div class="flex items-center justify-between">
                <button type="submit" class="w-full bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                    S'inscrire
                </button>
            </div>
        </form>

        <!-- Lien vers la page de connexion -->
        <div class="mt-4 text-center">
            <p>Déjà un compte ? <a href="register" class="text-yellow-500 hover:underline">Connectez-vous</a></p>
        </div>
    </div>
</main>

<?php include('src/app/Views/includes/footer.php'); ?>