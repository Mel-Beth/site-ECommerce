<?php include('src/app/Views/includes/head.php'); ?>
<?php include('src/app/Views/includes/header.php'); ?>
<?php include('src/app/Views/includes/sidebar.php'); ?>

<body class="bg-gray-100 h-screen overflow-hidden">

    <main class="ml-60 pt-16 p-6 h-screen overflow-auto">
        <div class="container mx-auto p-4">
            <h1 class="text-2xl font-bold mb-4">📊 Statistiques de l'e-commerce</h1>

            <!-- 📌 Filtres -->
            <div class="flex justify-between mb-4">
                <button onclick="loadStats('daily')" class="bg-blue-500 text-white px-4 py-2 rounded">📅 Jour</button>
                <button onclick="loadStats('weekly')" class="bg-green-500 text-white px-4 py-2 rounded">📆 Semaine</button>
                <button onclick="loadStats('monthly')" class="bg-orange-500 text-white px-4 py-2 rounded">📅 Mois</button>
            </div>

            <!-- 📊 Graphique des commandes -->
            <div class="bg-white p-4 rounded shadow-md">
                <h2 class="text-xl font-bold mb-2">📦 Commandes par jour</h2>
                <canvas id="ordersChart"></canvas>
            </div>

            <!-- 📈 Graphique des revenus -->
            <div class="bg-white p-4 rounded shadow-md mt-4">
                <h2 class="text-xl font-bold mb-2">💰 Revenus</h2>
                <canvas id="revenuesChart"></canvas>
            </div>

            <!-- 🏆 Meilleurs produits vendus -->
            <div class="bg-white p-4 rounded shadow-md mt-4">
                <h2 class="text-xl font-bold mb-2">🏆 Top Produits Vendus</h2>
                <table class="w-full">
                    <thead>
                        <tr>
                            <th class="p-2">Produit</th>
                            <th class="p-2">Ventes</th>
                        </tr>
                    </thead>
                    <tbody id="topProducts">
                        <!-- Les données seront chargées ici en AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function loadStats(period) {
            fetch(`admin/stats/${period}`)
                .then(response => response.json())
                .then(data => {
                    console.log("📊 Données reçues :", data);

                    if (data.orders.labels.length === 0) {
                        console.warn("⚠️ Aucune commande trouvée pour cette période.");
                    }
                    if (data.revenues.labels.length === 0) {
                        console.warn("⚠️ Aucun revenu trouvé pour cette période.");
                    }

                    updateChart(ordersChart, data.orders.labels, data.orders.values);
                    updateChart(revenuesChart, data.revenues.labels, data.revenues.values);
                    updateTopProducts(data.topProducts);
                })
                .catch(error => console.error("❌ Erreur lors du chargement des statistiques :", error));
        }

        function updateChart(chart, labels, values) {
            chart.data.labels = labels;
            chart.data.datasets[0].data = values;
            chart.update();
        }

        function updateTopProducts(products) {
            const tableBody = document.getElementById("topProducts");
            tableBody.innerHTML = "";
            products.forEach(product => {
                tableBody.innerHTML += `<tr><td class="p-2">${product.name}</td><td class="p-2">${product.sales}</td></tr>`;
            });
        }

        const ctxOrders = document.getElementById("ordersChart").getContext("2d");
        const ordersChart = new Chart(ctxOrders, {
            type: "line",
            data: {
                labels: [],
                datasets: [{ label: "Commandes", data: [], borderColor: "blue", borderWidth: 2 }]
            }
        });

        const ctxRevenues = document.getElementById("revenuesChart").getContext("2d");
        const revenuesChart = new Chart(ctxRevenues, {
            type: "bar",
            data: {
                labels: [],
                datasets: [{ label: "Revenus (€)", data: [], backgroundColor: "green" }]
            }
        });

        loadStats("daily"); // Charger les stats du jour par défaut
    </script>

<?php include('src/app/Views/includes/footer.php'); ?>
