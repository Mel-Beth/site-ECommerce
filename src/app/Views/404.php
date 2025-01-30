<?php include('src/app/Views/includes/head.php'); ?>
<?php include('src/app/Views/includes/header.php'); ?>
<?php include('src/app/Views/includes/sidebar.php'); ?>

<main class="flex-1 p-6 ml-60 min-h-screen">
    <div class="max-w-sm mx-auto bg-white shadow-lg rounded-lg p-6 text-center">
        <h2 class="text-2xl font-bold mb-6">Erreur 404</h2>
        <p class="text-gray-700 mb-4">La page que vous cherchez n’existe pas.</p>
        <p class="text-gray-700">
            Vous pouvez <a href="accueil" class="text-yellow-500 hover:underline">retourner à l'accueil</a> 
            ou <a href="contact" class="text-yellow-500 hover:underline">contacter</a> l'administrateur du site si vous pensez qu'il s'agit d'une erreur.
        </p>
    </div>
</main>

<?php include('src/app/Views/includes/footer.php'); ?>
<script src="cart.js"></script>