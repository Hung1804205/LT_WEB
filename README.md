# LT_WEB
# Clothing Store Website

Website bán quần áo trực tuyến dành cho khách hàng và quản trị viên.  
Dự án hỗ trợ các chức năng mua sắm cơ bản như xem sản phẩm, tìm kiếm, giỏ hàng, đặt hàng, theo dõi đơn hàng và quản trị sản phẩm, danh mục, đơn hàng, khách hàng.

---

## 1. Giới thiệu dự án

Dự án được xây dựng nhằm mô phỏng hệ thống bán quần áo online với 2 nhóm người dùng chính:

- **Khách hàng**: đăng ký, đăng nhập, xem sản phẩm, thêm giỏ hàng, đặt hàng, theo dõi đơn hàng
- **Quản trị viên / nhân viên**: quản lý sản phẩm, danh mục, đơn hàng, khách hàng, tài khoản quản trị

Mục tiêu của dự án:
- hỗ trợ bán hàng trực tuyến
- quản lý dữ liệu tập trung
- mô phỏng quy trình mua hàng thực tế
- làm nền tảng cho việc học và phát triển hệ thống web thương mại điện tử

---

## 2. Chức năng chính

### 2.1. Chức năng phía khách hàng
- Đăng ký tài khoản
- Đăng nhập, đăng xuất
- Xem danh sách sản phẩm
- Xem chi tiết sản phẩm
- Tìm kiếm sản phẩm
- Lọc sản phẩm theo danh mục, giá
- Thêm sản phẩm vào giỏ hàng
- Cập nhật giỏ hàng
- Xóa sản phẩm khỏi giỏ hàng
- Hiển thị tổng tiền trong giỏ hàng
- Đặt hàng
- Nhập thông tin nhận hàng
- Chọn phương thức thanh toán
- Xem lịch sử đơn hàng
- Xem trạng thái đơn hàng

### 2.2. Chức năng phía quản trị
- Đăng nhập trang admin
- Quản lý sản phẩm
- Thêm, sửa, xóa sản phẩm
- Quản lý danh mục
- Quản lý đơn hàng
- Cập nhật trạng thái đơn hàng
- Quản lý khách hàng
- Quản lý tài khoản admin/nhân viên

### 2.3. Chức năng dữ liệu hệ thống
- Lưu thông tin khách hàng
- Lưu thông tin sản phẩm
- Lưu giỏ hàng
- Lưu đơn hàng
- Lưu thanh toán
- Quản lý tồn kho cơ bản

---

## 3. Công nghệ sử dụng

### Frontend
- HTML
- CSS
- JavaScript

### Backend
- PHP

### Database
- MySQL

### Công cụ hỗ trợ
- Git / GitHub
- Draw.io / dbdiagram (thiết kế ERD)
- Postman (kiểm thử API)

---

## 4. Cấu trúc database

Dự án sử dụng các bảng chính sau:

- `users`
- `categories`
- `products`
- `product_images`
- `carts`
- `cart_items`
- `orders`
- `order_items`
- `payments`

### Một số quy ước dữ liệu
- `users.role`: `customer`, `admin`, `staff`
- `products.image`: ảnh đại diện
- `product_images`: ảnh phụ của sản phẩm
- `products.price`: giá gốc
- `products.sale_price`: giá khuyến mãi
- `orders.order_status`: `pending`, `confirmed`, `shipping`, `delivered`, `cancelled`
- `payments.payment_status`: `unpaid`, `paid`, `failed`

---

## 5. Cài đặt và chạy dự án

### Bước 1: Clone project
```bash
git clone <repository_url>
cd <project_name>
