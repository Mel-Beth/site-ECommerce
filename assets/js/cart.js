// Récupérer le panier depuis LocalStorage
function getCart() {
  return JSON.parse(localStorage.getItem("cart") || "{}");
}

// Sauvegarder le panier dans LocalStorage
function saveCart(cart) {
  localStorage.setItem("cart", JSON.stringify(cart));
}

// Ajouter un produit au panier
function addToCart(productId, productName, productPrice, quantity) {
  const cart = getCart();

  if (cart[productId]) {
    cart[productId].quantity += quantity;
  } else {
    cart[productId] = {
      id: productId,
      name: productName,
      price: productPrice,
      quantity: quantity,
    };
  }

  saveCart(cart);
  alert(`${quantity} x "${productName}" ajouté(s) au panier !`);
}

// Charger les articles du panier pour affichage
async function loadCart(endpoint) {
  const cart = getCart();
  const productIds = Object.keys(cart);

  const cartItemsDiv = document.getElementById("cart-items");
  const emptyCartMessage = document.getElementById("empty-cart-message");
  const cartSummary = document.getElementById("cart-summary");
  const totalPriceSpan = document.getElementById("total-price");

  cartItemsDiv.innerHTML = "";
  emptyCartMessage.style.display = "none";
  cartSummary.style.display = "none";

  if (productIds.length === 0) {
    emptyCartMessage.style.display = "block";
    return;
  }

  try {
    const response = await fetch(endpoint, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ productIds }),
    });

    const textResponse = await response.text(); // On récupère la réponse en texte d'abord pour vérifier
    console.log("Server Response:", textResponse);

    let products;
    try {
      products = JSON.parse(textResponse); // Essayer de parser le JSON
    } catch (e) {
      throw new Error("Invalid JSON response");
    }

    if (products.error) {
      console.error("API Error:", products.error);
      alert("Une erreur est survenue lors du chargement des produits.");
      return;
    }

    let total = 0;

    products.forEach((product) => {
      const quantity = cart[product.id].quantity;
      const subtotal = product.price * quantity;
      total += subtotal;

      const itemHTML = `
                <div class="flex items-center justify-between border p-4 rounded-lg shadow-lg hover:shadow-xl transition-all">
                    <div class="flex items-center space-x-4">
                        <img src="${
                          product.image || "default_image_url.jpg"
                        }" alt="${
        product.name
      }" class="w-20 h-20 object-cover rounded-lg">
                        <div>
                            <h2 class="text-lg font-semibold">${
                              product.name
                            }</h2>
                            <p class="text-gray-700">${product.price.toFixed(
                              2
                            )} €</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4 text-right">
                        <input type="number" value="${quantity}" min="1" class="w-16 text-center border p-1 rounded-md" onchange="updateQuantity(${
        product.id
      }, this.value)">
                        <p class="text-gray-800 font-bold">${subtotal.toFixed(
                          2
                        )} €</p>
                        <button onclick="removeFromCart(${
                          product.id
                        })" class="text-red-500 hover:text-red-700">
                            <i class="fas fa-trash-alt"></i> Supprimer
                        </button>
                    </div>
                </div>
            `;
      cartItemsDiv.innerHTML += itemHTML;
    });

    totalPriceSpan.innerText = total.toFixed(2);
    cartSummary.style.display = "flex";
  } catch (error) {
    console.error("Error:", error);
    alert("Une erreur s'est produite lors du chargement du panier.");
  }
}

// Mettre à jour la quantité d'un article
function updateQuantity(productId, newQuantity) {
  const cart = getCart();
  if (newQuantity > 0) {
    cart[productId].quantity = parseInt(newQuantity);
  } else {
    delete cart[productId];
  }
  saveCart(cart);
  loadCart("php/get_products.php");
}

// Supprimer un article du panier
function removeFromCart(productId) {
  const cart = getCart();
  delete cart[productId];
  saveCart(cart);
  loadCart("php/get_products.php");
}
