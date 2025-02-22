<?php include __DIR__ . '/../components/client-header.php'; ?>

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
                    <a href="/shop" class="list-group-item list-group-item-action d-flex align-items-center <?= !$selectedCategory ? 'active' : '' ?> rounded-3 p-3 shadow-sm border-primary">
                        <i class="fas fa-border-all me-2"></i>
                        All Products
                    </a>
                    <?php foreach ($categories as $category): ?>
                        <a href="/shop?category=<?= urlencode($category['name']) ?>"
                            class="list-group-item list-group-item-action d-flex align-items-center rounded-3 p-3 <?= $selectedCategory == $category['name'] ? 'active' : '' ?>">
                            <i class="fas fa-tag me-2 text-muted"></i>
                            <?= htmlspecialchars($category['name']) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-10">
            <div class="bg-white rounded-3 p-4 shadow-sm overflow-auto vh-75">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="h4"><?= $selectedCategory ? htmlspecialchars($selectedCategory) : 'All Products' ?></h2>
                    <a href="/process-order" class="btn btn-primary position-relative">
                        <i class="fas fa-shopping-cart me-2"></i>Cart
                        <span id="cart-count" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="display: none;">
                            0
                        </span>
                    </a>
                </div>

                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
                    <?php if (!empty($products)): ?>
                        <?php foreach ($products as $product): ?>
                            <div class="col">
                                <div class="card h-100 shadow-sm">
                                    <div class="ratio ratio-1x1 bg-light">
                                        <?php if (!empty($product['image_url'])): ?>
                                            <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="Product Image" class="w-100 h-100 object-fit-cover">
                                        <?php endif; ?>
                                    </div>
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title text-truncate"><?= htmlspecialchars($product['name']) ?></h5>
                                        <p class="fw-bold text-primary mb-2">₱<?= number_format($product['price'], 2) ?></p>
                                        <button class="btn btn-primary btn-sm mt-auto w-100 add-to-order"
                                            data-product-id="<?= $product['product_id'] ?>"
                                            data-product-name="<?= htmlspecialchars($product['name']) ?>"
                                            data-product-price="<?= $product['price'] ?>">
                                            <i class="bi bi-bag-check"></i>Add to Order
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted">No products available in this category.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const addToOrderButtons = document.querySelectorAll('.add-to-order');
    const orderList = JSON.parse(localStorage.getItem('orderList')) || [];

    addToOrderButtons.forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.dataset.productId;
            const productName = this.dataset.productName;
            const productPrice = this.dataset.productPrice;

            const product = { id: productId, name: productName, price: productPrice };
            orderList.push(product);
            localStorage.setItem('orderList', JSON.stringify(orderList));
            alert('Product added to order list');
        });
    });

    const orderOverview = document.getElementById('order-overview');
    if (orderOverview) {
        orderOverview.innerHTML = orderList.map(product => `
            <div>
                <h3>${product.name}</h3>
                <p>Price: ${product.price}</p>
            </div>
        `).join('');
    }
});
</script>

<?php include __DIR__ . '/../components/footer.php'; ?>