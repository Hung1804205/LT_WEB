<?php
require_once __DIR__ . '/../utils/response.php';

class ProductController
{
    public static function getPublicList(): void
    {
        successResponse('API lấy danh sách sản phẩm', [
            'items' => [],
            'pagination' => [
                'page' => 1,
                'limit' => 10,
                'total' => 0
            ]
        ]);
    }

    public static function getPublicDetail($id): void
    {
        successResponse('API lấy chi tiết sản phẩm', [
            'id' => (int)$id
        ]);
    }

    public static function getAdminList(): void
    {
        successResponse('API admin lấy danh sách sản phẩm', [
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
        successResponse('API tạo sản phẩm', []);
    }

    public static function update($id): void
    {
        successResponse('API cập nhật sản phẩm', [
            'id' => (int)$id
        ]);
    }

    public static function delete($id): void
    {
        successResponse('API xóa sản phẩm', [
            'id' => (int)$id
        ]);
    }

    public static function addImages($id): void
    {
        successResponse('API thêm ảnh phụ sản phẩm', [
            'product_id' => (int)$id
        ]);
    }

    public static function deleteImage($id): void
    {
        successResponse('API xóa ảnh phụ', [
            'image_id' => (int)$id
        ]);
    }
}