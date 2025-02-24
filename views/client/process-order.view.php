<?php include __DIR__ . '/../components/client-header.php'; ?>

<div class="container mt-5">
    <h2 class="mb-4">Checkout</h2>

    <!-- Order Overview Card -->
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

    <!-- Checkout Form -->
    <form id="checkout-form" class="needs-validation" novalidate>
        <!-- Customer Information Section -->
        <div class="row">
            <div class="col-md-6">
                <h3 class="mb-3">Customer Information</h3>
                <div class="mb-3">
                    <label for="fullName" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="fullName" name="full_name" required pattern="^[a-zA-Z ]+$" minlength="2" maxlength="100">
                    <div class="invalid-feedback">Please enter your full name (letters and spaces only)</div>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email">
                    <div class="invalid-feedback">Please enter a valid email address</div>
                </div>

                <div class="mb-3">
                    <label for="address" class="form-label">Delivery Address</label>
                    <textarea class="form-control" id="address" name="address" rows="3" required minlength="10" maxlength="200"></textarea>
                    <div class="invalid-feedback">Please enter your complete delivery address</div>
                </div>

                <div class="mb-3">
                    <label for="contact" class="form-label">Contact Number (PH)</label>
                    <div class="input-group">
                        <span class="input-group-text">+63</span>
                        <input type="tel" class="form-control" id="contact" name="contact" required pattern="^9[0-9]{9}$" placeholder="9XXXXXXXXX">
                    </div>
                    <div class="invalid-feedback">Please enter a valid Philippine mobile number</div>
                    <div class="form-text">Enter your 10-digit mobile number starting with 9</div>
                </div>

                <div class="mb-3">
                    <label for="zipCode" class="form-label">ZIP Code</label>
                    <input type="text" class="form-control" id="zipCode" name="zip" required pattern="[0-9]{4}" maxlength="4">
                    <div class="invalid-feedback">Please enter a valid 4-digit ZIP code</div>
                </div>
            </div>

            <!-- Payment Details Section -->
            <div class="col-md-6">
                <h3 class="mb-3">Payment Details</h3>
                <div class="mb-3">
                    <label for="paymentMethod" class="form-label">Mode of Payment</label>
                    <select class="form-select" id="paymentMethod" name="payment_method" required>
                        <option value="">Select payment method</option>
                        <option value="cash">Cash on Delivery</option>
                        <option value="gcash">GCash</option>
                    </select>
                    <div class="invalid-feedback">Please select a payment method</div>
                </div>

                <div id="gcashInfo" class="mb-3" style="display: none;">
                    <div class="mb-3">
                        <p><strong>Store GCash Account Number:</strong> 09171234567</p>
                    </div>
                    <div class="mb-3">
                        <label for="gcashRef" class="form-label">GCash Reference Number</label>
                        <input type="text" class="form-control" id="gcashRef" name="gcash_ref">
                        <div class="invalid-feedback">Please enter a valid GCash reference number (10-13 digits)</div>
                    </div>
                    <div class="mb-3">
                        <label for="gcashPhone" class="form-label">GCash Customer Phone Number</label>
                        <div class="input-group">
                            <span class="input-group-text">+63</span>
                            <input type="tel" class="form-control" id="gcashPhone" name="gcash_phone" pattern="^9[0-9]{9}$" placeholder="9XXXXXXXXX">
                        </div>
                        <div class="invalid-feedback">Please enter a valid Philippine mobile number</div>
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

        <!-- Terms and Submit Section -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="termsAgree" name="terms_agree" required>
                    <label class="form-check-label" for="termsAgree">
                        I agree to the <a href="/terms" target="_blank">terms and conditions</a>
                    </label>
                    <div class="invalid-feedback">You must agree to the terms and conditions</div>
                </div>

                <button type="submit" class="btn btn-primary btn-lg w-100">Place Order</button>
            </div>
        </div>
    </form>
</div>

<!-- Order Confirmation Modal -->
<div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmationModalLabel">Confirm Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to place this order?</p>
                <div id="orderSummary">
                    <!-- Order summary will be populated here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No, Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmOrderBtn">Yes, Place Order</button>
            </div>
        </div>
    </div>
</div>

<!-- Status Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statusModalLabel">Order Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="statusModalBody">
                <!-- Status message will be inserted here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Deletion Confirmation Modal -->
