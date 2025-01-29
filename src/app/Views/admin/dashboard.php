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
    <main class="ml-60 pt-16 p-6 h-screen overflow-auto">
        <h2 class="text-2xl font-bold mb-6">Statistiques</h2>

        <!-- Alerte pour les stocks bas -->
        <?php if (!empty($lowStockProducts)): ?>
            <div class="bg-red-500 text-white p-4 rounded-lg mb-6">
                <h3 class="text-lg font-bold">Alerte Stock Bas</h3>
                <ul>
                    <?php foreach ($lowStockProducts as $product): ?>
                        <li><?= htmlspecialchars($product['lib_article']) ?> (<?= $product['quantite_stock'] ?> en stock)</li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>


        <!-- Chiffres clés -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <!-- Total des commandes -->
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h3 class="text-xl font-semibold">Total des commandes</h3>
                <p class="text-2xl font-bold"><?= $totalOrders ?> Commandes</p>
            </div>

            <!-- Revenu total -->
            <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                <h3 class="text-xl font-semibold text-gray-700">Revenu total</h3>
                <p class="text-3xl font-bold text-green-600"><?= number_format($totalRevenue, 2) ?> €</p>
            </div>

            <!-- Commandes en attente -->
            <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                <h3 class="text-xl font-semibold text-gray-700">Commandes en attente</h3>
                <p class="text-3xl font-bold text-red-600"><?= $pendingOrdersCount ?></p>
            </div>

            <!-- Total des articles en stock -->
            <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                <h3 class="text-xl font-semibold text-gray-700">Total des articles en stock</h3>
                <p class="text-3xl font-bold text-blue-600"><?= $totalStock ?> articles</p>
            </div>

            <!-- Revenu moyen par commande -->
            <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                <h3 class="text-xl font-semibold text-gray-700">Revenu moyen par commande</h3>
                <p class="text-3xl font-bold text-purple-600"><?= number_format($avgRevenuePerOrder, 2) ?> €</p>
            </div>

            <!-- Nouveaux utilisateurs inscrits -->
            <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                <h3 class="text-xl font-semibold text-gray-700">Nouveaux utilisateurs inscrits</h3>
                <p class="text-3xl font-bold text-orange-600"><?= $newUsersCount ?> utilisateurs</p>
            </div>
        </div>

        <!-- Top 5 des produits les plus vendus -->
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h3 class="text-xl font-semibold">Top 5 des produits les plus vendus</h3>
            <ul>
                <?php foreach ($topSellingProducts as $product): ?>
                    <li><?= htmlspecialchars($product['lib_article']) ?> - <?= $product['total_sold'] ?> unités vendues</li>
                <?php endforeach; ?>
            </ul>
        </div>


        <!-- Autres graphiques -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mt-6">
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <canvas id="userTrafficChart"></canvas>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <canvas id="ordersChart"></canvas>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <canvas id="categoryChart"></canvas>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </main>

    <script>
        const userTrafficCtx = document.getElementById('userTrafficChart').getContext('2d');
        const ordersCtx = document.getElementById('ordersChart').getContext('2d');
        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');

        // Graphique du trafic utilisateur
        <?php if (!empty($dailyOrdersStats)): ?>
            new Chart(userTrafficCtx, {
                type: 'line',
                data: {
                    labels: <?= json_encode(array_column($dailyOrdersStats, 'date')) ?>,
                    datasets: [{
                        label: 'Trafic Utilisateur',
                        data: <?= json_encode(array_column($dailyOrdersStats, 'commandes')) ?>,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        <?php else: ?>
            alert("Aucune donnée disponible pour le graphique du trafic.");
        <?php endif; ?>

        // Graphique des commandes
        <?php if (!empty($dailyOrdersStats)): ?>
            new Chart(ordersCtx, {
                type: 'bar',
                data: {
                    labels: <?= json_encode(array_column($dailyOrdersStats, 'date')) ?>,
                    datasets: [{
                        label: 'Commandes',
                        data: <?= json_encode(array_column($dailyOrdersStats, 'commandes')) ?>,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        <?php else: ?>
            alert("Aucune donnée disponible pour le graphique des commandes.");
        <?php endif; ?>

        // Graphique des catégories
        <?php if (!empty($categoryStats)): ?>
            new Chart(categoryCtx, {
                type: 'doughnut',
                data: {
                    labels: <?= json_encode(array_column($categoryStats, 'categorie')) ?>, // Assurez-vous que 'categorie' est une colonne valide
                    datasets: [{
                        label: 'Commandes par Catégorie',
                        data: <?= json_encode(array_column($categoryStats, 'count')) ?>, // Assurez-vous que 'count' est une colonne valide
                        backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0'],
                        hoverOffset: 4
                    }]
                }
            });
        <?php else: ?>
            alert("Aucune donnée disponible pour le graphique des catégories.");
        <?php endif; ?>


        // Graphique des revenus mensuels
        <?php if (!empty($monthlyRevenueStats)): ?>
            new Chart(revenueCtx, {
                type: 'line',
                data: {
                    labels: <?= json_encode(array_column($monthlyRevenueStats, 'mois')) ?>, // Mois pour l'axe des X
                    datasets: [{
                        label: 'Revenus Mensuels',
                        data: <?= json_encode(array_column($monthlyRevenueStats, 'revenus')) ?>, // Revenus pour l'axe des Y
                        backgroundColor: 'rgba(255, 206, 86, 0.2)',
                        borderColor: 'rgba(255, 206, 86, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        <?php else: ?>
            alert("Aucune donnée disponible pour le graphique des revenus mensuels.");
        <?php endif; ?>
    </script>
</body>

</html>