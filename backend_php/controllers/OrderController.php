<?php
require_once __DIR__ . '/../utils/response.php';

class OrderController
{
    public static function create(): void
    {
        successResponse('API tạo đơn hàng', []);
    }

    public static function getHistory(): void
    {
        successResponse('API lấy lịch sử đơn hàng', [
            'items' => [],
            'pagination' => [
                'page' => 1,
                'limit' => 10,
                'total' => 0
            ]
        ]);
    }

    public static function getDetail($id): void
    {
        successResponse('API lấy chi tiết đơn hàng', [
            'id' => (int)$id
        ]);
    }

    public static function getStatus($id): void
    {
        successResponse('API lấy trạng thái đơn hàng', [
            'id' => (int)$id,
            'order_status' => 'pending'
        ]);
    }

    public static function getAdminList(): void
    {
        successResponse('API admin lấy danh sách đơn hàng', [
            'items' => [],
            'pagination' => [
                'page' => 1,
                'limit' => 10,
                'total' => 0
            ]
        ]);
    }

    public static function getAdminDetail($id): void
    {
        successResponse('API admin lấy chi tiết đơn hàng', [
            'id' => (int)$id
        ]);
    }

    public static function updateStatus($id): void
    {
        successResponse('API admin cập nhật trạng thái đơn hàng', [
            'id' => (int)$id
        ]);
    }
}