<?php include __DIR__ . '/../components/header.php'; ?>

<body class="bg-light">
    <div class="container mt-4">
        <div class="row">
            <!-- Static Category Sidebar -->
            <div class="col-md-2">
                <h5>Categories</h5>
                <div class="list-group">
                    <a href="#" class="list-group-item list-group-item-action active">All</a>
                    <a href="#" class="list-group-item list-group-item-action">Beverages</a>
                    <a href="#" class="list-group-item list-group-item-action">Food</a>
                    <a href="#" class="list-group-item list-group-item-action">Desserts</a>
                    <a href="#" class="list-group-item list-group-item-action">Snacks</a>
                </div>
            </div>

            <div class="col-md-7">
                <h2>Products</h2>
                <form method="GET" action="#" class="mb-3">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Search products...">
                        <button class="btn btn-outline-secondary" type="submit">Search</button>
                    </div>
                </form>

                <div class="row">
                    <!-- Example Product Card -->
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="placeholder-img">
                                Image
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">Product Name</h5>
                                <p class="card-text">₱00.00</p>
                                <a href="#" class="btn btn-primary">Add to Cart</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <h2>Cart</h2>
                <ul class="list-group">
                    <li class="list-group-item">Product Name x 2 = $19.98</li>
                    <li class="list-group-item active">Total</li>
                </ul>
                <a href="#" class="btn btn-success w-100 mt-3">Checkout</a>
                <a href="#" class="btn btn-danger w-100 mt-1">Clear Cart</a>
            </div>
        </div>
    </div>
</body>

</html>

<?php include __DIR__ . '/../components/footer.php'; ?>