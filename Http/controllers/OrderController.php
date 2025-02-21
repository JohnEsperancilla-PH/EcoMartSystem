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
                'user_id' => $this->session->get('user_id') ?? null,
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

            // Fetch cart items for the user
            $userId = $this->session->get('user_id');
            if (!$userId) {
                throw new Exception('User not authenticated');
            }

            $cartItems = $this->cartModel->getCartItems($userId);
            if (empty($cartItems)) {
                throw new Exception('Cart is empty');
            }

            // Calculate total amount
            $orderData['total_amount'] = array_reduce($cartItems, function($sum, $item) {
                return $sum + ($item['price_at_time'] * $item['quantity']);
            }, 0);

            // Begin transaction
            $this->db->begin_transaction();

            try {
                // Create order
                $orderId = $this->orderModel->createOrder($orderData, $cartItems);

                // Clear the cart after order is placed
                $cartId = $this->session->get('cart_id');
                $this->cartModel->clearCart($cartId);

                // Commit transaction
                $this->db->commit();

                // Return success response
                http_response_code(201);
                echo json_encode([
                    'success' => true,
                    'message' => 'Order created successfully',
                    'orderId' => $orderId
                ]);

            } catch (Exception $e) {
                // Rollback transaction on error
                $this->db->rollback();
                throw $e;
            }

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'error' => 'Failed to create order',
                'message' => $e->getMessage()
            ]);
        }
    }
}
