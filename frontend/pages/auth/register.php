<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký - Fashion Shop</title>
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
            <p class="left-desc">Tạo tài khoản miễn phí và khám phá hàng ngàn sản phẩm thời trang.</p>
            <a href="login.php" class="btn-learn">Đăng nhập →</a>
        </div>

         <!-- Bên phải: form -->    
        <div class="right-side">

            <div class="logo-box top-logo">
                <span class="logo-icon">👗</span>
                <span class="logo-name">FASHION</span>
            </div>

            <h2>Welcome!</h2>
            <p class="subtitle">Đăng ký ngay, miễn phí!</p>

            <!-- Thông báo lỗi -->
            <div class="alert alert-error" id="errorMsg" style="display:none;"></div>
            <div class="alert alert-success" id="successMsg" style="display:none;"></div>

            <div class="form-group">
                <input type="text" id="fullName" placeholder="Full Name">
            </div>

            <div class="form-group">
                <input type="email" id="email" placeholder="Email Address">
            </div>

            <div class="form-group">
                <input type="password" id="password" placeholder="Password">
            </div>

            <div class="form-group">
                <input type="text" id="phone" placeholder="Phone Number">
            </div>

            <div class="form-group">
                <input type="text" id="address" placeholder="Address">
            </div>

            <button class="btn-submit" id="btnRegister">Đăng ký</button>

            <p class="link-text">
                Đã có tài khoản? <a href="login.php">Đăng nhập</a>
            </p>

        </div>

    </div>

    <script src="/assets/js/api.js"></script>
    <script src="/assets/js/register.js"></script>

</body>
</html>