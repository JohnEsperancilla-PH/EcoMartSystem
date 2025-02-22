<?php include __DIR__ . '/../components/client-header.php'; ?>

<div class="container mt-5">
    <h2 class="mb-4">Checkout</h2>

    <div class="card p-4 mb-4">
        <h3>Order Overview</h3>
        <div id="order-items" class="mb-3">
            <!-- Order items will be populated here via JavaScript -->
        </div>
        <div class="border-top pt-3">
            <p><strong>Payment Method:</strong> <span id="payment-method-display">Cash on Delivery</span></p>
            <p><strong>Delivery Information:</strong></p>
            <p id="delivery-info-display">Not yet provided</p>
            <p><strong>Grand Total:</strong> <span id="grand-total">₱0.00</span></p>
        </div>
    </div>

    <form id="checkout-form" class="needs-validation" novalidate>
        <div class="row">
            <div class="col-md-6">
                <h3 class="mb-3">Customer Information</h3>
                <div class="mb-3">
                    <label for="fullName" class="form-label">Full Name</label>
                    <input type="text"
                        class="form-control"
                        id="fullName"
                        name="full_name"
                        required
                        pattern="^[a-zA-Z ]+$"
                        minlength="2"
                        maxlength="100">
                    <div class="invalid-feedback">
                        Please enter your full name (letters and spaces only)
                    </div>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email"
                        class="form-control"
                        id="email"
                        name="email">
                    <div class="invalid-feedback">
                        Please enter a valid email address
                    </div>
                </div>

                <div class="mb-3">
                    <label for="address" class="form-label">Delivery Address</label>
                    <textarea class="form-control"
                        id="address"
                        name="address"
                        rows="3"
                        required
                        minlength="10"
                        maxlength="200"></textarea>
                    <div class="invalid-feedback">
                        Please enter your complete delivery address
                    </div>
                </div>

                <div class="mb-3">
                    <label for="contact" class="form-label">Contact Number (PH)</label>
                    <div class="input-group">
                        <span class="input-group-text">+63</span>
                        <input type="tel"
                            class="form-control"
                            id="contact"
                            name="contact"
                            required
                            pattern="^9[0-9]{9}$"
                            placeholder="9XXXXXXXXX">
                    </div>
                    <div class="invalid-feedback">
                        Please enter a valid Philippine mobile number
                    </div>
                    <div class="form-text">
                        Enter your 10-digit mobile number starting with 9
                    </div>
                </div>

                <div class="mb-3">
                    <label for="zipCode" class="form-label">ZIP Code</label>
                    <input type="text"
                        class="form-control"
                        id="zipCode"
                        name="zip"
                        required
                        pattern="[0-9]{4}"
                        maxlength="4">
                    <div class="invalid-feedback">
                        Please enter a valid 4-digit ZIP code
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <h3 class="mb-3">Payment Details</h3>
                <div class="mb-3">
                    <label for="paymentMethod" class="form-label">Mode of Payment</label>
                    <select class="form-select"
                        id="paymentMethod"
                        name="payment_method"
                        required>
                        <option value="">Select payment method</option>
                        <option value="cash">Cash on Delivery</option>
                        <option value="gcash">GCash</option>
                    </select>
                    <div class="invalid-feedback">
                        Please select a payment method
                    </div>
                </div>

                <div id="gcashInfo" class="mb-3" style="display: none;">
                    <div class="mb-3">
                        <p><strong>Store GCash Account Number:</strong> 09171234567</p>
                    </div>
                    <div class="mb-3">
                        <label for="gcashRef" class="form-label">GCash Reference Number</label>
                        <input type="text"
                            class="form-control"
                            id="gcashRef"
                            name="gcash_ref">
                        <div class="invalid-feedback">
                            Please enter a valid GCash reference number (10-13 digits)
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="gcashPhone" class="form-label">GCash Customer Phone Number</label>
                        <div class="input-group">
                            <span class="input-group-text">+63</span>
                            <input type="tel"
                                class="form-control"
                                id="gcashPhone"
                                name="gcash_phone"
                                required
                                pattern="^9[0-9]{9}$"
                                placeholder="9XXXXXXXXX">
                        </div>
                        <div class="invalid-feedback">
                            Please enter a valid Philippine mobile number
                        </div>
                    </div>
                    <div class="mt-3">
                        <p class="mb-2">Scan QR Code to pay:</p>
                        <div class="text-center">
                            <img src="/assets/images/gcash-qr.png" alt="GCash QR Code" class="img-fluid" style="max-width: 200px;">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="form-check mb-3">
                    <input class="form-check-input"
                        type="checkbox"
                        id="termsAgree"
                        name="terms_agree"
                        required>
                    <label class="form-check-label" for="termsAgree">
                        I agree to the <a href="/terms" target="_blank">terms and conditions</a>
                    </label>
                    <div class="invalid-feedback">
                        You must agree to the terms and conditions
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-lg w-100">
                    Place Order
                </button>
            </div>
        </div>
    </form>
</div>

