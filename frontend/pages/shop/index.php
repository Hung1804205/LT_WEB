<?php include '../../includes/header.php'; ?>
<?php include '../../includes/navbar.php'; ?>

<h1>Danh sách sản phẩm</h1>

<div class="filter-box">

    <input 
        type="text" 
        id="searchInput"
        placeholder="Tìm kiếm sản phẩm..."
    >

    <select id="priceFilter">
        <option value="all">Tất cả giá</option>
        <option value="low">Dưới 200.000đ</option>
        <option value="high">Trên 200.000đ</option>
    </select>

</div>

<div class="product-grid" id="product-list"></div>

<script src="../../assets/js/product.js"></script>

<?php include '../../includes/footer.php'; ?>

