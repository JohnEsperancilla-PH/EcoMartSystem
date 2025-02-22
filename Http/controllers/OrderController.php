<?php
// Http/Controllers/OrderController.php

namespace Http\Controllers;

use Models\Orders;
use Models\OrderItems;
use Models\Cart;
use Core\Database;
use Core\Session;
use Exception;

class OrderController {
    private $orderModel;
    private $cartModel;
    private $db;
    private $session;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->orderModel = new Orders($this->db);
        $this->cartModel = new Cart($this->db);
        $this->session = new Session();
    }

    public function createOrder()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                http_response_code(405);
                echo json_encode(['error' => 'Method not allowed']);
                return;
            }

            // Get JSON data
            $data = json_decode(file_get_contents('php://input'), true);
            if (!$data) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid request data']);
                return;
            }

            // Validate required fields
            $required = ['customer', 'payment', 'items'];
            foreach ($required as $field) {
                if (!isset($data[$field])) {
                    http_response_code(400);
                    echo json_encode(['error' => "Missing required field: {$field}"]);
                    return;
                }
            }

            // Calculate total amount from items
            $totalAmount = array_reduce($data['items'], function ($sum, $item) {
                return $sum + ($item['price'] * ($item['quantity'] ?? 1));
            }, 0);

            // Create order data structure
            $orderData = [
                'user_id' => $this->session->get('user_id'),
                'customer_name' => $data['customer']['fullName'],
                'customer_email' => $data['customer']['email'],
                'customer_contact' => $data['customer']['contact'],
                'delivery_address' => $data['customer']['address'],
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'payment_method' => $data['payment']['method'],
                'gcash_ref' => $data['payment']['gcashRef'],
                'gcash_phone' => $data['payment']['gcashPhone']
            ];

            // Create the order
            $orderId = $this->orderModel->createOrder($orderData, $data['items']);

            // Return success response
            http_response_code(201);
            echo json_encode([
                'success' => true,
                'message' => 'Order created successfully',
                'orderId' => $orderId
            ]);
        } catch (Exception $e) {
            error_log('Order creation error: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'error' => 'Failed to create order',
                'message' => $e->getMessage()
            ]);
        }
    }
}
