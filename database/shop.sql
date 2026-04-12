DROP DATABASE IF EXISTS ecommerce_db;
CREATE DATABASE ecommerce_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ecommerce_db;

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS payments;
DROP TABLE IF EXISTS order_items;
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS cart_items;
DROP TABLE IF EXISTS carts;
DROP TABLE IF EXISTS product_images;
DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS users;

SET FOREIGN_KEY_CHECKS = 1;

-- =========================================================
-- 1. USERS
-- =========================================================
CREATE TABLE users (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20) DEFAULT NULL,
    address TEXT DEFAULT NULL,
    role ENUM('customer', 'admin', 'staff') NOT NULL DEFAULT 'customer',
    status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uk_users_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================================
-- 2. CATEGORIES
-- =========================================================
CREATE TABLE categories (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT DEFAULT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uk_categories_name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================================
-- 3. PRODUCTS
-- =========================================================
CREATE TABLE products (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    category_id INT UNSIGNED NOT NULL,
    name VARCHAR(150) NOT NULL,
    description TEXT DEFAULT NULL,
    price DECIMAL(10,2) NOT NULL,
    sale_price DECIMAL(10,2) DEFAULT NULL,
    stock_quantity INT NOT NULL DEFAULT 0,
    image VARCHAR(255) DEFAULT NULL,
    status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_products_category_id (category_id),
    KEY idx_products_status (status),
    KEY idx_products_name (name),
    CONSTRAINT fk_products_category
        FOREIGN KEY (category_id) REFERENCES categories(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,
    CONSTRAINT chk_products_price_positive
        CHECK (price > 0),
    CONSTRAINT chk_products_sale_price_valid
        CHECK (sale_price IS NULL OR (sale_price > 0 AND sale_price <= price)),
    CONSTRAINT chk_products_stock_non_negative
        CHECK (stock_quantity >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================================
-- 4. PRODUCT_IMAGES
-- =========================================================
CREATE TABLE product_images (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    product_id INT UNSIGNED NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_product_images_product_id (product_id),
    KEY idx_product_images_sort_order (sort_order),
    CONSTRAINT fk_product_images_product
        FOREIGN KEY (product_id) REFERENCES products(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT chk_product_images_sort_order_non_negative
        CHECK (sort_order >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================================
-- 5. CARTS
-- =========================================================
CREATE TABLE carts (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id INT UNSIGNED NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uk_carts_user_id (user_id),
    CONSTRAINT fk_carts_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================================
-- 6. CART_ITEMS
-- =========================================================
CREATE TABLE cart_items (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    cart_id INT UNSIGNED NOT NULL,
    product_id INT UNSIGNED NOT NULL,
    size VARCHAR(20) NOT NULL,
    color VARCHAR(30) NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    price DECIMAL(10,2) NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uk_cart_items_unique (cart_id, product_id, size, color),
    KEY idx_cart_items_product_id (product_id),
    CONSTRAINT fk_cart_items_cart
        FOREIGN KEY (cart_id) REFERENCES carts(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT fk_cart_items_product
        FOREIGN KEY (product_id) REFERENCES products(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,
    CONSTRAINT chk_cart_items_quantity_positive
        CHECK (quantity > 0),
    CONSTRAINT chk_cart_items_price_positive
        CHECK (price > 0),
    CONSTRAINT chk_cart_items_size_not_empty
        CHECK (CHAR_LENGTH(TRIM(size)) > 0),
    CONSTRAINT chk_cart_items_color_not_empty
        CHECK (CHAR_LENGTH(TRIM(color)) > 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================================
-- 7. ORDERS
-- =========================================================
CREATE TABLE orders (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id INT UNSIGNED NOT NULL,
    order_code VARCHAR(50) NOT NULL,
    receiver_name VARCHAR(100) NOT NULL,
    receiver_phone VARCHAR(20) NOT NULL,
    shipping_address TEXT NOT NULL,
    note TEXT DEFAULT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    order_status ENUM('pending', 'confirmed', 'shipping', 'delivered', 'cancelled')
        NOT NULL DEFAULT 'pending',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uk_orders_order_code (order_code),
    KEY idx_orders_user_id (user_id),
    KEY idx_orders_status (order_status),
    KEY idx_orders_created_at (created_at),
    CONSTRAINT fk_orders_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,
    CONSTRAINT chk_orders_total_amount_non_negative
        CHECK (total_amount >= 0),
    CONSTRAINT chk_orders_receiver_name_not_empty
        CHECK (CHAR_LENGTH(TRIM(receiver_name)) > 0),
    CONSTRAINT chk_orders_receiver_phone_not_empty
        CHECK (CHAR_LENGTH(TRIM(receiver_phone)) > 0),
    CONSTRAINT chk_orders_shipping_address_not_empty
        CHECK (CHAR_LENGTH(TRIM(shipping_address)) > 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================================
-- 8. ORDER_ITEMS
-- =========================================================
CREATE TABLE order_items (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    order_id INT UNSIGNED NOT NULL,
    product_id INT UNSIGNED NOT NULL,
    size VARCHAR(20) NOT NULL,
    color VARCHAR(30) NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    PRIMARY KEY (id),
    KEY idx_order_items_order_id (order_id),
    KEY idx_order_items_product_id (product_id),
    CONSTRAINT fk_order_items_order
        FOREIGN KEY (order_id) REFERENCES orders(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT fk_order_items_product
        FOREIGN KEY (product_id) REFERENCES products(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,
    CONSTRAINT chk_order_items_quantity_positive
        CHECK (quantity > 0),
    CONSTRAINT chk_order_items_price_positive
        CHECK (price > 0),
    CONSTRAINT chk_order_items_subtotal_non_negative
        CHECK (subtotal >= 0),
    CONSTRAINT chk_order_items_size_not_empty
        CHECK (CHAR_LENGTH(TRIM(size)) > 0),
    CONSTRAINT chk_order_items_color_not_empty
        CHECK (CHAR_LENGTH(TRIM(color)) > 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================================
-- 9. PAYMENTS
-- =========================================================
CREATE TABLE payments (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    order_id INT UNSIGNED NOT NULL,
    payment_method ENUM('COD', 'BANKING') NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    payment_status ENUM('unpaid', 'paid', 'failed') NOT NULL DEFAULT 'unpaid',
    transaction_code VARCHAR(100) DEFAULT NULL,
    paid_at DATETIME DEFAULT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uk_payments_order_id (order_id),
    KEY idx_payments_status (payment_status),
    KEY idx_payments_method (payment_method),
    CONSTRAINT fk_payments_order
        FOREIGN KEY (order_id) REFERENCES orders(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT chk_payments_amount_non_negative
        CHECK (amount >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================================
-- OPTIONAL SEED DATA
-- =========================================================
INSERT INTO categories (name, description) VALUES
('Áo thun', 'Các loại áo thun cộc tay, dài tay chất liệu cotton'),
('Áo sơ mi', 'Sơ mi công sở, sơ mi họa tiết đi chơi'),
('Quần jean', 'Quần jean nam nữ ống rộng, ống suông'),
('Váy', 'Các loại váy thời trang');

INSERT INTO users (full_name, email, password, phone, address, role, status) VALUES
('Hoàng Huy', 'huy.admin@gmail.com', '$2y$10$examplehashadmin', '0901111111', 'Quận 1, TP.HCM', 'admin', 'active'),
('Quốc Tuấn', 'tuan.staff@gmail.com', '$2y$10$examplehashstaff', '0902222222', 'Quận 3, TP.HCM', 'staff', 'active'),
('Trần Nguyên', 'nguyen.customer@gmail.com', '$2y$10$examplehashcustomer1', '0903333333', 'Gò Vấp, TP.HCM', 'customer', 'active'),
('Đức Tiến', 'tien.customer@gmail.com', '$2y$10$examplehashcustomer2', '0904444444', 'Thủ Đức, TP.HCM', 'customer', 'inactive');

INSERT INTO products (category_id, name, description, price, sale_price, stock_quantity, image, status) VALUES
(1, 'Áo thun Basic Nam', 'Áo thun trơn chất liệu 100% cotton thoáng mát.', 250000, 199000, 50, 'uploads/products/ao-thun-basic.jpg', 'active'),
(1, 'Áo thun Oversize Họa Tiết', 'Form rộng rãi, phong cách đường phố.', 300000, NULL, 30, 'uploads/products/ao-thun-over.jpg', 'active'),
(3, 'Quần Jean Ống Rộng Xanh Nhạt', 'Jeans denim co giãn nhẹ, form suông.', 450000, 399000, 20, 'uploads/products/quan-jean-xanh.jpg', 'active'),
(2, 'Áo Sơ Mi Lụa Trắng', 'Chất lụa mềm, không nhăn.', 350000, NULL, 0, 'uploads/products/somi-trang.jpg', 'inactive');

INSERT INTO product_images (product_id, image_url, sort_order) VALUES
(1, 'uploads/products/ao-thun-basic-detail-1.jpg', 1),
(1, 'uploads/products/ao-thun-basic-detail-2.jpg', 2),
(3, 'uploads/products/quan-jean-xanh-detail.jpg', 1);

INSERT INTO carts (user_id) VALUES
(3);

INSERT INTO cart_items (cart_id, product_id, size, color, quantity, price) VALUES
(1, 1, 'L', 'black', 2, 199000),
(1, 1, 'M', 'black', 1, 199000),
(1, 3, '30', 'blue', 1, 399000);

INSERT INTO orders (
    user_id, order_code, receiver_name, receiver_phone, shipping_address, note, total_amount, order_status
) VALUES
(3, 'ORD20260406001', 'Trần Nguyên', '0903333333', '12 Nguyễn Trãi, Quận 1, TP.HCM', 'Giao trong giờ hành chính', 797000, 'pending');

INSERT INTO order_items (order_id, product_id, size, color, quantity, price, subtotal) VALUES
(1, 1, 'L', 'black', 2, 199000, 398000),
(1, 3, '30', 'blue', 1, 399000, 399000);

INSERT INTO payments (order_id, payment_method, amount, payment_status, transaction_code, paid_at) VALUES
(1, 'COD', 797000, 'unpaid', NULL, NULL);