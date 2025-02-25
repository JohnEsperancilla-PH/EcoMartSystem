<?php

namespace Models;

use Exception;

class Orders
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function createOrder($orderData, $items)
    {
        try {
            error_log("Starting order creation in model");
            error_log("Order data: " . print_r($orderData, true));
            error_log("Items: " . print_r($items, true));

            // Insert into Orders table
            $query = "INSERT INTO Orders (
                user_id, customer_name, customer_email, customer_contact,
                delivery_address, total_amount, status, payment_method,
                gcash_ref, gcash_phone, maya_ref, maya_phone, order_date, updated_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";                   

            $stmt = $this->conn->prepare($query);
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $this->conn->error);
            }

            error_log("Prepared statement for Orders table");

            $stmt->bind_param(
                "issssdssssss",
                $orderData['user_id'],
                $orderData['customer_name'],
                $orderData['customer_email'],
                $orderData['customer_contact'],
                $orderData['delivery_address'],
                $orderData['total_amount'],
                $orderData['status'],
                $orderData['payment_method'],
                $orderData['gcash_ref'],
                $orderData['gcash_phone'],
                $orderData['maya_ref'],
                $orderData['maya_phone']
            );

            if (!$stmt->execute()) {
                throw new Exception("Failed to insert order: " . $stmt->error);
            }

            $orderId = $this->conn->insert_id;
            error_log("Order inserted with ID: " . $orderId);
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

                $productId = (int)$item['id'];
                $quantity = (int)$item['quantity'];
                $price = (float)$item['price'];

                error_log("Inserting order item - Product ID: $productId, Quantity: $quantity, Price: $price");

                $itemStmt->bind_param("iiid", $orderId, $productId, $quantity, $price);

                if (!$itemStmt->execute()) {
                    throw new Exception("Failed to insert order item: " . $itemStmt->error);
                }

                $itemStmt->close();
            }

            error_log("All order items inserted successfully");
            return $orderId;
        } catch (Exception $e) {
            error_log("Error in createOrder: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            throw $e;
        }
    }
}
