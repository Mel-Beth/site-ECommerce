<?php include('src/app/Views/includes/head.php'); ?>
<?php include('src/app/Views/includes/header.php'); ?>
<?php include('src/app/Views/includes/sidebar.php'); ?>

<main class="flex-1">
    <!-- Carrousel -->
    <div id="carousel" class="relative w-full overflow-hidden h-60 bg-gray-200 rounded-lg shadow-lg mb-8">
        <div class="flex transition-transform duration-500 ease-in-out" style="transform: translateX(0%);">
            <!-- Slide 1 -->
            <div class="flex-none w-full h-56">
                <img src="assets/images/carousel1.png" alt="Slide 1" class="w-full h-full object-cover">
            </div>
            <!-- Slide 2 -->
            <div class="flex-none w-full h-56">
                <img src="assets/images/carousel2.png" alt="Slide 2" class="w-full h-full object-cover">
            </div>
            <!-- Slide 3 -->
            <div class="flex-none w-full h-56">
                <img src="assets/images/carousel3.png" alt="Slide 3" class="w-full h-full object-cover">
            </div>
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
        <!-- Catégorie Électronique -->
        <div class="bg-white shadow-lg rounded-lg p-4 mb-2">
            <h3 class="text-xl font-bold mb-4">Électronique</h3>
            <div class="grid grid-cols-2 gap-2">
                <div class="flex flex-col items-center">
                    <img src="assets/images/audio.webp" alt="Audio" class="w-20 h-20 object-cover rounded">
                    <p class="text-sm mt-2">Audio</p>
                </div>
                <div class="flex flex-col items-center">
                    <img src="assets/images/telephone.webp" alt="Téléphones" class="w-20 h-20 object-cover rounded">
                    <p class="text-sm mt-2">Téléphones</p>
                </div>
                <div class="flex flex-col items-center">
                    <img src="assets/images/tv.webp" alt="TV et vidéo" class="w-20 h-20 object-cover rounded">
                    <p class="text-sm mt-2">TV et vidéo</p>
                </div>
                <div class="flex flex-col items-center">
                    <img src="assets/images/objetConnecte.webp" alt="Objets connectés" class="w-20 h-20 object-cover rounded">
                    <p class="text-sm mt-2">Objets connectés</p>
                </div>
            </div>
            <a href="category.php?cat=electronics" class="mt-4 block text-center bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">
                Voir plus
            </a>
        </div>

        <!-- Catégorie Mobilier -->
        <div class="bg-white shadow-lg rounded-lg p-4 mb-2">
            <h3 class="text-xl font-bold mb-4">Mobilier</h3>
            <div class="grid grid-cols-2 gap-2">
                <div class="flex flex-col items-center">
                    <img src="assets/images/literie.webp" alt="Literie" class="w-20 h-20 object-cover rounded">
                    <p class="text-sm mt-2">Literie</p>
                </div>
                <div class="flex flex-col items-center">
                    <img src="assets/images/luminaire.webp" alt="Luminaires" class="w-20 h-20 object-cover rounded">
                    <p class="text-sm mt-2">Luminaires</p>
                </div>
                <div class="flex flex-col items-center">
                    <img src="assets/images/decoration.webp" alt="Décoration" class="w-20 h-20 object-cover rounded">
                    <p class="text-sm mt-2">Décoration</p>
                </div>
                <div class="flex flex-col items-center">
                    <img src="assets/images/rangement.webp" alt="Rangements" class="w-20 h-20 object-cover rounded">
                    <p class="text-sm mt-2">Rangements</p>
                </div>
            </div>
            <a href="category.php?cat=furniture" class="mt-4 block text-center bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">
                Voir plus
            </a>
        </div>

        <!-- Catégorie Accessoires -->
        <div class="bg-white shadow-lg rounded-lg p-4 mb-2">
            <h3 class="text-xl font-bold mb-4">Accessoires</h3>
            <div class="grid grid-cols-2 gap-2">
                <div class="flex flex-col items-center">
                    <img src="assets/images/montre.webp" alt="Montres" class="w-20 h-20 object-cover rounded">
                    <p class="text-sm mt-2">Montres</p>
                </div>
                <div class="flex flex-col items-center">
                    <img src="assets/images/sac.webp" alt="Sacs" class="w-20 h-20 object-cover rounded">
                    <p class="text-sm mt-2">Sacs</p>
                </div>
                <div class="flex flex-col items-center">
                    <img src="assets/images/lunette.webp" alt="Lunettes" class="w-20 h-20 object-cover rounded">
                    <p class="text-sm mt-2">Lunettes</p>
                </div>
                <div class="flex flex-col items-center">
                    <img src="assets/images/chapeau.webp" alt="Chapeaux" class="w-20 h-20 object-cover rounded">
                    <p class="text-sm mt-2">Chapeaux</p>
                </div>
            </div>
            <a href="category.php?cat=accessories" class="mt-4 block text-center bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">
                Voir plus
            </a>
        </div>

        <!-- Catégorie Maison -->
        <div class="bg-white shadow-lg rounded-lg p-4 mb-2">
            <h3 class="text-xl font-bold mb-4">Maison</h3>
            <div class="grid grid-cols-2 gap-2">
                <div class="flex flex-col items-center">
                    <img src="assets/images/cuisine.webp" alt="Cuisine" class="w-20 h-20 object-cover rounded">
                    <p class="text-sm mt-2">Cuisine</p>
                </div>
                <div class="flex flex-col items-center">
                    <img src="assets/images/nettoyage.webp" alt="Nettoyage" class="w-20 h-20 object-cover rounded">
                    <p class="text-sm mt-2">Nettoyage</p>
                </div>
                <div class="flex flex-col items-center">
                    <img src="assets/images/outil.webp" alt="Outils" class="w-20 h-20 object-cover rounded">
                    <p class="text-sm mt-2">Outils</p>
                </div>
                <div class="flex flex-col items-center">
                    <img src="assets/images/decoration.webp" alt="Décoration" class="w-20 h-20 object-cover rounded">
                    <p class="text-sm mt-2">Décoration</p>
                </div>
            </div>
            <a href="category.php?cat=home" class="mt-4 block text-center bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">
                Voir plus
            </a>
        </div>
    </div>

    <!-- Cartes par catégorie -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <!-- Catégorie Accessoires Électroniques -->
        <div class="bg-white shadow-lg rounded-lg p-4 mb-2">
            <h3 class="text-xl font-bold mb-4">Accessoires Électroniques</h3>
            <div class="grid grid-cols-2 gap-2">
                <div class="flex flex-col items-center">
                    <img src="assets/images/casque.webp" alt="Casques" class="w-20 h-20 object-cover rounded">
                    <p class="text-sm mt-2">Casques</p>
                </div>
                <div class="flex flex-col items-center">
                    <img src="assets/images/souri.webp" alt="Souris" class="w-20 h-20 object-cover rounded">
                    <p class="text-sm mt-2">Souris</p>
                </div>
                <div class="flex flex-col items-center">
                    <img src="assets/images/clavier.webp" alt="Claviers" class="w-20 h-20 object-cover rounded">
                    <p class="text-sm mt-2">Claviers</p>
                </div>
                <div class="flex flex-col items-center">
                    <img src="assets/images/chargeur.webp" alt="Chargeurs" class="w-20 h-20 object-cover rounded">
                    <p class="text-sm mt-2">Chargeurs</p>
                </div>
            </div>
            <a href="category.php?cat=electronic-accessories" class="mt-4 block text-center bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">
                Voir plus
            </a>
        </div>

        <!-- Catégorie Jouets -->
        <div class="bg-white shadow-lg rounded-lg p-4 mb-2">
            <h3 class="text-xl font-bold mb-4">Jouets</h3>
            <div class="grid grid-cols-2 gap-2">
                <div class="flex flex-col items-center">
                    <img src="assets/images/lego.webp" alt="Lego" class="w-20 h-20 object-cover rounded">
                    <p class="text-sm mt-2">Lego</p>
                </div>
                <div class="flex flex-col items-center">
                    <img src="assets/images/poupee.webp" alt="Poupées" class="w-20 h-20 object-cover rounded">
                    <p class="text-sm mt-2">Poupées</p>
                </div>
                <div class="flex flex-col items-center">
                    <img src="assets/images/voiture.webp" alt="Voitures" class="w-20 h-20 object-cover rounded">
                    <p class="text-sm mt-2">Voitures</p>
                </div>
                <div class="flex flex-col items-center">
                    <img src="assets/images/jeusociete.webp" alt="Jeux de société" class="w-20 h-20 object-cover rounded">
                    <p class="text-sm mt-2">Jeux de société</p>
                </div>
            </div>
            <a href="category.php?cat=toys" class="mt-4 block text-center bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">
                Voir plus
            </a>
        </div>

        <!-- Catégorie Beauté -->
        <div class="bg-white shadow-lg rounded-lg p-4 mb-2">
            <h3 class="text-xl font-bold mb-4">Beauté</h3>
            <div class="grid grid-cols-2 gap-2">
                <div class="flex flex-col items-center">
                    <img src="assets/images/maquillage.webp" alt="Maquillage" class="w-20 h-20 object-cover rounded">
                    <p class="text-sm mt-2">Maquillage</p>
                </div>
                <div class="flex flex-col items-center">
                    <img src="assets/images/soincheveu.webp" alt="Soins des cheveux" class="w-20 h-20 object-cover rounded">
                    <p class="text-sm mt-2">Soins des cheveux</p>
                </div>
                <div class="flex flex-col items-center">
                    <img src="assets/images/soinpeau.webp" alt="Soins de la peau" class="w-20 h-20 object-cover rounded">
                    <p class="text-sm mt-2">Soins de la peau</p>
                </div>
                <div class="flex flex-col items-center">
                    <img src="assets/images/parfum.webp" alt="Parfums" class="w-20 h-20 object-cover rounded">
                    <p class="text-sm mt-2">Parfums</p>
                </div>
            </div>
            <a href="category.php?cat=beauty" class="mt-4 block text-center bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">
                Voir plus
            </a>
        </div>

        <!-- Catégorie Sport -->
        <div class="bg-white shadow-lg rounded-lg p-4 mb-2">
            <h3 class="text-xl font-bold mb-4">Sport</h3>
            <div class="grid grid-cols-2 gap-2">
                <div class="flex flex-col items-center">
                    <img src="assets/images/chaussures.webp" alt="Chaussures" class="w-20 h-20 object-cover rounded">
                    <p class="text-sm mt-2">Chaussures</p>
                </div>
                <div class="flex flex-col items-center">
                    <img src="assets/images/poids.webp" alt="Poids" class="w-20 h-20 object-cover rounded">
                    <p class="text-sm mt-2">Poids</p>
                </div>
                <div class="flex flex-col items-center">
                    <img src="assets/images/ballon.webp" alt="Ballons" class="w-20 h-20 object-cover rounded">
                    <p class="text-sm mt-2">Ballons</p>
                </div>
                <div class="flex flex-col items-center">
                    <img src="assets/images/raquette.webp" alt="Raquettes" class="w-20 h-20 object-cover rounded">
                    <p class="text-sm mt-2">Raquettes</p>
                </div>
            </div>
            <a href="category.php?cat=sport" class="mt-4 block text-center bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">
                Voir plus
            </a>
        </div>
    </div>
</main>

<script>
    const carousel = document.getElementById("carousel");
    const slides = carousel.querySelector(".flex");
    const prev = document.getElementById("prev");
    const next = document.getElementById("next");
    let index = 0;

    function updateCarousel() {
        slides.style.transform = `translateX(-${index * 100}%)`;
    }

    prev.addEventListener("click", () => {
        index = (index > 0) ? index - 1 : slides.children.length - 1;
        updateCarousel();
    });

    next.addEventListener("click", () => {
        index = (index < slides.children.length - 1) ? index + 1 : 0;
        updateCarousel();
    });
</script>

<?php include('src/app/Views/includes/footer.php'); ?>