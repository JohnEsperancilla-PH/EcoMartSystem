<?php include_once __DIR__ . '/../components/admin-header.php'; ?>

<div class="d-flex">
    <?php require_once __DIR__ . '/../components/admin-sidebar.php'; ?>

    <div class="main-content bg-light">
        <div class="container-fluid px-4 py-5">
            <!-- Products Management Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-1">Products Management</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                            <li class="breadcrumb-item active">Products</li>
                        </ol>
                    </nav>
                </div>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
                    <i class="bi bi-plus-lg me-1"></i>
                    Add New Product
                </button>
            </div>

            <!-- Product Filters -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <form class="row g-3" method="GET" action="">
                        <div class="col-12 col-md-3">
                            <label class="form-label">Category</label>
                            <select class="form-select" name="category">
                                <option value="">All Categories</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?php echo $category['category_id']; ?>"
                                        <?php echo (isset($_GET['category']) && $_GET['category'] == $category['category_id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($category['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12 col-md-3">
                            <label class="form-label">Stock Status</label>
                            <select class="form-select" name="stock_status">
                                <option value="">All Status</option>
                                <option value="in_stock" <?php echo (isset($_GET['stock_status']) && $_GET['stock_status'] == 'in_stock') ? 'selected' : ''; ?>>In Stock</option>
                                <option value="low_stock" <?php echo (isset($_GET['stock_status']) && $_GET['stock_status'] == 'low_stock') ? 'selected' : ''; ?>>Low Stock</option>
                                <option value="out_of_stock" <?php echo (isset($_GET['stock_status']) && $_GET['stock_status'] == 'out_of_stock') ? 'selected' : ''; ?>>Out of Stock</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label">Search Products</label>
                            <input type="text"
                                class="form-control"
                                name="search"
                                placeholder="Search by name, SKU..."
                                value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                        </div>
                        <div class="col-12 col-md-2">
                            <label class="form-label d-none d-md-block"> </label>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-search me-1"></i>
                                Search
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="row g-4">
                <?php if (empty($products)): ?>
                    <div class="col-12">
                        <div class="alert alert-info">
                            No products found matching your criteria.
                        </div>
                    </div>
                <?php else: ?>
                    <?php foreach ($products as $product): ?>
                        <div class="col-12 col-md-6 col-xl-3">
                            <div class="card border-0 shadow-sm h-100 d-flex flex-column">
                                <div style="height: 200px; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                                    <img src="<?php echo htmlspecialchars($product['image_url']); ?>"
                                        class="card-img-top"
                                        alt="<?php echo htmlspecialchars($product['name']); ?>"
                                        style="object-fit: contain; width: 100%; height: 100%;">
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="card-title mb-0 flex-grow-1"><?php echo htmlspecialchars($product['name']); ?></h5>
                                        <span class="badge bg-primary ms-2">₱<?php echo number_format($product['price'], 2); ?></span>
                                    </div>
                                    <p class="text-muted small mb-2"><?php echo htmlspecialchars($product['category_name']); ?></p>

                                    <div class="mt-auto"> 
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <?php
                                                $stockClass = match (true) {
                                                    $product['stock_quantity'] <= 0 => 'bg-danger',
                                                    $product['stock_quantity'] <= 10 => 'bg-warning',
                                                    default => 'bg-success'
                                                };
                                                ?>
                                                <span class="badge <?php echo $stockClass; ?>">
                                                    <?php echo $product['stock_quantity']; ?> in stock
                                                </span>
                                            </div>
                                            <div class="btn-group">
                                                <button type="button"
                                                    class="btn btn-sm btn-outline-primary"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editProduct<?php echo $product['product_id']; ?>"
                                                    data-product-id="<?php echo $product['product_id']; ?>"
                                                    data-product-name="<?php echo htmlspecialchars($product['name']); ?>">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button type="button"
                                                    class="btn btn-sm btn-outline-danger"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteProduct<?php echo $product['product_id']; ?>"
                                                    data-product-id="<?php echo $product['product_id']; ?>"
                                                    data-product-name="<?php echo htmlspecialchars($product['name']); ?>">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
                <nav class="mt-4">
                    <ul class="pagination justify-content-center">
                        <?php
                        $queryParams = $_GET;
                        // Previous page
                        if ($currentPage > 1): ?>
                            <?php
                            $queryParams['page'] = $currentPage - 1;
                            $prevUrl = '?' . http_build_query($queryParams);
                            ?>
                            <li class="page-item">
                                <a class="page-link" href="<?php echo $prevUrl; ?>">Previous</a>
                            </li>
                        <?php else: ?>
                            <li class="page-item disabled">
                                <span class="page-link">Previous</span>
                            </li>
                        <?php endif; ?>

                        <?php
                        // Show page numbers
                        for ($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++):
                            $queryParams['page'] = $i;
                            $pageUrl = '?' . http_build_query($queryParams);
                        ?>
                            <li class="page-item <?php echo ($i === $currentPage) ? 'active' : ''; ?>">
                                <a class="page-link" href="<?php echo $pageUrl; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>

                        <?php
                        // Next page
                        if ($currentPage < $totalPages):
                            $queryParams['page'] = $currentPage + 1;
                            $nextUrl = '?' . http_build_query($queryParams);
                        ?>
                            <li class="page-item">
                                <a class="page-link" href="<?php echo $nextUrl; ?>">Next</a>
                            </li>
                        <?php else: ?>
                            <li class="page-item disabled">
                                <span class="page-link">Next</span>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<?php include __DIR__ . '/../admin/modal/create-modal.view.php'; ?>

<!-- Include Edit and Delete Modals for each product -->
<?php foreach ($products as $product): ?>
    <?php include __DIR__ . '/../admin/modal/edit-modal.view.php'; ?>
    <?php include __DIR__ . '/../admin/modal/delete-modal.view.php'; ?>
<?php endforeach; ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle image preview
        document.querySelectorAll('input[type="file"][name="image"]').forEach(input => {
            input.addEventListener('change', function(e) {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    const preview = this.closest('.modal').querySelector('.image-preview');

                    if (preview) {
                        preview.style.display = 'block';
                        reader.onload = function(e) {
                            preview.src = e.target.result;
                        };
                        reader.readAsDataURL(this.files[0]);
                    }
                }
            });
        });
    });
</script>