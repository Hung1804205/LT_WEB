const cartItems = [
  {
    id: 1,
    name: "Áo thun",
    price: 150000,
    quantity: 2,
    image: "https://picsum.photos/300?1",
  },

  {
    id: 2,
    name: "Quần jean",
    price: 400000,
    quantity: 1,
    image: "https://picsum.photos/300?2",
  },
];

function loadCart() {
  const container = document.getElementById("cart-container");
  const totalDiv = document.getElementById("cart-total");

  if (!container) return;

  container.innerHTML = "";

  let total = 0;

  cartItems.forEach((item) => {
    total += item.price * item.quantity;

    container.innerHTML += `
      <div class="cart-card">

          <img src="${item.image}">

          <div class="cart-info">

              <h3>${item.name}</h3>

              <p class="cart-size">Size: M</p>

              <p class="cart-price">${item.price.toLocaleString()} đ</p>

              <div class="qty-box">
                  <button onclick="decreaseQty(${item.id})">−</button>
                  <span>${item.quantity}</span>
                  <button onclick="increaseQty(${item.id})">+</button>
              </div>

          </div>

          <button class="delete-btn" onclick="removeItem(${item.id})">
              ×
          </button>

      </div>
    `;
  });

  totalDiv.innerHTML = `
    <div class="cart-summary">

        <h2>Tổng đơn hàng</h2>

        <div class="summary-price">
            ${total.toLocaleString()} đ
        </div>

        <button class="checkout-btn" onclick="goCheckout()">
            Thanh toán
        </button>

    </div>
  `;
}

function increaseQty(id) {
  const item = cartItems.find((i) => i.id === id);
  item.quantity++;

  loadCart();
}

function decreaseQty(id) {
  const item = cartItems.find((i) => i.id === id);

  if (item.quantity > 1) {
    item.quantity--;
  }

  loadCart();
}

function removeItem(id) {
  const index = cartItems.findIndex((i) => i.id === id);

  cartItems.splice(index, 1);

  loadCart();
}

function goCheckout() {
  window.location.href = "checkout.php";
}

loadCart();
