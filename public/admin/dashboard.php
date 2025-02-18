<?php

// require_once DIR . '/Core/Session.php';
// require_once DIR . '/Core/Database.php';

// $database = new Database();
// $conn = $database->getConnection();

// Fetch all orders
// $ordersQuery = "SELECT o.order_id, u.email, o.total_amount, o.status, o.order_date 
//                 FROM Orders o 
//                 JOIN Users u ON o.user_id = u.user_id 
//                 ORDER BY o.order_date DESC";
// $ordersResult = $conn->query($ordersQuery);
// $recentOrders = [];

// if ($ordersResult && $ordersResult->num_rows > 0) {
//     while ($row = $ordersResult->fetch_assoc()) {
//         $recentOrders[] = $row;
//     }
// }

// Fetch all products
// $productsQuery = "SELECT p.product_id, p.name, p.price, p.stock_quantity, c.name as category_name
//                  FROM Products p
//                  LEFT JOIN Categories c ON p.category_id = c.category_id
//                  ORDER BY p.created_at DESC";
// $productsResult = $conn->query($productsQuery);
// $productsList = [];

// if ($productsResult && $productsResult->num_rows > 0) {
//     while ($row = $productsResult->fetch_assoc()) {
//         $productsList[] = $row;
//     }
// }

// // Get order and product counts for summary cards
// $totalOrdersCount = count($recentOrders);
// $totalProductsCount = count($productsList);

// // Close the database connection
// $database->close();

require_once DIR . '/views/admin/dashboard.view.php';