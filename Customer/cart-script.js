function initQuantityControls() {
    const cartItemsContainer = document.querySelector('.cart-items');

    cartItemsContainer.addEventListener('click', (e) => {
        const cartItem = e.target.closest('.cart-item');
        if (!cartItem) return;

        const pid = cartItem.getAttribute('data-id');
        const quantityInput = cartItem.querySelector('input[type="number"]');
        let quantity = parseInt(quantityInput.value);

        if (e.target.classList.contains('decrease-quantity')) {
            if (quantity > 1) {
                quantity -= 1;
                updateCartItem(pid, quantity);
            }
        } else if (e.target.classList.contains('increase-quantity')) {
            quantity += 1;
            updateCartItem(pid, quantity);
        } else if (e.target.classList.contains('cart-item-remove')) {
            e.preventDefault();
            removeCartItem(pid);
        }
    });

    cartItemsContainer.addEventListener('change', (e) => {
        if (e.target.type === 'number') {
            const cartItem = e.target.closest('.cart-item');
            const pid = cartItem.getAttribute('data-id');
            const quantity = parseInt(e.target.value);
            if (quantity >= 1) {
                updateCartItem(pid, quantity);
            } else {
                e.target.value = 1;
                alert('The quantity cannot be less than 1');
            }
        }
    });
}

function updateCartItem(pid, quantity) {
    fetch('update_cart.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `pid=${pid}&quantity=${quantity}`
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Failed to update shopping cart');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating the shopping cart');
        });
}

function removeCartItem(pid) {
    fetch('remove_from_cart.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `pid=${pid}`
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Failed to remove product');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while removing the item');
        });
}

function applyVIPDiscount() {
    const vipCodeInput = document.getElementById('vip-code');
    const applyBtn = document.getElementById('apply-vip');
    const totalSpan = document.getElementById('total');
    const hiddenTotalInput = document.getElementById('hidden-total');
    const subtotal = parseFloat(document.getElementById('subtotal').textContent.replace('$', ''));
    const deliveryCharge = parseFloat(document.getElementById('delivery-charge').textContent.replace('$', ''));

    applyBtn.addEventListener('click', () => {
        const code = vipCodeInput.value.trim();
        if (code === 'vip666') {
            const discountedTotal = (subtotal + deliveryCharge) * 0.8;
            totalSpan.textContent = '$' + discountedTotal.toFixed(2);
            hiddenTotalInput.value = discountedTotal.toFixed(2);
            alert('VIP discount applied successfully!');
        } else {
            alert('Invalid VIP code.');
        }
    });
}

function init() {
    initQuantityControls();
    applyVIPDiscount();
}

init();