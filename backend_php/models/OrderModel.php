<?php
require_once __DIR__ . '/../config/database.php';

class OrderModel
{
    public static function generateOrderCode(): string
    {
        return 'ORD' . date('YmdHis') . rand(100, 999);
    }

    public static function createOrder(int $userId, string $orderCode, string $receiverName, string $receiverPhone, string $shippingAddress, ?string $note, float $totalAmount): int
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare('
            INSERT INTO orders (user_id, order_code, receiver_name, receiver_phone, shipping_address, note, total_amount, order_status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ');
        $stmt->execute([$userId, $orderCode, $receiverName, $receiverPhone, $shippingAddress, $note, $totalAmount, 'pending']);
        return (int)$pdo->lastInsertId();
    }

    public static function createPayment(int $orderId, string $paymentMethod, float $amount): void
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare('
            INSERT INTO payments (order_id, payment_method, amount, payment_status)
            VALUES (?, ?, ?, ?)
        ');
        $stmt->execute([$orderId, $paymentMethod, $amount, 'unpaid']);
    }

    public static function getPaymentByOrderId(int $orderId): ?array
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare('SELECT * FROM payments WHERE order_id = ?');
        $stmt->execute([$orderId]);
        $payment = $stmt->fetch();
        return $payment ?: null;
    }

    public static function addOrderItem(int $orderId, int $productId, string $size, string $color, int $quantity, float $price, float $subtotal): void
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare('
            INSERT INTO order_items (order_id, product_id, size, color, quantity, price, subtotal)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ');
        $stmt->execute([$orderId, $productId, $size, $color, $quantity, $price, $subtotal]);
    }

    public static function getCartItemsByIds(array $itemIds): array
    {
        if (empty($itemIds)) {
            return [];
        }
        $pdo = Database::connect();
        $placeholders = implode(',', array_fill(0, count($itemIds), '?'));
        $stmt = $pdo->prepare("
            SELECT ci.*, p.name as product_name, p.image
            FROM cart_items ci
            JOIN products p ON ci.product_id = p.id
            WHERE ci.id IN ($placeholders)
        ");
        $stmt->execute($itemIds);
        return $stmt->fetchAll();
    }

    public static function deleteCartItems(array $itemIds): void
    {
        if (empty($itemIds)) {
            return;
        }
        $pdo = Database::connect();
        $placeholders = implode(',', array_fill(0, count($itemIds), '?'));
        $stmt = $pdo->prepare("DELETE FROM cart_items WHERE id IN ($placeholders)");
        $stmt->execute($itemIds);
    }

    public static function getOrdersByUserId(int $userId, int $page = 1, int $limit = 10): array
    {
        $offset = ($page - 1) * $limit;
        $pdo = Database::connect();
        
        $countStmt = $pdo->prepare('SELECT COUNT(*) as total FROM orders WHERE user_id = ?');
        $countStmt->execute([$userId]);
        $total = (int)$countStmt->fetch()['total'];

        $stmt = $pdo->prepare('
            SELECT id, order_code, receiver_name, receiver_phone, shipping_address, note, total_amount, order_status, created_at
            FROM orders
            WHERE user_id = ?
            ORDER BY created_at DESC
            LIMIT ? OFFSET ?
        ');
        $stmt->bindValue(1, $userId, PDO::PARAM_INT);
        $stmt->bindValue(2, $limit, PDO::PARAM_INT);
        $stmt->bindValue(3, $offset, PDO::PARAM_INT);
        $stmt->execute();
        $items = $stmt->fetchAll();

        return [
            'items' => $items,
            'total' => $total
        ];
    }

    public static function getOrderById(int $orderId): ?array
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare('SELECT * FROM orders WHERE id = ?');
        $stmt->execute([$orderId]);
        $order = $stmt->fetch();
        return $order ?: null;
    }

    public static function getOrderItems(int $orderId): array
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare('
            SELECT oi.*, p.name as product_name, p.image
            FROM order_items oi
            JOIN products p ON oi.product_id = p.id
            WHERE oi.order_id = ?
        ');
        $stmt->execute([$orderId]);
        return $stmt->fetchAll();
    }

    public static function getOrderStatus(int $orderId): ?array
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare('SELECT order_status FROM orders WHERE id = ?');
        $stmt->execute([$orderId]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public static function getUserById(int $userId): ?array
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare('SELECT id FROM users WHERE id = ?');
        $stmt->execute([$userId]);
        $user = $stmt->fetch();
        return $user ?: null;
    }
}