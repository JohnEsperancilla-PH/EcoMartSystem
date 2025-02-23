<?php

namespace Http\Controllers;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
            // Enable error reporting for debugging
            error_reporting(E_ALL);
            ini_set('display_errors', 1);

            // Log the incoming request
            error_log("Received order request: " . print_r($_POST, true));

            // Check authentication
            $userId = $this->session->get('user_id');
            if (!$userId) {
                throw new Exception('User not authenticated');
            }

            // Validate required fields
            if (
                empty($_POST['full_name']) || empty($_POST['email']) ||
                empty($_POST['contact']) || empty($_POST['address']) ||
                empty($_POST['payment_method']) || empty($_POST['items'])
            ) {
                throw new Exception('Missing required fields');
            }

            // Parse items from the form data
            $items = json_decode($_POST['items'], true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('Invalid items data');
            }

            // Calculate total
            $calculatedTotal = 0;
            foreach ($items as $item) {
                if (!isset($item['id'], $item['price'], $item['quantity'])) {
                    throw new Exception('Invalid item data structure');
                }
                $calculatedTotal += (float)$item['price'] * (int)$item['quantity'];
            }

            // Prepare order data
            $orderData = [
                'user_id' => $userId,
                'customer_name' => $_POST['full_name'],
                'customer_email' => $_POST['email'],
                'customer_contact' => $_POST['contact'],
                'delivery_address' => $_POST['address'],
                'total_amount' => $calculatedTotal,
                'status' => 'pending',
                'payment_method' => $_POST['payment_method'],
                'gcash_ref' => $_POST['gcash_ref'] ?? null,
                'gcash_phone' => $_POST['gcash_phone'] ?? null
            ];

            error_log("Processing order with data: " . print_r($orderData, true));

            // Begin transaction
            $this->db->getConnection()->begin_transaction();

            try {
                // Create order
                $orderId = $this->orderModel->createOrder($orderData, $items);

                // Clear cart
                $this->cartModel->clearCart($userId);

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
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }


    public function confirmOrder()
    {
        include_once DIR . '/public/client/order-confirmation.php';
    }
}
