<?php

class OrderItems {
    private $conn;

    public function __construct($connection) {
        $this->conn = $connection;
    }

    public function createOrderItem($orderId, $item) {
        $stmt = $this->conn->prepare('
            INSERT INTO OrderItems (
                order_id,
                product_id,
                quantity,
                price_at_time,
                created_at
            ) VALUES (?, ?, ?, ?, NOW())
        ');

        $stmt->bind_param('iiid', 
            $orderId, 
            $item['id'], 
            $item['quantity'], 
            $item['price']
        );

        return $stmt->execute();
    }

    public function getOrderItems($orderId) {
        $stmt = $this->conn->prepare('
            SELECT oi.*, p.name 
            FROM OrderItems oi
            JOIN Products p ON oi.product_id = p.product_id
            WHERE oi.order_id = ?
        ');
        
        $stmt->bind_param('i', $orderId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}