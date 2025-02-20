<?php
// Http/Controllers/OrderController.php

require_once __DIR__ . '/../../Models/Orders.php';
require_once __DIR__ . '/../../Models/OrderItems.php';
require_once __DIR__ . '/../../Core/Database.php';

class OrderController {
    private $orderModel;
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->orderModel = new Orders($this->db);
    }

    public function createOrder() {
        try {
            // Validate request
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                http_response_code(405);
                echo json_encode(['error' => 'Method not allowed']);
                return;
            }

            // Get JSON data from request body
            $data = json_decode(file_get_contents('php://input'), true);

            if (!$data) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid request data']);
                return;
            }

            // Prepare customer data
            $customerData = $data['customer'];
            
            // Create the order data structure
            $orderData = [
                'user_id' => null, // Default to null for guest orders
                'customer_name' => $customerData['fullName'],
                'customer_email' => $customerData['email'],
                'customer_contact' => $customerData['contact'],
                'delivery_address' => $customerData['address'],
                'total_amount' => 0,
                'status' => 'pending',
                'payment_method' => $data['payment']['method'],
                'gcash_ref' => $data['payment']['gcashRef'] ?? null,
                'gcash_phone' => $data['payment']['gcashPhone'] ?? null
            ];

            // Calculate total amount
            $orderData['total_amount'] = array_reduce($data['items'], function($sum, $item) {
                return $sum + ($item['price'] * $item['quantity']);
            }, 0);

            // Create order
            $orderId = $this->orderModel->createOrder($orderData, $data['items']);

            // Return success response
            http_response_code(201);
            echo json_encode([
                'success' => true,
                'message' => 'Order created successfully',
                'orderId' => $orderId
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'error' => 'Failed to create order',
                'message' => $e->getMessage()
            ]);
        }
    }
}
