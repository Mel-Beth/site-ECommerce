<?php
include 'includes/init.php';

// Récupérer les éventuelles erreurs de connexion
$error = $_SESSION['login_error'] ?? '';
unset($_SESSION['login_error']); // Supprimer l'erreur après l'affichage

include 'includes/head.php';
include 'includes/header.php';
include 'includes/sidebar.php';
?>

<main class="flex items-center justify-center h-screen bg-gray-100">
    <div class="bg-white p-8 rounded-lg shadow-lg w-96">
        <h1 class="text-2xl font-bold mb-4"><?= $t['login_title'] ?></h1>
        <?php if ($error): ?>
            <p class="text-red-500 mb-4"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <form method="post" action="">
            <label for="email" class="block text-gray-700"><?= $t['email'] ?>:</label>
            <input type="email" id="email" name="email" class="w-full p-2 border rounded mb-4" required>
            <label for="password" class="block text-gray-700"><?= $t['password'] ?>:</label>
            <input type="password" id="password" name="password" class="w-full p-2 border rounded mb-4" required>
            <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 w-full"><?= $t['login_button'] ?></button>
        </form>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
