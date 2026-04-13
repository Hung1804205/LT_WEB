<?php include '../../includes/header.php'; ?>
<?php include '../../includes/navbar.php'; ?>

<section class="checkout-page">

    <div class="checkout-left">

        <h2>Thông tin giao hàng</h2>

        <input type="text" placeholder="Họ và tên">
        <input type="text" placeholder="Số điện thoại">
        <input type="text" placeholder="Địa chỉ nhận hàng">

        <textarea placeholder="Ghi chú giao hàng"></textarea>

        <select>
            <option>Thanh toán khi nhận hàng</option>
            <option>Chuyển khoản ngân hàng</option>
        </select>

    </div>


    <div class="checkout-right">

        <h2>Đơn hàng của bạn</h2>

        <div class="checkout-cart-item">
            <span>Áo thun x2</span>
            <span>300.000đ</span>
        </div>

        <div class="checkout-cart-item">
            <span>Quần jean x1</span>
            <span>400.000đ</span>
        </div>

        <hr>

        <div class="checkout-total">
            <span>Tổng cộng</span>
            <strong>700.000đ</strong>
        </div>

        <button>Xác nhận đặt hàng</button>

    </div>

</section>

<?php include '../../includes/footer.php'; ?>