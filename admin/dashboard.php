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

// Charger les traductions
$translations = include '../includes/translations.php';

// Définir la langue actuelle
$lang = $_SESSION['lang'] ?? 'fr';

// Charger les traductions pour la langue actuelle
$t = $translations[$lang];

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard E-commerce</title>

    <!-- Intégration de Tailwind CSS pour le style et FontAwesome pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <!-- Chart.js pour les graphiques -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="bg-gray-100 h-screen">
    <!-- Fond gris clair, hauteur plein écran et désactivation du défilement horizontal -->

    <!-- Barre latérale -->
    <aside class="fixed inset-y-0 left-0 w-60 bg-gradient-to-b from-yellow-300 to-yellow-500 shadow-lg flex flex-col z-10">
        <!-- Position fixe, hauteur étendue sur l'écran vertical, largeur de 60 unités -->
        <!-- Fond en dégradé du jaune clair au jaune foncé, ombre légère, disposition verticale -->
        <div class="p-6 text-center text-white font-bold text-xl border-b">
            <!-- Remplissage intérieur, centrage du texte, blanc, gras et taille XL -->
            <i class="fas fa-store mr-2"></i>E-commerce
            <!-- Icône avec espace à droite -->
        </div>

        <!-- Menu -->
        <nav class="flex-1 p-4 space-y-4">
            <!-- Flex vertical prenant tout l'espace, rembourrage intérieur et espace vertical entre éléments -->
            <a href="dashboard.php" class="flex items-center space-x-3 text-white hover:text-yellow-200 p-3 rounded-lg">
                <!-- Icône et texte alignés horizontalement, espace entre eux -->
                <!-- Texte blanc avec effet au survol, rembourrage et coins arrondis -->
                <i class="fas fa-chart-line"></i> <span>Tableau de Bord</span>
            </a>
            <a href="products.php" class="flex items-center space-x-3 text-white hover:text-yellow-200 p-3 rounded-lg">
                <i class="fas fa-box"></i> <span>Articles</span>
            </a>
            <a href="orders.php" class="flex items-center space-x-3 text-white hover:text-yellow-200 p-3 rounded-lg">
                <i class="fas fa-truck"></i> <span>Commandes</span>
            </a>
            <a href="users.php" class="flex items-center space-x-3 text-white hover:text-yellow-200 p-3 rounded-lg">
                <i class="fas fa-truck"></i> <span>Utilisateurs</span>
            </a>
        </nav>
    </aside>

    <!-- En-tête fixée en haut -->
    <header class="fixed top-0 left-60 right-0 bg-white shadow-md h-16 flex items-center px-6 z-10">
        <!-- Position fixe, largeur définie, fond blanc, ombre légère -->
        <!-- Hauteur définie à 16 unités, alignement horizontal des éléments -->
        <h1 class="text-xl font-bold text-yellow-700 flex-1">Dashboard E-commerce</h1>
        <!-- Texte jaune foncé, taille XL, gras, prend tout l'espace restant -->
        <a href="../logout.php" class="text-yellow-500 hover:underline"><?= $t['logout'] ?></a>
    </header>

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
            datasets: [{
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

</body>

</html>