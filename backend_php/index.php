<?php
require_once __DIR__ . '/utils/request.php';
require_once __DIR__ . '/utils/response.php';

require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/ProductController.php';
require_once __DIR__ . '/controllers/CategoryController.php';
require_once __DIR__ . '/controllers/CartController.php';
require_once __DIR__ . '/controllers/OrderController.php';
require_once __DIR__ . '/controllers/AdminController.php';

$method = getMethod();
$path = getPath();

$routes = [
    ['POST', '#^/api/auth/register$#', fn() => AuthController::register()],
    ['POST', '#^/api/auth/login$#', fn() => AuthController::login()],
    ['POST', '#^/api/auth/logout$#', fn() => AuthController::logout()],
    ['GET',  '#^/api/auth/me$#', fn() => AuthController::me()],

    ['GET',  '#^/api/products$#', fn() => ProductController::getPublicList()],
    ['GET',  '#^/api/products/(\d+)$#', fn($id) => ProductController::getPublicDetail($id)],
    ['GET',  '#^/api/categories$#', fn() => CategoryController::getPublicList()],

    ['GET',  '#^/api/admin/products$#', fn() => ProductController::getAdminList()],
    ['POST', '#^/api/admin/products$#', fn() => ProductController::create()],
    ['PUT',  '#^/api/admin/products/(\d+)$#', fn($id) => ProductController::update($id)],
    ['DELETE', '#^/api/admin/products/(\d+)$#', fn($id) => ProductController::delete($id)],
    ['POST', '#^/api/admin/products/(\d+)/images$#', fn($id) => ProductController::addImages($id)],
    ['DELETE', '#^/api/admin/product-images/(\d+)$#', fn($id) => ProductController::deleteImage($id)],

    ['GET',  '#^/api/admin/categories$#', fn() => CategoryController::getAdminList()],
    ['POST', '#^/api/admin/categories$#', fn() => CategoryController::create()],
    ['PUT',  '#^/api/admin/categories/(\d+)$#', fn($id) => CategoryController::update($id)],
    ['DELETE', '#^/api/admin/categories/(\d+)$#', fn($id) => CategoryController::delete($id)],

    ['GET',  '#^/api/cart$#', fn() => CartController::getCurrentCart()],
    ['POST', '#^/api/cart/items$#', fn() => CartController::addItem()],
    ['PUT',  '#^/api/cart/items/(\d+)$#', fn($id) => CartController::updateItem($id)],
    ['DELETE', '#^/api/cart/items/(\d+)$#', fn($id) => CartController::deleteItem($id)],
    ['POST', '#^/api/cart/total$#', fn() => CartController::calculateTotal()],

    ['POST', '#^/api/orders$#', fn() => OrderController::create()],
    ['GET',  '#^/api/orders$#', fn() => OrderController::getHistory()],
    ['GET',  '#^/api/orders/(\d+)$#', fn($id) => OrderController::getDetail($id)],
    ['GET',  '#^/api/orders/(\d+)/status$#', fn($id) => OrderController::getStatus($id)],

    ['GET',  '#^/api/admin/orders$#', fn() => OrderController::getAdminList()],
    ['GET',  '#^/api/admin/orders/(\d+)$#', fn($id) => OrderController::getAdminDetail($id)],
    ['PATCH', '#^/api/admin/orders/(\d+)/status$#', fn($id) => OrderController::updateStatus($id)],

    ['GET',  '#^/api/admin/customers$#', fn() => AdminController::getCustomers()],
    ['GET',  '#^/api/admin/customers/(\d+)$#', fn($id) => AdminController::getCustomerDetail($id)],
    ['GET',  '#^/api/admin/users$#', fn() => AdminController::getUsers()],
    ['POST', '#^/api/admin/users$#', fn() => AdminController::createUser()],
    ['PUT',  '#^/api/admin/users/(\d+)$#', fn($id) => AdminController::updateUser($id)],
    ['DELETE', '#^/api/admin/users/(\d+)$#', fn($id) => AdminController::deleteUser($id)],
];

foreach ($routes as [$routeMethod, $pattern, $handler]) {
    if ($method === $routeMethod && preg_match($pattern, $path, $matches)) {
        array_shift($matches);
        $handler(...$matches);
        exit;
    }
}

errorResponse('Route không tồn tại', ['path' => $path, 'method' => $method], 404);