<div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="orderModalLabel">Order Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="orderModalBody">
                <!-- Message will be inserted here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmationModalLabel">Confirmation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to remove this item?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmRemove">Remove</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Load order data from localStorage
        const orderList = JSON.parse(localStorage.getItem('orderList')) || [];
        const orderItemsContainer = document.getElementById('order-items');
        const grandTotalElement = document.getElementById('grand-total');
        let itemToRemoveIndex = null; // Store the index of the item to remove

        // Display order items
        displayOrderItems();

        function displayOrderItems() {
            if (orderList.length === 0) {
                orderItemsContainer.innerHTML = '<p class="text-muted">Your order list is empty</p>';
                grandTotalElement.textContent = '₱0.00';
                return;
            }

            let html = '<div class="table-responsive"><table class="table">';
            html += '<thead><tr><th>Product</th><th>Price</th><th>Action</th></thead><tbody>';

            let grandTotal = 0;

            orderList.forEach((item, index) => {
                grandTotal += parseFloat(item.price);

                html += `
                    <tr>
                        <td>${item.name}</td>
                        <td>₱${parseFloat(item.price).toFixed(2)}</td>
                        <td>
                            <button class="btn btn-danger btn-sm remove-item-btn" data-index="${index}">Remove</button>
                        </td>
                    </tr>
                `;
            });

            html += '</tbody></table></div>';
            orderItemsContainer.innerHTML = html;
            grandTotalElement.textContent = `₱${grandTotal.toFixed(2)}`;

            // Add event listeners to the remove buttons
            const removeButtons = document.querySelectorAll('.remove-item-btn');
            removeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    itemToRemoveIndex = this.dataset.index;
                    const confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
                    confirmationModal.show();
                });
            });
        }

        // Confirmation Modal Logic
        document.getElementById('confirmRemove').addEventListener('click', function() {
            if (itemToRemoveIndex !== null) {
                removeOneItem(itemToRemoveIndex);
                itemToRemoveIndex = null; // Reset the index
                const confirmationModal = bootstrap.Modal.getInstance(document.getElementById('confirmationModal')); // Get the modal instance
                confirmationModal.hide(); // Close the modal
            }
        });

        function removeOneItem(index) {
            const item = orderList[index];
            orderList.splice(index, 1);
            localStorage.setItem('orderList', JSON.stringify(orderList));
            displayOrderItems();
            showModal(`${item.name} has been removed.`);
        }

        // Form validation and submission
        const form = document.getElementById('checkout-form');

        form.addEventListener('submit', async function(e) {
            e.preventDefault();

            if (orderList.length === 0) {
                alert('Your order list is empty. Please add items before checking out.');
                return;
            }

            if (!form.checkValidity()) {
                e.stopPropagation();
                form.classList.add('was-validated');
                return;
            }

            // Collect form data
            const formData = new FormData(form);
            const orderData = {
                customer: {
                    userId: window.currentUserId, // Add this to your view
                    fullName: formData.get('full_name'),
                    email: formData.get('email'),
                    address: formData.get('address'),
                    contact: formData.get('contact'),
                    zipCode: formData.get('zip')
                },
                payment: {
                    method: formData.get('payment_method'),
                    gcashRef: formData.get('gcash_ref'),
                    gcashPhone: formData.get('gcash_phone')
                },
                items: orderList.map(item => ({
                    id: item.id,
                    price: item.price
                })),
                termsAgreed: formData.get('terms_agree') === 'on'
            };

            try {
                const response = await fetch('/api/orders', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(orderData)
                });

                const result = await response.json();

                if (!response.ok) {
                    throw new Error(result.message || 'Order submission failed');
                }

                // Clear order list after successful order
                localStorage.removeItem('orderList');

                // Show success message
                showModal('Order placed successfully!');

                // Redirect after modal is closed
                const orderModal = document.getElementById('orderModal');
                orderModal.addEventListener('hidden.bs.modal', function() {
                    window.location.href = `/order-confirmation?id=${result.orderId}`;
                });

            } catch (error) {
                showModal('There was an error processing your order: ' + error.message);
            }
        });

        function showModal(message) {
            document.getElementById('orderModalBody').textContent = message;
            let modal = new bootstrap.Modal(document.getElementById('orderModal'));
            modal.show();
        }

        // Update delivery info display when address is entered
        document.getElementById('address').addEventListener('input', function() {
            const deliveryInfo = document.getElementById('delivery-info-display');
            deliveryInfo.textContent = this.value || 'Not yet provided';
        });

        // Show/hide GCash fields based on payment method selection
        const paymentMethodSelect = document.getElementById('paymentMethod');
        const paymentMethodDisplay = document.getElementById('payment-method-display');

        paymentMethodSelect.addEventListener('change', function() {
            const gcashInfo = document.getElementById('gcashInfo');
            if (this.value === 'gcash') {
                gcashInfo.style.display = 'block';
                paymentMethodDisplay.textContent = 'GCash';
            } else {
                gcashInfo.style.display = 'none';
                paymentMethodDisplay.textContent = 'Cash on Delivery';
            }
        });

        // Initialize payment method display based on selected value
        paymentMethodDisplay.textContent = paymentMethodSelect.value === 'gcash' ? 'GCash' : 'Cash on Delivery';
    });
</script>

<?php include __DIR__ . '/../components/footer.php'; ?>
