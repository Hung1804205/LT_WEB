<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký - Fashion Shop</title>
    <link rel="stylesheet" href="/LT_WEB/frontend/assets/css/style.css">
</head>
<body>

<div class="wrapper">

    <!-- Bên trái: ảnh, logo và khẩu hiệu -->
    <div class="left">
        <div class="logo">👗 FASHION</div>
        <h1>Nơi những tín đồ thời trang hội tụ</h1>

        <div class="photo-grid">
            <img src="image/6.jpg" alt="Fashion 6">
            <img src="image/7.jpg" alt="Fashion 7">
            <img src="image/8.jpg" alt="Fashion 8">
            <img src="image/9.jpg" alt="Fashion 9">
            <img src="image/10.jpg" alt="Fashion 10">
        </div>
    </div>

    <!-- Bên phải: form đăng ký -->
    <div class="right">
        <div class="form-box">

            <h2>Đăng ký</h2>
            <p class="sub">Vui lòng điền thông tin để tạo tài khoản</p>

            <div id="errorMsg"   class="error-box"   style="display:none;"></div>
            <div id="successMsg" class="success-box" style="display:none;"></div>

            <div class="field">
                <label>Họ và tên</label>
                <input type="text" id="fullName">
            </div>

            <div class="field">
                <label>Email</label>
                <input type="email" id="email">
            </div>

            <div class="field">
                <label>Mật khẩu</label>
                <div class="pass-wrap">
                   <input type="password" id="password">
                   <span class="eye-icon" onclick="xemPass('password')">👁</span>
                </div>
                <div id="passwordError" class="error-text" style="display:none;"></div>
            </div>

            <div class="field">
                <label>Số điện thoại</label>
                <input type="text" id="phone">
            </div>

            <div class="field">
                <label>Địa chỉ</label>
                <input type="text" id="address">
            </div>

            <button id="btnRegister" class="btn-login">Tạo tài khoản</button>

            <p class="switch">Đã có tài khoản? <a href="/LT_WEB/frontend/pages/auth/login.php">Đăng nhập</a></p>

        </div>
    </div>

</div>

<script src="/LT_WEB/frontend/assets/js/api.js"></script>
<script src="/LT_WEB/frontend/assets/js/auth.js"></script>

</body>
</html>