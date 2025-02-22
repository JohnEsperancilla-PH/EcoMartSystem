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
