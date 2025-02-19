<?php include_once __DIR__ . '/../components/admin-header.php'; ?>

<div class="d-flex">
    <?php require_once __DIR__ . '/../components/admin-sidebar.php'; ?>

    <div class="main-content bg-light">
        <div class="container-fluid px-4 py-5">
            <!-- Products Overview Cards -->
            <div class="row g-4 mb-4">
                <?php foreach ($categoryStats as $stat): ?>
                    <div class="col-12 col-md-6 col-xl-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="flex-shrink-0">
                                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                                            <i class="bi bi-box text-primary fs-4"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="fw-semibold mb-1"><?php echo htmlspecialchars($stat['category_name']); ?></h6>
                                        <div class="d-flex align-items-baseline">
                                            <h3 class="mb-0 me-2"><?php echo $stat['total_products']; ?></h3>
                                            <small class="text-muted">products</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <a href="/admin/products?category=<?php echo urlencode($stat['category_name']); ?>"
                                        class="btn btn-sm btn-outline-primary">
                                        View Products
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Quick Stats -->
            <div class="row g-4 mb-4">
                <div class="col-12 col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="display-4 text-primary mb-2">
                                <i class="bi bi-graph-up"></i>
                            </div>
                            <h5 class="fw-bold">₱<?php echo number_format($totalRevenue ?? 0, 2); ?></h5>
                            <p class="text-muted mb-0">Total Revenue</p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="display-4 text-success mb-2">
                                <i class="bi bi-bag-check"></i>
                            </div>
                            <h5 class="fw-bold"><?php echo $totalOrdersCount ?? 0; ?></h5>
                            <p class="text-muted mb-0">Total Orders</p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="display-4 text-info mb-2">
                                <i class="bi bi-people"></i>
                            </div>
                            <h5 class="fw-bold"><?php echo $totalCustomers ?? 0; ?></h5>
                            <p class="text-muted mb-0">Total Customers</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products Table -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-box-seam me-2"></i>
                            Products Overview
                        </h5>
                        <a href="/add-products" class="btn btn-sm btn-primary">
                            <i class="bi bi-plus-lg me-1"></i>
                            Add New Product
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0">Product</th>
                                    <th class="border-0">Category</th>
                                    <th class="border-0">Price</th>
                                    <th class="border-0">Added Date</th>
                                    <th class="border-0">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($products as $product): ?>
                                    <tr>
                                        <td class="align-middle">
                                            <div class="d-flex align-items-center">
                                                <img src="<?php echo htmlspecialchars($product['image_url']); ?>"
                                                    alt="<?php echo htmlspecialchars($product['name']); ?>"
                                                    class="rounded"
                                                    width="40">
                                                <span class="ms-2"><?php echo htmlspecialchars($product['name']); ?></span>
                                            </div>
                                        </td>
                                        <td class="align-middle"><?php echo htmlspecialchars($product['category_name']); ?></td>
                                        <td class="align-middle">₱<?php echo number_format($product['price'], 2); ?></td>
                                        <td class="align-middle"><?php echo date('M d, Y', strtotime($product['created_at'])); ?></td>
                                        <td class="align-middle">
                                            <div class="btn-group">
                                                <a href="/admin/products/edit/<?php echo $product['product_id']; ?>"
                                                    class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <button type="button"
                                                    class="btn btn-sm btn-outline-danger"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal<?php echo $product['product_id']; ?>">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>