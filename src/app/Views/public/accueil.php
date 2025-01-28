<?php include('src/app/Views/includes/head.php'); ?>
<?php include('src/app/Views/includes/header.php'); ?>
<?php include('src/app/Views/includes/sidebar.php'); ?>

<main class="flex-1 p-6 ml-60 min-h-screen">
    <!-- Carrousel -->
    <div id="carousel" class="relative w-full overflow-hidden h-60 bg-gray-200 rounded-lg shadow-lg mb-8">
        <div class="flex transition-transform duration-500 ease-in-out" style="transform: translateX(0%);">
            <?php if (!empty($carouselImages)): ?>
                <?php foreach ($carouselImages as $image): ?>
                    <div class="flex-none w-full h-56">
                        <img src="<?= htmlspecialchars($image['url_image']) ?>" alt="<?= htmlspecialchars($image['alt']) ?>" class="w-full h-full object-cover">
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aucune image disponible pour le carrousel.</p>
            <?php endif; ?>
        </div>
        <!-- Navigation -->
        <button id="prev" class="absolute top-1/2 left-4 transform -translate-y-1/2 text-white bg-black bg-opacity-50 rounded-full p-2">
            <i class="fas fa-chevron-left"></i>
        </button>
        <button id="next" class="absolute top-1/2 right-4 transform -translate-y-1/2 text-white bg-black bg-opacity-50 rounded-full p-2">
            <i class="fas fa-chevron-right"></i>
        </button>
    </div>

    <!-- Cartes par catégorie -->
    <h2 class="text-2xl font-bold mb-6">Catégories</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <?php if (!empty($categories)): ?>
            <?php foreach ($categories as $categorie): ?>
                <div class="bg-white shadow-lg rounded-lg p-4 mb-2">
                    <h3 class="text-xl font-bold mb-4"><?= htmlspecialchars($categorie['lib_categorie']) ?></h3>
                    <div class="grid grid-cols-2 gap-2">
                        <?php foreach ($categorie['sous_categories'] as $sous_categorie): ?>
                            <div class="flex flex-col items-center">
                                <img src="<?= htmlspecialchars($sous_categorie['image']) ?>" alt="<?= htmlspecialchars($sous_categorie['lib_sous_categorie']) ?>" class="w-20 h-20 object-cover rounded">
                                <p class="text-sm mt-2"><?= htmlspecialchars($sous_categorie['lib_sous_categorie']) ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <a href="category.php?cat=<?= htmlspecialchars($categorie['id_categorie']) ?>" class="mt-4 block text-center bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">
                        Voir plus
                    </a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucune catégorie disponible.</p>
        <?php endif; ?>
    </div>

    <!-- Articles -->
    <h2 class="text-2xl font-bold mb-6">Articles</h2>
    <div id="articles-container" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6"></div>
    <div id="pagination-container" class="mt-6 flex justify-center"></div>
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