<?php include_once __DIR__ . '/../components/admin-header.php'; ?>

<div class="d-flex">
    <?php require_once __DIR__ . '/../components/admin-sidebar.php'; ?>

    <div class="main-content bg-light w-100">
        <div class="container-fluid px-4 py-5">
            <!-- Order Filters -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <form id="filterForm" method="GET" class="row g-3">
                        <div class="col-12 col-md-3">
                            <label class="form-label">Date Range</label>
                            <select class="form-select" name="date_range" onchange="this.form.submit()">
                                <option value="today" <?php echo ($_GET['date_range'] ?? '') === 'today' ? 'selected' : ''; ?>>Today</option>
                                <option value="week" <?php echo ($_GET['date_range'] ?? '') === 'week' ? 'selected' : ''; ?>>This Week</option>
                                <option value="month" <?php echo ($_GET['date_range'] ?? 'month') === 'month' ? 'selected' : ''; ?>>This Month</option>
                                <option value="year" <?php echo ($_GET['date_range'] ?? '') === 'year' ? 'selected' : ''; ?>>This Year</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status" onchange="this.form.submit()">
                                <option value="">All Status</option>
                                <option value="pending" <?php echo ($_GET['status'] ?? '') === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="processing" <?php echo ($_GET['status'] ?? '') === 'processing' ? 'selected' : ''; ?>>Processing</option>
                                <option value="completed" <?php echo ($_GET['status'] ?? '') === 'completed' ? 'selected' : ''; ?>>Completed</option>
                                <option value="cancelled" <?php echo ($_GET['status'] ?? '') === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                            </select>
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
                                        <th class="border-0">User ID</th>
                                        <th class="border-0">Customer Info</th>
                                        <th class="border-0">Products Ordered</th>
                                        <th class="border-0">Contact Details</th>
                                        <th class="border-0">Delivery Address</th>
                                        <th class="border-0">Payment Details</th>
                                        <th class="border-0">Order Details</th>
                                        <th class="border-0">Status</th>
                                        <th class="border-0">Dates</th>
                                        <th class="border-0">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    usort($orders, function ($a, $b) {
                                        return $a['order_id'] - $b['order_id'];
                                    }); 
                                    
                                    foreach ($orders as $order): ?>
                                        <tr>
                                            <td>
                                                <span class="fw-medium">#<?php echo $order['order_id']; ?></span>
                                            </td>
                                            <td>
                                                <span class="text-muted"><?php echo $order['user_id']; ?></span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">

                                                    <div class="flex-grow-1 ms-2">
                                                        <h6 class="mb-0"><?php echo htmlspecialchars($order['customer_name']); ?></h6>
                                                        <small class="text-muted"><?php echo htmlspecialchars($order['customer_email']); ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <ul>
                                                    <?php
                                                    $itemsQuery = "SELECT p.name 
                                                                   FROM OrderItems oi
                                                                   JOIN Products p ON oi.product_id = p.product_id
                                                                   WHERE oi.order_id = ?";
                                                    $stmt = $this->db->prepare($itemsQuery);
                                                    $stmt->bind_param("i", $order['order_id']);
                                                    $stmt->execute();
                                                    $itemsResult = $stmt->get_result();
                                                    while ($item = $itemsResult->fetch_assoc()): ?>
                                                        <li><?php echo htmlspecialchars($item['name']); ?></li>
                                                    <?php endwhile; ?>
                                                </ul>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <span><?php echo htmlspecialchars($order['customer_contact']); ?></span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <small class="text-wrap" style="max-width: 200px;">
                                                        <?php echo htmlspecialchars($order['delivery_address']); ?>
                                                    </small>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <span class="fw-medium"><?php echo htmlspecialchars($order['payment_method']); ?></span>
                                                    
                                                    <?php 
                                                    $paymentMethod = strtolower(trim($order['payment_method'])); 
                                                    
                                                    if ($paymentMethod === 'gcash'): ?>
                                                        <small class="text-muted">GCash Ref: <?php echo isset($order['gcash_ref']) ? htmlspecialchars($order['gcash_ref']) : 'N/A'; ?></small>
                                                        <small class="text-muted">Phone: <?php echo isset($order['gcash_phone']) ? htmlspecialchars($order['gcash_phone']) : 'N/A'; ?></small>
                                                    <?php elseif ($paymentMethod === 'maya'): ?>  
                                                        <small class="text-muted">Maya Ref: <?php echo isset($order['maya_ref']) ? 
                                                        htmlspecialchars($order['maya_ref']) : 'N/A'; ?></small>  
                                                        <small class="text-muted">Phone: <?php echo isset($order['maya_phone']) ? 
                                                        htmlspecialchars($order['maya_phone']) : 'N/A'; ?></small>  
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <span class="fw-medium">₱<?php echo number_format($order['total_amount'], 2); ?></span>
                                                </div>
                                            </td>
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
                                                    <small>Created:<br><?php echo date('M d, Y h:i A', strtotime($order['order_date'])); ?></small>
                                                    <small class="text-muted">Updated:<br><?php echo date('M d, Y h:i A', strtotime($order['updated_at'])); ?></small>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-icon" data-bs-toggle="dropdown">
                                                        <i class="bi bi-three-dots-vertical"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        
                                                        <li>
                                                            <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editOrderModal<?php echo $order['order_id']; ?>">
                                                                <i class="bi bi-pencil me-2"></i>
                                                                Edit Order
                                                            </button>
                                                        </li>
                                                        <li>
                                                            <hr class="dropdown-divider">
                                                        </li>
                                                        <li>
                                                            <button class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#deleteOrderModal<?php echo $order['order_id']; ?>">
                                                                <i class="bi bi-trash me-2"></i>
                                                                Delete Order
                                                            </button>
                                                        </li>
                                                    </ul>
                                                </div>

                                                <!-- Edit Modal for each order -->
                                                <div class="modal fade" id="editOrderModal<?php echo $order['order_id']; ?>" tabindex="-1">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Edit Order #<?php echo $order['order_id']; ?></h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <form action="/update-order" method="POST">
                                                                <div class="modal-body">
                                                                    <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                                                                    <div class="mb-3">
                                                                        <label class="form-label">Order Status</label>
                                                                        <select class="form-select" name="status">
                                                                            <option value="pending" <?php echo $order['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                                                            <option value="processing" <?php echo $order['status'] === 'processing' ? 'selected' : ''; ?>>Processing</option>
                                                                            <option value="completed" <?php echo $order['status'] === 'completed' ? 'selected' : ''; ?>>Completed</option>
                                                                            <option value="cancelled" <?php echo $order['status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                                                            <option value="paid" <?php echo $order['status'] === 'paid' ? 'selected' : ''; ?>>Paid</option>
                                                                            <option value="shipped" <?php echo $order['status'] === 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                                                                            <option value="delivered" <?php echo $order['status'] === 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Delete Modal for each order -->
                                                <div class="modal fade" id="deleteOrderModal<?php echo $order['order_id']; ?>" tabindex="-1">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Delete Order #<?php echo $order['order_id']; ?></h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p>Are you sure you want to delete this order? This action cannot be undone.</p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                <form action="/delete-order" method="POST" style="display: inline;">
                                                                    <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                                                                    <button type="submit" class="btn btn-danger">Delete Order</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
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
            <?php if (!empty($orders) && $totalPages > 1): ?>
                <nav class="mt-4">
                    <ul class="pagination justify-content-center">
                        <?php
                        // Previous page link
                        $prevPage = $currentPage - 1;
                        $nextPage = $currentPage + 1;
                        ?>

                        <li class="page-item <?php echo $currentPage <= 1 ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $prevPage; ?>" <?php echo $currentPage <= 1 ? 'tabindex="-1" aria-disabled="true"' : ''; ?>>
                                Previous
                            </a>
                        </li>

                        <?php
                        // Calculate range of page numbers to show
                        $startPage = max(1, $currentPage - 2);
                        $endPage = min($totalPages, $currentPage + 2);

                        // Show first page if we're not starting at 1
                        if ($startPage > 1) {
                            echo '<li class="page-item"><a class="page-link" href="?page=1">1</a></li>';
                            if ($startPage > 2) {
                                echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                            }
                        }

                        // Show page numbers
                        for ($i = $startPage; $i <= $endPage; $i++) {
                            echo '<li class="page-item ' . ($currentPage == $i ? 'active' : '') . '">
                                <a class="page-link" href="?page=' . $i . '">' . $i . '</a>
                            </li>';
                        }

                        // Show last page if we're not ending at total pages
                        if ($endPage < $totalPages) {
                            if ($endPage < $totalPages - 1) {
                                echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                            }
                            echo '<li class="page-item"><a class="page-link" href="?page=' . $totalPages . '">' . $totalPages . '</a></li>';
                        }
                        ?>

                        <li class="page-item <?php echo $currentPage >= $totalPages ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $nextPage; ?>" <?php echo $currentPage >= $totalPages ? 'tabindex="-1" aria-disabled="true"' : ''; ?>>
                                Next
                            </a>
                        </li>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>
    </div>
</div>