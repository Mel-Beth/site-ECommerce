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
        <h2 class="text-2xl font-bold mb-6">Statistiques</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
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


    <!-- Scripts pour les graphiques -->
    <script>
        const userTrafficCtx = document.getElementById('userTrafficChart').getContext('2d');
        const ordersCtx = document.getElementById('ordersChart').getContext('2d');
        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');

        new Chart(userTrafficCtx, {
            type: 'line',
            data: {
                labels: <?= json_encode(array_column($userTrafficStats, 'date')) ?>,
                datasets: [{
                    label: 'Trafic Utilisateur',
                    data: <?= json_encode(array_column($userTrafficStats, 'visits')) ?>,
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

        new Chart(ordersCtx, {
            type: 'bar',
            data: {
                labels: <?= json_encode(array_column($ordersStats, 'date')) ?>,
                datasets: [{
                    label: 'Commandes',
                    data: <?= json_encode(array_column($ordersStats, 'orders')) ?>,
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

        new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: <?= json_encode(array_column($categoryStats, 'category')) ?>,
                datasets: [{
                    label: 'Catégories',
                    data: <?= json_encode(array_column($categoryStats, 'count')) ?>,
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0'],
                    hoverOffset: 4
                }]
            }
        });

        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: <?= json_encode(array_column($revenueStats, 'month')) ?>,
                datasets: [{
                    label: 'Revenus Mensuels',
                    data: <?= json_encode(array_column($revenueStats, 'revenue')) ?>,
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
    </script>

<footer class="bg-gray-800 text-white py-4 mt-4 ml-0 md:ml-60">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center">
            <p>&copy; 2025 Vide Ton Porte-Monnaie - Tous droits réservés</p>
            <div class="flex space-x-4">
                <a href="cgv" class="text-yellow-500 hover:underline">CGV</a>
                <a href="contact" class="text-yellow-500 hover:underline">Contact</a>
                <a href="faq" class="text-yellow-500 hover:underline">FAQ</a>
            </div>
        </div>
    </div>
</footer>

<script src="src/js/cart.js"></script>

</body>
</html>