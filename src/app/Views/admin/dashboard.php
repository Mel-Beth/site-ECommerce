<?php include('src/app/Views/includes/head.php'); ?>
<?php include('src/app/Views/includes/header.php'); ?>
<?php include('src/app/Views/includes/sidebar.php'); ?>

<body class="bg-gray-100 h-screen overflow-hidden">
    <main class="ml-60 pt-16 p-6 h-screen overflow-auto">
        <h2 class="text-2xl font-bold mb-6">Statistiques</h2>

        <!-- 🔴 Alerte pour les stocks bas -->
        <?php if (!empty($lowStockProducts)): ?>
            <div class="bg-red-500 text-white p-4 rounded-lg mb-6">
                <h3 class="text-lg font-bold">⚠️ Alerte Stock Bas</h3>
                <ul>
                    <?php foreach ($lowStockProducts as $product): ?>
                        <li><?= htmlspecialchars($product['lib_article']) ?> (<?= $product['quantite_stock'] ?> en stock)</li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- 🏆 Chiffres clés -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <?php
            $stats = [
                ["Total des commandes", $totalOrders, "commandes", "text-blue-600"],
                ["Revenu total", number_format($totalRevenue, 2) . " €", "", "text-green-600"],
                ["Commandes en attente", $pendingOrdersCount, "", "text-red-600"],
                ["Total des articles en stock", $totalStock, "articles", "text-blue-600"],
                ["Revenu moyen par commande", number_format($avgRevenuePerOrder, 2) . " €", "", "text-purple-600"],
                ["Nouveaux utilisateurs inscrits", $newUsersCount, "utilisateurs", "text-orange-600"]
            ];
            foreach ($stats as $stat) :
            ?>
                <div class="bg-white p-6 rounded-lg shadow-lg text-center relative">
                    <h3 class="text-xl font-semibold text-gray-700"><?= $stat[0] ?></h3>
                    <p class="text-3xl font-bold <?= $stat[3] ?>">
                        <?= $stat[1] . " " . $stat[2] ?>
                    </p>

                    <!-- ✅ Ajout du badge rouge 🔴 uniquement sur "Commandes en attente" -->
                    <?php if ($stat[0] === "Commandes en attente" && $stat[1] > 0): ?>
                        <span class="absolute top-0 right-0 bg-red-600 text-white text-xs font-bold px-2 py-1 rounded-full">
                            🔴 <?= $stat[1] ?>
                        </span>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>


        <!-- 📂 Bouton Exporter les statistiques -->
        <div class="mb-6 flex justify-end">
            <button id="exportStats" class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow-lg hover:bg-blue-700">
                📊 Exporter les statistiques
            </button>
        </div>

        <!-- 🏅 Top 5 des produits les plus vendus -->
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h3 class="text-xl font-semibold">🔥 Top 5 des produits les plus vendus</h3>
            <ul>
                <?php foreach ($topSellingProducts as $product): ?>
                    <li><?= htmlspecialchars($product['lib_article']) ?> - <?= $product['total_sold'] ?> unités vendues</li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- 📊 Graphiques -->
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
        const chartsData = {
            traffic: <?= json_encode($dailyOrdersStats ?? []) ?>,
            orders: <?= json_encode($dailyOrdersStats ?? []) ?>,
            categories: <?= json_encode($categoryStats ?? []) ?>,
            revenue: <?= json_encode($monthlyRevenueStats ?? []) ?>
        };

        console.log("📊 Données des graphiques :", chartsData);

        const chartConfigs = [{
                id: 'userTrafficChart',
                label: 'Trafic Utilisateur',
                dataArray: chartsData.traffic,
                dataKey: 'commandes',
                labelKey: 'date',
                type: 'line',
                color: 'rgba(75, 192, 192, 1)'
            },
            {
                id: 'ordersChart',
                label: 'Commandes',
                dataArray: chartsData.orders,
                dataKey: 'commandes',
                labelKey: 'date',
                type: 'bar',
                color: 'rgba(54, 162, 235, 1)'
            },
            {
                id: 'categoryChart',
                label: 'Commandes par Catégorie',
                dataArray: chartsData.categories,
                dataKey: 'count',
                labelKey: 'categorie',
                type: 'doughnut',
                color: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0']
            },
            {
                id: 'revenueChart',
                label: 'Revenus Mensuels',
                dataArray: chartsData.revenue,
                dataKey: 'revenus',
                labelKey: 'mois',
                type: 'line',
                color: 'rgba(255, 206, 86, 1)'
            }
        ];

        chartConfigs.forEach(config => {
            if (Array.isArray(config.dataArray) && config.dataArray.length > 0) {
                new Chart(document.getElementById(config.id).getContext('2d'), {
                    type: config.type,
                    data: {
                        labels: config.dataArray.map(item => item[config.labelKey]),
                        datasets: [{
                            label: config.label,
                            data: config.dataArray.map(item => item[config.dataKey]),
                            backgroundColor: Array.isArray(config.color) ? config.color.map(c => c + '0.2') : config.color + '0.2',
                            borderColor: config.color,
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        animation: {
                            duration: 2000
                        },
                        scales: config.type !== 'doughnut' ? {
                            y: {
                                beginAtZero: true
                            }
                        } : {}
                    }
                });
            } else {
                console.warn(`⚠️ Aucune donnée disponible pour ${config.label}`);
            }
        });

        // ✅ Exporter les statistiques en CSV
        document.getElementById('exportStats').addEventListener('click', function() {
            let csvContent = "data:text/csv;charset=utf-8,Statistique,Valeur\n";
            csvContent += "Total des commandes,<?= $totalOrders ?>\n";
            csvContent += "Revenu total,<?= number_format($totalRevenue, 2) ?> €\n";
            csvContent += "Commandes en attente,<?= $pendingOrdersCount ?>\n";
            csvContent += "Total des stocks,<?= $totalStock ?>\n";
            csvContent += "Revenu moyen par commande,<?= number_format($avgRevenuePerOrder, 2) ?> €\n";
            csvContent += "Nouveaux utilisateurs,<?= $newUsersCount ?>\n";

            let link = document.createElement("a");
            link.setAttribute("href", encodeURI(csvContent));
            link.setAttribute("download", "statistiques.csv");
            document.body.appendChild(link);
            link.click();
        });
    </script>

    <?php include('src/app/Views/includes/footer.php'); ?>