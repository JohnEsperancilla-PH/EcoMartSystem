<?php

namespace Http\controllers;

use Models\Orders;
use Models\Cart;
use Core\Database;
use Core\Session;
use Exception;

class OrderController
{
    private $orderModel;
    private $cartModel;
    private $session;
    private $db;

    public function __construct()
    {
        try {
            $this->db = new Database();
            $this->orderModel = new Orders($this->db->getConnection());
            $this->cartModel = new Cart($this->db->getConnection());
            $this->session = new Session();
        } catch (Exception $e) {
            error_log("Construction error in OrderController: " . $e->getMessage());
            throw $e;
        }
    }

    public function createOrder()
    {
        try {
            // Get user ID from session
            $userId = $this->session->get('user_id');
            if (!$userId) {
                throw new Exception('User not authenticated');
            }

            // Get raw POST data
            $rawData = file_get_contents('php://input');
            error_log("Raw POST data: " . $rawData);  // Debug log

            // Check if we have regular POST data
            if (empty($rawData) && !empty($_POST)) {
                $data = $_POST;
                $items = json_decode($data['items'] ?? '', true);
            } else {
                // Try to decode JSON data
                $data = json_decode($rawData, true);
                $items = $data['items'] ?? [];
            }

            // Validate data
            if (empty($data)) {
                throw new Exception('No data received');
            }

            error_log("Processed data: " . print_r($data, true));  // Debug log

            // Prepare order data
            $orderData = [
                'user_id' => $userId,
                'customer_name' => $data['full_name'] ?? '',
                'customer_email' => $data['email'] ?? '',
                'customer_contact' => $data['contact'] ?? '',
                'delivery_address' => $data['address'] ?? '',
                'total_amount' => 0, // Will be calculated from items
                'status' => 'pending',
                'payment_method' => $data['payment_method'] ?? 'cash',
                'gcash_ref' => $data['gcash_ref'] ?? null,
                'gcash_phone' => $data['gcash_phone'] ?? null,
                'maya_ref' => $data['maya_ref'] ?? null,
                'maya_phone' => $data['maya_phone'] ?? null,
            ];

            // Validate items
            if (empty($items)) {
                throw new Exception('No items in order');
            }

            // Calculate total
            $total = 0;
            foreach ($items as $item) {
                $total += (float)$item['price'] * (int)$item['quantity'];
            }
            $orderData['total_amount'] = $total;

            // Begin transaction
            $this->db->getConnection()->begin_transaction();

            try {
                // Create the order
                $orderId = $this->orderModel->createOrder($orderData, $items);

                // Clear the cart
                $cartId = $this->cartModel->getOrCreateCart($userId);
                $this->cartModel->clearCart($cartId);

                // Commit transaction
                $this->db->getConnection()->commit();

                // Send success response
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'order_id' => $orderId,
                    'message' => 'Order created successfully'
                ]);
            } catch (Exception $e) {
                $this->db->getConnection()->rollback();
                throw $e;
            }
        } catch (Exception $e) {
            error_log("Order creation error: " . $e->getMessage());

            header('Content-Type: application/json');
            http_response_code(500);
            
            $response = [
                'success' => false,
                'message' => isset($e) ? $e->getMessage() : 'Unknown error occurred'
            ];
            
            // Ensure proper JSON output
            echo json_encode($response);
            exit;             
        }
    }
}
