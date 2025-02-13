<?php include __DIR__ . '/../components/header.php'; ?>

<div class="container-lg mt-4">
    <div class="row g-4">
        <!-- Mobile Categories -->
        <div class="col-12 d-lg-none">
            <div class="d-flex flex-nowrap overflow-auto pb-2">
                <div class="list-group list-group-horizontal">
                    <a href="#" class="list-group-item list-group-item-action active rounded-pill">All</a>
                    <?php foreach ($categories as $category): ?>
                        <a href="#" class="list-group-item list-group-item-action rounded-pill">
                            <?= $category['name'] ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Desktop Categories -->
        <div class="col-lg-2 d-none d-lg-block">
            <div class="list-group">
                <a href="#" class="list-group-item list-group-item-action active">All</a>
                <?php foreach ($categories as $category): ?>
                    <a href="#" class="list-group-item list-group-item-action">
                        <?= $category['name'] ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-7">
            <div class="bg-white rounded-3 p-4 shadow-sm">
                <form class="mb-4">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search products...">
                        <button class="btn btn-primary">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>

                <h2 class="mb-4">Products</h2>
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    <?php for ($i = 0; $i < 6; $i++): ?>
                        <div class="col">
                            <div class="card h-100 shadow-sm">
                                <div class="ratio ratio-1x1">
                                    <img src="https://via.placeholder.com/300" class="card-img-top" alt="Product">
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title">Product Name</h5>
                                    <p class="fw-bold text-primary mt-auto">₱99.99</p>
                                    <button class="btn btn-outline-primary btn-sm">
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
            <div class="bg-white rounded-3 p-4 shadow-sm pt-5">
                <h3 class="h4 mb-3">
                    <i class="fas fa-shopping-cart me-2"></i>Cart
                    <span class="badge bg-primary rounded-pill">2</span>
                </h3>

                <ul class="list-group mb-3">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <span class="fw-bold">Product 1</span>
                            <br><small class="text-muted">2 x ₱99.99</small>
                        </div>
                        <button class="btn btn-link text-danger p-0">
                            <i class="fas fa-times"></i>
                        </button>
                    </li>
                </ul>

                <div class="d-flex justify-content-between mb-3">
                    <h5>Total:</h5>
                    <h5 class="text-primary">₱199.98</h5>
                </div>
                <button class="btn btn-primary w-100 mb-2">
                    <i class="fas fa-wallet me-2"></i>Checkout
                </button>
                <button class="btn btn-outline-danger w-100">
                    <i class="fas fa-trash me-2"></i>Clear Cart
                </button>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../components/footer.php'; ?>