document.addEventListener("DOMContentLoaded", function () {
  const cartKey = "ecommerce_cart";

  function loadCart() {
      return JSON.parse(localStorage.getItem(cartKey)) || [];
  }

  function saveCart(cart) {
      localStorage.setItem(cartKey, JSON.stringify(cart));
      updateHeaderCart();
  }

  function updateCartDisplay() {
      const cart = loadCart();
      const cartItemsContainer = document.getElementById("cart-items");
      const cartCount = document.getElementById("cart-count");
      const cartSubtotal = document.getElementById("cart-subtotal");
      const cartTotal = document.getElementById("cart-total");
      const shippingCost = document.getElementById("shipping-cost");
      const discount = document.getElementById("discount");
      const checkoutForm = document.getElementById("checkout-form");

      cartItemsContainer.innerHTML = "";
      let totalPrice = 0;

      if (cart.length === 0) {
          document.getElementById("cart-empty").style.display = "block";
          document.getElementById("cart-container").style.display = "none";
      } else {
          document.getElementById("cart-empty").style.display = "none";
          document.getElementById("cart-container").style.display = "block";

          cart.forEach((item) => {
              let totalItemPrice = item.price * item.quantity;
              totalPrice += totalItemPrice;

              const itemRow = document.createElement("div");
              itemRow.classList.add(
                  "flex",
                  "justify-between",
                  "items-center",
                  "border-b",
                  "border-gray-200",
                  "pb-4",
                  "mb-4"
              );

              itemRow.innerHTML = `
                  <div class="flex items-center">
                      <img src="assets/images/default.png" class="w-16 h-16 object-cover rounded-lg mr-4">
                      <div>
                          <h4 class="text-lg font-semibold">${item.name}</h4>
                          <p class="text-gray-600">${item.price.toFixed(2)} €</p>
                      </div>
                  </div>
                  <div class="text-right">
                      <input type="number" class="w-16 p-2 border rounded quantity-input" 
                             data-id="${item.id}" value="${item.quantity}" min="1">
                      <button class="text-red-500 hover:underline remove-btn" data-id="${item.id}">Supprimer</button>
                  </div>
              `;

              cartItemsContainer.appendChild(itemRow);
          });

          cartCount.innerText = cart.length;
          cartSubtotal.innerText = totalPrice.toFixed(2) + " €";
          shippingCost.innerText = "5.00 €";
          discount.innerText = "-0.00 €";
          cartTotal.innerText = (totalPrice + 5.0).toFixed(2) + " €";

          checkoutForm.style.display = "block";

          document.querySelectorAll(".remove-btn").forEach((button) => {
              button.addEventListener("click", function () {
                  const productId = this.getAttribute("data-id");
                  const newCart = cart.filter((item) => item.id != productId);
                  saveCart(newCart);
                  updateCartDisplay();
              });
          });

          document.querySelectorAll(".quantity-input").forEach((input) => {
              input.addEventListener("change", function () {
                  const productId = this.getAttribute("data-id");
                  const quantity = parseInt(this.value);
                  const cartItem = cart.find((item) => item.id == productId);
                  if (cartItem) {
                      cartItem.quantity = quantity;
                      saveCart(cart);
                      updateCartDisplay();
                  }
              });
          });

          document.getElementById("clear-cart").addEventListener("click", function () {
              localStorage.removeItem(cartKey);
              updateCartDisplay();
          });
      }
  }

  // Fonction pour mettre à jour le panier dans le header
  function updateHeaderCart() {
      const cart = loadCart();
      const cartCountHeader = document.getElementById("cart-count-header");
      const headerCartEmpty = document.getElementById("header-cart-empty");
      const headerCartItems = document.getElementById("header-cart-items");

      cartCountHeader.innerText = cart.length;
      headerCartItems.innerHTML = "";

      if (cart.length === 0) {
          headerCartEmpty.style.display = "block";
      } else {
          headerCartEmpty.style.display = "none";
          cart.forEach(item => {
              const listItem = document.createElement("li");
              listItem.classList.add("flex", "justify-between", "items-center");

              listItem.innerHTML = `
                  <span class="font-semibold">${item.name}</span>
                  <span>${item.quantity} x ${item.price.toFixed(2)} €</span>
                  <button class="text-red-500 hover:text-red-700 remove-btn-header" data-id="${item.id}">
                      <i class="fas fa-trash"></i>
                  </button>
              `;
              headerCartItems.appendChild(listItem);
          });

          document.querySelectorAll(".remove-btn-header").forEach(button => {
              button.addEventListener("click", function () {
                  const productId = this.getAttribute("data-id");
                  const newCart = cart.filter(item => item.id != productId);
                  saveCart(newCart);
                  updateHeaderCart();
                  updateCartDisplay();
              });
          });
      }
  }

  // Écouteur d'événement pour ajouter au panier
  document.querySelectorAll(".add-to-cart").forEach(button => {
      button.addEventListener("click", function () {
          const productId = this.getAttribute("data-id");
          const name = this.getAttribute("data-name");
          const price = parseFloat(this.getAttribute("data-price"));
          const discount = parseFloat(this.getAttribute("data-discount")) || 0;

          const cart = loadCart();
          const existingItem = cart.find(item => item.id == productId);

          if (existingItem) {
              existingItem.quantity += 1;
          } else {
              cart.push({ id: productId, name, price, quantity: 1, discount });
          }

          saveCart(cart);
          updateCartDisplay();
          updateHeaderCart();
      });
  });

  // Mettre à jour le panier au chargement de la page
  updateCartDisplay();
  updateHeaderCart();
});
