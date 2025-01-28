document.addEventListener("DOMContentLoaded", function () {
  const quantityInputs = document.querySelectorAll(".quantity-input");
  if (quantityInputs) {
    quantityInputs.forEach((input) => {
      input.addEventListener("change", function () {
        const productId = this.getAttribute("data-product-id");
        const quantity = parseInt(this.value);

        if (quantity < 1) {
          alert("La quantité doit être supérieure à 0.");
          this.value = 1; // Réinitialiser la quantité à 1
          return;
        }

        fetch(`/api/cart/update`, {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            "X-Requested-With": "XMLHttpRequest",
          },
          body: JSON.stringify({ product_id: productId, quantity: quantity }),
        })
          .then((response) => {
            if (!response.ok) {
              throw new Error("Erreur réseau");
            }
            return response.json();
          })
          .then((data) => {
            if (data.success) {
              // Mettre à jour le total du panier sans recharger la page
              document.getElementById("cart-total").innerText = data.new_total;
            } else {
              alert("Erreur lors de la mise à jour de la quantité : " + data.message);
            }
          })
          .catch((error) => {
            console.error("Erreur :", error);
            alert("Une erreur s'est produite lors de la mise à jour du panier.");
          });
      });
    });
  }
});

const removeForms = document.querySelectorAll(".remove-form");
if (removeForms) {
  removeForms.forEach((form) => {
    form.addEventListener("submit", function (event) {
      event.preventDefault();

      if (!confirm("Êtes-vous sûr de vouloir supprimer cet article du panier ?")) {
        return;
      }

      const productId = this.getAttribute("data-product-id");

      fetch(`/api/cart/remove`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-Requested-With": "XMLHttpRequest",
        },
        body: JSON.stringify({ product_id: productId }),
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            // Supprimer l'article du DOM sans recharger la page
            const itemToRemove = document.getElementById(`cart-item-${productId}`);
            if (itemToRemove) {
              itemToRemove.remove();
            }
            // Mettre à jour le total du panier
            document.getElementById("cart-total").innerText = data.new_total;
          } else {
            alert("Erreur lors de la suppression de l'article : " + data.message);
          }
        })
        .catch((error) => console.error("Erreur :", error));
    });
  });
}