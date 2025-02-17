<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | EcoMart</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        /* Custom styles for scrollable tables */
        .table-scrollable {
            height: 400px;
            overflow-y: auto;
        }
        .table-scrollable thead th {
            position: sticky;
            top: 0;
            background-color: white;
            z-index: 1;
        }
        .top-nav {
            background-color: #f8f9fa;
            padding: 10px 0;
            border-bottom: 1px solid #dee2e6;
        }
    </style>
</head>

<body>
    <!-- Top Navigation Bar -->
    <nav class="top-nav">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3>EcoMart Admin</h3>
                </div>
                <div class="d-flex align-items-center">
                    <?php if (isset($session)): ?>
                        <span class="me-3">Welcome, <?php echo htmlspecialchars($session->get('email')); ?></span>
                    <?php endif; ?>
                    <form action="/logout" method="POST">
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-box-arrow-right me-1"></i>Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content with adjusted padding -->
    <div class="container-fluid mt-4 p-md-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center mb-3">
            <h1 class="h2">Admin Dashboard</h1>
        </div>

        <!-- Dashboard Overview Cards -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <h5 class="card-title">Total Orders</h5>
                        <p class="card-text fs-4"><?php echo $totalOrdersCount; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <h5 class="card-title">Total Products</h5>
                        <p class="card-text fs-4"><?php echo $totalProductsCount; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-info">
                    <div class="card-body">
                        <h5 class="card-title">Latest Update</h5>
                        <p class="card-text"><?php echo date('Y-m-d H:i:s'); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Orders Table - Scrollable -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h2>Orders</h2>
                <a href="/admin/orders" class="btn btn-sm btn-primary">Manage Orders</a>
            </div>
            <div class="table-scrollable">
                <table class="table table-striped table-sm">
                    <thead>
                        <tr>
                            <th scope="col">Order ID</th>
                            <th scope="col">Customer</th>
                            <th scope="col">Amount</th>
                            <th scope="col">Status</th>
                            <th scope="col">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($recentOrders)): ?>
                            <tr>
                                <td colspan="5" class="text-center">No orders found</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($recentOrders as $order): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                                    <td><?php echo htmlspecialchars($order['email']); ?></td>
                                    <td>₱<?php echo number_format($order['total_amount'], 2); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo $order['status'] === 'completed' ? 'success' : ($order['status'] === 'pending' ? 'warning' : 'secondary'); ?>">
                                            <?php echo htmlspecialchars(ucfirst($order['status'])); ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('M d, Y', strtotime($order['order_date'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Products List Table - Scrollable (without view action) -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h2>Products List</h2>
                <a href="/admin/products" class="btn btn-sm btn-primary">Manage Products</a>
            </div>
            <div class="table-scrollable">
                <table class="table table-striped table-sm">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Name</th>
                            <th scope="col">Category</th>
                            <th scope="col">Price</th>
                            <th scope="col">Stock</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($productsList)): ?>
                            <tr>
                                <td colspan="5" class="text-center">No products found</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($productsList as $product): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($product['product_id']); ?></td>
                                    <td><?php echo htmlspecialchars($product['name']); ?></td>
                                    <td><?php echo htmlspecialchars($product['category_name'] ?? 'Uncategorized'); ?></td>
                                    <td>₱<?php echo number_format($product['price'], 2); ?></td>
                                    <td>
                                        <?php if ($product['stock_quantity'] > 10): ?>
                                            <span class="badge bg-success"><?php echo $product['stock_quantity']; ?></span>
                                        <?php elseif ($product['stock_quantity'] > 0): ?>
                                            <span class="badge bg-warning"><?php echo $product['stock_quantity']; ?></span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Out of stock</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>