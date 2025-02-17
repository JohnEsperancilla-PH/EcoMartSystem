<?php include __DIR__ . '/../components/client-header.php'; ?>

<div class="container mt-5">
    <h2 class="mb-4">Checkout</h2>

    <div class="card p-4 mb-4">
        <h3>Order Overview</h3>
        <div id="cart-items" class="mb-3">
            <!-- Cart items will be populated here via JavaScript -->
        </div>
        <div class="border-top pt-3">
            <p><strong>Payment Method:</strong> <span id="payment-method-display">Cash</span></p>
            <p><strong>Delivery Information:</strong></p>
            <p id="delivery-info-display">Not yet provided</p>
            <p><strong>Grand Total:</strong> <span id="grand-total">₱0.00</span></p>
        </div>
    </div>

    <!-- Rest of the form remains unchanged -->
    <form id="checkout-form" class="needs-validation" novalidate>
        <!-- Existing form content -->
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

    // Handle form submission
    document.getElementById('checkout-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (cart.length === 0) {
            alert('Your cart is empty. Please add items before checking out.');
            return;
        }

        // Add your checkout logic here
        // After successful checkout, clear the cart:
        localStorage.removeItem('cart');
        
        // Redirect to success page or show success message
        alert('Order placed successfully!');
        window.location.href = '/shop';
    });

    // Existing payment method change listener remains unchanged
});
</script>

<?php include __DIR__ . '/../components/footer.php'; ?>