<?php
// Models/Orders.php

namespace Models;

use Exception;

class Orders {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function createOrder($orderData, $items) {
        try {
            $this->conn->begin_transaction();

            // Insert into orders table with additional customer information
            $stmt = $this->conn->prepare('
                INSERT INTO Orders (
                    user_id,
                    customer_name,
                    customer_email,
                    customer_contact,
                    delivery_address,
                    total_amount,
                    status,
                    payment_method,
                    gcash_ref,
                    gcash_phone,
                    order_date,
                    updated_at
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
            ');

            $stmt->bind_param('issssdssss', 
                $orderData['user_id'],
                $orderData['customer_name'],
                $orderData['customer_email'],
                $orderData['customer_contact'],
                $orderData['delivery_address'],
                $orderData['total_amount'],
                $orderData['status'],
                $orderData['payment_method'],
                $orderData['gcash_ref'],
                $orderData['gcash_phone']
            );
            
            $stmt->execute();
            $orderId = $this->conn->insert_id;

            // Create OrderItems for each product
            $orderItems = new OrderItems($this->conn);
            foreach ($items as $item) {
                $orderItems->createOrderItem($orderId, [
                    'id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ]);
            }

            $this->conn->commit();
            return $orderId;

        } catch (Exception $e) {
            $this->conn->rollback();
            throw $e;
        }
    }
}
