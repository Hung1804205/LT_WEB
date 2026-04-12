<?php
require_once __DIR__ . '/../utils/response.php';

class CartController
{
    public static function getCurrentCart(): void
    {
        successResponse('API lấy giỏ hàng hiện tạip', [
            'cart_id' => 0,
            'items' => [],
            'total_amount' => 0
        ]);
    }

    public static function addItem(): void
    {
        successResponse('API thêm sản phẩm vào giỏ hàng', []);
    }

    public static function updateItem($id): void
    {
        successResponse('API cập nhật số lượng sản phẩm trong giỏ', [
            'id' => (int)$id
        ]);
    }

    public static function deleteItem($id): void
    {
        successResponse('API xóa sản phẩm khỏi giỏ hàng', [
            'id' => (int)$id
        ]);
    }

    public static function calculateTotal(): void
    {
        successResponse('API tính tổng tiền giỏ hàng', [
            'total_amount' => 0
        ]);
    }
}