<div class="modal fade" id="deletionConfirmationModal" tabindex="-1" aria-labelledby="deletionConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deletionConfirmationModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to remove this item from your order?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeletionBtn">Remove</button>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../components/footer.php'; ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize variables
        window.currentUserId = <?php echo json_encode($_SESSION['user_id']); ?>;
        const orderList = JSON.parse(localStorage.getItem('orderList')) || [];
        const orderItemsContainer = document.getElementById('order-items');
        const grandTotalElement = document.getElementById('grand-total');
        const form = document.getElementById('checkout-form');
        let orderData = null;
        let itemIndexToRemove = null; // Store the index of the item to remove

        // Display initial order items
        displayOrderItems();

        function displayOrderItems() {
            if (orderList.length === 0) {
                orderItemsContainer.innerHTML = '<p class="text-muted">Your order list is empty</p>';
                grandTotalElement.textContent = '₱0.00';
                return;
            }

            let html = '<div class="table-responsive"><table class="table">';
            html += '<thead><tr><th>Product</th><th>Quantity</th><th>Price</th><th>Subtotal</th><th>Actions</th></tr></thead><tbody>';

            let grandTotal = 0;

            orderList.forEach((item, index) => { // Added index
                const subtotal = parseFloat(item.price) * item.quantity;
                grandTotal += subtotal;

                html += `
                <tr>
                    <td>${item.name}</td>
                    <td>
                        <div class="input-group">
                            <button class="btn btn-outline-secondary quantity-decrement" type="button" data-index="${index}">-</button>
                            <input type="number" class="form-control quantity-input text-center" value="${item.quantity}" min="0" data-index="${index}">
                            <button class="btn btn-outline-secondary quantity-increment" type="button" data-index="${index}">+</button>
                        </div>
                    </td>
                    <td>₱${parseFloat(item.price).toFixed(2)}</td>
                    <td>₱${subtotal.toFixed(2)}</td>
                    <td>
                        <button class="btn btn-danger btn-sm remove-item" data-index="${index}">Remove</button>
                    </td>
                </tr>
            `;
            });

            html += '</tbody></table></div>';
            orderItemsContainer.innerHTML = html;
            grandTotalElement.textContent = `₱${grandTotal.toFixed(2)}`;

            // Attach event listeners to remove buttons
            const removeButtons = document.querySelectorAll('.remove-item');
            removeButtons.forEach(button => {
                button.addEventListener('click', showDeletionConfirmation);
            });

            // Attach event listeners to quantity inputs
            const quantityInputs = document.querySelectorAll('.quantity-input');
            quantityInputs.forEach(input => {
                input.addEventListener('change', updateQuantity);
            });

            // Attach event listeners to increment and decrement buttons
            const incrementButtons = document.querySelectorAll('.quantity-increment');
            incrementButtons.forEach(button => {
                button.addEventListener('click', incrementQuantity);
            });

            const decrementButtons = document.querySelectorAll('.quantity-decrement');
            decrementButtons.forEach(button => {
                button.addEventListener('click', decrementQuantity);
            });
        }

        function showDeletionConfirmation(event) {
            itemIndexToRemove = parseInt(event.target.dataset.index); // Store the index
            const deletionModal = new bootstrap.Modal(document.getElementById('deletionConfirmationModal'));
            deletionModal.show();
        }

        //Remove item function
        // Attach event listener to the confirmation button
        document.getElementById('confirmDeletionBtn').addEventListener('click', function() {
            const deletionModal = bootstrap.Modal.getInstance(document.getElementById('deletionConfirmationModal'));
            deletionModal.hide();

            if (itemIndexToRemove !== null) {
                orderList.splice(itemIndexToRemove, 1);
                localStorage.setItem('orderList', JSON.stringify(orderList));
                displayOrderItems();
                updateTotals();
                itemIndexToRemove = null; // Reset the index after removal
            }
        });

        function updateQuantity(event) {
            const indexToUpdate = parseInt(event.target.dataset.index);
            const newQuantity = parseInt(event.target.value);

            if (newQuantity > 0) {
                orderList[indexToUpdate].quantity = newQuantity;
            } else {
                // Remove the item if quantity is 0 or less
                orderList.splice(indexToUpdate, 1);
            }

            localStorage.setItem('orderList', JSON.stringify(orderList));
            displayOrderItems();
            updateTotals();
        }

        function incrementQuantity(event) {
            const indexToUpdate = parseInt(event.target.dataset.index);
            orderList[indexToUpdate].quantity++;
            localStorage.setItem('orderList', JSON.stringify(orderList));
            displayOrderItems();
            updateTotals();
        }

        function decrementQuantity(event) {
            const indexToUpdate = parseInt(event.target.dataset.index);
            if (orderList[indexToUpdate].quantity > 1) {
                orderList[indexToUpdate].quantity--;
            } else {
                orderList.splice(indexToUpdate, 1);
            }

            localStorage.setItem('orderList', JSON.stringify(orderList));
            displayOrderItems();
            updateTotals();
        }

        // Handle form submission
        form.addEventListener('submit', async function(e) {
            e.preventDefault();

            // Validate if there are items in the order
            if (orderList.length === 0) {
                showStatusModal('Your order list is empty. Please add items before checking out.');
                return;
            }

            // Form validation
            if (!form.checkValidity()) {
                e.stopPropagation();
                form.classList.add('was-validated');
                return;
            }

            // Get form data
            const formData = new FormData(form);

            // Prepare order data
            orderData = {
                customer: {
                    userId: window.currentUserId,
                    fullName: formData.get('full_name'),
                    email: formData.get('email'),
                    address: formData.get('address'),
                    contact: formData.get('contact'),
                    zipCode: formData.get('zip')
                },
                payment: {
                    method: formData.get('payment_method'),
                    gcashRef: formData.get('gcash_ref') || null,
                    gcashPhone: formData.get('gcash_phone') || null
                },
                items: orderList.map(item => ({
                    id: item.id,
                    name: item.name,
                    quantity: item.quantity,
                    price: item.price
                })),
                termsAgreed: formData.get('terms_agree') === 'on'
            };

            // Show confirmation modal with order summary
            showConfirmationModal(orderData);
        });

        // Function to show confirmation modal
        function showConfirmationModal(data) {
            const modal = new bootstrap.Modal(document.getElementById('confirmationModal'));
            const summaryDiv = document.getElementById('orderSummary');

            // Create summary HTML
            let summaryHtml = `
            <div class="mt-3">
                <h6>Order Details:</h6>
                <p>Customer: ${data.customer.fullName}<br>
                   Email: ${data.customer.email}<br>
                   Contact: +63${data.customer.contact}<br>
                   Address: ${data.customer.address}</p>
                <h6>Items:</h6>
                <ul>
        `;

            data.items.forEach(item => {
                summaryHtml += `<li>${item.name} - ${item.quantity}x - ₱${(item.price * item.quantity).toFixed(2)}</li>`;
            });

            summaryHtml += `
                </ul>
                <p><strong>Payment Method:</strong> ${data.payment.method === 'gcash' ? 'GCash' : 'Cash on Delivery'}</p>
                <p><strong>Total Amount:</strong> ${grandTotalElement.textContent}</p>
            </div>
        `;

            summaryDiv.innerHTML = summaryHtml;
            modal.show();
        }

        // Update the form submission handler in process-order.view.php
        // Update the form submission handler
        document.getElementById('confirmOrderBtn').addEventListener('click', async function() {
            try {
                if (!orderData) {
                    showStatusModal('No order data available');
                    return;
                }

                const confirmationModal = bootstrap.Modal.getInstance(document.getElementById('confirmationModal'));
                confirmationModal.hide();

                // Create the request data
                const requestData = {
                    full_name: orderData.customer.fullName,
                    email: orderData.customer.email,
                    contact: orderData.customer.contact,
                    address: orderData.customer.address,
                    payment_method: orderData.payment.method,
                    items: orderData.items.map(item => ({
                        id: parseInt(item.id),
                        quantity: parseInt(item.quantity),
                        price: parseFloat(item.price)
                    }))
                };

                // Add GCash details if applicable
                if (orderData.payment.method === 'gcash') {
                    requestData.gcash_ref = orderData.payment.gcashRef;
                    requestData.gcash_phone = orderData.payment.gcashPhone;
                }

                const response = await fetch('/orders', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(requestData)
                });

                const textResponse = await response.text(); // Get the raw response
                let result;

                try {
                    result = textResponse ? JSON.parse(textResponse) : {}; // Check before parsing
                } catch (error) {
                    console.error('Failed to parse server response:', error);
                    showStatusModal('Error processing order data. Please try again.');
                    return;
                }

                if (!response.ok) {
                    throw new Error(result.message || 'Failed to create order');
                }

                if (result.success) {
                    showStatusModal('Order placed successfully!');
                    localStorage.removeItem('orderList');

                    const statusModalBody = document.getElementById('statusModalBody');
                    statusModalBody.innerHTML = `
                <div class="alert alert-success">
                    <h4>Order Placed Successfully!</h4>
                    <p>Order ID: ${result.order_id}</p>
                    <p>Thank you for your purchase. We will process your order shortly.</p>
                </div>
            `;

                    const statusModal = new bootstrap.Modal(document.getElementById('statusModal'));
                    statusModal.show();

                    setTimeout(() => {
                        window.location.href = '/shop';
                    }, 3000);
                } else {
                    throw new Error(result.message || 'Failed to create order');
                }
            } catch (error) {
                console.error('Order submission error:', error);
                showStatusModal('Error: ' + error.message);
            }
        });

        // Update the showStatusModal function
        function showStatusModal(message) {
            const modalElement = document.getElementById('statusModal');
            const modalBody = document.getElementById('statusModalBody');

            // Format the message based on type
            if (message.startsWith('Error:')) {
                modalBody.innerHTML = `
            <div class="alert alert-danger">
                ${message}
            </div>
        `;
            } else {
                modalBody.innerHTML = `
            <div class="alert alert-success">
                ${message}
            </div>
        `;
            }

            const modal = new bootstrap.Modal(modalElement);
            modal.show();
        }

        function createInput(name, value) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = name;
            input.value = value || '';
            return input;
        }

        // Payment method handling
        const paymentMethodSelect = document.getElementById('paymentMethod');
        const paymentMethodDisplay = document.getElementById('payment-method-display');

        paymentMethodSelect.addEventListener('change', function() {
            const gcashInfo = document.getElementById('gcashInfo');
            const gcashPhone = document.getElementById('gcashPhone');

            if (this.value === 'gcash') {
                gcashInfo.style.display = 'block';
                gcashPhone.setAttribute('required', 'required');
                paymentMethodDisplay.textContent = 'GCash';
            } else {
                gcashInfo.style.display = 'none';
                gcashPhone.removeAttribute('required');
                paymentMethodDisplay.textContent = 'Cash on Delivery';
            }
        });

        // Initialize payment method display based on selected value
        paymentMethodDisplay.textContent = paymentMethodSelect.value === 'gcash' ? 'GCash' : 'Cash on Delivery';

        // Update delivery info display when address is entered
        document.getElementById('address').addEventListener('input', function() {
            const deliveryInfo = document.getElementById('delivery-info-display');
            deliveryInfo.textContent = this.value || 'Not yet provided';
        });

        // Handle modal close events
        document.getElementById('confirmationModal').addEventListener('hidden.bs.modal', function() {
            // Re-enable submit button if it was disabled
            const submitButton = form.querySelector('button[type="submit"]');
            submitButton.disabled = false;
            submitButton.innerHTML = 'Place Order';
        });

        document.getElementById('statusModal').addEventListener('hidden.bs.modal', function() {
            // If this was a success message and we're waiting to redirect, don't do anything
            if (this.querySelector('.modal-body').textContent.includes('successfully')) {
                return;
            }

            // Otherwise, reset the form state
            const submitButton = form.querySelector('button[type="submit"]');
            submitButton.disabled = false;
            submitButton.innerHTML = 'Place Order';
        });

        // Calculate and update totals
        function updateTotals() {
            let grandTotal = 0;
            orderList.forEach(item => {
                const subtotal = parseFloat(item.price) * item.quantity;
                grandTotal += subtotal;
            });
            grandTotalElement.textContent = `₱${grandTotal.toFixed(2)}`;
        }

        // Validate GCash details when required
        function validateGCashDetails() {
            if (paymentMethodSelect.value === 'gcash') {
                const gcashRef = document.getElementById('gcashRef').value;
                const gcashPhone = document.getElementById('gcashPhone').value;

                if (!gcashRef) {
                    showStatusModal('Please enter GCash reference number');
                    return false;
                }

                if (!gcashPhone) {
                    showStatusModal('Please enter GCash phone number');
                    return false;
                }

                // Validate GCash reference number format (10-13 digits)
                if (!/^\d{10,13}$/.test(gcashRef)) {
                    showStatusModal('Invalid GCash reference number format');
                    return false;
                }

                // Validate phone number format
                if (!/^9\d{9}$/.test(gcashPhone)) {
                    showStatusModal('Invalid phone number format');
                    return false;
                }
            }
            return true;
        }

        // Add error handling for JSON parsing
        window.addEventListener('error', function(e) {
            if (e.message.includes('JSON')) {
                console.error('JSON parsing error:', e);
                showStatusModal('Error processing order data. Please try again.');
            }
        });
    });
</script>