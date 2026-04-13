console.log("PRODUCT JS LOADED");
const products = [
  {
    id: 1,
    name: "Áo thun",
    price: 150000,
    image: "https://picsum.photos/300?1",
  },
  {
    id: 2,
    name: "Quần Jean",
    price: 400000,
    image: "https://picsum.photos/300?2",
  },
];

function loadProducts(filteredProducts = products) {
  const container = document.getElementById("product-list");

  if (!container) return;

  container.innerHTML = "";

  filteredProducts.forEach((p) => {
    container.innerHTML += `
    <div class="product-card">
    
        <a href="detail.php?id=${p.id}">
            <img src="${p.image}">
        </a>
    
        <h3>
            <a href="detail.php?id=${p.id}">
                ${p.name}
            </a>
        </h3>
    
        <p>${p.price.toLocaleString()} đ</p>
    
    </div>
    `;
  });
}

function goDetail(id) {
  window.location.href = `detail.php?id=${id}`;
}

loadProducts();

function getProductId() {
  const params = new URLSearchParams(window.location.search);
  return params.get("id");
}

function loadProductDetail() {
  const id = getProductId();

  if (!id) return;

  const container = document.getElementById("product-detail");

  if (!container) return;

  const p = products.find((item) => item.id == id);

  if (!p) {
    container.innerHTML = "<h2>Không tìm thấy sản phẩm</h2>";
    return;
  }

  container.innerHTML = `
  <div class="detail-wrapper">
  
      <div class="detail-thumbnails">
          <img src="${p.image}" class="thumb active-thumb">
          <img src="${p.image}" class="thumb">
          <img src="${p.image}" class="thumb">
          <img src="${p.image}" class="thumb">
      </div>
  
      <div class="detail-main-image">
          <img src="${p.image}" id="mainImage">
      </div>
  
      <div class="detail-info">
  
          <h1>${p.name}</h1>
  
          <div class="detail-price">
              ${p.price.toLocaleString()} đ
          </div>
  
          <div class="size-box">
              <button class="size-btn active">S</button>
              <button class="size-btn">M</button>
              <button class="size-btn">L</button>
              <button class="size-btn">XL</button>
          </div>
  
          <div class="qty-control">
              <button id="minusBtn">-</button>
              <span id="qtyValue">1</span>
              <button id="plusBtn">+</button>
          </div>
  
<button class="add-cart-btn" id="addCartBtn">              THÊM VÀO GIỎ
          </button>
  
          <div class="detail-description">
              <h3>Mô tả</h3>
              <p>
                  Sản phẩm thời trang cao cấp phong cách hiện đại,
                  chất liệu cotton mềm mại, phù hợp sử dụng hàng ngày.
              </p>
          </div>
  
      </div>
  
  </div>

  <div id="toast" class="toast">
    Đã thêm vào giỏ hàng 
</div>
  `;

  setupDetailEvents();
}

function setupDetailEvents() {
  // ===== SIZE ACTIVE =====
  const sizeButtons = document.querySelectorAll(".size-btn");

  sizeButtons.forEach((btn) => {
    btn.addEventListener("click", function () {
      sizeButtons.forEach((item) => {
        item.classList.remove("active");
      });

      this.classList.add("active");
    });
  });

  // ===== TOAST ADD TO CART =====
  const addCartBtn = document.getElementById("addCartBtn");
  const toast = document.getElementById("toast");

  addCartBtn.addEventListener("click", function () {
    toast.classList.add("show");

    setTimeout(() => {
      toast.classList.remove("show");
    }, 2500);
  });

  // ===== QUANTITY =====
  let qty = 1;

  const qtyValue = document.getElementById("qtyValue");
  const plusBtn = document.getElementById("plusBtn");
  const minusBtn = document.getElementById("minusBtn");

  plusBtn.addEventListener("click", function () {
    qty++;
    qtyValue.innerText = qty;
  });

  minusBtn.addEventListener("click", function () {
    if (qty > 1) {
      qty--;
      qtyValue.innerText = qty;
    }
  });
}

loadProductDetail();

function addToCart() {
  alert("Đã thêm vào giỏ hàng!");
}

const searchInput = document.getElementById("searchInput");
const priceFilter = document.getElementById("priceFilter");

if (searchInput) {
  searchInput.addEventListener("input", filterProducts);
}

if (priceFilter) {
  priceFilter.addEventListener("change", filterProducts);
}

function filterProducts() {
  const keyword = searchInput.value.toLowerCase();
  const priceValue = priceFilter.value;

  let filtered = products.filter((product) => {
    let matchName = product.name.toLowerCase().includes(keyword);

    let matchPrice = true;

    if (priceValue === "low") {
      matchPrice = product.price < 200000;
    } else if (priceValue === "high") {
      matchPrice = product.price >= 200000;
    }

    return matchName && matchPrice;
  });

  loadProducts(filtered);
}
