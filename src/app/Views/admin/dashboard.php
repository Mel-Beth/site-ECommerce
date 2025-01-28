<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Tableau de Bord Admin</h1>
        <p>Bienvenue dans l'interface d'administration.</p>

        <div class="mt-6 space-y-4">
            <a href="admin/orders" class="block bg-blue-500 text-white px-4 py-2 rounded text-center">Gérer les Commandes</a>
            <a href="admin/products" class="block bg-green-500 text-white px-4 py-2 rounded text-center">Gérer les Produits</a>
            <a href="admin/users" class="block bg-yellow-500 text-white px-4 py-2 rounded text-center">Gérer les Utilisateurs</a>
            <a href="admin/reviews" class="block bg-purple-500 text-white px-4 py-2 rounded text-center">Gérer les Avis</a>
        </div>

        <!-- Graphique des revenus mensuels -->
        <div class="mt-6">
            <canvas id="revenueChart"></canvas>
        </div>

        <form method="POST" action="admin/logout" class="mt-6">
            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">Déconnexion</button>
        </form>
    </div>

    <script>
        const ctx = document.getElementById('revenueChart').getContext('2d');
        const revenueChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?= json_encode(array_column($stats, 'mois')) ?>,
                datasets: [{
                    label: 'Revenus mensuels',
                    data: <?= json_encode(array_column($stats, 'revenus')) ?>,
                    backgroundColor: 'rgba(59, 130, 246, 0.2)',
                    borderColor: 'rgba(59, 130, 246, 1)',
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
</body>

</html>