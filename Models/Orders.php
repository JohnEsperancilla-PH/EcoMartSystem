<?php
// Models/Orders.php

class Orders {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function createOrder($userData, $items, $totalAmount) {
        try {
            $this->conn->begin_transaction();

            // Insert into orders table
            $stmt = $this->conn->prepare('
                INSERT INTO Orders (
                    user_id, 
                    total_amount, 
                    status, 
                    order_date,
                    updated_at
                ) VALUES (?, ?, ?, NOW(), NOW())
            ');

            // For guest orders, user_id will be null
            $status = 'pending';
            $stmt->bind_param('ids', $userData['user_id'], $totalAmount, $status);
            $stmt->execute();
            
            $orderId = $this->conn->insert_id;

            // Create OrderItems for each product
            $orderItems = new OrderItems($this->conn);
            foreach ($items as $item) {
                $orderItems->createOrderItem($orderId, $item);
            }

            $this->conn->commit();
            return $orderId;

        } catch (Exception $e) {
            $this->conn->rollback();
            throw $e;
        }
    }

    public function getOrderById($orderId) {
        $stmt = $this->conn->prepare('
            SELECT * FROM Orders WHERE order_id = ?
        ');
        $stmt->bind_param('i', $orderId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}
