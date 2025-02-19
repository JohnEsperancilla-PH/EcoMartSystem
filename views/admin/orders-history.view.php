<?php include_once __DIR__ . '/../components/admin-header.php'; ?>

<div class="d-flex">
    <?php require_once __DIR__ . '/../components/admin-sidebar.php'; ?>

    <div class="main-content bg-light w-100">
        <div class="container-fluid px-4 py-5">
            <!-- Order Filters -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <form class="row g-3">
                        <div class="col-12 col-md-3">
                            <label class="form-label">Date Range</label>
                            <select class="form-select" name="date_range">
                                <option value="today">Today</option>
                                <option value="week">This Week</option>
                                <option value="month" selected>This Month</option>
                                <option value="year">This Year</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status">
                                <option value="">All Status</option>
                                <option value="pending">Pending</option>
                                <option value="processing">Processing</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label">Search</label>
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search orders...">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Orders List -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <?php if (empty($orders)): ?>
                        <!-- Display when there are no orders -->
                        <div class="d-flex flex-column align-items-center justify-content-center p-5">
                            <i class="bi bi-box-seam display-1 text-muted"></i>
                            <h4 class="mt-3 text-muted">No Orders Found</h4>
                            <p class="text-muted">There are currently no orders matching your search criteria.</p>
                        </div>
                    <?php else: ?>
                        <!-- Display the table when there are orders -->
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-0">Order ID</th>
                                        <th class="border-0">Customer</th>
                                        <th class="border-0">Products</th>
                                        <th class="border-0">Total</th>
                                        <th class="border-0">Status</th>
                                        <th class="border-0">Date</th>
                                        <th class="border-0">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($orders as $order): ?>
                                        <tr>
                                            <td>
                                                <span class="fw-medium">#<?php echo $order['order_id']; ?></span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <div class="avatar avatar-sm">
                                                            <div class="avatar-title rounded-circle bg-primary bg-opacity-10 text-primary">
                                                                <?php echo strtoupper(substr($order['customer_name'], 0, 1)); ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1 ms-2">
                                                        <h6 class="mb-0"><?php echo htmlspecialchars($order['customer_name']); ?></h6>
                                                        <small class="text-muted"><?php echo htmlspecialchars($order['email']); ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?php echo $order['total_items']; ?> items</td>
                                            <td>₱<?php echo number_format($order['total_amount'], 2); ?></td>
                                            <td>
                                                <?php
                                                $statusClass = match ($order['status']) {
                                                    'pending' => 'bg-warning',
                                                    'processing' => 'bg-info',
                                                    'completed' => 'bg-success',
                                                    'cancelled' => 'bg-danger',
                                                    default => 'bg-secondary'
                                                };
                                                ?>
                                                <span class="badge <?php echo $statusClass; ?>">
                                                    <?php echo ucfirst($order['status']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <span><?php echo date('M d, Y', strtotime($order['order_date'])); ?></span>
                                                    <small class="text-muted"><?php echo date('h:i A', strtotime($order['order_date'])); ?></small>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-icon" data-bs-toggle="dropdown">
                                                        <i class="bi bi-three-dots-vertical"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li>
                                                            <a class="dropdown-item" href="/admin/orders/<?php echo $order['order_id']; ?>">
                                                                <i class="bi bi-eye me-2"></i>
                                                                View Details
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="#">
                                                                <i class="bi bi-printer me-2"></i>
                                                                Print Invoice
                                                            </a>
                                                        </li>
                                                        <?php if ($order['status'] === 'pending'): ?>
                                                            <li>
                                                                <hr class="dropdown-divider">
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item text-danger" href="#">
                                                                    <i class="bi bi-x-circle me-2"></i>
                                                                    Cancel Order
                                                                </a>
                                                            </li>
                                                        <?php endif; ?>
                                                    </ul>
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

            <!-- Pagination -->
            <?php if (!empty($orders)): ?>
                <nav class="mt-4">
                    <ul class="pagination justify-content-center">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1">Previous</a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">Next</a>
                        </li>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>
    </div>
</div>