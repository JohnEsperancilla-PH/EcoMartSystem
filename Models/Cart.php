<?php

namespace Models;

class Cart {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function addToCart($userId, $productId, $quantity = 1) {
        try {
            // First, check if user has an active cart
            $cartId = $this->getOrCreateCart($userId);

            // Check if product already exists in cart
            $stmt = $this->conn->prepare('
                SELECT cart_item_id, quantity 
                FROM cartitems 
                WHERE cart_id = ? AND product_id = ?
            ');
            $stmt->bind_param('ii', $cartId, $productId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Update existing cart item
                $item = $result->fetch_assoc();
                $newQuantity = $item['quantity'] + $quantity;
                
                $updateStmt = $this->conn->prepare('
                    UPDATE cartitems 
                    SET quantity = ?, updated_at = NOW() 
                    WHERE cart_item_id = ?
                ');
                $updateStmt->bind_param('ii', $newQuantity, $item['cart_item_id']);
                return $updateStmt->execute();
            } else {
                // Add new cart item
                $stmt = $this->conn->prepare('
                    INSERT INTO cartitems (cart_id, product_id, quantity, price_at_time, added_at) 
                    VALUES (?, ?, ?, (SELECT price FROM products WHERE product_id = ?), NOW())
                ');
                $stmt->bind_param('iiii', $cartId, $productId, $quantity, $productId);
                return $stmt->execute();
            }
        } catch (\Exception $e) {
            error_log('Error in addToCart: ' . $e->getMessage());
            error_log('Trace: ' . $e->getTraceAsString());
            throw $e;
        }
    }

    public function submitCartItems($userId, $cartItems) {
        $cartId = $this->getOrCreateCart($userId);
        
        // Begin transaction
        $this->conn->begin_transaction();
        
        try {
            // Clear existing cart items
            $this->clearCartItems($cartId);
            
            // Add new items from form submission
            foreach ($cartItems as $item) {
                $productId = $item['product_id'];
                $quantity = $item['quantity'];
                
                $stmt = $this->conn->prepare('
                    INSERT INTO CartItems (cart_id, product_id, quantity, price_at_time, added_at) 
                    VALUES (?, ?, ?, (SELECT price FROM Products WHERE product_id = ?), NOW())
                ');
                $stmt->bind_param('iiii', $cartId, $productId, $quantity, $productId);
                $stmt->execute();
            }
            
            // Commit transaction
            $this->conn->commit();
            return true;
        } catch (\Exception $e) {
            // Rollback transaction on error
            $this->conn->rollback();
            throw $e;
        }
    }

    public function getOrCreateCart($userId) {
        // Check for existing active cart
        $stmt = $this->conn->prepare('
            SELECT cart_id FROM cart 
            WHERE user_id = ? AND status = "active"
        ');
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $cart = $result->fetch_assoc();
            return $cart['cart_id'];
        }

        // Create new cart
        $stmt = $this->conn->prepare('
            INSERT INTO cart (user_id, created_at, updated_at, status) 
            VALUES (?, NOW(), NOW(), "active")
        ');
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        return $this->conn->insert_id;
    }

    public function getCartItems($userId) {
        $stmt = $this->conn->prepare('
            SELECT p.name, ci.quantity, ci.price_at_time, (ci.quantity * ci.price_at_time) as total
            FROM cart c
            JOIN cartitems ci ON c.cart_id = ci.cart_id
            JOIN products p ON ci.product_id = p.product_id
            WHERE c.user_id = ? AND c.status = "active"
        ');
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function clearCart($cartId) {
        // Update cart status to 'completed'
        $stmt = $this->conn->prepare('
            UPDATE cart 
            SET status = "completed", updated_at = NOW()
            WHERE cart_id = ?
        ');
        $stmt->bind_param('i', $cartId);
        return $stmt->execute();
    }

    private function clearCartItems($cartId) {
        // Delete all items from cart
        $stmt = $this->conn->prepare('
            DELETE FROM cartitems 
            WHERE cart_id = ?
        ');
        $stmt->bind_param('i', $cartId);
        return $stmt->execute();
    }

    public function getCartCount($userId) {
        $stmt = $this->conn->prepare('
            SELECT SUM(quantity) as count
            FROM cartitems ci
            JOIN cart c ON ci.cart_id = c.cart_id
            WHERE c.user_id = ? AND c.status = "active"
        ');
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['count'] ?? 0;
    }

    public function removeFromCart($userId, $productId) {
        $cartId = $this->getOrCreateCart($userId);
        $stmt = $this->conn->prepare('
            DELETE FROM cartitems
            WHERE cart_id = ? AND product_id = ?
        ');
        $stmt->bind_param('ii', $cartId, $productId);
        return $stmt->execute();
    }
}
