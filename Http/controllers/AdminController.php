<?php

class AdminController
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function dashboard()
    {
        $session = new \Core\Session();
        if (!$session->get('authenticated') || !$session->get('user_id') || $session->get('user_role') !== 'admin') {
            header('Location: /login');
            exit();
        }

        // Get all required dashboard data
        $categoryStats = $this->getCategoryStats();
        $products = $this->getProducts();
        $totalRevenue = $this->getTotalRevenue();
        $totalOrdersCount = $this->getTotalOrders();
        $totalCustomers = $this->getTotalCustomers();

        // Pass data to the view
        include_once DIR . '/views/admin/dashboard.view.php';
    }

    public function addProducts()
    {
        $session = new \Core\Session();
        if (!$session->get('authenticated') || !$session->get('user_id') || $session->get('user_role') !== 'admin') {
            header('Location: /login');
            exit();
        }

        // Get categories for the dropdown
        $categories = $this->getAllCategories();

        include_once DIR . '/views/admin/add-products.view.php';
    }

    public function orders()
    {
        $session = new \Core\Session();
        if (!$session->get('authenticated') || !$session->get('user_id') || $session->get('user_role') !== 'admin') {
            header('Location: /login');
            exit();
        }

        // Get all orders with customer details
        $orders = $this->getAllOrders();

        include_once DIR . '/views/admin/orders-history.view.php';
    }

    private function getCategoryStats()
    {
        $query = "SELECT 
            c.name as category_name,
            COUNT(p.product_id) as total_products
        FROM Categories c
        LEFT JOIN Products p ON c.category_id = c.category_id
        GROUP BY c.category_id, c.name
        ORDER BY c.name ASC";

        $result = $this->db->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    private function getProducts()
    {
        $query = "SELECT 
            p.*, 
            c.name as category_name
        FROM Products p
        JOIN Categories c ON p.category_id = c.category_id
        ORDER BY p.created_at DESC";

        $result = $this->db->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    private function getTotalRevenue()
    {
        $query = "SELECT COALESCE(SUM(total_amount), 0) as total_revenue FROM Orders";
        $result = $this->db->query($query);
        $row = $result->fetch_assoc();
        return $row['total_revenue'];
    }

    private function getTotalOrders()
    {
        $query = "SELECT COUNT(*) as total_orders FROM Orders";
        $result = $this->db->query($query);
        $row = $result->fetch_assoc();
        return $row['total_orders'];
    }

    private function getTotalCustomers()
    {
        $query = "SELECT COUNT(*) as total_customers FROM Users WHERE role = 'customer'";
        $result = $this->db->query($query);
        $row = $result->fetch_assoc();
        return $row['total_customers'];
    }

    private function getAllCategories()
    {
        $query = "SELECT category_id, name FROM Categories ORDER BY name ASC";
        $result = $this->db->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    private function getAllOrders()
    {
        $query = "SELECT 
            o.*,
            u.email as customer_email,
            CONCAT(up.first_name, ' ', up.last_name) as customer_name
        FROM Orders o
        JOIN Users u ON o.user_id = u.user_id
        LEFT JOIN user_profiles up ON u.user_id = up.user_id
        ORDER BY o.order_date DESC";

        $result = $this->db->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Add new product
    public function saveProduct()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/products');
            exit();
        }

        $name = $_POST['name'] ?? '';
        $price = $_POST['price'] ?? 0;
        $categoryId = $_POST['category_id'] ?? 0;
        $imageUrl = ''; // Handle image upload here

        // Handle image upload if file was submitted
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imageUrl = $this->handleImageUpload($_FILES['image']);
        }

        $stmt = $this->db->prepare("
            INSERT INTO Products (name, price, category_id, image_url, created_at, updated_at)
            VALUES (?, ?, ?, ?, NOW(), NOW())
        ");

        $stmt->bind_param("sdss", $name, $price, $categoryId, $imageUrl);

        if ($stmt->execute()) {
            $_SESSION['success'] = 'Product added successfully';
        } else {
            $_SESSION['error'] = 'Failed to add product';
        }

        header('Location: /admin/products');
        exit();
    }

    // Delete product
    public function deleteProduct($productId)
    {
        $stmt = $this->db->prepare("DELETE FROM Products WHERE product_id = ?");
        $stmt->bind_param("i", $productId);

        if ($stmt->execute()) {
            $_SESSION['success'] = 'Product deleted successfully';
        } else {
            $_SESSION['error'] = 'Failed to delete product';
        }

        header('Location: /admin/products');
        exit();
    }

    private function handleImageUpload($file)
    {
        $targetDir = DIR . "/public/uploads/products/";
        $fileName = uniqid() . "_" . basename($file['name']);
        $targetFile = $targetDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            return "/uploads/products/" . $fileName;
        }

        return '';
    }
}
