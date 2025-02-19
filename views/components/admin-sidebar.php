<div class="sidebar bg-dark text-white">
    <div class="sidebar-content">
        <div class="px-3 py-4">
            <h3 class="text-white mb-3 text-center">EcoMart</h3>
            <hr class="border-secondary">
        </div>

        <ul class="nav flex-column px-3">
            <li class="nav-item mb-2">
                <a href="/admin/dashboard" class="nav-link text-white <?php echo ($_SERVER['REQUEST_URI'] == '/admin/dashboard') ? 'active bg-primary' : ''; ?>">
                    <i class="bi bi-speedometer2 me-2"></i>
                    Dashboard
                </a>
            </li>
            <li class="nav-item mb-2">
                <a href="/admin/products/add" class="nav-link text-white <?php echo (strpos($_SERVER['REQUEST_URI'], '/admin/products/add') !== false) ? 'active bg-primary' : ''; ?>">
                    <i class="bi bi-plus-circle me-2"></i>
                    Add Product
                </a>
            </li>
            <li class="nav-item mb-2">
                <a href="/admin/orders" class="nav-link text-white <?php echo (strpos($_SERVER['REQUEST_URI'], '/admin/orders') !== false) ? 'active bg-primary' : ''; ?>">
                    <i class="bi bi-cart3 me-2"></i>
                    Orders History
                </a>
            </li>
        </ul>
    </div>
</div>