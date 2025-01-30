<?php include('src/app/Views/includes/head.php'); ?>
<?php include('src/app/Views/includes/header.php'); ?>
<?php include('src/app/Views/includes/sidebar.php'); ?>

<main class="flex-1 p-6 ml-60 min-h-screen">
    <h2 class="text-2xl font-bold mb-6">Tous les articles</h2>

    <!-- Filtres -->
    <div class="mb-4">
        <form method="GET" action="articles">
            <select name="categorie" class="p-2 border rounded">
                <option value="">Toutes les catégories</option>
                <?php foreach ($categories as $categorie): ?>
                    <option value="<?= htmlspecialchars($categorie['id_categorie']) ?>" <?= isset($_GET['categorie']) && $_GET['categorie'] == $categorie['id_categorie'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($categorie['lib_categorie']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Filtrer</button>
        </form>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <?php foreach ($articles as $article): ?>
            <div class="bg-white shadow-lg rounded-lg p-4">
                <img src="<?= htmlspecialchars('assets/images/' . ($article['image'] ?? 'default.png')) ?>" alt="<?= htmlspecialchars($article['lib_article']) ?>" class="w-full h-48 object-cover rounded-md mb-4">
                <h3 class="text-lg font-bold"><?= htmlspecialchars($article['lib_article']) ?></h3>
                <p class="text-gray-700"><?= number_format($article['prix'], 2) ?> €</p>
                <a href="product?id=<?= $article['id_article'] ?>" class="mt-4 inline-block bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">
                    Voir les détails
                </a>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <div class="mt-6 flex justify-center">
        <?php if ($page > 1): ?>
            <a href="articles?page=<?= $page - 1 ?>" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Précédent</a>
        <?php endif; ?>
        <?php if ($page < $totalPages): ?>
            <a href="articles?page=<?= $page + 1 ?>" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 ml-4">Suivant</a>
        <?php endif; ?>
    </div>
</main>
<script>
    const articles = <?= json_encode($articles) ?>; // Charger les articles depuis PHP
    const itemsPerPage = 10;
    let currentPage = 1;

    function renderArticles() {
        const start = (currentPage - 1) * itemsPerPage;
        const end = start + itemsPerPage;
        const paginatedArticles = articles.slice(start, end);

        const articlesContainer = document.getElementById('articles-container');
        articlesContainer.innerHTML = '';

        paginatedArticles.forEach(article => {
            const articleHTML = `
                <div class="bg-white shadow-lg rounded-lg p-4">
                    <img src="${article.url_image}" alt="${article.lib_article}" class="w-full h-48 object-cover rounded-md mb-4">
                    <h3 class="text-lg font-bold">${article.lib_article}</h3>
                    <p class="text-gray-700">${article.prix.toFixed(2)} €</p>
                    <a href="product?id=${article.id_article}" class="mt-4 inline-block bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">
                        Voir les détails
                    </a>
                </div>
            `;
            articlesContainer.innerHTML += articleHTML;
        });

        renderPagination();
    }

    function renderPagination() {
        const totalPages = Math.ceil(articles.length / itemsPerPage);
        const paginationContainer = document.getElementById('pagination-container');
        paginationContainer.innerHTML = '';

        if (currentPage > 1) {
            paginationContainer.innerHTML += `<button onclick="changePage(${currentPage - 1})" class="bg-yellow-500 px-4 py-2 text-white rounded">Précédent</button>`;
        }

        if (currentPage < totalPages) {
            paginationContainer.innerHTML += `<button onclick="changePage(${currentPage + 1})" class="bg-yellow-500 px-4 py-2 text-white rounded ml-4">Suivant</button>`;
        }
    }

    function changePage(page) {
        currentPage = page;
        renderArticles();
    }

    // Initialisation
    document.addEventListener('DOMContentLoaded', renderArticles);
</script>

<?php include('src/app/Views/includes/footer.php'); ?>

<script src="cart.js"></script>