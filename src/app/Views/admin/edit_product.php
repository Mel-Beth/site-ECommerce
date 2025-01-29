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
            <h1 class="text-2xl font-bold mb-4">Éditer un Produit</h1>

            <form method="post" action="admin/productsAdmin/update/<?= $product['id_article'] ?>">
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Nom</label>
                    <input type="text" name="name" id="name" value="<?= htmlspecialchars($product['lib_article']) ?>" class="mt-1 p-2 border rounded w-full">
                </div>
                <div class="mb-4">
                    <label for="price" class="block text-sm font-medium text-gray-700">Prix</label>
                    <input type="number" name="price" id="price" value="<?= htmlspecialchars($product['prix']) ?>" class="mt-1 p-2 border rounded w-full">
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="description" class="mt-1 p-2 border rounded w-full"><?= htmlspecialchars($product['description']) ?></textarea>
                </div>
                <div class="mb-4">
                    <label for="promotion" class="block text-sm font-medium text-gray-700">Promotion (%)</label>
                    <input type="number" name="promotion" id="promotion" value="<?= htmlspecialchars($product['taux_promotion'] ?? 0) ?>" class="mt-1 p-2 border rounded w-full">
                </div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Mettre à jour</button>
            </form>
        </div>
    </main>
</body>

</html>