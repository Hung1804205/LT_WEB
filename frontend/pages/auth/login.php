<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - Fashion Shop</title>
    <link rel="stylesheet" href="/LT_WEB/frontend/assets/css/style.css">
</head>
<body>

<div class="wrapper">

    <!-- Bên trái: ảnh, logo và khẩu hiệu -->
    <div class="left">
        <div class="logo">👗 FASHION</div>
        <h1>Nơi phong cách của bạn<br>bắt đầu</h1>

        <!-- Lưới ảnh thời trang -->
       <div class="photo-grid">
    <img src="image/1.jpg" alt="Fashion 1">
    <img src="image/2.jpg" alt="Fashion 2">
    <img src="image/3.jpg" alt="Fashion 3">
    <img src="image/4.jfif" alt="Fashion 4">
    <img src="image/5.jpg" alt="Fashion 5">
</div>
    </div>

    <!-- Bên phải: form đăng nhập -->
    <div class="right">
        <div class="form-box">

            <h1>Đăng nhập</h1>
            <p class="sub">Trải nghiệm không gian thời trang dành riêng cho bạn!</p>

             <div id="errorMsg" class="error-box" style="display:none;"></div>

            <div class="field">
                <label>Email</label>
                <input type="email" id="email">
            </div>

            <div class="field">
                <label>Mật khẩu</label>
                <input type="password" id="password">
            </div>

            <button id="btnLogin" class="btn-login">Đăng nhập</button>

            <p class="switch">Chưa có tài khoản? <a href="/LT_WEB/frontend/pages/auth/register.php">Đăng ký</a></p>

        </div>
    </div>

</div>

<script src="/LT_WEB/frontend/assets/js/api.js"></script>
<script src="/LT_WEB/frontend/assets/js/auth.js"></script>

</body>
</html>