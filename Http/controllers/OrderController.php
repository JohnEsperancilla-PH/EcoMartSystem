<?php
// Controllers/OrderController.php

class OrderController {
    private $orderModel;
    
    public function __construct() {
        $this->orderModel = new Orders();
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

            // Calculate total amount
            $totalAmount = array_reduce($data['items'], function($sum, $item) {
                return $sum + ($item['price'] * $item['quantity']);
            }, 0);

            // Create order
            $orderId = $this->orderModel->createOrder(
                $data['customer'],
                $data['items'],
                $totalAmount
            );

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

    public function confirmOrder($orderId) {
        try {
            $order = $this->orderModel->getOrderById($orderId);
            
            if (!$order) {
                throw new Exception('Order not found');
            }

            require_once __DIR__ . '/../views/client/order-confirmation.view.php';

        } catch (Exception $e) {
            // Redirect to error page
            header('Location: /error?message=' . urlencode($e->getMessage()));
            exit;
        }
    }
}