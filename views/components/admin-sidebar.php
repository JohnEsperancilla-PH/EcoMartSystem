<div class="sidebar bg-dark text-white">
    <div class="sidebar-content">
        <div class="px-3 py-4">
            <a class="navbar-brand" href="/shop">
                <img src="..\images\ecomart-logo.png" alt="EcoMart Logo" height="40">
            </a>
            <hr class="border-secondary">
        </div>

        <ul class="nav flex-column px-3">
            <li class="nav-item mb-2">
                <a href="/dashboard" class="nav-link text-white <?php echo ($_SERVER['REQUEST_URI'] == '/dashboard') ? 'active bg-primary' : ''; ?>">
                    <i class="bi bi-speedometer2 me-2"></i>
                    Dashboard
                </a>
            </li>
            <li class="nav-item mb-2">
                <a href="/add-products" class="nav-link text-white <?php echo (strpos($_SERVER['REQUEST_URI'], '/add-products') !== false) ? 'active bg-primary' : ''; ?>">
                    <i class="bi bi-plus-circle me-2"></i>
                    Add Product
                </a>
            </li>
            <li class="nav-item mb-2">
                <a href="/order-history" class="nav-link text-white <?php echo (strpos($_SERVER['REQUEST_URI'], '/order-history') !== false) ? 'active bg-primary' : ''; ?>">
                    <i class="bi bi-cart3 me-2"></i>
                    Orders History
                </a>
            </li>
        </ul>
    </div>
</div>