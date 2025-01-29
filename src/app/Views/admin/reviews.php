<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard Admin</title>

    <!-- Tailwind CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" />
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<?php include('src/app/Views/includes/header.php'); ?>
<?php include('src/app/Views/includes/sidebar.php'); ?>

<body class="bg-gray-100 h-screen overflow-hidden">

    <!-- Contenu principal -->
    <main class="ml-60 pt-16 p-6 h-screen overflow-auto">
        <div class="container mx-auto p-4">
            <h1 class="text-2xl font-bold mb-4">Gestion des Avis</h1>

            <?php if (!empty($reviews)): ?>
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
                                <td class="p-2"><?= $review['approuve'] ? 'Approuvé' : 'En attente' ?></td>
                                <td class="p-2">
                                    <form method="post" action="admin/reviews/approve">
                                        <input type="hidden" name="reviewId" value="<?= $review['id_avis'] ?>">
                                        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Approuver</button>
                                    </form>
                                    <form method="post" action="admin/reviews/delete">
                                        <input type="hidden" name="reviewId" value="<?= $review['id_avis'] ?>">
                                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-gray-500">Aucun avis à afficher.</p>
            <?php endif; ?>
        </div>
    </main>
</body>

</html>