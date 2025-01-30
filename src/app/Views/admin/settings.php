<?php include('src/app/Views/includes/head.php'); ?>
<?php include('src/app/Views/includes/header.php'); ?>
<?php include('src/app/Views/includes/sidebar.php'); ?>

<body class="bg-gray-100 h-screen overflow-hidden">

    <main class="ml-60 pt-16 p-6 h-screen overflow-auto">
        <div class="container mx-auto p-4">
            <h1 class="text-2xl font-bold mb-4">⚙️ Paramètres du site</h1>

            <?php if (isset($success)): ?>
                <p class="text-green-600"><?= htmlspecialchars($success) ?></p>
            <?php elseif (isset($error)): ?>
                <p class="text-red-600"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>

            <!-- ⚙️ Formulaire des paramètres -->
            <form method="post" action="admin/settings/update">
                <div class="bg-white p-6 rounded shadow-md">
                    <label class="block mb-2">💰 Taux de TVA (%)</label>
                    <input type="number" name="tva" value="<?= htmlspecialchars($tva) ?>" class="w-full p-2 border rounded">

                    <label class="block mt-4 mb-2">🚚 Frais de livraison (€)</label>
                    <input type="number" name="livraison" value="<?= htmlspecialchars($livraison) ?>" class="w-full p-2 border rounded">

                    <label class="block mt-4 mb-2">🔧 Mode maintenance</label>
                    <input type="checkbox" name="maintenance" <?= $maintenance == '1' ? 'checked' : '' ?> class="mr-2">
                    Activer le mode maintenance

                    <button type="submit" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded">💾 Enregistrer</button>
                </div>
            </form>

            <!-- 🔒 Formulaire de changement de mot de passe -->
            <h2 class="text-xl font-bold mt-6">🔑 Modifier le mot de passe</h2>
            <form method="post" action="admin/settings/updatePassword">
                <div class="bg-white p-6 rounded shadow-md">
                    <label class="block mb-2">Mot de passe actuel</label>
                    <input type="password" name="current_password" class="w-full p-2 border rounded">

                    <label class="block mt-4 mb-2">Nouveau mot de passe</label>
                    <input type="password" name="new_password" class="w-full p-2 border rounded">

                    <label class="block mt-4 mb-2">Confirmer le nouveau mot de passe</label>
                    <input type="password" name="confirm_password" class="w-full p-2 border rounded">

                    <button type="submit" class="mt-4 bg-red-500 text-white px-4 py-2 rounded">🔄 Modifier</button>
                </div>
            </form>
        </div>
    </main>

<?php include('src/app/Views/includes/footer.php'); ?>
