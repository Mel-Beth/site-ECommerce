<?php
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

    <main></main>

    <?php include '../includes/footer.php'; ?>


</body>

</html>