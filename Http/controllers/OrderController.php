<?php

namespace Http\Controllers;

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
            error_log("Construction error: " . $e->getMessage());
            throw $e;
        }
    }

    public function createOrder()
    {
        try {
            // Enable detailed error reporting
            ini_set('display_errors', 1);
            error_reporting(E_ALL);

            // Log the request method and headers
            error_log("Request Method: " . $_SERVER['REQUEST_METHOD']);
            error_log("Content Type: " . $_SERVER['CONTENT_TYPE'] ?? 'Not set');

            // Get and log raw input
            $rawInput = file_get_contents('php://input');
            error_log("Raw input: " . $rawInput);

            // Parse input data
            $postData = [];
            if (!empty($_POST)) {
                $postData = $_POST;
                error_log("Using POST data");
            } elseif (!empty($rawInput)) {
                $postData = json_decode($rawInput, true);
                error_log("Using raw JSON data");
            }

            error_log("Processed input data: " . print_r($postData, true));

            // Validate session
            $userId = $this->session->get('user_id');
            if (!$userId) {
                throw new Exception('User not authenticated');
            }
            error_log("User ID: " . $userId);

            // Validate required fields
            if (
                !isset($postData['full_name']) || !isset($postData['email']) ||
                !isset($postData['contact']) || !isset($postData['address']) ||
                !isset($postData['payment_method']) || !isset($postData['items'])
            ) {
                throw new Exception('Missing required fields');
            }

            // Parse items
            $items = is_string($postData['items']) ? json_decode($postData['items'], true) : $postData['items'];
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('Invalid items data: ' . json_last_error_msg());
            }
            error_log("Parsed items: " . print_r($items, true));

            // Calculate total amount
            $calculatedTotal = 0;
            foreach ($items as $item) {
                $calculatedTotal += $item['price'] * $item['quantity'];
            }
            error_log("Calculated total: " . $calculatedTotal);

            // Prepare order data
            $orderData = [
                'user_id' => $userId,
                'customer_name' => $postData['full_name'],
                'customer_email' => $postData['email'],
                'customer_contact' => $postData['contact'],
                'delivery_address' => $postData['address'],
                'total_amount' => $calculatedTotal,
                'status' => 'pending',
                'payment_method' => $postData['payment_method'],
                'gcash_ref' => $postData['gcash_ref'] ?? null,
                'gcash_phone' => $postData['gcash_phone'] ?? null
            ];
            error_log("Prepared order data: " . print_r($orderData, true));

            // Begin transaction
            $this->db->getConnection()->begin_transaction();
            error_log("Transaction started");

            try {
                // Create order
                $orderId = $this->orderModel->createOrder($orderData, $items);
                error_log("Order created with ID: " . $orderId);

                if (!$orderId) {
                    throw new Exception('Order creation failed');
                }

                // Clear cart
                $this->cartModel->clearCart($userId);
                error_log("Cart cleared for user: " . $userId);

                // Commit transaction
                $this->db->getConnection()->commit();
                error_log("Transaction committed");

                // Send success response
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'order_id' => $orderId,
                    'message' => 'Order created successfully'
                ]);
                exit;
            } catch (Exception $e) {
                // Rollback on error
                $this->db->getConnection()->rollback();
                error_log("Transaction rolled back due to error: " . $e->getMessage());
                throw $e;
            }
        } catch (Exception $e) {
            error_log("Order creation failed: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());

            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Order creation failed',
                'error' => $e->getMessage(),
                'debug_info' => [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ]
            ]);
            exit;
        }
    }

    public function confirmOrder()
    {
        include_once DIR . '/public/client/order-confirmation.php';
    }
}
