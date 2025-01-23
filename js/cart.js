// js/cart.js

document.addEventListener("DOMContentLoaded", function () {
    // Gérer la mise à jour de la quantité
    const quantityInputs = document.querySelectorAll(".quantity-input");
    quantityInputs.forEach((input) => {
      input.addEventListener("change", function () {
        const productId = this.getAttribute("data-product-id");
        const quantity = this.value;
  
        console.log("Sending request to update quantity:", { product_id: productId, quantity: quantity }); // Log pour déboguer
  
        fetch(`cart`, {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            "X-Requested-With": "XMLHttpRequest",
            "Accept": "application/json",
          },
          body: JSON.stringify({ product_id: productId, quantity: quantity }),
        })
          .then((response) => {
            if (!response.ok) {
              throw new Error("Network response was not ok");
            }
            return response.json();
          })
          .then((data) => {
            if (data.success) {
              window.location.reload(); // Recharger la page pour afficher la nouvelle quantité
            } else {
              alert("Erreur lors de la mise à jour de la quantité");
            }
          })
          .catch((error) => console.error("Error:", error));
      });
    });
  });

  // Gérer la suppression d'un article
  const removeForms = document.querySelectorAll(".remove-form");
  removeForms.forEach((form) => {
    form.addEventListener("submit", function (event) {
      event.preventDefault();

      const productId = this.getAttribute("data-product-id");

      // Pour la suppression d'un article
      fetch(`cart`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-Requested-With": "XMLHttpRequest",
        },
        body: JSON.stringify({ remove_product_id: productId }),
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            window.location.reload(); // Recharger la page pour afficher le panier mis à jour
          } else {
            alert("Erreur lors de la suppression de l'article");
          }
        })
        .catch((error) => console.error("Error:", error));
    });
  });
});
