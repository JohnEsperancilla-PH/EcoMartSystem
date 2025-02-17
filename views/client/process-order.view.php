<?php include __DIR__ . '/../components/client-header.php'; ?>

<div class="container mt-5">
    <h2 class="mb-4">Checkout</h2>

    <div class="card p-4 mb-4">
        <h3>Order Overview</h3>
        <div id="cart-items" class="mb-3">
            <!-- Cart items will be populated here via JavaScript -->
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
                           name="email" 
                           required 
                           pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$">
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
                    <label for="gcashRef" class="form-label">GCash Reference Number</label>
                    <input type="text" 
                           class="form-control" 
                           id="gcashRef" 
                           name="gcash_ref" 
                           pattern="^[0-9]{10,13}$">
                    <div class="invalid-feedback">
                        Please enter a valid GCash reference number
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Load cart data from localStorage
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const cartItemsContainer = document.getElementById('cart-items');
    const grandTotalElement = document.getElementById('grand-total');

    // Display cart items
    displayCartItems();

    function displayCartItems() {
        if (cart.length === 0) {
            cartItemsContainer.innerHTML = '<p class="text-muted">Your cart is empty</p>';
            grandTotalElement.textContent = '₱0.00';
            return;
        }

        let html = '<div class="table-responsive"><table class="table">';
        html += '<thead><tr><th>Product</th><th>Quantity</th><th>Price</th><th>Total</th></tr></thead><tbody>';
        
        let grandTotal = 0;
        
        cart.forEach(item => {
            const itemTotal = item.price * item.quantity;
            grandTotal += itemTotal;
            
            html += `
                <tr>
                    <td>${item.name}</td>
                    <td>${item.quantity}</td>
                    <td>₱${item.price.toFixed(2)}</td>
                    <td>₱${itemTotal.toFixed(2)}</td>
                </tr>
            `;
        });

        html += '</tbody></table></div>';
        cartItemsContainer.innerHTML = html;
        grandTotalElement.textContent = `₱${grandTotal.toFixed(2)}`;
    }

    // Payment method change handler
    document.getElementById('paymentMethod').addEventListener('change', function() {
        const gcashInfo = document.getElementById('gcashInfo');
        const gcashRef = document.getElementById('gcashRef');
        const paymentDisplay = document.getElementById('payment-method-display');
        
        if (this.value === 'gcash') {
            gcashInfo.style.display = 'block';
            gcashRef.required = true;
            paymentDisplay.textContent = 'GCash';
        } else {
            gcashInfo.style.display = 'none';
            gcashRef.required = false;
            paymentDisplay.textContent = 'Cash on Delivery';
        }
    });

    // Form validation and submission
    const form = document.getElementById('checkout-form');
    
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        if (cart.length === 0) {
            alert('Your cart is empty. Please add items before checking out.');
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
                fullName: formData.get('full_name'),
                email: formData.get('email'),
                address: formData.get('address'),
                contact: formData.get('contact'),
                zipCode: formData.get('zip')
            },
            payment: {
                method: formData.get('payment_method'),
                gcashRef: formData.get('gcash_ref') || null
            },
            items: cart,
            termsAgreed: formData.get('terms_agree') === 'on'
        };

        try {
            // Send order to server
            const response = await fetch('/api/orders', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(orderData)
            });

            if (!response.ok) {
                throw new Error('Order submission failed');
            }

            // Clear cart after successful order
            localStorage.removeItem('cart');
            
            // Show success message and redirect
            alert('Order placed successfully!');
            window.location.href = '/order-confirmation';
            
        } catch (error) {
            console.error('Order submission error:', error);
            alert('There was an error processing your order. Please try again.');
        }
    });

    // Update delivery info display when address is entered
    document.getElementById('address').addEventListener('input', function() {
        const deliveryInfo = document.getElementById('delivery-info-display');
        deliveryInfo.textContent = this.value || 'Not yet provided';
    });
});
</script>

<?php include __DIR__ . '/../components/footer.php'; ?> 