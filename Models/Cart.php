<?php

class Cart {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function addToCart($userId, $productId, $quantity = 1) {
        // First, check if user has an active cart
        $cartId = $this->getOrCreateCart($userId);

        // Check if product already exists in cart
        $stmt = $this->conn->prepare('
            SELECT cart_item_id, quantity 
            FROM CartItems 
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
                UPDATE CartItems 
                SET quantity = ?, updated_at = NOW() 
                WHERE cart_item_id = ?
            ');
            $updateStmt->bind_param('ii', $newQuantity, $item['cart_item_id']);
            return $updateStmt->execute();
        } else {
            // Add new cart item
            $stmt = $this->conn->prepare('
                INSERT INTO CartItems (cart_id, product_id, quantity, price_at_time, added_at) 
                VALUES (?, ?, ?, (SELECT price FROM Products WHERE product_id = ?), NOW())
            ');
            $stmt->bind_param('iiii', $cartId, $productId, $quantity, $productId);
            return $stmt->execute();
        }
    }

    private function getOrCreateCart($userId) {
        // Check for existing active cart
        $stmt = $this->conn->prepare('
            SELECT cart_id FROM Cart 
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
            INSERT INTO Cart (user_id, created_at, updated_at, status) 
            VALUES (?, NOW(), NOW(), "active")
        ');
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        return $this->conn->insert_id;
    }

    public function getCartItems($userId) {
        $stmt = $this->conn->prepare('
            SELECT p.name, ci.quantity, ci.price_at_time, (ci.quantity * ci.price_at_time) as total
            FROM Cart c
            JOIN CartItems ci ON c.cart_id = ci.cart_id
            JOIN Products p ON ci.product_id = p.product_id
            WHERE c.user_id = ? AND c.status = "active"
        ');
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}