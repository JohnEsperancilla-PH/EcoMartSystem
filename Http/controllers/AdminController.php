<?php

use Core\Database;

class AdminController
{
    private $db;
    private $itemsPerPage = 10;

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

        $categoryFilter = $_GET['category'] ?? '';

        // Get all required dashboard data
        $categoryStats = $this->getCategoryStats();
        $products = $this->getProducts(1, ['category' => $categoryFilter]);
        $totalRevenue = $this->getTotalRevenue();
        $totalOrdersCount = $this->getTotalOrders();
        $totalCustomers = $this->getTotalCustomers();
        $totalProducts = $this->getTotalProductCount();

        include_once DIR . '/views/admin/dashboard.view.php';
    }

    public function addProducts()
    {
        $session = new \Core\Session();
        if (!$session->get('authenticated') || !$session->get('user_id') || $session->get('user_role') !== 'admin') {
            header('Location: /login');
            exit();
        }

        // Get current page from URL parameter
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        // Get filters from URL parameters
        $filters = [
            'category' => $_GET['category'] ?? '',
            'stock_status' => $_GET['stock_status'] ?? '',
            'search' => $_GET['search'] ?? ''
        ];

        // Get categories for the dropdown
        $categories = $this->getAllCategories();

        // Get paginated products
        $products = $this->getProducts($currentPage, $filters);

        // Get total products count for pagination
        $totalProducts = $this->getTotalProducts($filters);
        $totalPages = ceil($totalProducts / $this->itemsPerPage);

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
        c.category_id,
        c.name as category_name,
        COUNT(p.product_id) as total_products
        FROM Categories c
        LEFT JOIN Products p ON c.category_id = p.category_id
        GROUP BY c.category_id, c.name
        ORDER BY c.name ASC";

        $result = $this->db->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    private function getProducts($page = 1, $filters = [])
    {
        $offset = ($page - 1) * $this->itemsPerPage;

        // Base query
        $query = "SELECT 
        p.*, 
        c.name as category_name
        FROM Products p
        JOIN Categories c ON p.category_id = c.category_id
        WHERE 1=1";

        $params = [];
        $types = "";

        // Add category filter
        if (!empty($filters['category'])) {
            $query .= " AND c.name = ?";
            $params[] = $filters['category'];
            $types .= "s";
        }

        // Add stock status filter
        if (!empty($filters['stock_status'])) {
            switch ($filters['stock_status']) {
                case 'out_of_stock':
                    $query .= " AND p.stock_quantity <= 0";
                    break;
                case 'low_stock':
                    $query .= " AND p.stock_quantity > 0 AND p.stock_quantity <= 10";
                    break;
                case 'in_stock':
                    $query .= " AND p.stock_quantity > 10";
                    break;
            }
        }

        // Add search filter
        if (!empty($filters['search'])) {
            $query .= " AND (p.name LIKE ? OR p.description LIKE ?)";
            $searchTerm = "%" . $filters['search'] . "%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $types .= "ss";
        }

        // Add pagination
        $query .= " ORDER BY p.created_at DESC LIMIT ?, ?";
        $params[] = $offset;
        $params[] = $this->itemsPerPage;
        $types .= "ii";

        // Prepare and execute the statement
        $stmt = $this->db->prepare($query);

        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $products = $result->fetch_all(MYSQLI_ASSOC);

        // If no products found, set a flash message
        if (empty($products) && !empty($filters['category'])) {
            $_SESSION['info'] = "No products found in category '" . htmlspecialchars($filters['category']) . "'";
        }

        return $products;
    }

    private function getTotalProductCount()
    {
        $query = "SELECT COUNT(*) as total FROM Products";
        $result = $this->db->query($query);
        $row = $result->fetch_assoc();
        return $row['total'];
    }

    private function getTotalProducts($filters = [])
    {
        $query = "SELECT COUNT(*) as total FROM Products p WHERE 1=1";

        if (!empty($filters['category'])) {
            $query .= " AND p.category_id = " . intval($filters['category']);
        }

        if (!empty($filters['stock_status'])) {
            switch ($filters['stock_status']) {
                case 'out_of_stock':
                    $query .= " AND p.stock_quantity <= 0";
                    break;
                case 'low_stock':
                    $query .= " AND p.stock_quantity > 0 AND p.stock_quantity >= 10";
                    break;
                case 'in_stock':
                    $query .= " AND p.stock_quantity > 10";
                    break;
            }
        }

        if (!empty($filters['search'])) {
            $search = $this->db->real_escape_string($filters['search']);
            $query .= " AND (p.name LIKE '%$search%' OR p.description LIKE '%$search%')";
        }

        $result = $this->db->query($query);
        $row = $result->fetch_assoc();
        return $row['total'];
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
        $session = new \Core\Session();
        if (!$session->get('authenticated') || !$session->get('user_id') || $session->get('user_role') !== 'admin') {
            header('Location: /login');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/products');
            exit();
        }

        $name = $_POST['name'] ?? '';
        $price = $_POST['price'] ?? 0;
        $categoryId = $_POST['category_id'] ?? 0;
        $stockQuantity = $_POST['stock_quantity'] ?? 0;
        $imageUrl = '';

        // Handle image upload if file was submitted
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imageUrl = $this->handleImageUpload($_FILES['image']);
        }

        $stmt = $this->db->prepare("
        INSERT INTO Products (name, price, category_id, image_url, stock_quantity, created_at, updated_at)
        VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())
    ");

        $stmt->bind_param("sdisss", $name, $price, $categoryId, $imageUrl, $stockQuantity);

        if ($stmt->execute()) {
            $_SESSION['success'] = 'Product added successfully';
        } else {
            $_SESSION['error'] = 'Failed to add product';
        }

        header('Location: /admin/products');
        exit();
    }

    public function createProduct()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /add-products');
            exit();
        }

        $name = $_POST['name'] ?? '';
        $categoryId = $_POST['category_id'] ?? '';
        $price = $_POST['price'] ?? '';
        $stockQuantity = $_POST['stock_quantity'] ?? '';

        // Handle file upload
        $imageUrl = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imageUrl = $this->handleImageUpload($_FILES['image']);
            if (empty($imageUrl)) {
                header('Location: /add-products');
                exit();
            }
        }

        $stmt = $this->db->prepare("INSERT INTO products (name, category_id, price, stock_quantity, image_url) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sidis", $name, $categoryId, $price, $stockQuantity, $imageUrl);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Product added successfully!";
        } else {
            $_SESSION['error'] = "Error adding product: " . $stmt->error;
        }

        header('Location: /add-products');
        exit();
    }

    public function updateProduct()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /add-products');
            exit();
        }

        $productId = $_POST['product_id'] ?? '';
        $name = $_POST['name'] ?? '';
        $categoryId = $_POST['category_id'] ?? '';
        $price = $_POST['price'] ?? '';
        $stockQuantity = $_POST['stock_quantity'] ?? '';

        $query = "UPDATE products SET name = ?, category_id = ?, price = ?, stock_quantity = ?";
        $params = [$name, $categoryId, $price, $stockQuantity];
        $types = "sidi";

        // Handle new image upload 
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imageUrl = $this->handleImageUpload($_FILES['image']);
            if (!empty($imageUrl)) {
                $query .= ", image_url = ?";
                $params[] = $imageUrl;
                $types .= "s";
            }
        }

        $query .= " WHERE product_id = ?";
        $params[] = $productId;
        $types .= "i";

        $stmt = $this->db->prepare($query);

        if ($stmt === false) {
            $_SESSION['error'] = "Error preparing statement: " . $this->db->error;
            header('Location: /add-products');
            exit();
        }

        $stmt->bind_param($types, ...$params);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Product updated successfully!";
        } else {
            $_SESSION['error'] = "Error updating product: " . $stmt->error;
        }

        header('Location: /add-products');
        exit();
    }

    public function deleteProduct()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /add-products');
            exit();
        }

        $productId = $_POST['product_id'] ?? '';

        $stmt = $this->db->prepare("DELETE FROM products WHERE product_id = ?");
        $stmt->bind_param("i", $productId);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Product deleted successfully!";
        } else {
            $_SESSION['error'] = "Error deleting product: " . $stmt->error;
        }

        header('Location: /add-products');
        exit();
    }

    private function handleImageUpload($file)
    {
        $basePublicDir = DIR . "/public";
        $uploadDir = "/images/products/";
        $targetDir = $basePublicDir . $uploadDir;

        // Create directory if it doesn't exist
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $fileName = uniqid() . "_" . basename($file['name']);
        $targetFile = $targetDir . $fileName;

        // Only allow certain file formats
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($file['type'], $allowedTypes)) {
            $_SESSION['error'] = 'Invalid file type. Only JPG, PNG and GIF are allowed.';
            return '';
        }

        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            return "/images/products/" . $fileName; // Updated return path
        }

        $_SESSION['error'] = 'Failed to upload file. Please check directory permissions.';
        return '';
    }
}
