<?php

namespace Models;

use Exception;

class Orders
{
    private $conn;
    private $orderItems;

    public function __construct($db)
    {
        $this->conn = $db;
        $this->orderItems = new OrderItems($db);
    }

    public function createOrder($orderData, $items)
    {
        try {
            // Start transaction
            $this->conn->begin_transaction();

            // Insert into Orders table
            $query = "INSERT INTO Orders (
                user_id, customer_name, customer_email, customer_contact,
                delivery_address, total_amount, status, payment_method,
                gcash_ref, gcash_phone, order_date, updated_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";

            $stmt = $this->conn->prepare($query);
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $this->conn->error);
            }

            // Bind parameters with explicit type casting
            $stmt->bind_param(
                "issssdssss",
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

            if (!$stmt->execute()) {
                throw new Exception("Failed to insert order: " . $stmt->error);
            }

            $orderId = $this->conn->insert_id;
            $stmt->close();

            // Insert order items
            foreach ($items as $item) {
                $itemQuery = "INSERT INTO OrderItems (
                    order_id, product_id, quantity, price_at_time, created_at
                ) VALUES (?, ?, ?, ?, NOW())";

                $itemStmt = $this->conn->prepare($itemQuery);
                if (!$itemStmt) {
                    throw new Exception("Failed to prepare item insert: " . $this->conn->error);
                }

                // Ensure proper type casting
                $productId = (int)$item['id'];
                $quantity = (int)$item['quantity'];
                $price = (float)$item['price'];

                $itemStmt->bind_param("iiid", $orderId, $productId, $quantity, $price);

                if (!$itemStmt->execute()) {
                    throw new Exception("Failed to insert order item: " . $itemStmt->error);
                }

                $itemStmt->close();
            }

            // If we get here, commit the transaction
            $this->conn->commit();
            return $orderId;
        } catch (Exception $e) {
            // Rollback on any error
            if ($this->conn->connect_errno != 0) {
                $this->conn->rollback();
            }
            error_log("Order creation failed: " . $e->getMessage());
            throw $e;
        }
    }
}
