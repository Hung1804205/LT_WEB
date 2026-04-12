<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - Fashion Shop</title>
    <link rel="stylesheet" href="/LT_WEB/frontend/assets/css/style.css">
</head>
<body>

    <div class="container">

        <!-- Bên trái: dark panel -->
        <div class="left-side">
            <div class="logo-box">
                <span class="logo-icon">👗</span>
                <span class="logo-name">FASHION</span>
            </div>
            <p class="left-desc">Mua sắm thời trang dễ dàng, nhanh chóng và tiện lợi mọi lúc mọi nơi.</p>
           <a href="register.php" class="btn-learn">Đăng ký ngay →</a>
        </div>

        <!-- Bên phải: form -->
        <div class="right-side">

            <div class="logo-box top-logo">
                <span class="logo-icon">👗</span>
                <span class="logo-name">FASHION</span>
            </div>

            <h2>Welcome!</h2>
            <p class="subtitle">Đăng nhập để tiếp tục</p>

            <!-- Thông báo lỗi --> 
            <div class="alert alert-error" id="errorMsg" style="display:none;"></div>

            <div class="form-group">
                <input type="email" id="email" placeholder="Email">
            </div>

            <div class="form-group">
                <input type="password" id="password" placeholder="Password">
            </div>

            <button class="btn-submit" id="btnLogin">Đăng nhập</button>

            <p class="link-text">
                Chưa có tài khoản? <a href="register.php">Đăng ký</a>
            </p>

        </div>

    </div>

    <script src="/assets/js/api.js"></script>
    <script src="/assets/js/login.js"></script>

</body>
</html>