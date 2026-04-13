<?php
require_once __DIR__ . '/../utils/response.php';
require_once __DIR__ . '/../models/OrderModel.php';

class OrderController
{
    public static function create(): void
    {
        $data = getJsonBody();

        $required = ['user_id', 'receiver_name', 'receiver_phone', 'shipping_address', 'selected_cart_item_ids'];
        $missing = [];
        foreach ($required as $field) {
            if (!isset($data[$field]) || (is_string($data[$field]) && trim($data[$field]) === '')) {
                $missing[] = $field;
            }
        }

        if (!empty($missing)) {
            errorResponse('Thiếu dữ liệu bắt buộc', ['missing' => $missing]);
            return;
        }

        if (empty($data['selected_cart_item_ids'])) {
            errorResponse('selected_cart_item_ids không được rỗng', ['selected_cart_item_ids' => 'Phải chọn ít nhất 1 sản phẩm']);
            return;
        }

        $userId = (int)$data['user_id'];
        $receiverName = trim($data['receiver_name']);
        $receiverPhone = trim($data['receiver_phone']);
        $shippingAddress = trim($data['shipping_address']);
        $note = isset($data['note']) ? trim($data['note']) : null;
        $paymentMethod = isset($data['payment_method']) ? strtoupper(trim($data['payment_method'])) : 'COD';

        if (!in_array($paymentMethod, ['COD', 'BANKING'])) {
            errorResponse('payment_method không hợp lệ', ['payment_method' => 'Chỉ chấp nhận COD hoặc BANKING']);
            return;
        }

        $user = OrderModel::getUserById($userId);
        if (!$user) {
            errorResponse('User không tồn tại', ['user_id' => 'User không tìm thấy']);
            return;
        }

        $cartItems = OrderModel::getCartItemsByIds($data['selected_cart_item_ids']);
        if (empty($cartItems)) {
            errorResponse('Không tìm thấy sản phẩm trong giỏ', ['selected_cart_item_ids' => 'Các sản phẩm không tồn tại']);
            return;
        }

        $orderCode = OrderModel::generateOrderCode();
        $totalAmount = 0;
        foreach ($cartItems as $item) {
            $totalAmount += $item['quantity'] * $item['price'];
        }

        $pdo = Database::connect();
        try {
            $pdo->beginTransaction();

            $orderId = OrderModel::createOrder($userId, $orderCode, $receiverName, $receiverPhone, $shippingAddress, $note, $totalAmount);

            OrderModel::createPayment($orderId, $paymentMethod, $totalAmount);

            foreach ($cartItems as $item) {
                $subtotal = $item['quantity'] * $item['price'];
                OrderModel::addOrderItem($orderId, $item['product_id'], $item['size'], $item['color'], $item['quantity'], $item['price'], $subtotal);
            }

            OrderModel::deleteCartItems($data['selected_cart_item_ids']);

            $pdo->commit();

            successResponse('Đặt hàng thành công', [
                'order_id' => $orderId,
                'order_code' => $orderCode,
                'total_amount' => (float)$totalAmount,
                'order_status' => 'pending'
            ]);
        } catch (Exception $e) {
            $pdo->rollBack();
            errorResponse('Lỗi khi tạo đơn hàng', ['error' => $e->getMessage()]);
        }
    }

    public static function getHistory(): void
    {
        $data = getJsonBody();
        $userId = $data['user_id'] ?? null;

        if (!$userId) {
            errorResponse('Thiếu user_id', ['user_id' => 'user_id là bắt buộc']);
            return;
        }

        $page = isset($data['page']) ? (int)$data['page'] : 1;
        $limit = isset($data['limit']) ? (int)$data['limit'] : 10;

        if ($page < 1) $page = 1;
        if ($limit < 1) $limit = 10;

        $result = OrderModel::getOrdersByUserId($userId, $page, $limit);

        successResponse('Lấy lịch sử đơn hàng thành công', [
            'items' => $result['items'],
            'pagination' => [
                'page' => $page,
                'limit' => $limit,
                'total' => $result['total']
            ]
        ]);
    }

    public static function getDetail($id): void
    {
        $order = OrderModel::getOrderById($id);

        if (!$order) {
            errorResponse('Đơn hàng không tồn tại', ['id' => 'Không tìm thấy đơn hàng']);
            return;
        }

        $items = OrderModel::getOrderItems($id);
        $formattedItems = [];
        foreach ($items as $item) {
            $formattedItems[] = [
                'id' => (int)$item['id'],
                'product_id' => (int)$item['product_id'],
                'product_name' => $item['product_name'],
                'image' => $item['image'],
                'size' => $item['size'],
                'color' => $item['color'],
                'quantity' => (int)$item['quantity'],
                'price' => (float)$item['price'],
                'subtotal' => (float)$item['subtotal']
            ];
        }

        $payment = OrderModel::getPaymentByOrderId($id);

        successResponse('Lấy chi tiết đơn hàng thành công', [
            'id' => (int)$order['id'],
            'order_code' => $order['order_code'],
            'receiver_name' => $order['receiver_name'],
            'receiver_phone' => $order['receiver_phone'],
            'shipping_address' => $order['shipping_address'],
            'note' => $order['note'],
            'total_amount' => (float)$order['total_amount'],
            'order_status' => $order['order_status'],
            'created_at' => $order['created_at'],
            'items' => $formattedItems,
            'payment' => $payment ? [
                'payment_method' => $payment['payment_method'],
                'payment_status' => $payment['payment_status'],
                'amount' => (float)$payment['amount']
            ] : null
        ]);
    }

    public static function getStatus($id): void
    {
        $order = OrderModel::getOrderStatus($id);

        if (!$order) {
            errorResponse('Đơn hàng không tồn tại', ['id' => 'Không tìm thấy đơn hàng']);
            return;
        }

        successResponse('Lấy trạng thái đơn hàng thành công', [
            'order_status' => $order['order_status']
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