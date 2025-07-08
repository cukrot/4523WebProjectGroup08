<?php
session_start();
if (!isset($_SESSION)) {
    die('Session failed to start.');
}
include 'config.php';

$cart_items = $_SESSION['cart'] ?? [];
$subtotal = 0;
foreach ($cart_items as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}
$delivery_charge = $subtotal * 0.05;
$total = $subtotal + $delivery_charge;


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - Smile & Sunshine</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="cart-styles.css">
    <script src="cart-script.js" defer></script>
</head>
<body>
    <header>
        <div class="container">
            <div class="logo">Smile & Sunshine</div>
            <nav>
                <ul>
                    <li><a href="home.php">Back to Home</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section class="cart">
        <div class="container">
            <h2>Your Shopping Cart</h2>
            <div class="cart-wrapper">
                <div class="cart-items">
                    <?php if (empty($cart_items)): ?>
                        <p class="empty-cart">Your cart is empty.</p>
                    <?php else: ?>
                        <?php foreach ($cart_items as $pid => $item): ?>
                            <div class="cart-item" data-id="<?php echo $pid; ?>">
                                <div class="cart-item-image"></div>
                                <div class="cart-item-details">
                                    <h4><?php echo htmlspecialchars($item['name']); ?></h4>
                                    <p>$<?php echo number_format($item['price'], 2); ?></p>
                                    <div class="cart-item-quantity">
                                        <button class="decrease-quantity">-</button>
                                        <input type="number" value="<?php echo $item['quantity']; ?>" min="1">
                                        <button class="increase-quantity">+</button>
                                    </div>
                                    <a href="#" class="cart-item-remove">Remove</a>
                                </div>
                                <div class="cart-item-total">
                                    $<?php echo number_format($item['price'] * $item['quantity'], 2); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <div class="cart-summary">
                    <h3>Cart Summary</h3>
                    <div class="summary-item">
                        <span>Subtotal</span>
                        <span id="subtotal">$<?php echo number_format($subtotal, 2); ?></span>
                    </div>
                    <div class="summary-item">
                        <span>Delivery Charge (5%)</span>
                        <span id="delivery-charge">$<?php echo number_format($delivery_charge, 2); ?></span>
                    </div>
                    <div class="summary-item total">
                        <span>Total</span>
                        <span id="total">$<?php echo number_format($total, 2); ?></span>
                    </div>
                    <form action="checkout.php" method="post">
                        <button type="submit" class="checkout-btn">Proceed to Checkout</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <div class="footer-section">
                <h3>Smile & Sunshine</h3>
                <p>We have toys that bring joy and spark creativity for kids of all ages. From toddlers to teens.</p>
            </div>
            <div class="footer-section">
                <h3>COMPANY</h3>
                <ul>
                    <li><a href="#">About</a></li>
                    <li><a href="#">Features</a></li>
                    <li><a href="#">Works</a></li>
                    <li><a href="#">Career</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>HELP</h3>
                <ul>
                    <li><a href="#">Customer Support</a></li>
                    <li><a href="#">Delivery Details</a></li>
                    <li><a href="#">Terms & Conditions</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>FAQ</h3>
                <ul>
                    <li><a href="#">Account</a></li>
                    <li><a href="#">Manage Deliveries</a></li>
                    <li><a href="#">Orders</a></li>
                    <li><a href="#">Payments</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>Shop.co © 2000-2025, All Rights Reserved</p>
        </div>
    </footer>
</body>
</html>