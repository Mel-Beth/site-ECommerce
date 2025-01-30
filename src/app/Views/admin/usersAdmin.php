<?php include('src/app/Views/includes/head.php'); ?>
<?php include('src/app/Views/includes/header.php'); ?>
<?php include('src/app/Views/includes/sidebar.php'); ?>

<body class="bg-gray-100 h-screen overflow-hidden">

    <!-- Contenu principal -->
    <main class="ml-60 pt-16 p-6 h-screen overflow-auto">
        <div class="container mx-auto p-4">
            <h1 class="text-2xl font-bold mb-4">👥 Gestion des Utilisateurs</h1>

            <!-- 🔍 Barre de recherche -->
            <div class="flex justify-between mb-4">
                <input type="text" id="searchUser" placeholder="🔍 Rechercher un utilisateur..." class="p-2 border rounded w-1/3">
                <select id="filterStatus" class="p-2 border rounded w-1/4">
                    <option value="">Tous les comptes</option>
                    <option value="active">✅ Actifs</option>
                    <option value="inactive">⏳ Inactifs</option>
                </select>
            </div>

            <?php if (!empty($users)): ?>
                <table class="w-full bg-white shadow-lg rounded-lg">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="p-2">ID</th>
                            <th class="p-2">Pseudo</th>
                            <th class="p-2">Email</th>
                            <th class="p-2">Rôle</th>
                            <th class="p-2">Statut</th>
                            <th class="p-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="userTable">
                        <?php foreach ($users as $user): ?>
                            <tr class="border-b">
                                <td class="p-2"><?= htmlspecialchars($user['id_membre']) ?></td>
                                <td class="p-2"><?= htmlspecialchars($user['pseudo_membre']) ?></td>
                                <td class="p-2"><?= htmlspecialchars($user['email']) ?></td>
                                <td class="p-2"><?= htmlspecialchars($user['lib_role']) ?></td>
                                <td class="p-2">
                                    <?= isset($user['is_active']) && $user['is_active'] ? '<span class="text-green-600 font-bold">✅ Actif</span>' : '<span class="text-red-600 font-bold">⏳ Inactif</span>' ?>
                                </td>
                                <td class="p-2 flex space-x-2">
                                    <!-- ✅ Modifier le rôle -->
                                    <form method="post" action="admin/usersAdmin/updateRole">
                                        <input type="hidden" name="userId" value="<?= $user['id_membre'] ?>">
                                        <select name="role" class="p-2 border rounded">
                                            <option value="1" <?= $user['id_role'] === 1 ? 'selected' : '' ?>>Admin</option>
                                            <option value="2" <?= $user['id_role'] === 2 ? 'selected' : '' ?>>Utilisateur</option>
                                        </select>
                                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">🔄 Mettre à jour</button>
                                    </form>

                                    <!-- 🛑 Désactiver un compte -->
                                    <?php if ($user['is_active']): ?>
                                        <form method="post" action="admin/usersAdmin/deactivate">
                                            <input type="hidden" name="userId" value="<?= $user['id_membre'] ?>">
                                            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">🚫 Désactiver</button>
                                        </form>
                                    <?php else: ?>
                                        <form method="post" action="admin/usersAdmin/activate">
                                            <input type="hidden" name="userId" value="<?= $user['id_membre'] ?>">
                                            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">✅ Activer</button>
                                        </form>
                                    <?php endif; ?>

                                    <!-- 📦 Voir les commandes de l'utilisateur -->
                                    <a href="admin/users/orders/<?= $user['id_membre'] ?>" class="bg-green-500 text-white px-4 py-2 rounded">📦 Voir Commandes</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-gray-500">⚠️ Aucun utilisateur à afficher.</p>
            <?php endif; ?>
        </div>
    </main>

    <script>
        // 🔍 Recherche Utilisateur
        document.getElementById('searchUser').addEventListener('input', function() {
            let search = this.value.toLowerCase();
            document.querySelectorAll("#userTable tr").forEach(row => {
                row.style.display = row.innerText.toLowerCase().includes(search) ? "" : "none";
            });
        });

        // 📌 Filtrage des comptes actifs/inactifs
        document.getElementById('filterStatus').addEventListener('change', function() {
            let status = this.value;

            document.querySelectorAll("#userTable tr").forEach(row => {
                let isActive = row.cells[4]?.innerText.includes("✅");

                if (status === "active" && !isActive) {
                    row.style.display = "none";
                } else if (status === "inactive" && isActive) {
                    row.style.display = "none";
                } else {
                    row.style.display = "";
                }
            });
        });
    </script>

    <?php include('src/app/Views/includes/footer.php'); ?>