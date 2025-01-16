<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit();
}

include '../php/db.php'; // Connexion à la BDD

// Récupération des statistiques
try {
    // Commandes par jour
    $stmt = $pdo->query("
        SELECT DATE(created_at) as date, COUNT(*) as commandes, SUM(total_price) as revenus
        FROM commandes
        GROUP BY DATE(created_at)
        ORDER BY DATE(created_at) ASC
    ");
    $stats = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Commandes par catégorie
    $stmt = $pdo->query("
        SELECT a.category, COUNT(cd.id) as commandes, SUM(cd.quantity * cd.price) as revenus
        FROM commande_details cd
        JOIN articles a ON cd.article_id = a.id
        GROUP BY a.category
        ORDER BY commandes DESC
    ");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des statistiques : " . $e->getMessage());
}

include '../includes/header.php';
include '../includes/sidebar.php';
?>

<main class="ml-60 mt-16 p-6">
    <h1 class="text-2xl font-bold mb-6">Tableau de bord</h1>

    <!-- Cartes statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-yellow-500 text-white p-4 rounded-lg shadow-md">
            <h2 class="text-xl font-bold">Commandes</h2>
            <p class="text-3xl font-bold"><?= array_sum(array_column($stats, 'commandes')) ?></p>
        </div>
        <div class="bg-green-500 text-white p-4 rounded-lg shadow-md">
            <h2 class="text-xl font-bold">Revenus</h2>
            <p class="text-3xl font-bold"><?= number_format(array_sum(array_column($stats, 'revenus')), 2) ?> €</p>
        </div>
        <div class="bg-blue-500 text-white p-4 rounded-lg shadow-md">
            <h2 class="text-xl font-bold">Catégories</h2>
            <p class="text-3xl font-bold"><?= count($categories) ?></p>
        </div>
    </div>

    <!-- Graphique des commandes par jour -->
    <div class="bg-white p-6 rounded-lg shadow-md mb-6">
        <h2 class="text-xl font-bold mb-4">Commandes et revenus par jour</h2>
        <canvas id="ordersChart"></canvas>
    </div>

    <!-- Graphique des commandes par catégorie -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-bold mb-4">Commandes par catégorie</h2>
        <canvas id="categoriesChart"></canvas>
    </div>
</main>

<script>
// Données pour les commandes par jour
const ordersData = {
    labels: <?= json_encode(array_column($stats, 'date')) ?>,
    datasets: [
        {
            label: 'Commandes',
            data: <?= json_encode(array_column($stats, 'commandes')) ?>,
            backgroundColor: 'rgba(255, 206, 86, 0.5)',
            borderColor: 'rgba(255, 206, 86, 1)',
            borderWidth: 1,
            yAxisID: 'y1',
        },
        {
            label: 'Revenus (€)',
            data: <?= json_encode(array_column($stats, 'revenus')) ?>,
            backgroundColor: 'rgba(75, 192, 192, 0.5)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1,
            yAxisID: 'y2',
        }
    ]
};

// Graphique des commandes par jour
const ordersChartCtx = document.getElementById('ordersChart').getContext('2d');
new Chart(ordersChartCtx, {
    type: 'bar',
    data: ordersData,
    options: {
        responsive: true,
        scales: {
            y1: {
                type: 'linear',
                position: 'left',
                beginAtZero: true
            },
            y2: {
                type: 'linear',
                position: 'right',
                beginAtZero: true
            }
        }
    }
});

// Données pour les commandes par catégorie
const categoriesData = {
    labels: <?= json_encode(array_column($categories, 'category')) ?>,
    datasets: [{
        label: 'Revenus (€)',
        data: <?= json_encode(array_column($categories, 'revenus')) ?>,
        backgroundColor: [
            'rgba(255, 99, 132, 0.5)',
            'rgba(54, 162, 235, 0.5)',
            'rgba(255, 206, 86, 0.5)',
            'rgba(75, 192, 192, 0.5)',
            'rgba(153, 102, 255, 0.5)',
            'rgba(255, 159, 64, 0.5)'
        ],
        borderColor: [
            'rgba(255, 99, 132, 1)',
            'rgba(54, 162, 235, 1)',
            'rgba(255, 206, 86, 1)',
            'rgba(75, 192, 192, 1)',
            'rgba(153, 102, 255, 1)',
            'rgba(255, 159, 64, 1)'
        ],
        borderWidth: 1
    }]
};

// Graphique des commandes par catégorie
const categoriesChartCtx = document.getElementById('categoriesChart').getContext('2d');
new Chart(categoriesChartCtx, {
    type: 'pie',
    data: categoriesData,
    options: {
        responsive: true
    }
});
</script>

<?php include '../includes/footer.php'; ?>
