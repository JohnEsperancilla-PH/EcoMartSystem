<?php include_once __DIR__ . '/../components/admin-header.php'; ?>

<div class="d-flex">
    <?php require_once __DIR__ . '/../components/admin-sidebar.php'; ?>

    <div class="main-content bg-light">
        <div class="container-fluid px-4 py-5">
            <!-- Main Stats -->
            <div class="row g-4 mb-4">
                <div class="col-12 col-md-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-primary bg-opacity-10 p-3 rounded">
                                        <i class="bi bi-box-seam text-primary fs-4"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="text-muted mb-1">Total Products</p>
                                    <h3 class="mb-0"><?php echo $totalProducts; ?></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-success bg-opacity-10 p-3 rounded">
                                        <i class="bi bi-currency-dollar text-success fs-4"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="text-muted mb-1">Total Revenue</p>
                                    <h3 class="mb-0">₱<?php echo number_format($totalRevenue, 2); ?></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-info bg-opacity-10 p-3 rounded">
                                        <i class="bi bi-bag-check text-info fs-4"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="text-muted mb-1">Total Orders</p>
                                    <h3 class="mb-0"><?php echo $totalOrdersCount; ?></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-warning bg-opacity-10 p-3 rounded">
                                        <i class="bi bi-people text-warning fs-4"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="text-muted mb-1">Total Customers</p>
                                    <h3 class="mb-0"><?php echo $totalCustomers; ?></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Category Overview -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-grid me-2"></i>
                            Category Overview
                        </h5>
                        <a href="/add-products" class="btn btn-sm btn-outline-primary">
                            Manage Categories
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <!-- All Products Card -->
                        <div class="col-12 col-md-6 col-xl-3">
                            <div class="card h-100 border-primary border-opacity-25">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="flex-shrink-0">
                                            <div class="bg-primary bg-opacity-10 p-3 rounded">
                                                <i class="bi bi-boxes text-primary fs-4"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="fw-semibold mb-1">All Products</h6>
                                            <div class="d-flex align-items-baseline">
                                                <h3 class="mb-0 me-2"><?php echo $totalProducts; ?></h3>
                                                <small class="text-muted">items</small>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="/add-products" class="btn btn-sm btn-primary w-100">
                                        View All Products
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Individual Category Cards -->
                        <?php foreach ($categoryStats as $stat): ?>
                            <div class="col-12 col-md-6 col-xl-3">
                                <div class="card h-100 border-secondary border-opacity-25">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="flex-shrink-0">
                                                <div class="bg-secondary bg-opacity-10 p-3 rounded">
                                                    <i class="bi bi-box text-secondary fs-4"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="fw-semibold mb-1"><?php echo htmlspecialchars($stat['category_name']); ?></h6>
                                                <div class="d-flex align-items-baseline">
                                                    <h3 class="mb-0 me-2"><?php echo $stat['total_products']; ?></h3>
                                                    <small class="text-muted">items</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex gap-2">
                                            <a href="/add-products?category=<?php echo urlencode($stat['category_name']); ?>"
                                                class="btn btn-sm btn-outline-secondary flex-grow-1">
                                                View Products
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Recent Products -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-clock-history me-2"></i>
                            <?php
                            if (!empty($_GET['category'])) {
                                echo htmlspecialchars($_GET['category']) . " Products";
                            } else {
                                echo "Recent Products";
                            }
                            ?>
                        </h5>
                        <a href="/add-products" class="btn btn-sm btn-primary">
                            <i class="bi bi-plus-lg me-1"></i>
                            Add New Product
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($products)): ?>
                        <div class="p-4 text-center">
                            <i class="bi bi-inbox text-muted fs-1"></i>
                            <p class="text-muted mt-2">No products found</p>
                            <?php if (!empty($_GET['category'])): ?>
                                <a href="/dashboard" class="btn btn-sm btn-outline-primary">
                                    View All Products
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-0">Product</th>
                                        <th class="border-0">Category</th>
                                        <th class="border-0">Price</th>
                                        <th class="border-0">Stock</th>
                                        <th class="border-0">Added Date</th>
                                        <th class="border-0">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($products as $product): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="<?php echo htmlspecialchars($product['image_url']); ?>"
                                                        alt="<?php echo htmlspecialchars($product['name']); ?>"
                                                        class="rounded"
                                                        width="40"
                                                        height="40"
                                                        style="object-fit: cover;">
                                                    <div class="ms-3">
                                                        <h6 class="mb-0"><?php echo htmlspecialchars($product['name']); ?></h6>
                                                        <small class="text-muted">ID: #<?php echo $product['product_id']; ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                                    <?php echo htmlspecialchars($product['category_name']); ?>
                                                </span>
                                            </td>
                                            <td>₱<?php echo number_format($product['price'], 2); ?></td>
                                            <td>
                                                <?php
                                                $stockClass = 'success';
                                                if ($product['stock_quantity'] <= 0) {
                                                    $stockClass = 'danger';
                                                } elseif ($product['stock_quantity'] <= 10) {
                                                    $stockClass = 'warning';
                                                }
                                                ?>
                                                <span class="badge bg-<?php echo $stockClass; ?> bg-opacity-10 text-<?php echo $stockClass; ?>">
                                                    <?php echo $product['stock_quantity']; ?> in stock
                                                </span>
                                            </td>
                                            <td><?php echo date('M d, Y', strtotime($product['created_at'])); ?></td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="/add-products?edit=<?php echo $product['product_id']; ?>"
                                                        class="btn btn-sm btn-outline-secondary">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-danger"
                                                        onclick="confirmDelete(<?php echo $product['product_id']; ?>)">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this product?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" action="/admin/products/delete" method="POST" style="display: inline;">
                    <input type="hidden" name="product_id" id="deleteProductId">
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmDelete(productId) {
        document.getElementById('deleteProductId').value = productId;
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    }
</script>