<?php include __DIR__ . '/../components/header.php'; ?>

<div class="container-lg mt-4">
    <div class="row g-4">
        <!-- Mobile Categories -->
        <div class="col-12 d-lg-none">
            <div class="d-flex flex-nowrap overflow-auto pb-3 scrollbar-overlay">
                <div class="d-flex gap-2 pe-3">
                    <?php foreach ($categories as $category): ?>
                        <a href="#" class="btn btn-outline-primary rounded-pill px-3 d-flex align-items-center shadow-hover">
                            <i class="fas fa-tag me-2"></i><?= $category['name'] ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Desktop Categories -->
        <div class="col-lg-2 d-none d-lg-block">
            <div class="sticky-top" style="top: 1rem;">
                <h3 class="h5 text-muted mb-3">Categories</h3>
                <div class="list-group gap-2">
                    <a href="#" class="list-group-item list-group-item-action d-flex align-items-center active rounded-3 p-3 shadow-sm border-primary">
                        <i class="fas fa-border-all me-2"></i>
                        All Products
                        <span class="badge bg-primary ms-auto"><?= count($products ?? []) ?></span>
                    </a>
                    <?php foreach ($categories as $category): ?>
                        <a href="#" class="list-group-item list-group-item-action d-flex align-items-center rounded-3 p-3 hover-shadow transition-all">
                            <i class="fas fa-tag me-2 text-muted"></i>
                            <span class="text-truncate"><?= $category['name'] ?></span>
                            <span class="badge bg-light text-muted ms-auto">15</span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-7">
            <div class="bg-white rounded-3 p-4 shadow-sm">
                <form class="mb-4">
                    <div class="input-group input-group-lg shadow-sm">
                        <input type="text" class="form-control border-primary" placeholder="Search products...">
                        <button class="btn btn-primary px-4">
                            <i class="fas fa-search me-2 d-none d-sm-inline"></i>
                            <span>Search</span>
                        </button>
                    </div>
                </form>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="h4 mb-0">Featured Products</h2>
                    <div class="d-none d-md-block">
                        <select class="form-select form-select-sm border-primary">
                            <option>Sort by Price</option>
                            <option>Sort by Popularity</option>
                        </select>
                    </div>
                </div>

                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    <?php for ($i = 0; $i < 6; $i++): ?>
                        <div class="col">
                            <div class="card h-100 shadow-sm hover-shadow-lg transition-all">
                                <div class="ratio ratio-1x1 bg-light">
                                    <img src="https://via.placeholder.com/300" class="card-img-top p-3 object-fit-contain" alt="Product">
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title text-truncate">Product Name</h5>
                                    <p class="fw-bold text-primary mb-2">₱99.99</p>
                                    <button class="btn btn-primary btn-sm mt-auto w-100">
                                        <i class="fas fa-cart-plus me-2"></i>Add to Cart
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>
        </div>

        <!-- Cart Sidebar -->
        <div class="col-lg-3">
            <div class="bg-white rounded-3 p-4 shadow-sm sticky-top" style="top: 1rem;">
                <div class="d-flex align-items-center mb-3">
                    <h3 class="h4 mb-0">
                        <i class="fas fa-shopping-cart me-2"></i>Cart
                    </h3>
                    <span class="badge bg-primary rounded-pill ms-2">2</span>
                </div>

                <ul class="list-group mb-3">
                    <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                        <div class="me-3">
                            <div class="text-truncate fw-bold">Product 1</div>
                            <small class="text-muted">2 x ₱99.99</small>
                        </div>
                        <button class="btn btn-sm btn-link text-danger p-1">
                            <i class="fas fa-times"></i>
                        </button>
                    </li>
                </ul>

                <div class="border-top pt-3">
                    <div class="d-flex justify-content-between mb-3">
                        <h5 class="mb-0">Total:</h5>
                        <h5 class="text-primary mb-0">₱199.98</h5>
                    </div>
                    <button class="btn btn-primary w-100 mb-2">
                        <i class="fas fa-wallet me-2"></i>Checkout
                    </button>
                    <button class="btn btn-outline-danger w-100">
                        <i class="fas fa-trash me-2"></i>Clear Cart
                    </button>
                </div>

                <div class="text-center text-muted mt-3 small">
                    <i class="fas fa-lock me-2"></i>Secure checkout
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../components/footer.php'; ?>