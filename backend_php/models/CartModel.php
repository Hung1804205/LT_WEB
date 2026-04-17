<?php
require_once __DIR__ . '/../config/database.php';

class CartModel
{
    public static function getCartByUserId(int $userId): ?array
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare('SELECT * FROM carts WHERE user_id = ?');
        $stmt->execute([$userId]);
        $cart = $stmt->fetch();
        return $cart ?: null;
    }

    public static function createCart(int $userId): int
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare('INSERT INTO carts (user_id) VALUES (?)');
        $stmt->execute([$userId]);
        return (int)$pdo->lastInsertId();
    }

    public static function getOrCreateCart(int $userId): array
    {
        $cart = self::getCartByUserId($userId);
        if (!$cart) {
            $cartId = self::createCart($userId);
            $cart = ['id' => $cartId, 'user_id' => $userId];
        }
        return $cart;
    }

    public static function getCartItems(int $cartId): array
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare('
            SELECT 
                ci.id,
                ci.product_id,
                p.name as product_name,
                p.image,
                ci.size,
                ci.color,
                ci.quantity,
                ci.price,
                (ci.quantity * ci.price) as subtotal
            FROM cart_items ci
            JOIN products p ON ci.product_id = p.id
            WHERE ci.cart_id = ?
            ORDER BY ci.created_at DESC
        ');
        $stmt->execute([$cartId]);
        return $stmt->fetchAll();
    }

    public static function findCartItem(int $cartId, int $productId, string $size, string $color): ?array
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare('
            SELECT * FROM cart_items 
            WHERE cart_id = ? AND product_id = ? AND size = ? AND color = ?
        ');
        $stmt->execute([$cartId, $productId, $size, $color]);
        $item = $stmt->fetch();
        return $item ?: null;
    }

    public static function addCartItem(int $cartId, int $productId, string $size, string $color, int $quantity, float $price): int
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare('
            INSERT INTO cart_items (cart_id, product_id, size, color, quantity, price)
            VALUES (?, ?, ?, ?, ?, ?)
        ');
        $stmt->execute([$cartId, $productId, $size, $color, $quantity, $price]);
        return (int)$pdo->lastInsertId();
    }

    public static function updateCartItemQuantity(int $itemId, int $quantity): void
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare('UPDATE cart_items SET quantity = ? WHERE id = ?');
        $stmt->execute([$quantity, $itemId]);
    }

    public static function getProductById(int $productId): ?array
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
        $stmt->execute([$productId]);
        $product = $stmt->fetch();
        return $product ?: null;
    }

    public static function deleteCartItem(int $itemId): void
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare('DELETE FROM cart_items WHERE id = ?');
        $stmt->execute([$itemId]);
    }

    public static function getCartItemById(int $itemId): ?array
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare('
            SELECT 
                ci.id,
                ci.product_id,
                p.name as product_name,
                p.image,
                ci.size,
                ci.color,
                ci.quantity,
                ci.price,
                (ci.quantity * ci.price) as subtotal
            FROM cart_items ci
            JOIN products p ON ci.product_id = p.id
            WHERE ci.id = ?
        ');
        $stmt->execute([$itemId]);
        $item = $stmt->fetch();
        return $item ?: null;
    }

    public static function getCartItemsByIds(int $cartId, array $itemIds): array
    {
        if (empty($itemIds)) {
            return [];
        }
        $pdo = Database::connect();
        $placeholders = implode(',', array_fill(0, count($itemIds), '?'));
        $stmt = $pdo->prepare("
            SELECT 
                ci.id,
                ci.product_id,
                p.name as product_name,
                p.image,
                ci.size,
                ci.color,
                ci.quantity,
                ci.price,
                (ci.quantity * ci.price) as subtotal
            FROM cart_items ci
            JOIN products p ON ci.product_id = p.id
            WHERE ci.cart_id = ? AND ci.id IN ($placeholders)
        ");
        $params = array_merge([$cartId], $itemIds);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}