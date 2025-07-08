<?php
session_start();
include 'config.php';

if (isset($_GET['pid'])) {
    $pid = $_GET['pid'];
    $stmt = $pdo->prepare('SELECT * FROM product WHERE pid = ?');
    $stmt->execute([$pid]);
    $product = $stmt->fetch();
    if (!$product) {
        header('Location: index.php');
        exit;
    }
} else {
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $product['pname']; ?> - Smile & Sunshine</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="product_detail_styles.css"> 
    
</head>
<body data-page="product-detail">
    <header>
        <div class="container">
            <div class="logo">Smile & Sunshine</div>
            <nav>
                <ul>
                    <li><a href="index.php#on-sale">On Sale</a></li>
                    <li><a href="index.php#new-arrivals">New Arrivals</a></li>
                    <li><a href="index.php#top-selling">Top Selling</a></li>
                    <li><a href="index.php#customer-reviews">Customer Reviews</a></li>
                </ul>
            </nav>
            <div class="header-right">
                <div class="search-bar">
                    <form action="search.php" method="get">
                        <input type="text" name="query" placeholder="Search for products...">
                        <button type="submit">🔍</button>
                    </form>
                </div>
                <div class="header-icons">
                    <a href="cart.php" class="cart-icon">🛒</a>
                    <a href="<?php echo isset($_SESSION['customer_id']) ? 'member.php' : 'auth.php'; ?>" class="user-icon">👤</a>
                </div>
            </div>
        </div>
    </header>

    <section class="product-detail">
        <div class="container">
            <div class="product-image" style="background-image: url('<?php echo $product['pimage'] ?? 'default.jpg'; ?>');"></div>
            <div class="product-info">
                <h2><?php echo $product['pname']; ?></h2>
                <p>$<?php echo number_format($product['pcost'], 2); ?></p>
                <p><?php echo htmlspecialchars($product['pdesc'] ?? 'No description available.'); ?></p>
                <button class="add-to-cart" data-id="<?php echo $product['pid']; ?>" data-name="<?php echo $product['pname']; ?>" data-price="<?php echo $product['pcost']; ?>">Add to Cart</button>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <div class="footer-section">
                <h3>Smile & Sunshine</h3>
                <p>We have toys that bring joy and spark creativity for kids of all ages.</p>
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
    <script src="script.js"></script>
</body>
</html>