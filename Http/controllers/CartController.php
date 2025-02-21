<?php
// Http/Controllers/CartController.php

namespace Http\Controllers;

use Models\Cart;
use Core\Database;
use Core\Session;
use Exception;

class CartController {
    private $cartModel;
    private $session;

    public function __construct() {
        $database = new Database();
        $this->cartModel = new Cart($database->getConnection());
        $this->session = new Session();
    }

    public function addToCart() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $this->session->get('user_id');
            $productId = $_POST['product_id'];
            $quantity = 1; // Default quantity

            try {
                if ($this->cartModel->addToCart($userId, $productId, $quantity)) {
                    echo json_encode(['success' => true]);
                } else {
                    throw new Exception('Failed to add product to cart');
                }
            } catch (Exception $e) {
                error_log('Error in addToCart: ' . $e->getMessage());
                error_log('Trace: ' . $e->getTraceAsString());
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
        } else {
            error_log('Invalid request method for addToCart');
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
        }
    }

    public function getCartItems() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $userId = $this->session->get('user_id');
            $items = $this->cartModel->getCartItems($userId);
            echo json_encode(['success' => true, 'items' => $items]);
        }
    }

    public function removeFromCart() {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                http_response_code(405);
                echo json_encode(['error' => 'Method not allowed']);
                return;
            }

            $data = json_decode(file_get_contents('php://input'), true);
            if (!$data) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid request data']);
                return;
            }

            $userId = $this->session->get('user_id');
            if (!$userId) {
                throw new Exception('User not authenticated');
            }

            $productId = $data['productId'];

            $this->cartModel->removeFromCart($userId, $productId);

            http_response_code(200);
            echo json_encode(['success' => true, 'message' => 'Product removed from cart']);
        } catch (Exception $e) {
            error_log($e->getMessage()); // Log the error message
            http_response_code(500);
            echo json_encode(['error' => 'Failed to remove product from cart', 'message' => $e->getMessage() ]);
        }
    }

    public function clearCart() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cartId = $this->session->get('cart_id');
            if ($this->cartModel->clearCart($cartId)) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false]);
            }
        }
    }

    public function getCartCount() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $userId = $this->session->get('user_id');
            $count = $this->cartModel->getCartCount($userId);
            echo json_encode(['count' => $count]);
        }
    }

    public function syncCart() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $this->session->get('user_id');
            $cartData = json_decode($_POST['cart'], true);

            try {
                foreach ($cartData as $item) {
                    $this->cartModel->addToCart($userId, $item['productId'], $item['quantity']);
                }
                echo json_encode(['success' => true]);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
        }
    }

    public function addToCartItems() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $this->session->get('user_id');
            $productId = $_POST['product_id'];
            $quantity = $_POST['quantity'];

            try {
                if ($this->cartModel->addToCartItems($userId, $productId, $quantity)) {
                    echo json_encode(['success' => true]);
                } else {
                    throw new Exception('Failed to add product to cart items');
                }
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
        }
    }
}
