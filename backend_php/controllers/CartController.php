<?php
require_once __DIR__ . '/../utils/response.php';
require_once __DIR__ . '/../models/CartModel.php';

class CartController
{
    public static function getCurrentCart(): void
    {
        $data = getJsonBody();
        $userId = $data['user_id'] ?? null;

        if (!$userId) {
            errorResponse('Thiếu user_id', ['user_id' => 'user_id là bắt buộc']);
            return;
        }

        $cart = CartModel::getCartByUserId($userId);

        if (!$cart) {
            successResponse('Lấy giỏ hàng thành công', [
                'cart_id' => null,
                'user_id' => (int)$userId,
                'items' => [],
                'total_amount' => 0
            ]);
            return;
        }

        $items = CartModel::getCartItems($cart['id']);
        $totalAmount = array_sum(array_column($items, 'subtotal'));

        successResponse('Lấy giỏ hàng thành công', [
            'cart_id' => (int)$cart['id'],
            'user_id' => (int)$userId,
            'items' => $items,
            'total_amount' => (float)$totalAmount
        ]);
    }

    public static function addItem(): void
    {
        $data = getJsonBody();
        
        $required = ['user_id', 'product_id', 'size', 'color', 'quantity'];
        $missing = [];
        foreach ($required as $field) {
            if (!isset($data[$field])) {
                $missing[] = $field;
            }
        }

        if (!empty($missing)) {
            errorResponse('Thiếu dữ liệu', ['missing' => $missing]);
            return;
        }

        $userId = (int)$data['user_id'];
        $productId = (int)$data['product_id'];
        $size = trim($data['size']);
        $color = trim($data['color']);
        $quantity = (int)$data['quantity'];

        if ($quantity <= 0) {
            errorResponse('Số lượng phải lớn hơn 0', ['quantity' => 'quantity phải > 0']);
            return;
        }

        $product = CartModel::getProductById($productId);
        if (!$product) {
            errorResponse('Sản phẩm không tồn tại', ['product_id' => 'Sản phẩm không tìm thấy']);
            return;
        }

        $cart = CartModel::getOrCreateCart($userId);
        
        $existingItem = CartModel::findCartItem($cart['id'], $productId, $size, $color);
        
        if ($existingItem) {
            $newQuantity = $existingItem['quantity'] + $quantity;
            CartModel::updateCartItemQuantity($existingItem['id'], $newQuantity);
        } else {
            $price = (float)$product['price'];
            CartModel::addCartItem($cart['id'], $productId, $size, $color, $quantity, $price);
        }

        $items = CartModel::getCartItems($cart['id']);
        $totalAmount = array_sum(array_column($items, 'subtotal'));

        successResponse('Thêm sản phẩm vào giỏ hàng thành công', [
            'cart_id' => (int)$cart['id'],
            'user_id' => $userId,
            'items' => $items,
            'total_amount' => (float)$totalAmount
        ]);
    }

    public static function updateItem($id): void
    {
        $data = getJsonBody();
        $quantity = $data['quantity'] ?? null;

        if ($quantity === null) {
            errorResponse('Thiếu quantity', ['quantity' => 'quantity là bắt buộc']);
            return;
        }

        $quantity = (int)$quantity;
        if ($quantity <= 0) {
            errorResponse('Số lượng phải lớn hơn 0', ['quantity' => 'quantity phải > 0']);
            return;
        }

        $item = CartModel::getCartItemById($id);
        if (!$item) {
            errorResponse('Sản phẩm trong giỏ không tồn tại', ['id' => 'Không tìm thấy sản phẩm']);
            return;
        }

        CartModel::updateCartItemQuantity($id, $quantity);

        successResponse('Cập nhật số lượng sản phẩm thành công', [
            'id' => (int)$id,
            'quantity' => $quantity
        ]);
    }

    public static function deleteItem($id): void
    {
        $item = CartModel::getCartItemById($id);
        if (!$item) {
            errorResponse('Sản phẩm trong giỏ không tồn tại', ['id' => 'Không tìm thấy sản phẩm']);
            return;
        }

        CartModel::deleteCartItem($id);

        successResponse('Xóa sản phẩm khỏi giỏ hàng thành công', [
            'id' => (int)$id
        ]);
    }

    public static function calculateTotal(): void
    {
        $data = getJsonBody();
        $userId = $data['user_id'] ?? null;
        $itemIds = $data['item_ids'] ?? [];

        if (!$userId) {
            errorResponse('Thiếu user_id', ['user_id' => 'user_id là bắt buộc']);
            return;
        }

        if (empty($itemIds)) {
            successResponse('Tính tổng tiền thành công', [
                'items' => [],
                'total_amount' => 0
            ]);
            return;
        }

        $cart = CartModel::getCartByUserId($userId);
        if (!$cart) {
            successResponse('Tính tổng tiền thành công', [
                'items' => [],
                'total_amount' => 0
            ]);
            return;
        }

        $items = CartModel::getCartItemsByIds($cart['id'], $itemIds);
        $totalAmount = array_sum(array_column($items, 'subtotal'));

        successResponse('Tính tổng tiền thành công', [
            'items' => $items,
            'total_amount' => (float)$totalAmount
        ]);
    }
}