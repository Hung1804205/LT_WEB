<?php
require_once __DIR__ . '/../utils/response.php';

class CategoryController
{
    public static function getPublicList(): void
    {
        successResponse('API lấy danh sách danh mục', [
            'items' => [],
            'pagination' => [
                'page' => 1,
                'limit' => 10,
                'total' => 0
            ]
        ]);
    }

    public static function getAdminList(): void
    {
        successResponse('API admin lấy danh sách danh mục', [
            'items' => [],
            'pagination' => [
                'page' => 1,
                'limit' => 10,
                'total' => 0
            ]
        ]);
    }

    public static function create(): void
    {
        successResponse('API tạo danh mục', []);
    }

    public static function update($id): void
    {
        successResponse('API cập nhật danh mục', [
            'id' => (int)$id
        ]);
    }

    public static function delete($id): void
    {
        successResponse('API xóa danh mục', [
            'id' => (int)$id
        ]);
    }
}