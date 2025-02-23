<?php

namespace Models;

use Models\OrderItems;
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
            error_log("Starting transaction for order creation");
            $this->conn->begin_transaction();

            // Log the SQL query
            $query = "
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
            ";
            error_log("Order insert query: " . $query);
            error_log("Order data: " . print_r($orderData, true));

            // Prepare statement with error checking
            $stmt = $this->conn->prepare($query);
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $this->conn->error);
            }

            // Bind parameters with type checking
            $bindResult = $stmt->bind_param(
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

            if (!$bindResult) {
                throw new Exception("Bind failed: " . $stmt->error);
            }

            // Execute with error checking
            if (!$stmt->execute()) {
                throw new Exception("Execute failed: " . $stmt->error);
            }

            $orderId = $this->conn->insert_id;
            error_log("Created order with ID: " . $orderId);
            $stmt->close();

            // Insert order items
            error_log("Starting order items insertion");
            foreach ($items as $item) {
                error_log("Processing item: " . print_r($item, true));

                $itemQuery = "
                    INSERT INTO OrderItems (
                        order_id,
                        product_id,
                        quantity,
                        price_at_time,
                        created_at
                    ) VALUES (?, ?, ?, ?, NOW())
                ";

                $itemStmt = $this->conn->prepare($itemQuery);
                if (!$itemStmt) {
                    throw new Exception("Item prepare failed: " . $this->conn->error);
                }

                if (!$itemStmt->bind_param("iiid", $orderId, $item['id'], $item['quantity'], $item['price'])) {
                    throw new Exception("Item bind failed: " . $itemStmt->error);
                }

                if (!$itemStmt->execute()) {
                    throw new Exception("Item execute failed: " . $itemStmt->error);
                }

                $itemStmt->close();
                error_log("Successfully inserted item for order ID: " . $orderId);
            }

            error_log("All items processed, committing transaction");
            $this->conn->commit();
            return $orderId;
        } catch (Exception $e) {
            error_log("Error in createOrder: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());

            try {
                $this->conn->rollback();
                error_log("Transaction rolled back");
            } catch (Exception $rollbackError) {
                error_log("Rollback failed: " . $rollbackError->getMessage());
            }

            throw $e;
        }
    }
}
