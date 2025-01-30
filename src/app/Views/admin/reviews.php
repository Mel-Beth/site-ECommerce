<?php include('src/app/Views/includes/head.php'); ?>
<?php include('src/app/Views/includes/header.php'); ?>
<?php include('src/app/Views/includes/sidebar.php'); ?>

<body class="bg-gray-100 h-screen overflow-hidden">

    <!-- Contenu principal -->
    <main class="ml-60 pt-16 p-6 h-screen overflow-auto">
        <div class="container mx-auto p-4">
            <h1 class="text-2xl font-bold mb-4">📝 Gestion des Avis</h1>

            <?php if (!empty($reviews)):?>
                <table class="w-full bg-white shadow-lg rounded-lg">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="p-2">ID</th>
                            <th class="p-2">Utilisateur</th>
                            <th class="p-2">Commentaire</th>
                            <th class="p-2">Statut</th>
                            <th class="p-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reviews as $review): ?>
                            <tr class="border-b">
                                <td class="p-2"><?= htmlspecialchars($review['id_avis']) ?></td>
                                <td class="p-2"><?= htmlspecialchars($review['pseudo_membre']) ?></td>
                                <td class="p-2"><?= htmlspecialchars($review['commentaire']) ?></td>
                                <td class="p-2">
                                    <?= $review['approuve'] ? '<span class="text-green-600 font-bold">✅ Approuvé</span>' : '<span class="text-red-600 font-bold">⏳ En attente</span>' ?>
                                </td>
                                <td class="p-2 flex space-x-2">
                                    <?php if (!$review['approuve']): ?>
                                        <form method="post" action="reviews/approve">
                                            <input type="hidden" name="reviewId" value="<?= $review['id_avis'] ?>">
                                            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">✔️ Approuver</button>
                                        </form>
                                    <?php endif; ?>
                                    
                                    <form method="post" action="reviews/delete">
                                        <input type="hidden" name="reviewId" value="<?= $review['id_avis'] ?>">
                                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded" onclick="return confirm('❌ Supprimer cet avis ?')">🗑 Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-gray-500">⚠️ Aucun avis à afficher.</p>
            <?php endif; ?>
        </div>
    </main>

<?php include('src/app/Views/includes/footer.php'); ?>
