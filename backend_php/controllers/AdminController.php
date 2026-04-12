<?php
require_once __DIR__ . '/../utils/response.php';

class AdminController
{
    public static function getCustomers(): void
    {
        successResponse('API admin lấy danh sách khách hàng', [
            'items' => [],
            'pagination' => [
                'page' => 1,
                'limit' => 10,
                'total' => 0
            ]
        ]);
    }

    public static function getCustomerDetail($id): void
    {
        successResponse('API admin lấy chi tiết khách hàng', [
            'id' => (int)$id
        ]);
    }

    public static function getUsers(): void
    {
        successResponse('API admin lấy danh sách admin/staff', [
            'items' => [],
            'pagination' => [
                'page' => 1,
                'limit' => 10,
                'total' => 0
            ]
        ]);
    }

    public static function createUser(): void
    {
        successResponse('API tạo tài khoản admin/staff', []);
    }

    public static function updateUser($id): void
    {
        successResponse('API cập nhật tài khoản admin/staff', [
            'id' => (int)$id
        ]);
    }

    public static function deleteUser($id): void
    {
        successResponse('API xóa tài khoản admin/staff', [
            'id' => (int)$id
        ]);
    }
}