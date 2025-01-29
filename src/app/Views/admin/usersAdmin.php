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
            <h1 class="text-2xl font-bold mb-4">Gestion des Utilisateurs</h1>

            <?php if (!empty($users)): ?>
                <table class="w-full bg-white shadow-lg rounded-lg">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="p-2">ID</th>
                            <th class="p-2">Pseudo</th>
                            <th class="p-2">Email</th>
                            <th class="p-2">Rôle</th>
                            <th class="p-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr class="border-b">
                                <td class="p-2"><?= htmlspecialchars($user['id_membre']) ?></td>
                                <td class="p-2"><?= htmlspecialchars($user['pseudo_membre']) ?></td>
                                <td class="p-2"><?= htmlspecialchars($user['email']) ?></td>
                                <td class="p-2"><?= htmlspecialchars($user['lib_role']) ?></td>
                                <td class="p-2">
                                    <form method="post" action="admin/usersAdmin/updateRole">
                                        <input type="hidden" name="userId" value="<?= $user['id_membre'] ?>">
                                        <select name="role" class="p-2 border rounded">
                                            <option value="1" <?= $user['id_role'] === 1 ? 'selected' : '' ?>>Admin</option>
                                            <option value="2" <?= $user['id_role'] === 2 ? 'selected' : '' ?>>Utilisateur</option>
                                        </select>
                                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Mettre à jour</button>
                                    </form>
                                    <a href="admin/users/orders/<?= $user['id_membre'] ?>" class="bg-green-500 text-white px-4 py-2 rounded">Voir les commandes</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-gray-500">Aucun utilisateur à afficher.</p>
            <?php endif; ?>
        </div>
    </main>
</body>

</html>
