function placeOrder() {
  alert("Đặt hàng thành công!");

  window.location.href = "orders.php";
}

const orders = [
  {
    id: "#ORD001",
    date: "12/04/2026",
    total: 700000,
    status: "Đang xử lý",
  },

  {
    id: "#ORD002",
    date: "10/04/2026",
    total: 450000,
    status: "Đã giao",
  },
];

function loadOrders() {
  const container = document.getElementById("orders-container");

  if (!container) return;

  orders.forEach((order) => {
    container.innerHTML += `
          <div class="order-card">
              <h2>${order.id}</h2>

              <p>Ngày đặt: ${order.date}</p>

              <p>Tổng tiền: ${order.total.toLocaleString()} đ</p>

              <p>Trạng thái: ${order.status}</p>
          </div>
      `;
  });
}

loadOrders();
