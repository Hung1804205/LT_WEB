<?php
require_once __DIR__ . '/../utils/response.php';

class AuthController
{
    public static function register(): void
    {
        successResponse('API đăng ký', []);
    }

    public static function login(): void
    {
        successResponse('API đăng nhập', []);
    }

    public static function logout(): void
    {
        successResponse('API đăng xuất', []);
    }

    public static function me(): void
    {
        successResponse('API lấy user hiện tại', []);
    }
}