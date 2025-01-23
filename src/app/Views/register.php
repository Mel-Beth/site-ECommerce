<?php require_once 'includes/head.php'; ?>
<?php require_once 'includes/header.php'; ?>
<?php require_once 'includes/sidebar.php'; ?>


<main class="flex items-center justify-center min-h-screen bg-gray-100">
    <form method="POST" action="register.php" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <h2 class="text-2xl font-bold mb-6 text-center">Créer un compte</h2>
        <!-- Champ pseudo -->
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="pseudo_membre">Pseudo</label>
            <input type="text" id="pseudo_membre" name="pseudo_membre" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
        <!-- Champ email -->
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="email">Email</label>
            <input type="email" id="email" name="email" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
        <!-- Champ mot de passe -->
        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="password">Mot de passe</label>
            <input type="password" id="password" name="password" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline">
        </div>
        <!-- Bouton d'inscription -->
        <div class="flex items-center justify-between">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">S'inscrire</button>
        </div>
    </form>
</main>

<?php require_once 'includes/footer.php'; ?>