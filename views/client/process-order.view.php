<?php include __DIR__ . '/../components/client-header.php'; ?>

<div class="container mt-5">
    <h2 class="mb-4">Checkout</h2>

    <div class="card p-4 mb-4">
        <h3>Order Overview</h3>
        <p><strong>Products:</strong> Sample Product List</p>
        <p><strong>Payment Method:</strong> <span id="payment-method-display">Cash</span></p>
        <p><strong>Delivery Information:</strong></p>
        <p id="delivery-info-display">Not yet provided</p>
        <p><strong>Grand Total:</strong> ₱0.00</p>
    </div>

    <form id="checkout-form" class="needs-validation" novalidate>
        <div class="row">
            <div class="col-md-6">
                <h3>Customer Information</h3>
                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" class="form-control" name="full_name" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" class="form-control" name="email" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Address</label>
                    <input type="text" class="form-control" name="address" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Contact Number (PH)</label>
                    <input type="tel" class="form-control" name="contact" pattern="09[0-9]{9}" placeholder="09XXXXXXXXX" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">ZIP Code</label>
                    <input type="text" class="form-control" name="zip" required>
                </div>
            </div>

            <div class="col-md-6">
                <h3>Payment</h3>
                <div class="mb-3">
                    <label class="form-label">Mode of Payment</label>
                    <select class="form-select" name="payment_method" id="payment-method" required>
                        <option value="cash">Cash</option>
                        <option value="gcash">GCash</option>
                    </select>
                </div>
                <div id="gcash-info" class="mb-3" style="display: none;">
                    <label class="form-label">GCash Reference Number</label>
                    <input type="text" class="form-control" name="gcash_ref" id="gcash-ref">
                    <div class="mt-3 text-center">
                        <img src="https://via.placeholder.com/150" alt="GCash QR Code" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>

        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="agree" required>
            <label class="form-check-label">I agree to the terms and conditions</label>
        </div>

        <button type="submit" class="btn btn-primary">Place Order</button>
    </form>
</div>

<script>
    document.getElementById('payment-method').addEventListener('change', function() {
        const gcashInfo = document.getElementById('gcash-info');
        const paymentDisplay = document.getElementById('payment-method-display');

        if (this.value === 'gcash') {
            gcashInfo.style.display = 'block';
            document.getElementById('gcash-ref').setAttribute('required', 'true');
            paymentDisplay.textContent = 'GCash';
        } else {
            gcashInfo.style.display = 'none';
            document.getElementById('gcash-ref').removeAttribute('required');
            paymentDisplay.textContent = 'Cash';
        }
    });
</script>


<?php include __DIR__ . '/../components/footer.php'; ?>