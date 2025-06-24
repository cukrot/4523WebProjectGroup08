// Sample cart data (for testing purposes, will be replaced by localStorage)
const sampleCartItems = [
    { id: 1, name: "Interactive Robot Kit", price: 212, quantity: 1 },
    { id: 2, name: "Plush Teddy Bear", price: 80, quantity: 2 }
];

// Load cart items from localStorage or use sample data if empty
function loadCartItems() {
    const cartItems = JSON.parse(localStorage.getItem('cart')) || sampleCartItems;
    return cartItems;
}

// Save cart items to localStorage
function saveCartItems(cartItems) {
    localStorage.setItem('cart', JSON.stringify(cartItems));
}

// Render cart items
function renderCartItems() {
    const cartItemsContainer = document.querySelector('.cart-items');
    const cartItems = loadCartItems();

    // Clear existing items
    cartItemsContainer.innerHTML = '';

    if (cartItems.length === 0) {
        cartItemsContainer.innerHTML = '<p class="empty-cart">Your cart is empty.</p>';
        updateCartSummary();
        return;
    }

    cartItems.forEach(item => {
        const cartItem = document.createElement('div');
        cartItem.classList.add('cart-item');
        cartItem.innerHTML = `
            <div class="cart-item-image"></div>
            <div class="cart-item-details">
                <h4>${item.name}</h4>
                <p>$${item.price.toFixed(2)}</p>
                <div class="cart-item-quantity">
                    <button class="decrease-quantity" data-id="${item.id}">-</button>
                    <input type="number" value="${item.quantity}" min="1" data-id="${item.id}">
                    <button class="increase-quantity" data-id="${item.id}">+</button>
                </div>
                <a href="#" class="cart-item-remove" data-id="${item.id}">Remove</a>
            </div>
            <div class="cart-item-total">$${(item.price * item.quantity).toFixed(2)}</div>
        `;
        cartItemsContainer.appendChild(cartItem);
    });

    updateCartSummary();
}

// Update cart summary (subtotal, tax, total)
function updateCartSummary() {
    const cartItems = loadCartItems();
    const subtotal = cartItems.reduce((sum, item) => sum + item.price * item.quantity, 0);
    const deliveryCharge = subtotal * 0.05; // 5% delivery charge
    const total = subtotal + deliveryCharge;

    document.getElementById('subtotal').textContent = `$${subtotal.toFixed(2)}`;
    document.getElementById('tax').textContent = `$${deliveryCharge.toFixed(2)}`;
    document.getElementById('total').textContent = `$${total.toFixed(2)}`;
}

// Handle quantity changes
function initQuantityControls() {
    const cartItemsContainer = document.querySelector('.cart-items');

    // Decrease quantity
    cartItemsContainer.addEventListener('click', (e) => {
        if (e.target.classList.contains('decrease-quantity')) {
            const id = parseInt(e.target.getAttribute('data-id'));
            let cartItems = loadCartItems();
            const item = cartItems.find(item => item.id === id);
            if (item.quantity > 1) {
                item.quantity -= 1;
                saveCartItems(cartItems);
                renderCartItems();
            }
        }
    });

    // Increase quantity
    cartItemsContainer.addEventListener('click', (e) => {
        if (e.target.classList.contains('increase-quantity')) {
            const id = parseInt(e.target.getAttribute('data-id'));
            let cartItems = loadCartItems();
            const item = cartItems.find(item => item.id === id);
            item.quantity += 1;
            saveCartItems(cartItems);
            renderCartItems();
        }
    });

    // Manual quantity input
    cartItemsContainer.addEventListener('input', (e) => {
        if (e.target.type === 'number') {
            const id = parseInt(e.target.getAttribute('data-id'));
            const newQuantity = parseInt(e.target.value);
            if (newQuantity < 1) {
                e.target.value = 1;
                return;
            }
            let cartItems = loadCartItems();
            const item = cartItems.find(item => item.id === id);
            item.quantity = newQuantity;
            saveCartItems(cartItems);
            renderCartItems();
        }
    });
}

// Handle remove item
function initRemoveItem() {
    const cartItemsContainer = document.querySelector('.cart-items');
    cartItemsContainer.addEventListener('click', (e) => {
        if (e.target.classList.contains('cart-item-remove')) {
            e.preventDefault();
            const id = parseInt(e.target.getAttribute('data-id'));
            let cartItems = loadCartItems();
            cartItems = cartItems.filter(item => item.id !== id);
            saveCartItems(cartItems);
            renderCartItems();
        }
    });
}

// Handle checkout
function initCheckout() {
    const checkoutBtn = document.querySelector('.checkout-btn');
    checkoutBtn.addEventListener('click', () => {
        const cartItems = loadCartItems();
        if (cartItems.length === 0) {
            alert('Your cart is empty. Add some items before checking out!');
            return;
        }
        alert('Thank you for your purchase! Your order has been placed.');
        // Clear the cart after checkout
        saveCartItems([]);
        renderCartItems();
    });
}

// Initialize the cart page
function init() {
    renderCartItems();
    initQuantityControls();
    initRemoveItem();
    initCheckout();
}

// Run the initialization
init();